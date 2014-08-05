//<!--
    img_ajax_preloader =new Image();
    img_bg =new Image();
    img_ajax_preloader.src ='/img/ajax-loader.gif';
    img_bg.src = '/img/bg-locked-post.png';
    $(document).ready(function(){
		$("#login_link").click(function () {
			$("#topnav span").toggleClass('menu-open');
		    $("#signin_menu").toggle();
		    $("#header_username:visible").focus();
		    return false;
		});
		$("#addcom").click(function () {
		    $("#DivCommentsForm").toggle();
		});
		$('#comment_ok').click(function() {
			$('#comment_ok').attr("disabled","disabled");
			$('#comment_preloader').html('<img src="'+img_ajax_preloader.src+'" alt="wait for 2 hours" />');
			$.post("/tmpl/add_comment.php", { comment_author: $('#comment_author').val(), comment_text: $('#comment_text').val(), comment_cat: $('#comment_cat').val(), comment_itm: $('#comment_itm').val() },
				function(data){
					if (data!='') {
						$('#comments_comments').append(data);
						$('#comment_text').val('');
					} else {
						alert('Что-то не получилось');
					}
					$('#comment_preloader').html('');
					$('#comment_ok').attr("disabled","");
			   		return true;
				});
	   		return true;
		});
		$('#anketa_ok').click(function() {
			$('#anketa_preloader').html('<img src="'+img_ajax_preloader.src+'" alt="wait for 2 hours" />');
			$.post("/practice/send_anketa.php", { name: $('#name').val(), city: $('#city').val(), contact: $('#contact').val(), age: $('#age').val(), occupation: $('#occupation').val(), comments: $('#comments').val() },
				function(data){
					$('#DivAnketa').html(data);
					$('#anketa_preloader').html('');
			   		return true;
				});
	   		return true;
		});
    });
    function show_the_rest(id_comment) {
    	$('#show_comment_'+id_comment).remove();
    	$('#rest_of_'+id_comment).show();
    	return true;
    }
//-->
