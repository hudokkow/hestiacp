# HestiaCP Next UI (`web/next/`)

An opt-in, side-by-side modern web interface that **coexists** with the legacy UI.
Users select it per-account via a `UI_VERSION` preference; the legacy UI remains the
default and is fully untouched in behaviour.

> Status: beta. Coexists with the legacy interface. Add/edit forms for the core
> resources (Web, DNS, Mail, Database, Cron, Backups) are implemented here; a few
> deep settings pages (e.g. access keys, some edit sub-pages) still intentionally
> fall through to the legacy UI.

---

## How it is selected (backend substrate)

The preference lives in the user's `user.conf` as `UI_VERSION` (`legacy` | `next`).

| Piece                 | File                                                            | Notes                                                                                    |
| --------------------- | --------------------------------------------------------------- | ---------------------------------------------------------------------------------------- |
| Set preference        | `bin/v-change-user-ui-version`                                  | Mirrors `v-change-user-theme`; validates `legacy\|next` via `is_ui_version_format_valid` |
| Validator             | `func/main.sh`                                                  | `is_ui_version_format_valid` + `ui_version` case in `is_format_valid`                    |
| Known key + migration | `func/syshealth.sh`                                             | `UI_VERSION` added to user known-keys + migration block                                  |
| List output           | `bin/v-list-user`                                               | `UI_VERSION` threaded into json/shell/plain/csv                                          |
| Session mirror        | `web/inc/main.php`                                              | `$_SESSION["userUI"]` set on login/switch, guarded by `!isset($_SESSION["look"])`        |
| Persist on save       | `web/edit/user/index.php` + `web/templates/pages/edit_user.php` | `Interface Version` select (gated)                                                       |
| System policies       | `hestia.conf`                                                   | `POLICY_SYSTEM_ENABLE_NEXT_UI` (default `no`), `POLICY_USER_CHANGE_UI` (default `no`)    |

### Switching paths

- Topbar switcher — `web/templates/includes/panel.php` (shown only when `POLICY_SYSTEM_ENABLE_NEXT_UI=yes`)
- Switch handler — `web/switch-ui/index.php` (CSRF-checked; calls `v-change-user-ui-version`, redirects back)
- Login redirect — `web/login/index.php` redirects to `/next/` when `UI_VERSION=next` and the policy allows

To enable for a user:

```bash
v-change-sys-config-value 'POLICY_SYSTEM_ENABLE_NEXT_UI' 'yes'
v-change-user-ui-version < user > next
```

---

## Architecture

No `render_page()` / `extract($GLOBALS)` is used. Every page is an **explicit view model**:
the front controller pulls real data through the existing `v-*` CLIs and renders a
template. The legacy session, `HESTIA_CMD`, and Alpine/FontAwesome are reused.

```text
web/next/
├── index.php                 # Front controller: view-model + ?p= route dispatch
├── css/src/                 # Layered token system (built by build.js)
│   ├── index.css            # @layer order entry: reset, tokens, base, layout, components, utilities
│   ├── tokens.css           # OKLCH teal tokens, fluid type, dark default + light override
│   ├── layout.css           # .app-shell 3-column grid + container queries
│   ├── components/*.css     # buttons, cards, forms, data-list, nav, rail, tiles, graph, dialog, alerts
│   └── utilities.css
├── js/src/index.js          # Alpine store (ui: nav drawer, theme toggle) + logFeed component
├── templates/
│   ├── header.php           # <head>, topbar, left nav
│   ├── footer.php           # right server-info rail + activity feed
│   ├── partials.php         # next_nav_groups() navigation model
│   └── pages/               # one template per route (home, web, dns, mail, db, cron, backup, + forms)
└── dist/                    # build output — GITIGNORED
```

### Design system

- **Teal on dark**, OKLCH tokens, `color-mix()` for tints/shadows.
- `clamp()` fluid typography; `@layer` conflict-free cascade; container queries.
- Native light/dark via `:root[data-theme="light"]`; topbar toggle flips `data-theme`.
- WCAG 2.2 AA: semantic landmarks (`<header><nav><main><aside>`), `aria-current` nav
  state, labelled controls, `aria-live` log feed, `prefers-reduced-motion` respected.

### Routing

All routes are `GET /next/?p=<page>` or `POST` to the same with a `token` CSRF field.
The controller maps `p` to a template and pre-fetches its CLI data. Edit/delete forms
POST to `p=<resource>-edit` / `p=<resource>-delete` handlers.

| `p`                                               | Page         | CLI data source                                                                          |
| ------------------------------------------------- | ------------ | ---------------------------------------------------------------------------------------- |
| `home`                                            | Dashboard    | `v-list-user`, `v-list-sys-info`                                                         |
| `web` / `web-add` / `web-edit` / `web-delete`     | Web domains  | `v-list-web-domains`, `v-list-web-domain`, `v-add-web-domain`, `v-delete-domain`         |
| `dns` / `dns-add` / `dns-edit` / `dns-delete`     | DNS zones    | `v-list-dns-domains`, `v-list-dns-domain`, `v-add-dns-domain`, `v-delete-dns-domain`     |
| `mail` / `mail-add` / `mail-edit` / `mail-delete` | Mail domains | `v-list-mail-domains`, `v-list-mail-domain`, `v-add-mail-domain`, `v-delete-mail-domain` |
| `db` / `db-add` / `db-edit` / `db-delete`         | Databases    | `v-list-databases`, `v-add-database`, `v-change-database-password`, `v-delete-database`  |
| `cron` / `cron-add` / `cron-edit` / `cron-delete` | Cron jobs    | `v-list-cron-jobs`, `v-list-cron-job`, `v-add-cron-job`, `v-delete-cron-job`             |
| `backup` / `backup-create` / `backup-delete`      | Backups      | `v-list-user-backups`, `v-backup-user`, `v-delete-user-backup`                           |

Backup **download** still uses the legacy `/download/backup/` stream route (binary delivery).

### CSRF

Forms render `<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">`
and call `verify_csrf($_POST)` — the same mechanism as the legacy UI.

---

## Build

The existing `build.js` is extended with `buildNextJS()` and `buildNextCSS()`. They
reuse esbuild + Lightning CSS (browserslist `defaults` supports every feature used).

```bash
npm install
node build.js # builds legacy + next UI
```

- `buildNextCSS()` bundles `web/next/css/src/index.css` (with its `@import` layer order)
  into `web/next/dist/main.min.css`.
- `buildNextJS()` bundles `web/next/js/src/index.js` into `web/next/dist/main.min.js`.
- `web/next/dist/` is gitignored — never commit build artifacts.

## Localization

The locale scanner (`web/locale/hst_scan_i18n.sh`) is recursive and automatically
includes `web/next/**`, so all `__()` strings are picked up into `hestiacp.pot`.
