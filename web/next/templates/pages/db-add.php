<?php
// web/next/templates/pages/db-add.php
$error = "";
$success = "";
$v_db = "";
$v_dbuser = "";
$v_dbpass = "";
$v_type = "mysql";
$v_charset = "utf8mb4";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
	verify_csrf($_POST);
	$v_db = trim($_POST["v_database"] ?? "");
	$v_dbuser = trim($_POST["v_dbuser"] ?? "");
	$v_dbpass = $_POST["v_dbpass"] ?? "";
	$v_type = trim($_POST["v_type"] ?? "mysql");
	$v_charset = trim($_POST["v_charset"] ?? "utf8mb4");
	if ($v_db === "" || $v_dbuser === "" || $v_dbpass === "") {
		$error = _("Database name, user and password are required.");
	} else {
		exec(
			HESTIA_CMD .
				"v-add-database " .
				quoteshellarg($view["user"]) .
				" " .
				quoteshellarg($v_db) .
				" " .
				quoteshellarg($v_dbuser) .
				" " .
				quoteshellarg($v_dbpass) .
				" " .
				quoteshellarg($v_type) .
				" " .
				quoteshellarg("") .
				" " .
				quoteshellarg($v_charset),
			$out,
			$rv,
		);
		if ($rv === 0) {
			$success = sprintf(_("Database %s added."), $v_db);
			$v_db = $v_dbuser = "";
		} else {
			$error = implode(" ", array_slice($out, -3));
		}
	}
}
?>
		<main class="app-main" id="main">
			<div class="app-content" style="max-width:48rem">
				<header class="u-mb-4">
					<h1><?= _("Add Database") ?></h1>
					<p><?= _("Create a new database and user.") ?></p>
				</header>
				<?php if ($success): ?><div class="alert alert-success" role="status"><?= htmlspecialchars(
	$success,
) ?></div><?php endif; ?>
				<?php if ($error): ?><div class="alert alert-danger" role="alert"><?= htmlspecialchars(
	$error,
) ?></div><?php endif; ?>
				<section class="card">
					<form method="post" action="/next/?p=db-add" class="form">
						<input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION["token"]) ?>">
						<div class="form-row">
							<div class="field">
								<label class="field-label" for="v_database"><?= _("Database") ?></label>
								<input class="input" id="v_database" name="v_database" type="text" required placeholder="app_db" value="<?= htmlspecialchars(
        	$v_db,
        ) ?>" autocomplete="off">
							</div>
							<div class="field">
								<label class="field-label" for="v_dbuser"><?= _("User") ?></label>
								<input class="input" id="v_dbuser" name="v_dbuser" type="text" required placeholder="app_user" value="<?= htmlspecialchars(
        	$v_dbuser,
        ) ?>" autocomplete="off">
							</div>
						</div>
						<div class="field">
							<label class="field-label" for="v_dbpass"><?= _("Password") ?></label>
							<input class="input" id="v_dbpass" name="v_dbpass" type="password" required autocomplete="new-password">
						</div>
						<div class="form-row">
							<div class="field">
								<label class="field-label" for="v_type"><?= _("Type") ?></label>
								<select class="select" id="v_type" name="v_type">
									<option value="mysql" <?= $v_type === "mysql" ? "selected" : "" ?>>MySQL</option>
									<option value="pgsql" <?= $v_type === "pgsql" ? "selected" : "" ?>>PostgreSQL</option>
								</select>
							</div>
							<div class="field">
								<label class="field-label" for="v_charset"><?= _("Charset") ?></label>
								<input class="input" id="v_charset" name="v_charset" type="text" value="<?= htmlspecialchars(
        	$v_charset,
        ) ?>" autocomplete="off">
							</div>
						</div>
						<div class="u-flex u-gap-3">
							<button class="btn btn-primary" type="submit"><?= _("Add Database") ?></button>
							<a class="btn btn-ghost" href="/next/?p=db"><?= _("Cancel") ?></a>
						</div>
					</form>
				</section>
			</div>
		</main>
