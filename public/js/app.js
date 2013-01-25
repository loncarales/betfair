 var ajaxLoader = "<img id='ajax-loader' src='/img/ajax-loader.gif' alt='loading...' />";
	 
function applyAccordianHeight(container) {
	var height = $(window).height() - $("#container-top").height() - $("#container-menu").height() - $("#container-footer").height();
	
	$('#accordion').css('height', height-80);
	$('#tabs').css('height', height-105);
	$(container).css('height', height-180);
	$('#my-favorites').css('height', height-195);
	
}
function handleContent(jsonResponse, container) {
	if (jsonResponse.success) {
		$(container).html(jsonResponse.html);
	} else {
        window.location.href = '/logout';
	}
}
$(document).ready(function(){
	$.ajaxSetup ({  
		cache: false  
	});
	/**
	 * Menu Handling
	 */
	$("ul.submenu").parent().append("<span></span>");
	$("ul.menu li span").hover(function() {
		$(this).addClass("subhover");
	}, function(){	
		$(this).removeClass("subhover");
	});
	$("ul.menu li").click(function() {
		if ($(this).find("span").length) {
			$(this).parent().find("ul.submenu").slideDown('fast').show();
			$(this).parent().hover(function() {
			}, function(){
				$(this).parent().find("ul.submenu").slideUp('slow');
			});
		}
	});
	
	/**
	 * Menu Links
	 */
	$("#menu-all-sports").click(function(){
		$("#west").html(ajaxLoader);
		$.getJSON("/sports/all", function(json) {
			handleContent(json,"#west");
		});
	});
	$("#menu-active-sports").click(function(){
		$("#west").html(ajaxLoader);
		$.getJSON("/sports/active", function(json) {
			handleContent(json,"#west");
		});
	});
});