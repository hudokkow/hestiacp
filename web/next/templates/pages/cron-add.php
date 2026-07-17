<?php
// web/next/templates/pages/cron-add.php
$error = "";
$success = "";
$v_min = "*";
$v_hour = "*";
$v_day = "*";
$v_month = "*";
$v_wday = "*";
$v_cmd = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
	verify_csrf($_POST);
	$v_min = trim($_POST["v_min"] ?? "*");
	$v_hour = trim($_POST["v_hour"] ?? "*");
	$v_day = trim($_POST["v_day"] ?? "*");
	$v_month = trim($_POST["v_month"] ?? "*");
	$v_wday = trim($_POST["v_wday"] ?? "*");
	$v_cmd = trim($_POST["v_cmd"] ?? "");
	if ($v_cmd === "") {
		$error = _("Command is required.");
	} else {
		exec(
			HESTIA_CMD .
				"v-add-cron-job " .
				quoteshellarg($view["user"]) .
				" " .
				quoteshellarg($v_min) .
				" " .
				quoteshellarg($v_hour) .
				" " .
				quoteshellarg($v_day) .
				" " .
				quoteshellarg($v_month) .
				" " .
				quoteshellarg($v_wday) .
				" " .
				quoteshellarg($v_cmd),
			$out,
			$rv,
		);
		if ($rv === 0) {
			$success = _("Cron job added.");
			$v_cmd = "";
		} else {
			$error = implode(" ", array_slice($out, -3));
		}
	}
}
?>
		<main class="app-main" id="main">
			<div class="app-content" style="max-width:48rem">
				<header class="u-mb-4">
					<h1><?= _("Add Cron Job") ?></h1>
					<p><?= _("Schedule a command using standard cron syntax.") ?></p>
				</header>
				<?php if ($success): ?><div class="alert alert-success" role="status"><?= htmlspecialchars(
	$success,
) ?></div><?php endif; ?>
				<?php if ($error): ?><div class="alert alert-danger" role="alert"><?= htmlspecialchars(
	$error,
) ?></div><?php endif; ?>
				<section class="card">
					<form method="post" action="/next/?p=cron-add" class="form">
						<input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION["token"]) ?>">
						<div class="form-row">
							<div class="field"><label class="field-label" for="v_min"><?= _(
       	"Minute",
       ) ?></label><input class="input" id="v_min" name="v_min" value="<?= htmlspecialchars(
	$v_min,
) ?>" autocomplete="off"></div>
							<div class="field"><label class="field-label" for="v_hour"><?= _(
       	"Hour",
       ) ?></label><input class="input" id="v_hour" name="v_hour" value="<?= htmlspecialchars(
	$v_hour,
) ?>" autocomplete="off"></div>
							<div class="field"><label class="field-label" for="v_day"><?= _(
       	"Day",
       ) ?></label><input class="input" id="v_day" name="v_day" value="<?= htmlspecialchars(
	$v_day,
) ?>" autocomplete="off"></div>
							<div class="field"><label class="field-label" for="v_month"><?= _(
       	"Month",
       ) ?></label><input class="input" id="v_month" name="v_month" value="<?= htmlspecialchars(
	$v_month,
) ?>" autocomplete="off"></div>
							<div class="field"><label class="field-label" for="v_wday"><?= _(
       	"Weekday",
       ) ?></label><input class="input" id="v_wday" name="v_wday" value="<?= htmlspecialchars(
	$v_wday,
) ?>" autocomplete="off"></div>
						</div>
						<div class="field">
							<label class="field-label" for="v_cmd"><?= _("Command") ?></label>
							<input class="input" id="v_cmd" name="v_cmd" type="text" required placeholder="/usr/bin/uptime" value="<?= htmlspecialchars(
       	$v_cmd,
       ) ?>" autocomplete="off">
						</div>
						<div class="u-flex u-gap-3">
							<button class="btn btn-primary" type="submit"><?= _("Add Job") ?></button>
							<a class="btn btn-ghost" href="/next/?p=cron"><?= _("Cancel") ?></a>
						</div>
					</form>
				</section>
			</div>
		</main>
