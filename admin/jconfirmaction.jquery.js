/*
 * jQuery Plugin : jConfirmAction
 * 
 * by Hidayat Sagita
 * http://www.webstuffshare.com
 * Licensed Under GPL version 2 license.
 *
 */
(function($){

	jQuery.fn.jConfirmAction = function (options) {
		
		// Some jConfirmAction options (limited to customize language) :
		// question : a text for your question.
		// yesAnswer : a text for Yes answer.
		// cancelAnswer : a text for Cancel/No answer.
		var theOptions = jQuery.extend ({
			question: "Apakah Anda Yakin ?",
			yesAnswer: "Ya",
			cancelAnswer: "Batal"
		}, options);
		
		return this.each (function () {
			
			$(this).bind('click', function(e) {
			e.preventDefault();
			thisHref	= $(this).attr('href');
			table		= $(this).attr('id');
			
				if($(this).next('.question').length <= 0)
					$(this).after('<div class="question">'+theOptions.question+'<br/> <span class="yes">'+theOptions.yesAnswer+'</span><span class="cancel">'+theOptions.cancelAnswer+'</span></div>');
				
				$(this).next('.question').animate({opacity: 1}, 300);
				
				if(table == 'publish-member'){
					$('.yes').bind('click', function(){
						$(this).parents('.question').fadeOut(300, function() {
						var dataString = 'page=member&id='+ thisHref;
							$.ajax({
										url		: "inc/getdata/update.php",
										data	: dataString,
										cache	: false,
										success	: function(html){
																	window.location = '?page=member';
																}
									});
						});
					});
				}								
				else if(table == 'publish-testiproduk'){
					$('.yes').bind('click', function(){
						$(this).parents('.question').fadeOut(300, function() {
						var dataString = 'page=testiproduk&id='+ thisHref;
							$.ajax({
										url		: "inc/getdata/update.php",
										data	: dataString,
										cache	: false,
										success	: function(html){
																	window.location = '?page=testiproduk';
																}
									});
						});
					});
				}
				else
				{
					$('.yes').bind('click', function(){
						$(this).parents('.question').fadeOut(300, function() {
						var dataString = 'table='+ table +'&id='+ thisHref;
							$.ajax({
										url		: "inc/getdata/delete.php",
										data	: dataString,
										cache	: false,
										success	: function(html){
																	$(".row"+thisHref).fadeOut('slow', function() {$(this).remove();});
																}
									});
						});
					});
				}	
			
				$('.cancel').bind('click', function(){
					$(this).parents('.question').fadeOut(300, function() {
						$(this).remove();
					});
				});
		   	});
		});
	}
	
})(jQuery);