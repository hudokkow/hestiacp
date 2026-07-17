<?php
use function Hestiacp\quoteshellarg\quoteshellarg;
// Main include
include $_SERVER["DOCUMENT_ROOT"] . "/inc/main.php";
// Check token
verify_csrf($_GET);

// Only allow switching when the modern UI is enabled system-wide
if ($_SESSION["POLICY_SYSTEM_ENABLE_NEXT_UI"] !== "yes") {
	header("Location: /");
	exit();
}

// Respect the per-user change policy (admins may still switch)
if ($_SESSION["POLICY_USER_CHANGE_UI"] === "no" && $_SESSION["userContext"] !== "admin") {
	header("Location: /");
	exit();
}

$allowed = ["legacy", "next"];
$ui = isset($_GET["ui"]) ? strtolower(trim($_GET["ui"])) : "legacy";
if (!in_array($ui, $allowed, true)) {
	$ui = "legacy";
}

$username = quoteshellarg($_SESSION["user"]);
$ui_arg = quoteshellarg($ui);

exec(HESTIA_CMD . "v-change-user-ui-version " . $username . " " . $ui_arg, $output, $return_var);
check_return_code($return_var, $output);
unset($output);
unset($return_var);

// Keep session in sync
unset($_SESSION["userUI"]);
$_SESSION["userUI"] = $ui;

// Redirect back to where the user came from, falling back to the dashboard
$referer = $_SERVER["HTTP_REFERER"] ?? "";
if (!empty($referer) && strpos($referer, $_SERVER["HTTP_HOST"]) !== false) {
	header("Location: " . $referer);
} else {
	header("Location: /");
}
exit();
