(function($) {
		  
		  
	window.Zizanie = {
		
		'init' : function() {
			
			$(".rageSlider").slider({
				
			});	
			
			
		}
		
	};
	
	Zizanie.Comments = {
		'moveFormWithQuote' : function(commId, parentId, respondId, postId) {
			
			if(!addComment.moveForm(commId,parentId,respondId,postId)) {
				var quote = $("#"+commId).find(".comment-body").html();
				$('#comment').val("<blockquote>"+quote+"</blockquote>");
				return false;
			} else {
				return true;
			}
			
		},
		
	};
		  
	
	$(document).ready(Zizanie.init);
	
})(jQuery);
