(function($) {
		  
		  
	window.Zizanie = {
		
		'init' : function() {
			
			$(".comment-form-rage .slider").slider({
				'min' : 0,
				'max' : 10,
				'step' : 1,
				'slide' : function(event,ui) {
					$(this).parent().find(".level").text(ui.value+"/10");
				},
				'change' : function(event,ui) {
					$(this).parent().find(".level").text(ui.value+"/10");
				}
			});	
			
			$(".zizanie-comment-editor").ckeditor();
			
		}
		
	};
	
	Zizanie.Comments = {
		'moveFormWithQuote' : function(commId, parentId, respondId, postId) {
			
			var commentEditor = $(".zizanie-comment-editor").ckeditorGet();
			if(commentEditor) {
				commentEditor.destroy();
			}
			
			if(!addComment.moveForm(commId,parentId,respondId,postId)) {
				
				var proxyCancel = $("#cancel-comment-reply-link").get(0).onclick;
				$("#cancel-comment-reply-link").get(0).onclick = function() {
					var commentEditor = $(".zizanie-comment-editor").ckeditorGet();
					if(commentEditor) {
						commentEditor.destroy();
					}
					proxyCancel.apply(this,arguments);
					$(".zizanie-comment-editor").ckeditor();
					$(".zizanie-comment-editor").ckeditorGet().setData("",function(){this.focus();});
					$(".zizanie-comment-editor").ckeditorGet().focus();
				};
				
				$(".zizanie-comment-editor").ckeditor();
				$(".zizanie-comment-editor").ckeditorGet().setData("");
				
				var quote = $("#"+commId).find(".comment-body").html();
				var author = '<cite class="fn"><strong>'+$("#"+commId+" cite").eq(0).text()+'</strong></cite> <strong class="says">'+$("#"+commId+" span.says").eq(0).text()+'</strong><br />';
				$(".zizanie-comment-editor").ckeditorGet().setData("<blockquote>"+author+quote+"</blockquote>",function() {
					
				});
				return false;
			} else {
				return true;
			}
			
		},
		
	};
		  
	
	$(document).ready(Zizanie.init);
	
})(jQuery);
