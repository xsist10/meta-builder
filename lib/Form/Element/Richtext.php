<?php

/*
 * Builder_Form_Element_Richtext
 *
 * Copyright(c) 2011, Thomas Shone
 * Licensed under the Creative Commons Attribution 3.0 Unported License.
 * http://www.shone.co.za
 *
 * Constructs forms elements from meta data
 */

class Builder_Form_Element_Richtext
{
	public function Build($aMeta, $aData = array())
	{
		$sTheme = !empty($aMeta['mode'])
					? $aMeta['mode']
					: 'simple';
		
		$sResult .= "<script>
$(document).ready(function()
{
	tinyMCE.init({
		// General options
		mode :     'textareas',
		elements : '" . $aMeta['id'] . "',
		theme : '" . $sTheme . "',
		theme_advanced_toolbar_location : 'top',
		theme_advanced_toolbar_align : 'left',
		theme_advanced_statusbar_location : 'bottom',
		theme_advanced_resizing : true,
	});
});
</script>";
		   
		return $sResult;
	}
}
