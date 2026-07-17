<?php
// Next UI front controller — explicit view model, no render_page() / extract($GLOBALS).
include $_SERVER["DOCUMENT_ROOT"] . "/inc/main.php";

if (($_SESSION["userUI"] ?? "legacy") !== "next") {
	header("Location: /");
	exit();
}

// Explicit view model
$view = [
	"user" => $_SESSION["user"] ?? "",
	"role" => $_SESSION["userContext"] ?? "user",
	"theme" => $_SESSION["userTheme"] ?? ($_SESSION["THEME"] ?? "dark"),
	"token" => $_SESSION["token"] ?? "",
];

// Pull shared real data through existing CLIs (never web/api).
exec(HESTIA_CMD . "v-list-sys-info json", $sysOut, $rc2);
$sysRaw = $rc2 === 0 ? json_decode(implode("", $sysOut), true) : [];
$sysData = $sysRaw["sysinfo"] ?? [];

// Route
$page = preg_replace("/[^a-z]/", "", $_GET["p"] ?? "home");

function next_stat_tile($label, $value, $meta = "") {
	return ["label" => $label, "value" => $value, "meta" => $meta];
}

switch ($page) {
	case "web":
		$view["page_title"] = _("Web Domains");
		exec(
			HESTIA_CMD . "v-list-web-domains " . quoteshellarg($view["user"]) . " json",
			$out,
			$rc,
		);
		$domains = $rc === 0 ? json_decode(implode("", $out), true) : [];
		$content = __DIR__ . "/templates/pages/web.php";
		break;

	case "dns":
		$view["page_title"] = _("DNS Domains");
		exec(
			HESTIA_CMD . "v-list-dns-domains " . quoteshellarg($view["user"]) . " json",
			$out,
			$rc,
		);
		$domains = $rc === 0 ? json_decode(implode("", $out), true) : [];
		$content = __DIR__ . "/templates/pages/dns.php";
		break;

	case "mail":
		$view["page_title"] = _("Mail Domains");
		exec(
			HESTIA_CMD . "v-list-mail-domains " . quoteshellarg($view["user"]) . " json",
			$out,
			$rc,
		);
		$domains = $rc === 0 ? json_decode(implode("", $out), true) : [];
		$content = __DIR__ . "/templates/pages/mail.php";
		break;

	case "db":
		$view["page_title"] = _("Databases");
		exec(HESTIA_CMD . "v-list-databases " . quoteshellarg($view["user"]) . " json", $out, $rc);
		$databases = $rc === 0 ? json_decode(implode("", $out), true) : [];
		$content = __DIR__ . "/templates/pages/db.php";
		break;

	case "cron":
		$view["page_title"] = _("Cron Jobs");
		exec(HESTIA_CMD . "v-list-cron-jobs " . quoteshellarg($view["user"]) . " json", $out, $rc);
		$jobs = $rc === 0 ? json_decode(implode("", $out), true) : [];
		$content = __DIR__ . "/templates/pages/cron.php";
		break;

	case "backup":
		$view["page_title"] = _("Backups");
		exec(
			HESTIA_CMD . "v-list-user-backups " . quoteshellarg($view["user"]) . " json",
			$out,
			$rc,
		);
		$backups = $rc === 0 ? json_decode(implode("", $out), true) : [];
		$content = __DIR__ . "/templates/pages/backup.php";
		break;

	case "web-add":
		$view["page_title"] = _("Add Web Domain");
		$content = __DIR__ . "/templates/pages/web-add.php";
		break;

	case "web-edit":
		$view["page_title"] = _("Edit Web Domain");
		$domain = preg_replace("/[^a-zA-Z0-9.\-]/", "", $_GET["domain"] ?? "");
		exec(
			HESTIA_CMD .
				"v-list-web-domain " .
				quoteshellarg($view["user"]) .
				" " .
				quoteshellarg($domain) .
				" json",
			$out,
			$rc,
		);
		$domainData = $rc === 0 ? json_decode(implode("", $out), true) : [];
		$domainData = $domainData[$domain] ?? [];
		$content = __DIR__ . "/templates/pages/web-edit.php";
		break;

	case "web-delete":
		if ($_SERVER["REQUEST_METHOD"] === "POST") {
			verify_csrf($_POST);
			$del = preg_replace("/[^a-zA-Z0-9.\-]/", "", $_POST["domain"] ?? "");
			exec(
				HESTIA_CMD .
					"v-delete-domain " .
					quoteshellarg($view["user"]) .
					" " .
					quoteshellarg($del),
				$out,
				$rc,
			);
		}
		header("Location: /next/?p=web");
		exit();

	case "dns-add":
		$view["page_title"] = _("Add DNS Domain");
		$content = __DIR__ . "/templates/pages/dns-add.php";
		break;

	case "dns-edit":
		$view["page_title"] = _("Edit DNS Domain");
		$domain = preg_replace("/[^a-zA-Z0-9.\-]/", "", $_GET["domain"] ?? "");
		exec(
			HESTIA_CMD .
				"v-list-dns-domain " .
				quoteshellarg($view["user"]) .
				" " .
				quoteshellarg($domain) .
				" json",
			$out,
			$rc,
		);
		$domainData = $rc === 0 ? json_decode(implode("", $out), true) : [];
		$domainData = $domainData[$domain] ?? [];
		$content = __DIR__ . "/templates/pages/dns-edit.php";
		break;

	case "dns-delete":
		if ($_SERVER["REQUEST_METHOD"] === "POST") {
			verify_csrf($_POST);
			$del = preg_replace("/[^a-zA-Z0-9.\-]/", "", $_POST["domain"] ?? "");
			exec(
				HESTIA_CMD .
					"v-delete-dns-domain " .
					quoteshellarg($view["user"]) .
					" " .
					quoteshellarg($del),
				$out,
				$rc,
			);
		}
		header("Location: /next/?p=dns");
		exit();

	case "mail-edit":
		$view["page_title"] = _("Edit Mail Domain");
		$domain = preg_replace("/[^a-zA-Z0-9.\-]/", "", $_GET["domain"] ?? "");
		exec(
			HESTIA_CMD .
				"v-list-mail-domain " .
				quoteshellarg($view["user"]) .
				" " .
				quoteshellarg($domain) .
				" json",
			$out,
			$rc,
		);
		$domainData = $rc === 0 ? json_decode(implode("", $out), true) : [];
		$domainData = $domainData[$domain] ?? [];
		$content = __DIR__ . "/templates/pages/mail-edit.php";
		break;

	case "mail-delete":
		if ($_SERVER["REQUEST_METHOD"] === "POST") {
			verify_csrf($_POST);
			$del = preg_replace("/[^a-zA-Z0-9.\-]/", "", $_POST["domain"] ?? "");
			exec(
				HESTIA_CMD .
					"v-delete-mail-domain " .
					quoteshellarg($view["user"]) .
					" " .
					quoteshellarg($del),
				$out,
				$rc,
			);
		}
		header("Location: /next/?p=mail");
		exit();

	case "db-add":
		$view["page_title"] = _("Add Database");
		$content = __DIR__ . "/templates/pages/db-add.php";
		break;

	case "db-edit":
		$view["page_title"] = _("Edit Database");
		$db = preg_replace("/[^a-zA-Z0-9_]/", "", $_GET["database"] ?? "");
		exec(HESTIA_CMD . "v-list-databases " . quoteshellarg($view["user"]) . " json", $out, $rc);
		$databases = $rc === 0 ? json_decode(implode("", $out), true) : [];
		$dbData = $databases[$db] ?? [];
		$content = __DIR__ . "/templates/pages/db-edit.php";
		break;

	case "db-delete":
		if ($_SERVER["REQUEST_METHOD"] === "POST") {
			verify_csrf($_POST);
			$del = preg_replace("/[^a-zA-Z0-9_]/", "", $_POST["database"] ?? "");
			exec(
				HESTIA_CMD .
					"v-delete-database " .
					quoteshellarg($view["user"]) .
					" " .
					quoteshellarg($del),
				$out,
				$rc,
			);
		}
		header("Location: /next/?p=db");
		exit();

	case "home":
	default:
		$page = "home";
		$view["page_title"] = _("Dashboard");
		exec(HESTIA_CMD . "v-list-user " . quoteshellarg($view["user"]) . " json", $userOut, $rc);
		$userData = $rc === 0 ? json_decode(implode("", $userOut), true) : [];
		$userData = $userData[$view["user"]] ?? [];
		$tiles = [
			next_stat_tile(
				_("Web Domains"),
				$userData["WEB_DOMAINS"] ?? "0",
				sprintf(_("of %s used"), $userData["U_WEB_DOMAINS"] ?? "0"),
			),
			next_stat_tile(
				_("DNS Domains"),
				$userData["DNS_DOMAINS"] ?? "0",
				sprintf(_("of %s used"), $userData["U_DNS_DOMAINS"] ?? "0"),
			),
			next_stat_tile(
				_("Mail Domains"),
				$userData["MAIL_DOMAINS"] ?? "0",
				sprintf(_("of %s used"), $userData["U_MAIL_DOMAINS"] ?? "0"),
			),
			next_stat_tile(
				_("Databases"),
				$userData["DATABASES"] ?? "0",
				sprintf(_("of %s used"), $userData["U_DATABASES"] ?? "0"),
			),
		];
		$content = __DIR__ . "/templates/pages/home.php";
		break;
}

include __DIR__ . "/templates/header.php";
include $content;
include __DIR__ . "/templates/footer.php";
