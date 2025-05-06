<?php
/**
 *
 * @package phpBB Extension - wintstar Hash value in stylesheet.css
 * @version 1.0.0
 * @author St. Frank <webdesign@stephan-frank.de> https://www.stephan-frank.de
 * @copyright (c) 2024 St.Frank
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
// Some characters you may want to copy&paste:
// ’ » “ ” …

$lang = array_merge($lang, [
	'FILE'							=> 'File',
	'LAST_FILE_CHANGE'				=> 'Last file change',
	'SELECT_STYLE'					=> 'Select style',
	'STYLE_SELECT'					=> 'Style selection',
	'HASH_VALUE_UPDATE'				=> 'Update hash value',
	'HASH_VALUE_UP_TO_DATE'			=> 'Hash values are up-to-date',
	'HASH_VALUE_NOT_UP_TO_DATE'		=> 'Hash value is not up to date',
	'HASH_VALUE_IS_UPDATED'			=> 'All changed hash values have been updated and entered in stylesheet.css.',
	'NO_HASH_VALUE'					=> 'This style has no hash value',
	'NEW_FILE_HASH_VALUE'			=> 'Updated hash values',
	'NO_FILE_HASH_VALUE'			=> 'There are no updated hash values',
	'STYLESHEET'					=> 'stylesheet.css',
	'STYLESHEET_FILES'				=> 'Here you can see all imported CSS files of this file. Recently edited files are at the top.',
	'STYLESHEET_NEW'				=> 'Updated stylesheet.css',
	'ERROR_STYLECHANGER'			=> 'Style ID is not available',
]);
