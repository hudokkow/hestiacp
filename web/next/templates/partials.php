<?php
// web/next/templates/partials.php
// Shared helpers + navigation model for the next UI templates.

if (!function_exists("next_nav_groups")) {
	function next_nav_groups($user, $current = "home") {
		$map = [
			"home" => "/next/",
			"web" => "/next/?p=web",
		];
		$items = [
			[
				"label" => _("Manage"),
				"items" => [
					[
						"label" => _("Dashboard"),
						"href" => "/next/",
						"icon" => "home",
						"key" => "home",
					],
					[
						"label" => _("Web"),
						"href" => "/next/?p=web",
						"icon" => "globe",
						"key" => "web",
					],
					[
						"label" => _("DNS"),
						"href" => "/next/?p=dns",
						"icon" => "network-wired",
						"key" => "dns",
					],
					[
						"label" => _("Mail"),
						"href" => "/next/?p=mail",
						"icon" => "envelope",
						"key" => "mail",
					],
					[
						"label" => _("Databases"),
						"href" => "/next/?p=db",
						"icon" => "database",
						"key" => "db",
					],
					[
						"label" => _("Backups"),
						"href" => "/next/?p=backup",
						"icon" => "box-archive",
						"key" => "backup",
					],
				],
			],
			[
				"label" => _("System"),
				"items" => [
					[
						"label" => _("Cron"),
						"href" => "/next/?p=cron",
						"icon" => "clock",
						"key" => "cron",
					],
					["label" => _("Services"), "href" => "/list/services/", "icon" => "server"],
					[
						"label" => _("Firewall"),
						"href" => "/list/firewall/",
						"icon" => "shield-halved",
					],
					[
						"label" => _("Settings"),
						"href" => "/edit/user/?user=" . urlencode($user),
						"icon" => "gear",
					],
				],
			],
		];
		foreach ($items as &$group) {
			foreach ($group["items"] as &$item) {
				if (isset($item["key"]) && $item["key"] === $current) {
					$item["current"] = true;
				}
			}
		}
		return $items;
	}
}
