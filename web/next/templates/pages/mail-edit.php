<?php
// web/next/templates/pages/mail-edit.php
$v_domain = $domain ?? ""; ?>
		<main class="app-main" id="main">
			<div class="app-content" style="max-width:48rem">
				<header class="u-mb-4">
					<h1><?= htmlspecialchars($v_domain) ?></h1>
					<p><?= _("Mail domain configuration.") ?></p>
				</header>
				<section class="card">
					<div class="card-header"><h2 class="card-title"><?= _("Details") ?></h2></div>
					<div class="card-body">
						<p><?= _("Accounts") ?>: <strong><?= htmlspecialchars(
	$domainData["ACCOUNTS"] ?? "0",
) ?></strong></p>
						<p><?= _("Disk") ?>: <strong><?= htmlspecialchars($domainData["U_DISK"] ?? "0") ?> <?= _(
 	"MB",
 ) ?></strong></p>
						<p><?= _("Anti-spam") ?>: <?= ($domainData["ANTISPAM"] ?? "no") === "yes"
	? _("On")
	: _("Off") ?></p>
						<p><?= _("Anti-virus") ?>: <?= ($domainData["ANTIVIRUS"] ?? "no") === "yes"
	? _("On")
	: _("Off") ?></p>
						<p><?= _("SSL") ?>: <?= ($domainData["SSL"] ?? "no") === "yes" ? _("Yes") : _("No") ?></p>
					</div>
				</section>
				<section class="card">
					<div class="card-header"><h2 class="card-title"><?= _("Danger Zone") ?></h2></div>
					<form method="post" action="/next/?p=mail-delete" class="form" x-data
						x-on:submit.prevent="if(confirm('<?= _("Delete this mail domain?") ?>')) $el.submit()">
						<input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION["token"]) ?>">
						<input type="hidden" name="domain" value="<?= htmlspecialchars($v_domain) ?>">
						<button class="btn btn-danger" type="submit"><?= _("Delete Domain") ?></button>
					</form>
				</section>
				<div class="u-mt-4">
					<a class="btn btn-ghost" href="/next/?p=mail"><?= _("Back") ?></a>
				</div>
			</div>
		</main>
