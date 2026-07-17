<?php
// web/next/templates/header.php
// Semantic header: <head>, topbar, left nav. Expects $view (user/token/theme) in scope.
include __DIR__ . "/partials.php";
$navGroups = next_nav_groups($view["user"], $page ?? "home");
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars(
	$_SESSION["language"] ?? "en",
) ?>" data-theme="<?= htmlspecialchars($view["theme"]) ?>">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?= htmlspecialchars($view["page_title"] ?? _("Control Panel")) ?> — HestiaCP</title>
	<link rel="stylesheet" href="/next/dist/main.min.css?<?= JS_LATEST_UPDATE ?>">
	<script defer src="/js/dist/alpinejs-collapse.min.js?<?= JS_LATEST_UPDATE ?>"></script>
	<script defer src="/js/dist/alpinejs.min.js?<?= JS_LATEST_UPDATE ?>"></script>
	<script defer src="/next/dist/main.min.js?<?= JS_LATEST_UPDATE ?>"></script>
</head>
<body>
	<div class="app-shell" x-data>
		<header class="app-topbar">
			<button class="btn btn-icon btn-ghost" aria-label="<?= _(
   	"Toggle navigation",
   ) ?>" @click="Alpine.store('ui').toggleNav()">
				<i class="fas fa-bars" aria-hidden="true"></i>
			</button>
			<strong>HestiaCP</strong>
			<span class="badge badge-accent"><?= _("Modern") ?></span>
			<span class="u-text-muted u-text-sm" style="margin-inline-start:auto"><?= htmlspecialchars(
   	$view["user"],
   ) ?></span>
			<button class="btn btn-icon btn-ghost" aria-label="<?= _(
   	"Toggle theme",
   ) ?>" @click="Alpine.store('ui').toggleTheme()">
				<i class="fas fa-circle-half-stroke" aria-hidden="true"></i>
			</button>
			<a class="btn btn-icon btn-ghost" title="<?= _(
   	"Back to legacy UI",
   ) ?>" href="/switch-ui/?ui=legacy&token=<?= htmlspecialchars($view["token"]) ?>">
				<i class="fas fa-arrow-left-long" aria-hidden="true"></i>
			</a>
		</header>

		<nav class="app-nav" aria-label="<?= _("Primary") ?>">
			<div class="nav-brand">
				<span class="brand-mark">H</span>
				<span><?= _("Control Panel") ?></span>
			</div>
			<?php foreach ($navGroups as $group): ?>
				<div class="nav-group">
					<p class="nav-group-label"><?= htmlspecialchars($group["label"]) ?></p>
					<ul class="nav-list" role="list">
						<?php foreach ($group["items"] as $item): ?>
							<li>
								<a class="nav-link" href="<?= htmlspecialchars($item["href"]) ?>"
									<?= !empty($item["current"]) ? 'aria-current="page"' : "" ?>>
									<i class="nav-icon fas fa-<?= htmlspecialchars($item["icon"]) ?>" aria-hidden="true"></i>
									<span><?= htmlspecialchars($item["label"]) ?></span>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endforeach; ?>
		</nav>
