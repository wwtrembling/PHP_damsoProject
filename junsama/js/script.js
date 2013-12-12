var hanreg = /[¤¡-¤¾¤¿-¤Ó°¡-ÆR]/g;
var engreg = /[a-zA-Z]/g;
var specialreg = /[`~!@#$%^&*()_\[\]-_\+=\|\\,\?\/:;'"]/g;

// ¼ýÀÚ¸¸ ÀÔ·Â
function isNumber(o) {
	if( o.value == '' ) return;
	if( hanreg.test(o.value) || engreg.test(o.value) ) {
		alert('¼ýÀÚ¸¸ ÀÔ·Â °¡´ÉÇÕ´Ï´Ù.');
		o.value = '';
		o.focus();
		return false;
	}
}

//ÀÌ¸ÞÀÏ
function isEmail(o) {
	if( o.value == '' ) return;
	if( o.value.search(/@/) == -1 || o.value.search(/./) == -1 ) {
		alert('¿Ã¹Ù¸¥ ÀÌ¸ÞÀÏ ÁÖ¼Ò¸¦ ÀÔ·ÂÇØ ÁÖ¼¼¿ä.');
		o.value = '';
		o.focus();
		return false;
	}
}

// textbox Å¬¸¯ ½Ã °ª »èÁ¦
function Write( t ) {
	gubun = t;
	
	if ( gubun == 1 ) {
		document.getElementById('publicationNo').value = '';
	} else {
		document.getElementById('publication').value = '';
	}
}

function twitter(path) {
	var f = document.thisform;
	var targetAct = 'http://110.13.170.154/tempProject/junsama/twit/redirect.php?path='+path;
	
	f.act.value = 'twitter';
	window.open(targetAct,'twitter','width=750, height=700, scrollbars=yes');
	//f.action = "http://110.13.170.154/tempProject/junsama/twit/";
	//f.submit();
	//alert('tw');
}