<?php
// web/next/templates/pages/backup.php
// Backups list. Expects $backups (assoc backupid=>fields) in scope.
?>
		<main class="app-main" id="main">
			<div class="app-content">
				<header class="u-flex u-items-center u-justify-between u-mb-4">
					<div>
						<h1><?= _("Backups") ?></h1>
						<p><?= sprintf(_("%d backup(s)"), count($backups)) ?></p>
					</div>
					<form method="post" action="/next/?p=backup-create" class="u-inline">
						<input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION["token"]) ?>">
						<button class="btn btn-primary" type="submit">
							<i class="fas fa-plus" aria-hidden="true"></i> <?= _("Create Backup") ?>
						</button>
					</form>
				</header>

				<section class="card">
					<table class="data-list">
						<caption><?= _("Backups for this account") ?></caption>
						<thead>
							<tr>
								<th scope="col"><?= _("Backup") ?></th>
								<th scope="col"><?= _("Type") ?></th>
								<th scope="col"><?= _("Size") ?></th>
								<th scope="col"><?= _("Includes") ?></th>
								<th scope="col"><?= _("Date") ?></th>
								<th scope="col" class="col-actions"><?= _("Actions") ?></th>
							</tr>
						</thead>
						<tbody>
							<?php if (empty($backups)): ?>
								<tr><td colspan="6" class="u-text-muted u-text-sm"><?= _("No backups yet.") ?></td></tr>
							<?php else: ?>
								<?php foreach ($backups as $id => $b): ?>
									<?php $includes = array_filter([
         	($b["WEB"] ?? "no") === "yes" ? _("Web") : null,
         	($b["DNS"] ?? "no") === "yes" ? _("DNS") : null,
         	($b["MAIL"] ?? "no") === "yes" ? _("Mail") : null,
         	($b["DB"] ?? "no") === "yes" ? _("DB") : null,
         	($b["CRON"] ?? "no") === "yes" ? _("Cron") : null,
         	($b["UDIR"] ?? "no") === "yes" ? _("Home") : null,
         ]); ?>
									<tr>
										<th scope="row"><?= htmlspecialchars($id) ?></th>
										<td><?= htmlspecialchars($b["TYPE"] ?? "—") ?></td>
										<td><?= htmlspecialchars($b["SIZE"] ?? "—") ?></td>
										<td><?= htmlspecialchars(implode(", ", $includes)) ?></td>
										<td><?= htmlspecialchars($b["DATE"] ?? "—") ?></td>
										<td class="col-actions">
											<a class="btn btn-sm btn-ghost" href="/download/backup/?backup=<?= urlencode($id) ?>"><?= _(
	"Download",
) ?></a>
											<form method="post" action="/next/?p=backup-delete" class="u-inline" x-data
												x-on:submit.prevent="if(confirm('<?= _("Delete this backup?") ?>')) $el.submit()">
												<input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION["token"]) ?>">
												<input type="hidden" name="backup" value="<?= htmlspecialchars($id) ?>">
												<button class="btn btn-sm btn-ghost" type="submit"><?= _("Delete") ?></button>
											</form>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php endif; ?>
						</tbody>
					</table>
				</section>
			</div>
		</main>
