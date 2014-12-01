jQuery(document).ready( function($) {
	//$('body').prepend('#sbmobilemenuwrap');
	//$('sbmobilemenuwrap').prependTo('body');
	//$("sbmobilemenuwrap").detach().appendTo('body')
	//var _elementClone = $(".sbmobilemenuwrap").html();
	var _elementClone = $(".dl-menuwrapper").clone(); 

	$(".dl-menuwrapper").remove();
	console.log(_elementClone);
//	$("body").prepend($("sbmobilemenuwrap"));
	$("body").prepend(_elementClone);//this will prepend cloned  element to the #mydiv content

} );


var $j = jQuery.noConflict();
$j(function() {
	$j( '#dl-menu' ).dlmenu();
});

