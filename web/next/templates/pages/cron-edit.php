<?php
// web/next/templates/pages/cron-edit.php
$error = "";
$success = "";
$v_job = $job ?? "";
$v_cmd = $jobData["CMD"] ?? "";

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
				"v-change-cron-job " .
				quoteshellarg($view["user"]) .
				" " .
				quoteshellarg($v_job) .
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
		$success = $rv === 0 ? _("Cron job updated.") : implode(" ", array_slice($out, -3));
		if ($rv === 0) {
			exec(
				HESTIA_CMD .
					"v-list-cron-job " .
					quoteshellarg($view["user"]) .
					" " .
					quoteshellarg($v_job) .
					" json",
				$o2,
				$rc2,
			);
			$jd = $rc2 === 0 ? json_decode(implode("", $o2), true) : [];
			$jobData = $jd[$v_job] ?? $jd;
		} else {
			$error = $success;
			$success = "";
		}
	}
}
?>
		<main class="app-main" id="main">
			<div class="app-content" style="max-width:48rem">
				<header class="u-mb-4">
					<h1><?= sprintf(_("Cron Job #%s"), htmlspecialchars($v_job)) ?></h1>
				</header>
				<?php if ($success): ?><div class="alert alert-success" role="status"><?= htmlspecialchars(
	$success,
) ?></div><?php endif; ?>
				<?php if ($error): ?><div class="alert alert-danger" role="alert"><?= htmlspecialchars(
	$error,
) ?></div><?php endif; ?>
				<section class="card">
					<form method="post" action="/next/?p=cron-edit&job=<?= urlencode($v_job) ?>" class="form">
						<input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION["token"]) ?>">
						<div class="form-row">
							<div class="field"><label class="field-label" for="v_min"><?= _(
       	"Minute",
       ) ?></label><input class="input" id="v_min" name="v_min" value="<?= htmlspecialchars(
	$_POST["v_min"] ?? ($jobData["MIN"] ?? "*"),
) ?>" autocomplete="off"></div>
							<div class="field"><label class="field-label" for="v_hour"><?= _(
       	"Hour",
       ) ?></label><input class="input" id="v_hour" name="v_hour" value="<?= htmlspecialchars(
	$_POST["v_hour"] ?? ($jobData["HOUR"] ?? "*"),
) ?>" autocomplete="off"></div>
							<div class="field"><label class="field-label" for="v_day"><?= _(
       	"Day",
       ) ?></label><input class="input" id="v_day" name="v_day" value="<?= htmlspecialchars(
	$_POST["v_day"] ?? ($jobData["DAY"] ?? "*"),
) ?>" autocomplete="off"></div>
							<div class="field"><label class="field-label" for="v_month"><?= _(
       	"Month",
       ) ?></label><input class="input" id="v_month" name="v_month" value="<?= htmlspecialchars(
	$_POST["v_month"] ?? ($jobData["MONTH"] ?? "*"),
) ?>" autocomplete="off"></div>
							<div class="field"><label class="field-label" for="v_wday"><?= _(
       	"Weekday",
       ) ?></label><input class="input" id="v_wday" name="v_wday" value="<?= htmlspecialchars(
	$_POST["v_wday"] ?? ($jobData["WDAY"] ?? "*"),
) ?>" autocomplete="off"></div>
						</div>
						<div class="field">
							<label class="field-label" for="v_cmd"><?= _("Command") ?></label>
							<input class="input" id="v_cmd" name="v_cmd" type="text" required value="<?= htmlspecialchars(
       	$v_cmd,
       ) ?>" autocomplete="off">
						</div>
						<div class="u-flex u-gap-3">
							<button class="btn btn-primary" type="submit"><?= _("Save") ?></button>
							<a class="btn btn-ghost" href="/next/?p=cron"><?= _("Back") ?></a>
						</div>
					</form>
				</section>
				<section class="card">
					<div class="card-header"><h2 class="card-title"><?= _("Danger Zone") ?></h2></div>
					<form method="post" action="/next/?p=cron-delete" class="form" x-data
						x-on:submit.prevent="if(confirm('<?= _("Delete this cron job?") ?>')) $el.submit()">
						<input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION["token"]) ?>">
						<input type="hidden" name="job" value="<?= htmlspecialchars($v_job) ?>">
						<button class="btn btn-danger" type="submit"><?= _("Delete Job") ?></button>
					</form>
				</section>
			</div>
		</main>
