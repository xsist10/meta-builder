/**
 * Grid Paginiation and Ordering
 *
 * Written by Thomas Shone, 2010
 * http://www.shone.co.za
 *
 * Built and adapted from the excellent tutorial here:
 * http://www.packtpub.com/article/jquery-table-manipulation-part1
 *
 * Written by Jonathan Chaffer and Karl Swedberg in August 2007
 */

$(document).ready(function() {

	$.fn.alternateRowColors = function() {
		$(this).filter('tbody.grid-dataset tr:odd td').removeClass('row-even').addClass('row-odd');
		$(this).filter('tbody.grid-dataset tr:even td').removeClass('row-odd').addClass('row-even');
		return this;
	};
	
	function TogglePreviousNextControls($pager, currentPage, numPages)
	{
		// Show/Hide the Previous Button
		if (currentPage > 0)
		{
			$pager.find('span.page-previous').show();
		}
		else
		{
			$pager.find('span.page-previous').hide();
		}
		
		// Show/Hide the Next Button
		if (currentPage < numPages - 1)
		{
			$pager.find('span.page-next').show();
		}
		else
		{
			$pager.find('span.page-next').hide();
		}
	}

	$('table.paging').each(function() {
		var currentPage = 0;
		var numPerPage = $(this).attr('rel');
        if (numPerPage <= 0)
        {
            numPerPage = 0;
        }

		var $table = $(this);

		$table.bind('repaginate', function() {
			$table.find('tbody tr').show().filter(':lt(' + (currentPage * numPerPage) + ')').hide().end().filter(':gt(' + ((currentPage + 1) * numPerPage - 1) + ')').hide().end();
		});

		var numRows = $table.find('tbody tr').length;
		var numPages = Math.ceil(numRows / numPerPage);

		if (numPages > 1) {
			var $pager = $('<div class="grid-paging"></div>');

			
			// Add the previous button
			$('<span class="page page-previous">Previous</span>').bind('click', {
				'newPage': page
			}, function(event){
				currentPage = currentPage > 0
								? currentPage - 1
							    : 0;
				$table.trigger('repaginate');
				$('span.page[data=' + currentPage + ']').addClass('active').siblings().removeClass('active');
				
				TogglePreviousNextControls($pager, currentPage, numPages);
			}).appendTo($pager).addClass('clickable');
			
			for (var page = 0; page < numPages; page++) {
				$('<span class="page" data="' + page + '">' + (page + 1) + '</span>').bind('click', {
					'newPage': page
				}, function(event){
					currentPage = event.data['newPage'];
					$table.trigger('repaginate');
					$(this).addClass('active').siblings().removeClass('active');
					
					TogglePreviousNextControls($pager, currentPage, numPages);
				}).appendTo($pager).addClass('clickable');
			}
			
			// Add the next button
			$('<span class="page page-next">Next</span>').bind('click', {
				'newPage': page
			}, function(event){
				currentPage = currentPage < numPages
								? currentPage + 1
							    : numPages;
				$table.trigger('repaginate');
				$('span.page[data=' + currentPage + ']').addClass('active').siblings().removeClass('active');
				
				TogglePreviousNextControls($pager, currentPage, numPages);
			}).appendTo($pager).addClass('clickable');

			$pager.find('span.page:eq(1)').addClass('active');
			$pager.find('span.page-previous').hide();
			$pager.insertAfter($table);
			$table.trigger('repaginate');
		}
	});

    $('tr.grid-row').find('td input[type=checkbox]').each(function()
    {
        var checkboxImage = $(this).is(':checked')
                                ? 'grid-checkbox-checked'
                                : 'grid-checkbox-unchecked';
        $(this).hide();
        $(this).parent('td').append('<div class="grid-checkbox ' + checkboxImage + '"></div>');
    });
    
    $('tr.grid-row').find('td input[type=radio]').each(function()
    {
        var checkboxImage = $(this).is(':checked')
                                ? 'grid-radio-checked'
                                : 'grid-radio-unchecked';
        $(this).hide();
        $(this).parent('td').append('<div class="grid-radio ' + checkboxImage + '"></div>');
    });

    // Enable rows to be toggled when clicked on
	$('tr.grid-row').click(function() {
		var checkbox = $(this).find('td input[type=checkbox]');
		checkbox.attr('checked', !checkbox.attr('checked'));
		$(this).toggleClass('selected');
        $(this).find('td div.grid-checkbox')
               .toggleClass('grid-checkbox-checked')
               .toggleClass('grid-checkbox-unchecked');
        
        var radio = $(this).find('td input[type=radio]');
        if (radio.length)
        {
        	$(this).parent('tbody')
        	       .parent('table')
        	       .find('tr.grid-row')
        	       .removeClass('selected');
        	$(this).addClass('selected');
        	
            var all_radio = $(this).parent('tbody')
		 	   .parent('table')
			   .find('input[type=radio]');

			$(all_radio).each(function()
			{
				$(this).attr('checked', false)
					   .parent('td').find('div.grid-radio')
					   .removeClass('grid-radio-checked')
					   .addClass('grid-radio-unchecked')
			});
        	
        	radio.attr('checked', true);
        	$(this).find('td div.grid-radio')
        		   .addClass('grid-radio-checked')
        		   .removeClass('grid-radio-unchecked');
        }
	});

    // Toggle the fields already selected on page load
    $('input.grid-checkbox:checked').each(function() {
        $(this).parent('td').parent('tr').addClass('selected');
    });

	$('table.sortable').each(function() {
		var $table = $(this);
		$table.alternateRowColors($table);

		$('thead td.grid-column', $table).each(function(column) {

            $(this).click(function(){
                var findSortKey;
                // Make sure we add the right class to the order list
                if ($(this).is('.grid-sort-alpha')) {
                    findSortKey = function($cell) {
                        return $cell.find('.sort-key').text().toUpperCase()
                                          + ' ' + $cell.text().toUpperCase();
                    };
                    sortClass = 'aplha';
                }
                else if ($(this).is('.grid-sort-numeric')) {
                    findSortKey = function($cell) {
                        var key = parseFloat($cell.text());
                        return isNaN(key) ? 0 : key;
                    };
                    sortClass = 'numeric';
                }
                else if ($(this).is('.grid-sort-date')) {
                    findSortKey = function($cell) {
                        return Date.parse('1 ' + $cell.text());
                    };
                    sortClass = 'date';
                }
                if (findSortKey) {

					var newDirection = 1;

      				if ($(this).is('.sorted-asc')) {
						newDirection = -1;
					}

					var rows = $table.find('tbody > tr').get();
					$.each(rows, function(index, row){
						var $cell = $(row).children('td').eq(column);
						/*row.sortKey = $cell.find('.sort-key').text().toUpperCase() +
						' ' +
						$cell.text().toUpperCase();*/
                        row.sortKey = findSortKey($cell);
					});

					rows.sort(function(a, b){
						if (a.sortKey < b.sortKey)
							return -newDirection;
						if (a.sortKey > b.sortKey)
							return newDirection;
						return 0;
					});

					$.each(rows, function(index, row){
						$table.children('tbody').append(row);
						row.sortKey = null;
					});

					$table.find('thead.grid-columns td').removeClass('sorted-asc sorted-desc')
                                                        .removeClass('sorted-asc-' + sortClass)
                                     	   				.removeClass('sorted-desc-' + sortClass);

					var $sortHead = $table.find('thead.grid-columns td').filter(':nth-child(' + (column + 1) + ')');
					if (newDirection == 1) {
						$sortHead.addClass('sorted-asc sorted-asc-' + sortClass);
					} else {
						$sortHead.addClass('sorted-desc sorted-desc-' + sortClass);
					}
					$table.find('tbody td').removeClass('sorted').filter(':nth-child(' + (column + 1) + ')').addClass('sorted');

					$table.alternateRowColors();
					$table.trigger('repaginate');
				}
			});
		});
	});
	
	// Enable mass toggling of checkboxes
    $('.checkbox-list-select-all').click(function ()
    {
    	$(this).parent('td')
    	       .parent('tr')
    		   .parent('thead')
    		   .parent('table')
    		   .find('input[type=checkbox]')
    		       .attr('checked', true)
    		   .parent('td')
    		   .parent('tr')
    		       .addClass('selected')
    		   .parent('tbody')
    		   .find('td div.grid-checkbox')
    		       .addClass('grid-checkbox-checked')
    		   	   .removeClass('grid-checkbox-unchecked');
    });
    $('.checkbox-list-select-none').click(function ()
    {
    	$(this).parent('td')
    		   .parent('tr')
    		   .parent('thead')
    		   .parent('table')
  		       .find('input[type=checkbox]')
        	       .attr('checked', false)
    		   .parent('td')
    		   .parent('tr')
    		       .removeClass('selected')
    		   .parent('tbody')
    		   .find('td div.grid-checkbox')
    		       .removeClass('grid-checkbox-checked')
    		   	   .addClass('grid-checkbox-unchecked');
    	
    });
    $('.checkbox-list-select-invert').click(function ()
    {
    	$(this).parent('td').parent('tr').parent('thead').parent('table').find('input[type=checkbox]').each(function()
    	{
    		$(this).attr('checked', !$(this).attr('checked'));
    		$(this).parent('td').parent('tr').toggleClass('selected');
    		$(this).parent('td').find('div.grid-checkbox')
				.toggleClass('grid-checkbox-checked')
				.toggleClass('grid-checkbox-unchecked');
    	});
    });
});
