( function() {

	var siteBranding = document.getElementsByClassName( 'site-branding' )[0];
	if ( siteBranding.clientHeight > 0 ) {
		return;
	}
	document.body.className += ' no-site-branding';

} )();



/* STUCK HEADER NAV
 * Make nav fixed on scroll
==================================*/

window.onscroll = function changeClass(){
	var scrollPosY = window.pageYOffset | document.body.scrollTop;
	var navContainer = document.getElementById('search-navigation');
	var navOffset = navContainer.getBoundingClientRect().top;
	var navHeight = navContainer.getBoundingClientRect().height;
	var nav = document.getElementById('sitenav');

	if(navOffset <= 0) {
		nav.className = ('is-fixed');
  } else if(0 <= navOffset) {
		nav.className =  ('');
  }
}

