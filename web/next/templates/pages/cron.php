<?php
// web/next/templates/pages/cron.php
// Cron jobs list. Expects $jobs (assoc jobid=>fields) in scope.
?>
		<main class="app-main" id="main">
			<div class="app-content">
				<header class="u-flex u-items-center u-justify-between u-mb-4">
					<div>
						<h1><?= _("Cron Jobs") ?></h1>
						<p><?= sprintf(_("%d job(s)"), count($jobs)) ?></p>
					</div>
					<a class="btn btn-primary" href="/next/?p=cron-add">
						<i class="fas fa-plus" aria-hidden="true"></i> <?= _("Add Job") ?>
					</a>
				</header>

				<section class="card">
					<table class="data-list">
						<caption><?= _("Scheduled cron jobs for this account") ?></caption>
						<thead>
							<tr>
								<th scope="col"><?= _("Job") ?></th>
								<th scope="col"><?= _("Schedule") ?></th>
								<th scope="col"><?= _("Command") ?></th>
								<th scope="col"><?= _("Status") ?></th>
								<th scope="col" class="col-actions"><?= _("Actions") ?></th>
							</tr>
						</thead>
						<tbody>
							<?php if (empty($jobs)): ?>
								<tr><td colspan="5" class="u-text-muted u-text-sm"><?= _("No cron jobs yet.") ?></td></tr>
							<?php else: ?>
								<?php foreach ($jobs as $job => $j): ?>
									<?php $schedule = sprintf(
         	"%s %s %s %s %s",
         	$j["MIN"] ?? "*",
         	$j["HOUR"] ?? "*",
         	$j["DAY"] ?? "*",
         	$j["MONTH"] ?? "*",
         	$j["WDAY"] ?? "*",
         ); ?>
									<tr>
										<th scope="row"><?= htmlspecialchars($job) ?></th>
										<td><code><?= htmlspecialchars($schedule) ?></code></td>
										<td><code><?= htmlspecialchars($j["CMD"] ?? "—") ?></code></td>
										<td>
											<?php if (($j["SUSPENDED"] ?? "no") === "yes"): ?>
												<span class="badge badge-danger"><?= _("Suspended") ?></span>
											<?php else: ?>
												<span class="badge badge-accent"><?= _("Active") ?></span>
											<?php endif; ?>
										</td>
										<td class="col-actions">
											<a class="btn btn-sm btn-ghost" href="/next/?p=cron-edit&job=<?= urlencode($job) ?>"><?= _(
	"Edit",
) ?></a>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php endif; ?>
						</tbody>
					</table>
				</section>
			</div>
		</main>
