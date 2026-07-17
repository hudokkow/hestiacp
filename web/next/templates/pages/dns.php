<?php
// web/next/templates/pages/dns.php
// DNS domains list. Expects $domains (assoc domain=>fields) in scope.
?>
		<main class="app-main" id="main">
			<div class="app-content">
				<header class="u-flex u-items-center u-justify-between u-mb-4">
					<div>
						<h1><?= _("DNS Domains") ?></h1>
						<p><?= sprintf(_("%d domain(s)"), count($domains)) ?></p>
					</div>
					<a class="btn btn-primary" href="/add/dns/">
						<i class="fas fa-plus" aria-hidden="true"></i> <?= _("Add Domain") ?>
					</a>
				</header>

				<section class="card">
					<table class="data-list">
						<caption><?= _("DNS domains managed by this account") ?></caption>
						<thead>
							<tr>
								<th scope="col"><?= _("Domain") ?></th>
								<th scope="col"><?= _("IP") ?></th>
								<th scope="col"><?= _("Template") ?></th>
								<th scope="col"><?= _("Records") ?></th>
								<th scope="col"><?= _("DNSSEC") ?></th>
								<th scope="col"><?= _("Status") ?></th>
								<th scope="col" class="col-actions"><?= _("Actions") ?></th>
							</tr>
						</thead>
						<tbody>
							<?php if (empty($domains)): ?>
								<tr><td colspan="7" class="u-text-muted u-text-sm"><?= _("No DNS domains yet.") ?></td></tr>
							<?php else: ?>
								<?php foreach ($domains as $domain => $d): ?>
									<tr>
										<th scope="row"><a href="/edit/dns/?domain=<?= urlencode($domain) ?>"><?= htmlspecialchars(
	$domain,
) ?></a></th>
										<td><?= htmlspecialchars($d["IP"] ?? "—") ?></td>
										<td><?= htmlspecialchars($d["TPL"] ?? "—") ?></td>
										<td><?= htmlspecialchars($d["RECORDS"] ?? "0") ?></td>
										<td>
											<?php if (($d["DNSSEC"] ?? "no") === "yes"): ?>
												<span class="badge badge-success"><?= _("On") ?></span>
											<?php else: ?>
												<span class="badge"><?= _("Off") ?></span>
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
											<a class="btn btn-sm btn-ghost" href="/edit/dns/?domain=<?= urlencode($domain) ?>"><?= _(
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
