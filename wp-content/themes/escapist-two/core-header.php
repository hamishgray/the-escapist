<?php
/**
 * The core site header
 */
?>

<?php

	$header = '
		<div class="core">
			<div class="core__inner">

				<ul class="core__nav">
					<li class="core__nav-item">
						<a href="https://www.secretescapes.com/current-sales" data-track="magazine-header-click" class="core__nav-link core__nav-link--orange">Current Sales</a></li>
					<li class="core__nav-item">
						<a href="https://www.secretescapes.com/spa/filter" data-track="magazine-header-click" class="core__nav-link">Spa</a>
					</li>
					<li class="core__nav-item">
						<a href="https://www.secretescapes.com/family/filter" data-track="magazine-header-click" class="core__nav-link">Family</a>
					</li>
					<li class="core__nav-item">
						<a href="https://www.secretescapes.com/how-it-works" data-track="magazine-header-click" class="core__nav-link">How it works</a>
					</li>
				</ul>

				<ul class="core__subnav">
					<li class="core__subnav-item">
						<a href="https://www.secretescapes.com/app" data-track="magazine-header-click" class="core__subnav-link">Get our app</a>
					</li>
					<li class="core__subnav-item">
						<a href="https://www.secretescapes.com/invite-friends" data-track="magazine-header-click" class="core__subnav-link">Invite friends</a>
					</li>
					<li class="core__subnav-item">
						<a href="https://www.secretescapes.com/contact" data-track="magazine-header-click" class="core__subnav-link">Contact</a>
					</li>
				</ul>

			</div>
		</div>
	';
	echo do_shortcode('[ifurlparam param="fromApp" empty="1"]' . $header . '[/ifurlparam]');
?>