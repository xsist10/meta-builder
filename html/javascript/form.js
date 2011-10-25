/*
 * Form Scripting
 *
 * Written by Thomas Shone, 2010
 * http://www.shone.co.za
 *
 */

/*$.tools.validator.fn("[minlength]", function(input, value) {
    var min = input.attr("minlength");

    return value.length >= min ? true :
    {
        en: "Please provide at least " +min+ " character" + (min > 1 ? "s" : "")
    };
});

$.tools.validator.fn("[maxlength]", function(input, value)
{
    var max = input.attr("maxlength");

    return value.length <= max ? true :
    {
        en: "Please provide no more than " + max + " character" + (max > 1 ? "s" : "")
    };
});

$.tools.validator.fn("[type=telephone]", "Please supply a valid telephone number", function(input, value)
{
    return /^[0-9\-\(\)\ ]+$/.test(value);
});

$.tools.validator.fn("[custom]", function(input, value)
{
    var url = input.attr("custom");

    $.getJSON(url,
        {
            value: value
        },
        function(data)
        {
            return data != null && data == true ? true :
            {
                en: data ? data : "Invalid entry"
            };
        }
    );

    return false;
});*/

$(document).ready(function()
{

    // Remove hints when the form submits
    $('input[type=submit]').click(function()
    {
        $('input.input-hint-present').each(function(i, item)
        {
            var hint = $(item).attr('hint');

            if ($(item).val() == hint)
            {
                $(item).val('');
            }
        });
    });

    // Prepopulate hint fields
    $('input.input-hint-present').each(function(i, item)
    {
        var hint = $(item).attr('hint');

        if ($(item).val() == '' || $(item).val() == hint)
        {
            $(item).val(hint);
            $(this).addClass('input-hint');
        }
    });

    // Repopulate and clear hints
    $('input.input-hint-present').focus(function()
    {
        var hint = $(this).attr('hint');
        if ($(this).val() == hint)
        {
            $(this).val('');
            $(this).removeClass('input-hint');
        }
    }).blur(function()
    {
        if ($(this).val() == '')
        {
            var hint = $(this).attr('hint');
            $(this).val(hint);
            $(this).addClass('input-hint');
        }
    });

    // Enabled Date Select Box
    $(':date').dateinput({
        format: 'yyyy-mm-dd',
        selectors: true,
        yearRange: [-10, 10]
    });

    // Enable Tooltips
    $('input[title]').tooltip({
        // place tooltip on the right edge
        position: "center right"
    });

    // Enable mass toggling of checkboxes
    $('.checkbox-list-select-all').click(function ()
    {
    	$(this).parent('li').parent('ul').find('input[type=checkbox]').attr('checked', true);
    });
    $('.checkbox-list-select-none').click(function ()
    {
    	$(this).parent('li').parent('ul').find('input[type=checkbox]').attr('checked', false);
    });
    $('.checkbox-list-select-invert').click(function ()
    {
    	$(this).parent('li').parent('ul').find('input[type=checkbox]').each(function()
    	{
    		$(this).attr('checked', !$(this).attr('checked'));
    	});
    });
});
