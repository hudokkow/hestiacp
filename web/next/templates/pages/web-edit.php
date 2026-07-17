<?php
// web/next/templates/pages/web-edit.php
// Edit web domain form.
$error = "";
$success = "";
$v_domain = $domain ?? "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
	verify_csrf($_POST);
	$v_ssl = !empty($_POST["v_ssl"]);
	$v_aliases = trim($_POST["v_aliases"] ?? "");

	// Toggle SSL via letsencrypt add/delete
	$current_ssl = ($domainData["SSL"] ?? "no") === "yes";
	if ($v_ssl && !$current_ssl) {
		exec(
			HESTIA_CMD .
				"v-add-letsencrypt-domain " .
				quoteshellarg($view["user"]) .
				" " .
				quoteshellarg($v_domain) .
				" " .
				quoteshellarg("") .
				" " .
				quoteshellarg("yes"),
			$o,
			$rv,
		);
	} elseif (!$v_ssl && $current_ssl) {
		exec(
			HESTIA_CMD .
				"v-delete-letsencrypt-domain " .
				quoteshellarg($view["user"]) .
				" " .
				quoteshellarg($v_domain),
			$o2,
			$rv2,
		);
	}

	// Update aliases if changed
	if ($v_aliases !== ($domainData["ALIAS"] ?? "")) {
		exec(
			HESTIA_CMD .
				"v-change-web-domain " .
				quoteshellarg($view["user"]) .
				" " .
				quoteshellarg($v_domain) .
				" " .
				quoteshellarg("") .
				" " .
				quoteshellarg($v_aliases),
			$o3,
			$rv3,
		);
	}
	$success = sprintf(_("Domain %s updated."), $v_domain);

	// Refresh data
	exec(
		HESTIA_CMD .
			"v-list-web-domain " .
			quoteshellarg($view["user"]) .
			" " .
			quoteshellarg($v_domain) .
			" json",
		$out,
		$rc,
	);
	$domainData = $rc === 0 ? json_decode(implode("", $out), true) : [];
	$domainData = $domainData[$v_domain] ?? $domainData;
}
?>
		<main class="app-main" id="main">
			<div class="app-content" style="max-width:48rem">
				<header class="u-mb-4">
					<h1><?= htmlspecialchars($v_domain) ?></h1>
					<p><?= _("Web domain configuration.") ?></p>
				</header>

				<?php if ($success): ?>
					<div class="alert alert-success" role="status"><?= htmlspecialchars($success) ?></div>
				<?php endif; ?>
				<?php if ($error): ?>
					<div class="alert alert-danger" role="alert"><?= htmlspecialchars($error) ?></div>
				<?php endif; ?>

				<section class="card">
					<form method="post" action="/next/?p=web-edit&domain=<?= urlencode($v_domain) ?>" class="form">
						<input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION["token"]) ?>">
						<div class="field">
							<span class="field-label"><?= _("IP Address") ?></span>
							<input class="input" type="text" value="<?= htmlspecialchars($domainData["IP"] ?? "—") ?>" disabled>
						</div>
						<div class="field">
							<span class="field-label"><?= _("Template") ?></span>
							<input class="input" type="text" value="<?= htmlspecialchars(
       	$domainData["TPL"] ?? "—",
       ) ?>" disabled>
						</div>
						<div class="field">
							<label class="field-label" for="v_aliases"><?= _("Aliases") ?></label>
							<input class="input" id="v_aliases" name="v_aliases" type="text"
								value="<?= htmlspecialchars($domainData["ALIAS"] ?? "") ?>" autocomplete="off">
						</div>
						<div class="field">
							<label class="field-label" for="v_ssl">
								<input id="v_ssl" name="v_ssl" type="checkbox" style="width:auto;margin-inline-end:.5rem"
									<?= ($domainData["SSL"] ?? "no") === "yes" ? "checked" : "" ?>>
								<?= _("SSL enabled (Let's Encrypt)") ?>
							</label>
						</div>
						<div class="u-flex u-gap-3">
							<button class="btn btn-primary" type="submit"><?= _("Save") ?></button>
							<a class="btn btn-ghost" href="/next/?p=web"><?= _("Back") ?></a>
						</div>
					</form>
				</section>

				<section class="card">
					<div class="card-header">
						<h2 class="card-title"><?= _("Danger Zone") ?></h2>
					</div>
					<form method="post" action="/next/?p=web-delete" class="form" x-data
						x-on:submit.prevent="if(confirm('<?= _(
      	"Delete this domain? This cannot be undone.",
      ) ?>')) $el.submit()">
						<input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION["token"]) ?>">
						<input type="hidden" name="domain" value="<?= htmlspecialchars($v_domain) ?>">
						<button class="btn btn-danger" type="submit"><?= _("Delete Domain") ?></button>
					</form>
				</section>
			</div>
		</main>
