<?php
// web/next/templates/footer.php
// Right rail (server info + activity) and closing markup. Expects $sysData in scope.
?>
		<aside class="app-rail" aria-label="<?= _("Server information") ?>">
			<div class="rail-section">
				<h2 class="rail-title"><?= _("Server") ?></h2>
				<div class="rail-stat">
					<span class="rail-stat-label"><?= _("Load") ?></span>
					<span class="rail-stat-value"><?= htmlspecialchars($sysData["LOADAVERAGE"] ?? "—") ?></span>
				</div>
				<div class="rail-stat">
					<span class="rail-stat-label"><?= _("OS") ?></span>
					<span class="rail-stat-value"><?= htmlspecialchars($sysData["OS"] ?? "—") ?></span>
				</div>
				<div class="rail-stat">
					<span class="rail-stat-label"><?= _("Arch") ?></span>
					<span class="rail-stat-value"><?= htmlspecialchars($sysData["ARCH"] ?? "—") ?></span>
				</div>
			</div>
			<div class="rail-section" x-data="logFeed">
				<h2 class="rail-title"><?= _("Recent Activity") ?></h2>
				<div class="log-feed" aria-live="polite">
					<template x-for="log in logs" :key="log.time + log.msg">
						<div class="log-item" :data-level="log.level">
							<span class="log-time" x-text="log.time"></span>
							<span class="log-msg" x-text="log.msg"></span>
						</div>
					</template>
					<p class="u-text-sm u-text-muted" x-show="logs.length === 0"><?= _("No recent events.") ?></p>
				</div>
			</div>
		</aside>
	</div>
</body>
</html>
