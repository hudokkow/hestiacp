<?php
// web/next/templates/pages/db.php
// Databases list. Expects $databases (assoc dbname=>fields) in scope.
?>
		<main class="app-main" id="main">
			<div class="app-content">
				<header class="u-flex u-items-center u-justify-between u-mb-4">
					<div>
						<h1><?= _("Databases") ?></h1>
						<p><?= sprintf(_("%d database(s)"), count($databases)) ?></p>
					</div>
					<a class="btn btn-primary" href="/add/db/">
						<i class="fas fa-plus" aria-hidden="true"></i> <?= _("Add Database") ?>
					</a>
				</header>

				<section class="card">
					<table class="data-list">
						<caption><?= _("Databases managed by this account") ?></caption>
						<thead>
							<tr>
								<th scope="col"><?= _("Database") ?></th>
								<th scope="col"><?= _("Type") ?></th>
								<th scope="col"><?= _("User") ?></th>
								<th scope="col"><?= _("Host") ?></th>
								<th scope="col"><?= _("Charset") ?></th>
								<th scope="col"><?= _("Status") ?></th>
								<th scope="col" class="col-actions"><?= _("Actions") ?></th>
							</tr>
						</thead>
						<tbody>
							<?php if (empty($databases)): ?>
								<tr><td colspan="7" class="u-text-muted u-text-sm"><?= _("No databases yet.") ?></td></tr>
							<?php else: ?>
								<?php foreach ($databases as $db => $d): ?>
									<tr>
										<th scope="row"><a href="/edit/db/?database=<?= urlencode($db) ?>"><?= htmlspecialchars(
	$db,
) ?></a></th>
										<td><?= htmlspecialchars($d["TYPE"] ?? "—") ?></td>
										<td><?= htmlspecialchars($d["DBUSER"] ?? "—") ?></td>
										<td><?= htmlspecialchars($d["HOST"] ?? "—") ?></td>
										<td><?= htmlspecialchars($d["CHARSET"] ?? "—") ?></td>
										<td>
											<?php if (($d["SUSPENDED"] ?? "no") === "yes"): ?>
												<span class="badge badge-danger"><?= _("Suspended") ?></span>
											<?php else: ?>
												<span class="badge badge-accent"><?= _("Active") ?></span>
											<?php endif; ?>
										</td>
										<td class="col-actions">
											<a class="btn btn-sm btn-ghost" href="/edit/db/?database=<?= urlencode($db) ?>"><?= _("Edit") ?></a>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php endif; ?>
						</tbody>
					</table>
				</section>
			</div>
		</main>
