$(function(){
	$('#button-import-list').click(function() {
		$('.vbx-subscriptions form:not(.import-list), .vbx-subscriptions .subscriber').slideUp();
		$('.vbx-subscriptions form.import-list').slideToggle();
		return false;
	});
	$('#button-export-list').click(function() {
		$('.vbx-subscriptions form:not(.export-list), .vbx-subscriptions .subscriber').slideUp();
		$('.vbx-subscriptions form.export-list').slideToggle();
		return false;
	});
	$('#button-add-list').click(function() {
		$('.vbx-subscriptions form:not(.add-list), .vbx-subscriptions .subscriber').slideUp();
		$('.vbx-subscriptions form.add-list').slideToggle();
		return false;
	});
	$('.vbx-subscriptions a.subscribers').click(function() {
		var $list = $(this).parent().parent().parent();
		var id = $list.attr('id');
		var $form=$('.vbx-subscriptions form:not(.add):visible');
		$('.vbx-subscriptions .subscriber:not(.' + id + '):visible').slideUp();
		$('.vbx-subscriptions .subscriber.' + id).slideToggle();
		$form[id.match(/([\d]+)/)[1] != $form.find('input[name=list]').val() ? 'slideUp' : 'show']();
		return false;
	});
	$('.vbx-subscriptions .list a.delete').click(function() {
		var $list = $(this).parent().parent().parent();
		var id = $list.attr('id');
		if(confirm('You are about to delete "' + $list.children().children('span').eq(0).text() + '" and all its subscribers.'))
			$.ajax({
				type: 'POST',
				url: window.location,
				data: { remove: id.match(/([\d]+)/)[1] },
				success: function() {
					$list.add('.vbx-subscriptions .subscriber.' + id).hide(500);
				},
				dataType: 'text'
			});
		return false
	});
	$('.vbx-subscriptions .subscriber a.delete').click(function() {
		var $subscriber = $(this).parent().parent().parent();
		var id = $subscriber.attr('id').split('_');
		var $list = $('#list_' + id[1]);
		var $num = $list.find('span').eq(1);
		if(confirm('You are about to remove ' + $subscriber.children().children('span').eq(0).text() + ' from "' + $list.find('span').eq(0).text() + '".'))
			$.ajax({
				type: 'POST',
				url: window.location,
				data: { remove: id[2], list: id[1] },
				success: function() {
					$subscriber.hide(500);
					$num.text(parseInt($num.text()) - 1);
				},
				dataType: 'text'
			});
		return false
	});
	$('.vbx-subscriptions a.sms').click(function() {
		var $list = $(this).parent().parent().parent();
		var id = $list.attr('id');
		var list = id.match(/([\d]+)/)[1];
		var $input = $('.vbx-subscriptions form.update-sms input[name=list]');
		var $form = $('.vbx-subscriptions form.update-sms');
		$('.vbx-subscriptions form:visible').not($form).add('.vbx-subscriptions .subscriber:not(.' + id + ')').slideUp();
		$form[list == $input.val() ? 'slideToggle' : 'slideDown']();
		$form.children('h3').children('span').text($list.children().children('span').eq(0).text());
		$input.val(list);
		return false
	});
	$('.vbx-subscriptions a.call').click(function() {
		var $list = $(this).parent().parent().parent();
		var id = $list.attr('id');
		var list = id.match(/([\d]+)/)[1];
		var $input = $('.vbx-subscriptions form.update-dial input[name=list]');
		var $form = $('.vbx-subscriptions form.update-dial');
		$('.vbx-subscriptions form:visible').not($form).add('.vbx-subscriptions .subscriber:not(.' + id + ')').slideUp();
		$form[list == $input.val() ? 'slideToggle' : 'slideDown']();
		$form.children('h3').children('span').text($list.children().children('span').eq(0).text());
		$input.val(list);
		return false
	});
})
