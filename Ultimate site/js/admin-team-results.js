	function tourn_close(id_tourn) {
		$('#preloader_'+id_tourn).html('<img src="/img/ajax-loader.gif" alt="wait for 2 hours" style="vertical-align: middle;" />');
		$.post("/teams/set_tourn_result.php", { id_team: $('#id_team').val(), id_tourn: id_tourn, ptcp: 0 },
			function(data) {
				$('#preloader_'+id_tourn).html('');
				if (data==1) {
					$('#list_'+id_tourn+'_edit').remove();
				} else {
					alert('Что-то не получилось');
				}
			});
		return true;
	}

	function tourn_set_results(id_tourn) {
		var scores = new Array();
		$('#preloader_'+id_tourn).html('<img src="/img/ajax-loader.gif" alt="wait for 2 hours" style="vertical-align: middle;" />');
		$("input:checked",$('#tourn_'+id_tourn)).each(function() { scores.push(this.value);} );
		$.post("/teams/set_tourn_result.php", { id_team: $('#id_team').val(), id_tourn: id_tourn, 'scores[]': scores, mvp0: $('#mvp0_'+id_tourn).val(), mvp1: $('#mvp1_'+id_tourn).val(), comment: $('#comment_'+id_tourn).val(), ptcp: 1 },
			function(data) {
				$('#preloader_'+id_tourn).html('');
				if (data!='') {
					$('#list_'+id_tourn).html(data);
					$('#list_'+id_tourn).show();
					$('#list_'+id_tourn+'_edit').remove();
				} else {
					alert('Что-то не получилось');
				}
			});
		return true;
	}

	function tourn_edit_results(id_tourn) {
		$('#preloader_'+id_tourn).html('<img src="/img/ajax-loader.gif" alt="wait for 2 hours" style="vertical-align: middle;" />');
		$.post("/teams/set_tourn_result.php", { id_team: $('#id_team').val(), id_tourn: id_tourn, action: 'get_edit_form' },
			function(data) {
				$('#preloader_'+id_tourn).html('');
				if (data!='') {
					$('#list_'+id_tourn).hide();
					$('#list_'+id_tourn).after(data);
				} else {
					alert('Что-то не получилось');
				}
			});
		return true;
	}

	function tourn_comment(id_tourn) {
		$('#preloader_'+id_tourn).html('<img src="/img/ajax-loader.gif" alt="wait for 2 hours" style="vertical-align: middle;" />');
		$.post("/teams/set_tourn_result.php", { id_team: $('#id_team').val(), id_tourn: id_tourn, action: 'get_comments_form' },
			function(data) {
				$('#preloader_'+id_tourn).html('');
				if (data!='') {
					$('#list_'+id_tourn).hide();
					$('#list_'+id_tourn).after(data);
					$('#comment_'+id_tourn).focus();
				} else {
					alert('Что-то не получилось');
				}
			});
		return true;
	}

	function tourn_cancel_edit(id_tourn) {
		$('#list_'+id_tourn).show();
		$('#list_'+id_tourn+'_edit').remove();
		return true;
	}
