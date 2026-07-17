<?php
// web/next/templates/pages/web-add.php
// Add web domain form.
$error = "";
$success = "";
$v_domain = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
	verify_csrf($_POST);
	$v_domain = trim($_POST["v_domain"] ?? "");
	$v_ip = trim($_POST["v_ip"] ?? "");
	$v_aliases = trim($_POST["v_aliases"] ?? "");
	$v_ssl = !empty($_POST["v_ssl"]);

	if ($v_domain === "") {
		$error = _("Domain name is required.");
	} else {
		$cmd =
			HESTIA_CMD .
			"v-add-web-domain " .
			quoteshellarg($view["user"]) .
			" " .
			quoteshellarg($v_domain);
		$cmd .= $v_ip !== "" ? " " . quoteshellarg($v_ip) : " " . quoteshellarg("");
		$cmd .= " " . quoteshellarg("no"); // restart
		$cmd .= $v_aliases !== "" ? " " . quoteshellarg($v_aliases) : "";
		exec($cmd, $out, $return_var);
		if ($return_var === 0) {
			if ($v_ssl) {
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
					$out2,
					$rv2,
				);
			}
			$success = sprintf(_("Domain %s added."), $v_domain);
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
					<h1><?= _("Add Web Domain") ?></h1>
					<p><?= _("Create a new virtual host for this account.") ?></p>
				</header>

				<?php if ($success): ?>
					<div class="alert alert-success" role="status"><?= htmlspecialchars($success) ?></div>
				<?php endif; ?>
				<?php if ($error): ?>
					<div class="alert alert-danger" role="alert"><?= htmlspecialchars($error) ?></div>
				<?php endif; ?>

				<section class="card">
					<form method="post" action="/next/?p=web-add" class="form">
						<input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION["token"]) ?>">
						<div class="field">
							<label class="field-label" for="v_domain"><?= _("Domain") ?></label>
							<input class="input" id="v_domain" name="v_domain" type="text" required
								placeholder="example.com" value="<?= htmlspecialchars($v_domain) ?>" autocomplete="off">
							<span class="help-text"><?= _("Fully qualified domain name, e.g. example.com") ?></span>
						</div>
						<div class="field">
							<label class="field-label" for="v_ip"><?= _("IP Address") ?></label>
							<input class="input" id="v_ip" name="v_ip" type="text"
								placeholder="leave blank for shared" autocomplete="off">
						</div>
						<div class="field">
							<label class="field-label" for="v_aliases"><?= _("Aliases") ?></label>
							<input class="input" id="v_aliases" name="v_aliases" type="text"
								placeholder="www.example.com" autocomplete="off">
						</div>
						<div class="field">
							<label class="field-label" for="v_ssl">
								<input id="v_ssl" name="v_ssl" type="checkbox" style="width:auto;margin-inline-end:.5rem">
								<?= _("Issue Let's Encrypt SSL certificate") ?>
							</label>
						</div>
						<div class="u-flex u-gap-3">
							<button class="btn btn-primary" type="submit"><?= _("Add Domain") ?></button>
							<a class="btn btn-ghost" href="/next/?p=web"><?= _("Cancel") ?></a>
						</div>
					</form>
				</section>
			</div>
		</main>
