<?php
// web/next/templates/pages/dns-edit.php
$error = "";
$success = "";
$v_domain = $domain ?? "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
	verify_csrf($_POST);
	$v_ip = trim($_POST["v_ip"] ?? "");
	$v_ttl = trim($_POST["v_ttl"] ?? "");
	$v_soa = trim($_POST["v_soa"] ?? "");
	$v_dnssec = !empty($_POST["v_dnssec"]);

	exec(
		HESTIA_CMD .
			"v-change-dns-domain-ip " .
			quoteshellarg($view["user"]) .
			" " .
			quoteshellarg($v_domain) .
			" " .
			quoteshellarg($v_ip),
		$o,
		$rv,
	);
	if ($v_ttl !== ($domainData["TTL"] ?? "")) {
		exec(
			HESTIA_CMD .
				"v-change-dns-domain-ttl " .
				quoteshellarg($view["user"]) .
				" " .
				quoteshellarg($v_domain) .
				" " .
				quoteshellarg($v_ttl),
			$o2,
			$rv2,
		);
	}
	if ($v_soa !== ($domainData["SOA"] ?? "")) {
		exec(
			HESTIA_CMD .
				"v-change-dns-domain-soa " .
				quoteshellarg($view["user"]) .
				" " .
				quoteshellarg($v_domain) .
				" " .
				quoteshellarg($v_soa),
			$o3,
			$rv3,
		);
	}
	$cur = ($domainData["DNSSEC"] ?? "no") === "yes";
	if ($v_dnssec !== $cur) {
		exec(
			HESTIA_CMD .
				"v-change-dns-domain-dnssec " .
				quoteshellarg($view["user"]) .
				" " .
				quoteshellarg($v_domain) .
				" " .
				quoteshellarg($v_dnssec ? "yes" : "no"),
			$o4,
			$rv4,
		);
	}
	$success = sprintf(_("DNS domain %s updated."), $v_domain);
	exec(
		HESTIA_CMD .
			"v-list-dns-domain " .
			quoteshellarg($view["user"]) .
			" " .
			quoteshellarg($v_domain) .
			" json",
		$out,
		$rc,
	);
	$domainData = $rc === 0 ? json_decode(implode("", $out), true) : [];
	$domainData = $domainData[$v_domain] ?? $domainData;
}
?>
		<main class="app-main" id="main">
			<div class="app-content" style="max-width:48rem">
				<header class="u-mb-4">
					<h1><?= htmlspecialchars($v_domain) ?></h1>
					<p><?= _("DNS domain configuration.") ?></p>
				</header>
				<?php if ($success): ?><div class="alert alert-success" role="status"><?= htmlspecialchars(
	$success,
) ?></div><?php endif; ?>
				<?php if ($error): ?><div class="alert alert-danger" role="alert"><?= htmlspecialchars(
	$error,
) ?></div><?php endif; ?>
				<section class="card">
					<form method="post" action="/next/?p=dns-edit&domain=<?= urlencode($v_domain) ?>" class="form">
						<input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION["token"]) ?>">
						<div class="field">
							<label class="field-label" for="v_ip"><?= _("IP Address") ?></label>
							<input class="input" id="v_ip" name="v_ip" type="text" value="<?= htmlspecialchars(
       	$domainData["IP"] ?? "",
       ) ?>" autocomplete="off">
						</div>
						<div class="field">
							<label class="field-label" for="v_ttl"><?= _("TTL") ?></label>
							<input class="input" id="v_ttl" name="v_ttl" type="text" value="<?= htmlspecialchars(
       	$domainData["TTL"] ?? "",
       ) ?>" autocomplete="off">
						</div>
						<div class="field">
							<label class="field-label" for="v_soa"><?= _("SOA") ?></label>
							<input class="input" id="v_soa" name="v_soa" type="text" value="<?= htmlspecialchars(
       	$domainData["SOA"] ?? "",
       ) ?>" autocomplete="off">
						</div>
						<div class="field">
							<label class="field-label" for="v_dnssec">
								<input id="v_dnssec" name="v_dnssec" type="checkbox" style="width:auto;margin-inline-end:.5rem"
									<?= ($domainData["DNSSEC"] ?? "no") === "yes" ? "checked" : "" ?>>
								<?= _("DNSSEC enabled") ?>
							</label>
						</div>
						<div class="u-flex u-gap-3">
							<button class="btn btn-primary" type="submit"><?= _("Save") ?></button>
							<a class="btn btn-ghost" href="/next/?p=dns"><?= _("Back") ?></a>
						</div>
					</form>
				</section>
				<section class="card">
					<div class="card-header"><h2 class="card-title"><?= _("Danger Zone") ?></h2></div>
					<form method="post" action="/next/?p=dns-delete" class="form" x-data
						x-on:submit.prevent="if(confirm('<?= _("Delete this DNS domain?") ?>')) $el.submit()">
						<input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION["token"]) ?>">
						<input type="hidden" name="domain" value="<?= htmlspecialchars($v_domain) ?>">
						<button class="btn btn-danger" type="submit"><?= _("Delete Domain") ?></button>
					</form>
				</section>
			</div>
		</main>
