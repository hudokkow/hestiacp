<?php
// web/next/templates/pages/db-edit.php
$error = "";
$success = "";
$v_db = $db ?? "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
	verify_csrf($_POST);
	$v_dbpass = $_POST["v_dbpass"] ?? "";
	if ($v_dbpass !== "") {
		exec(
			HESTIA_CMD .
				"v-change-database-password " .
				quoteshellarg($view["user"]) .
				" " .
				quoteshellarg($v_db) .
				" " .
				quoteshellarg($v_dbuser) .
				" " .
				quoteshellarg($v_dbpass),
			$out,
			$rv,
		);
		if ($rv !== 0) {
			$error = implode(" ", array_slice($out, -3));
		} else {
			$success = sprintf(_("Password for %s updated."), $v_db);
		}
	} else {
		$success = _("No changes made.");
	}
	exec(HESTIA_CMD . "v-list-databases " . quoteshellarg($view["user"]) . " json", $out2, $rc);
	$databases = $rc === 0 ? json_decode(implode("", $out2), true) : [];
	$dbData = $databases[$v_db] ?? [];
}
?>
		<main class="app-main" id="main">
			<div class="app-content" style="max-width:48rem">
				<header class="u-mb-4">
					<h1><?= htmlspecialchars($v_db) ?></h1>
					<p><?= _("Database configuration.") ?></p>
				</header>
				<?php if ($success): ?><div class="alert alert-success" role="status"><?= htmlspecialchars(
	$success,
) ?></div><?php endif; ?>
				<?php if ($error): ?><div class="alert alert-danger" role="alert"><?= htmlspecialchars(
	$error,
) ?></div><?php endif; ?>
				<section class="card">
					<div class="card-body">
						<p><?= _("Type") ?>: <strong><?= htmlspecialchars($dbData["TYPE"] ?? "—") ?></strong></p>
						<p><?= _("User") ?>: <strong><?= htmlspecialchars($dbData["DBUSER"] ?? "—") ?></strong></p>
						<p><?= _("Host") ?>: <strong><?= htmlspecialchars($dbData["HOST"] ?? "—") ?></strong></p>
						<p><?= _("Charset") ?>: <strong><?= htmlspecialchars($dbData["CHARSET"] ?? "—") ?></strong></p>
					</div>
				</section>
				<section class="card">
					<form method="post" action="/next/?p=db-edit&database=<?= urlencode($v_db) ?>" class="form">
						<input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION["token"]) ?>">
						<input type="hidden" name="v_dbuser" value="<?= htmlspecialchars($dbData["DBUSER"] ?? "") ?>">
						<div class="field">
							<label class="field-label" for="v_dbpass"><?= _("New Password") ?></label>
							<input class="input" id="v_dbpass" name="v_dbpass" type="password" autocomplete="new-password">
							<span class="help-text"><?= _("Leave blank to keep current password.") ?></span>
						</div>
						<div class="u-flex u-gap-3">
							<button class="btn btn-primary" type="submit"><?= _("Update Password") ?></button>
							<a class="btn btn-ghost" href="/next/?p=db"><?= _("Back") ?></a>
						</div>
					</form>
				</section>
				<section class="card">
					<div class="card-header"><h2 class="card-title"><?= _("Danger Zone") ?></h2></div>
					<form method="post" action="/next/?p=db-delete" class="form" x-data
						x-on:submit.prevent="if(confirm('<?= _("Delete this database?") ?>')) $el.submit()">
						<input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION["token"]) ?>">
						<input type="hidden" name="database" value="<?= htmlspecialchars($v_db) ?>">
						<button class="btn btn-danger" type="submit"><?= _("Delete Database") ?></button>
					</form>
				</section>
			</div>
		</main>
