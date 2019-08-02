

/* GET COOKIES
 * Function to get cookies to assign logged in user to analytics
==================================*/

function getCookie(cname) {
  var name = cname + '=';
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
        c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
    }
  }
  return '';
}


/* SCRAPE FOOTER NAV
 * Function to get footer nav links from core site
==================================*/

var getHTML = function ( url, callback ) {
	if ( !window.XMLHttpRequest ) return;
	// Create new request
	var xhr = new XMLHttpRequest();
	// Setup callback
	xhr.onload = function() {
		if ( callback && typeof( callback ) === 'function' ) {
			callback( this.responseXML );
		}
	}
	// Get the HTML
	xhr.open( 'GET', url );
	xhr.responseType = 'document';
	xhr.send();
};

getHTML( 'https://www.secretescapes.com/current-sales', function (response) {
  var targetElem = document.querySelector('.footer__sitemap');
	var remoteElem = response.querySelector('#core-footer');
	targetElem.innerHTML = remoteElem.innerHTML;
});

