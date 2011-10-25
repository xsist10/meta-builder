<?php

/**
 * Configure the autoloader
 */

if (!defined('META_BUILDER_DIR'))
{
	define('META_BUILDER_DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR);
}

define('INPUT', 'input');
define('SESSION', 'session');
define('MESSAGE_ERROR_INLINE', 'message-error-inline');

spl_autoload_register('MetaBuilderAutoload');

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
