(function($) {
	var $selectAll = $('.js-select-all')
	var $chapters = $('.js-checkboxes').find('input[type="checkbox"]')
	var $checkedChapters = []

	$selectAll.change(function() {
		$checkedChapters = $('.js-checkboxes').find('input[type="checkbox"]:checked')

		$checkedChapters.length < $chapters.length ?
			$chapters.prop('checked', true) : $chapters.prop('checked', false)
	})

	$chapters.change(function() {
		$checkedChapters = $('.js-checkboxes').find('input[type="checkbox"]:checked')

		$checkedChapters.length === $chapters.length ?
			$selectAll.prop('checked', true) : $selectAll.prop('checked', false)
	})
})(jQuery)