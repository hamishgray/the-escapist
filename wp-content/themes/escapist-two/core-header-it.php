<?php
/**
 * The core site header
 */
?>

<?php

	$header = '
		<!-- core-header-it.php -->
		<div class="core">
			<div class="core__inner">

				<ul class="core__nav">
					<li class="core__nav-item">
						<a href="https://it.secretescapes.com/offerte-in-corso" data-track="magazine-header-click" class="core__nav-link core__nav-link--orange">Offerte In Corso</a></li>
					<li class="core__nav-item">
						<a href="https://it.secretescapes.com/come-funziona" data-track="magazine-header-click" class="core__nav-link">Come Funziona</a>
					</li>
					<li class="core__nav-item">
						<a href="https://it.secretescapes.com/offerta-voucher" data-track="magazine-header-click" class="core__nav-link">Buoni Regalo</a>
					</li>
				</ul>

				<ul class="core__subnav">
					<li class="core__subnav-item">
						<a href="https://it.secretescapes.com/invita-amici" data-track="magazine-header-click" class="core__subnav-link">Invita I Tuoi Amici</a>
					</li>
					<li class="core__subnav-item">
						<a href="https://it.secretescapes.com/contattaci" data-track="magazine-header-click" class="core__subnav-link">Contattaci</a>
					</li>
				</ul>

			</div>
		</div>
	';
	echo do_shortcode('[ifurlparam param="fromApp" empty="1"]' . $header . '[/ifurlparam]');
?>