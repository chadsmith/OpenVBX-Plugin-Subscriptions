$(function(){
	$('#button-add-list').click(function(e) {
		$('.vbx-subscriptions form:not(:first):visible').slideUp();
		$('.vbx-subscriptions form:first').slideToggle();
		return false;
	});
	$('.vbx-subscriptions a.delete').click(function(){
		var $list=$(this).parent().parent().parent();
		if(confirm('You are about to delete "'+$list.children().children('span').eq(0).text()+'" and all its subscribers.'))
			$.ajax({
				type:'POST',
				url:window.location,
				data:{remove:$list.attr('id').match(/([\d]+)/)[1]},
				success:function(){
					$list.hide(500)
				},
				dataType:'text'
			});
		return false
	});
	$('.vbx-subscriptions a.sms').click(function(){
		var $list=$(this).parent().parent().parent();
		var id=$list.attr('id').match(/([\d]+)/)[1];
		var $input=$('.vbx-subscriptions input[name=list]').eq(0);
		var $form=$('.vbx-subscriptions form:eq(1)');
		$('.vbx-subscriptions form:visible').not($form).slideUp();
		$form[id==$input.val()?'slideToggle':'slideDown']();
		$form.children('h3').children('span').text($list.children().children('span').eq(0).text());
		$input.val(id);
		return false
	});
	$('.vbx-subscriptions a.call').click(function(){
		var $list=$(this).parent().parent().parent();
		var id=$list.attr('id').match(/([\d]+)/)[1];
		var $input=$('.vbx-subscriptions input[name=list]').eq(1);
		var $form=$('.vbx-subscriptions form:eq(2)');
		$('.vbx-subscriptions form:visible').not($form).slideUp();
		$form[id==$input.val()?'slideToggle':'slideDown']();
		$form.children('h3').children('span').text($list.children().children('span').eq(0).text());
		$input.val(id);
		return false
	});
})