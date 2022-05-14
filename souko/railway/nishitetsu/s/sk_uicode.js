$(document).ready(function(){
	$(".formation_controlinfo").click(
		function(){
			$(this).next(".trainlist").toggle();
			$(this).toggleClass("selected_formation");
		}
	);
	
	$(".trainlist tr").click(
		function(){
			$(this).next().next(".memo").toggle();
			$(this).next(".memo").toggle();
		}
	);
	
	$(".trainlist").hide();
	$(".memo").hide();
});
