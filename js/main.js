
$(document).ready(function(){
	var VO = {
		actionURL:'system/do.php'
	};
	$('.action.addSpread').click(function(e){
		e.preventDefault();
		$.get(VO.actionURL, {action:'addSpread'}, function(data){
			var returnData = $.parseJSON(data);
			console.log(returnData);
			location.reload();

		});
	})
	$('.action.editPage').on('click', function(e){
		e.preventDefault();
		$(this).parent().children('.page').each(function(i){
			if ($(this).children('.edit').hasClass('hidden')) {
				$(this).children('.edit').removeClass('hidden');
				$(this).children('.info').addClass('hidden');
			} else {
				$(this).children('.edit').addClass('hidden');
				$(this).children('.info').removeClass('hidden');

			}
			
		})

	})
	$('.action.updatePage').on('click', function(e){
		e.preventDefault();
		var activePage = $('#page-'+$(this).attr('update'));
		var data = {
			'id': 		$(this).attr('update'),
			'action': 	'updatePage',
			'content': 	activePage.children('.edit').children('.content').children('textarea').val(),
			'title': 	activePage.children('.edit').children('.title').children('input').val(),
			'hasImage': activePage.children('.edit').children('.image').children('input').is(':checked')
		}
		$.post(VO.actionURL, data, function(returnData) {
			var parsedReturnData = $.parseJSON(returnData);
			console.log(parsedReturnData);
			setMessage(parsedReturnData.message);

		});

	});
	function setMessage(m) {
		$('.message')
			.html(m)
			.fadeIn()
			.delay(1000)
			.fadeOut();
	}

});