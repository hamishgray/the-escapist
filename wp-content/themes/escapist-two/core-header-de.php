<?php
/**
 * The core site header
 */
?>

<?php

	$header = '
		<!-- core-header-uk.php -->
		<div class="core">
			<div class="core__inner">

				<ul class="core__nav">
					<li class="core__nav-item">
						<a href="https://www.secretescapes.de/aktuelle-angebote" data-track="magazine-header-click" class="core__nav-link core__nav-link--orange">Aktuelle Angebote</a></li>
					<li class="core__nav-item">
						<a href="https://www.secretescapes.de/unser-geheimnis" data-track="magazine-header-click" class="core__nav-link">Unser Geheimnis</a>
					</li>
					<li class="core__nav-item">
						<a href="https://www.secretescapes.de/reisekategorien/filter" data-track="magazine-header-click" class="core__nav-link">Reisekategorien</a>
					</li>
				</ul>

				<ul class="core__subnav">
					<li class="core__subnav-item">
						<a href="https://www.secretescapes.de/freunde-einladen" data-track="magazine-header-click" class="core__subnav-link">Freunde einladen</a>
					</li>
					<li class="core__subnav-item">
						<a href="https://www.secretescapes.de/kontakt-und-impressum" data-track="magazine-header-click" class="core__subnav-link">Kontakt & Impressum</a>
					</li>
				</ul>

			</div>
		</div>
	';

	if(!isset($_COOKIE["fromApp"])) {
		echo do_shortcode('[ifurlparam param="fromApp" empty="1"]' . $header . '[/ifurlparam]');
	}
?>