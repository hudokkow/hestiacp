<?php
// web/next/templates/pages/mail-add.php
$error = "";
$success = "";
$v_domain = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
	verify_csrf($_POST);
	$v_domain = trim($_POST["v_domain"] ?? "");
	$v_antispam = !empty($_POST["v_antispam"]) ? "yes" : "no";
	$v_antivirus = !empty($_POST["v_antivirus"]) ? "yes" : "no";
	$v_dkim = !empty($_POST["v_dkim"]) ? "yes" : "no";
	if ($v_domain === "") {
		$error = _("Domain name is required.");
	} else {
		exec(
			HESTIA_CMD .
				"v-add-mail-domain " .
				quoteshellarg($view["user"]) .
				" " .
				quoteshellarg($v_domain) .
				" " .
				quoteshellarg($v_antispam) .
				" " .
				quoteshellarg($v_antivirus) .
				" " .
				quoteshellarg($v_dkim),
			$out,
			$rv,
		);
		if ($rv === 0) {
			$success = sprintf(_("Mail domain %s added."), $v_domain);
			$v_domain = "";
		} else {
			$error = implode(" ", array_slice($out, -3));
		}
	}
}
?>
		<main class="app-main" id="main">
			<div class="app-content" style="max-width:48rem">
				<header class="u-mb-4">
					<h1><?= _("Add Mail Domain") ?></h1>
					<p><?= _("Create a new mail domain for this account.") ?></p>
				</header>
				<?php if ($success): ?><div class="alert alert-success" role="status"><?= htmlspecialchars(
	$success,
) ?></div><?php endif; ?>
				<?php if ($error): ?><div class="alert alert-danger" role="alert"><?= htmlspecialchars(
	$error,
) ?></div><?php endif; ?>
				<section class="card">
					<form method="post" action="/next/?p=mail-add" class="form">
						<input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION["token"]) ?>">
						<div class="field">
							<label class="field-label" for="v_domain"><?= _("Domain") ?></label>
							<input class="input" id="v_domain" name="v_domain" type="text" required placeholder="example.com" value="<?= htmlspecialchars(
       	$v_domain,
       ) ?>" autocomplete="off">
						</div>
						<div class="field">
							<label class="field-label" for="v_antispam">
								<input id="v_antispam" name="v_antispam" type="checkbox" style="width:auto;margin-inline-end:.5rem" checked>
								<?= _("Anti-spam") ?>
							</label>
						</div>
						<div class="field">
							<label class="field-label" for="v_antivirus">
								<input id="v_antivirus" name="v_antivirus" type="checkbox" style="width:auto;margin-inline-end:.5rem" checked>
								<?= _("Anti-virus") ?>
							</label>
						</div>
						<div class="field">
							<label class="field-label" for="v_dkim">
								<input id="v_dkim" name="v_dkim" type="checkbox" style="width:auto;margin-inline-end:.5rem" checked>
								<?= _("DKIM") ?>
							</label>
						</div>
						<div class="u-flex u-gap-3">
							<button class="btn btn-primary" type="submit"><?= _("Add Domain") ?></button>
							<a class="btn btn-ghost" href="/next/?p=mail"><?= _("Cancel") ?></a>
						</div>
					</form>
				</section>
			</div>
		</main>
