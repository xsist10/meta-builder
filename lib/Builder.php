<?php

/*
 * TODO: Fix Date Selection CSS and Date Range
 * TODO: Add masking for inputs
 * TODO: Select box images only working in FF and aren't shown on the selected option. Fix when I'm feeling masochistic
 * TODO: Fix the damn jQuery Tools validation for radio/checkbox. See above conditions for fixing
 * TODO: Fix issue where one rich text textarea on a form makes all the other textareas also turn into a rich text textarea
 * TODO: Add element level errors for reloaded forms
  * TODO: Add map picker element
 */

/**
 * Configure the autoloader
 */
if (!defined('META_BUILDER_DIR'))
{
	define('META_BUILDER_DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR);
}

spl_autoload_register('MetaBuilderAutoload');

/**
 * Generic Autoloader to locate and load all the required
 * libraries for the meta builder
 * 
 * @param	string		$sClassName
 * @return	boolean
 */
function MetaBuilderAutoload($sClassName)
{
	$aClassPath = explode('_', str_replace('Builder_', '', $sClassName));
	$sClassName = META_BUILDER_DIR . implode('/', $aClassPath) . '.php';
	if (is_file($sClassName))
	{
		require_once $sClassName;
		return true;
	}
	return false;
}

/**
 * Modify this to change how URLs are built in the builder
 *
 * @param   string      $sUrl
 * @return  string
 */
function BuildUrl($sUrl)
{
	return $sUrl;
}

/**
 * Modify this to change how images are located in the builder
 *
 * @param   string      $sUrl
 * @return  string
 */
function BuildImage($sImage)
{
	return '/html/images/' . $sImage;
}
