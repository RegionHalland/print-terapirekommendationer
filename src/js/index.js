(function($) {
	var $selectAll = $('.js-select-all')
	var $chapters = $('.js-checkboxes').find('input[type="checkbox"]')

	$selectAll.change(function() {
		console.log($('.js-checkboxes:checked').length)
		console.log($('.js-checkboxes').length)
		$('.js-checkboxes:checked').length <= $('.js-checkboxes').length ?
			$chapters.prop('checked', true) : $chapters.prop('checked', false)
	})

	$chapters.change(function() {
		$('.js-checkboxes:checked').length === $('.js-checkboxes').length ?
			$selectAll.prop('checked', true) : $selectAll.prop('checked', false)
	})
})(jQuery)