<?php
/**
 * The core site header
 */
?>

<?php

	$header = '
		<!-- core-header-se.php -->
		<div class="core">
			<div class="core__inner">

				<ul class="core__nav">
					<li class="core__nav-item">
						<a href="https://www.secretescapes.se/aktuella-kampanjer" data-track="magazine-header-click" class="core__nav-link core__nav-link--orange">Aktuella kampanjer</a>
					</li>
					<li class="core__nav-item">
						<a href="https://www.secretescapes.se/presentkort-offer" data-track="magazine-header-click" class="core__nav-link">Presentkort</a>
					</li>
					<li class="core__nav-item">
						<a href="https://www.secretescapes.se/how-it-works" data-track="magazine-header-click" class="core__nav-link">Så funkar det</a>
					</li>
				</ul>

				<ul class="core__subnav">
					<li class="core__subnav-item">
						<a href="https://www.secretescapes.se/bjud-in-vanner" data-track="magazine-header-click" class="core__subnav-link">Bjud in vänner</a>
					</li>
					<li class="core__subnav-item">
						<a href="https://www.secretescapes.se/kontakt" data-track="magazine-header-click" class="core__subnav-link">Kontakta oss</a>
					</li>
				</ul>

			</div>
		</div>
	';
	echo do_shortcode('[ifurlparam param="fromApp" empty="1"]' . $header . '[/ifurlparam]');
?>