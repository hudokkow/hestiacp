<?php
// web/next/templates/pages/web.php
// Web domains list. Expects $domains (assoc domain=>fields) in scope.
?>
		<main class="app-main" id="main">
			<div class="app-content">
				<header class="u-flex u-items-center u-justify-between u-mb-4">
					<div>
						<h1><?= _("Web Domains") ?></h1>
						<p><?= sprintf(_("%d domain(s)"), count($domains)) ?></p>
					</div>
					<a class="btn btn-primary" href="/add/web/">
						<i class="fas fa-plus" aria-hidden="true"></i> <?= _("Add Domain") ?>
					</a>
				</header>

				<section class="card">
					<table class="data-list">
						<caption><?= _("Web domains managed by this account") ?></caption>
						<thead>
							<tr>
								<th scope="col"><?= _("Domain") ?></th>
								<th scope="col"><?= _("IP") ?></th>
								<th scope="col"><?= _("Template") ?></th>
								<th scope="col"><?= _("SSL") ?></th>
								<th scope="col"><?= _("Status") ?></th>
								<th scope="col" class="col-actions"><?= _("Actions") ?></th>
							</tr>
						</thead>
						<tbody>
							<?php if (empty($domains)): ?>
								<tr>
									<td colspan="6" class="u-text-muted u-text-sm"><?= _("No web domains yet.") ?></td>
								</tr>
							<?php else: ?>
								<?php foreach ($domains as $domain => $d): ?>
									<tr>
										<th scope="row"><a href="/edit/web/?domain=<?= urlencode($domain) ?>"><?= htmlspecialchars(
	$domain,
) ?></a></th>
										<td><?= htmlspecialchars($d["IP"] ?? "—") ?></td>
										<td><?= htmlspecialchars($d["TPL"] ?? "—") ?></td>
										<td>
											<?php if (($d["SSL"] ?? "no") === "yes"): ?>
												<span class="badge badge-success"><?= _("Yes") ?></span>
											<?php else: ?>
												<span class="badge"><?= _("No") ?></span>
											<?php endif; ?>
										</td>
										<td>
											<?php if (($d["SUSPENDED"] ?? "no") === "yes"): ?>
												<span class="badge badge-danger"><?= _("Suspended") ?></span>
											<?php else: ?>
												<span class="badge badge-accent"><?= _("Active") ?></span>
											<?php endif; ?>
										</td>
										<td class="col-actions">
											<a class="btn btn-sm btn-ghost" href="/edit/web/?domain=<?= urlencode($domain) ?>"><?= _(
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
