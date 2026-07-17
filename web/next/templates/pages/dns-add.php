<?php
// web/next/templates/pages/dns-add.php
$error = "";
$success = "";
$v_domain = "";
$v_ip = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
	verify_csrf($_POST);
	$v_domain = trim($_POST["v_domain"] ?? "");
	$v_ip = trim($_POST["v_ip"] ?? "");
	if ($v_domain === "") {
		$error = _("Domain name is required.");
	} else {
		$cmd =
			HESTIA_CMD .
			"v-add-dns-domain " .
			quoteshellarg($view["user"]) .
			" " .
			quoteshellarg($v_domain);
		$cmd .= $v_ip !== "" ? " " . quoteshellarg($v_ip) : " " . quoteshellarg("");
		$cmd .=
			" " .
			quoteshellarg("") .
			" " .
			quoteshellarg("") .
			" " .
			quoteshellarg("") .
			" " .
			quoteshellarg("") .
			" " .
			quoteshellarg("") .
			" " .
			quoteshellarg("") .
			" " .
			quoteshellarg("") .
			" " .
			quoteshellarg("yes");
		exec($cmd, $out, $rv);
		if ($rv === 0) {
			$success = sprintf(_("DNS domain %s added."), $v_domain);
			$v_domain = "";
			$v_ip = "";
		} else {
			$error = implode(" ", array_slice($out, -3));
		}
	}
}
?>
		<main class="app-main" id="main">
			<div class="app-content" style="max-width:48rem">
				<header class="u-mb-4">
					<h1><?= _("Add DNS Domain") ?></h1>
					<p><?= _("Create a new DNS zone for this account.") ?></p>
				</header>
				<?php if ($success): ?><div class="alert alert-success" role="status"><?= htmlspecialchars(
	$success,
) ?></div><?php endif; ?>
				<?php if ($error): ?><div class="alert alert-danger" role="alert"><?= htmlspecialchars(
	$error,
) ?></div><?php endif; ?>
				<section class="card">
					<form method="post" action="/next/?p=dns-add" class="form">
						<input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION["token"]) ?>">
						<div class="field">
							<label class="field-label" for="v_domain"><?= _("Domain") ?></label>
							<input class="input" id="v_domain" name="v_domain" type="text" required placeholder="example.com" value="<?= htmlspecialchars(
       	$v_domain,
       ) ?>" autocomplete="off">
						</div>
						<div class="field">
							<label class="field-label" for="v_ip"><?= _("IP Address") ?></label>
							<input class="input" id="v_ip" name="v_ip" type="text" placeholder="leave blank for shared" value="<?= htmlspecialchars(
       	$v_ip,
       ) ?>" autocomplete="off">
						</div>
						<div class="u-flex u-gap-3">
							<button class="btn btn-primary" type="submit"><?= _("Add Domain") ?></button>
							<a class="btn btn-ghost" href="/next/?p=dns"><?= _("Cancel") ?></a>
						</div>
					</form>
				</section>
			</div>
		</main>
