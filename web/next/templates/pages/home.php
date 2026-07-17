<?php
// web/next/templates/pages/home.php
// Dashboard content. Expects $userData, $sysData, $tiles in scope.
?>
		<main class="app-main" id="main">
			<div class="app-content">
				<header>
					<h1><?= _("Dashboard") ?></h1>
					<p><?= sprintf(_("Welcome back, %s."), htmlspecialchars($view["user"])) ?></p>
				</header>

				<section class="tile-grid" aria-label="<?= _("Account statistics") ?>">
					<?php foreach ($tiles as $tile): ?>
						<article class="tile">
							<span class="tile-label"><?= htmlspecialchars($tile["label"]) ?></span>
							<span class="tile-value"><?= htmlspecialchars($tile["value"]) ?></span>
							<span class="tile-meta"><?= htmlspecialchars($tile["meta"]) ?></span>
						</article>
					<?php endforeach; ?>
				</section>

				<section class="card">
					<div class="card-header">
						<h2 class="card-title"><?= _("System") ?></h2>
					</div>
					<div class="card-body">
						<p><?= _("Hostname") ?>: <strong><?= htmlspecialchars($sysData["HOSTNAME"] ?? "—") ?></strong></p>
						<p><?= _("OS") ?>: <?= htmlspecialchars(
	($sysData["OS"] ?? "—") . " " . ($sysData["VERSION"] ?? ""),
) ?></p>
						<p><?= _("Uptime") ?>: <?= htmlspecialchars($sysData["UPTIME"] ?? "—") ?> min</p>
						<p>Hestia: <?= htmlspecialchars($sysData["HESTIA"] ?? "—") ?> (<?= htmlspecialchars(
 	$sysData["RELEASE"] ?? "",
 ) ?>)</p>
					</div>
				</section>
			</div>
		</main>
