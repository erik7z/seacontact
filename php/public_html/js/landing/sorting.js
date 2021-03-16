$(window).load(function(){
	
	var $container = $('.portfolio_block, .shop_block');

	$container.isotope({
		itemSelector : '.element',
		masonry: {columnWidth: 1}
	});
    
	var $optionSets = $('#options .option-set'),
		$optionLinks = $optionSets.find('a');

	$optionLinks.click(function(){
		var $this = $(this);
		// don't proceed if already selected
		if ( $this.hasClass('selected') ) {
			return false;
		}
		var $optionSet = $this.parents('.option-set');
		$optionSet.find('.selected').removeClass('selected');
		$this.addClass('selected');

		// make option object dynamically, i.e. { filter: '.my-filter-class' }
		var options = {},
			key = $optionSet.attr('data-option-key'),
			value = $this.attr('data-option-value');
		// parse 'false' as false boolean
		value = value === 'false' ? false : value;
		options[ key ] = value;
		if ( key === 'layoutMode' && typeof changeLayoutMode === 'function' ) {
			// changes in layout modes need extra logic
			changeLayoutMode( $this, options )
		} else {
			// otherwise, apply new options
			$container.isotope( options );
		}
	
		return false;
	});
	
	// toggle variable sizes of all elements
	$('#toggle-sizes').find('a.view_full').click(function(){
		$('.shop_block')
			.addClass('variable-sizes')
			.isotope('reLayout');
		return false;
	});
	$('#toggle-sizes').find('a.view_box').click(function(){
		$('.shop_block')
			.removeClass('variable-sizes')
			.isotope('reLayout');
		return false;
	});
	

});




