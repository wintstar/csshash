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
	'FILE'							=> 'Datei',
	'LAST_FILE_CHANGE'				=> 'Letzte Dateiänderung',
	'SELECT_STYLE'					=> 'Style auswählen',
	'STYLE_SELECT'					=> 'Style-Auswahl',
	'HASH_VALUE_UPDATE'				=> 'Hash-Wert aktualisieren',
	'HASH_VALUE_UP_TO_DATE'			=> 'Hash-Werte sind aktuell',
	'HASH_VALUE_NOT_UP_TO_DATE'		=> 'Hash-Wert ist nicht aktuell',
	'HASH_VALUE_IS_UPDATED'			=> 'Alle geänderten Hash-Werte wurden in der stylesheet.css aktualisiert und eingetragen.',
	'NO_HASH_VALUE'					=> 'Dieser Style hat keinen Hash-Wert',
	'NEW_FILE_HASH_VALUE'			=> 'Aktualisierte Hash-Werte',
	'NO_FILE_HASH_VALUE'			=> 'Es gibt keine aktualisierte Hash-Werte',
	'STYLESHEET'					=> 'stylesheet.css',
	'STYLESHEET_FILES'				=> 'Hier können Sie alle importierten CSS Dateien dieser Datei sehen. Zuletzt bearbeitete Dateien stehen am Anfang.',
	'STYLESHEET_NEW'				=> 'Aktualisierte stylesheet.css',
	'ERROR_STYLECHANGER'			=> 'Style ID ist nicht vorhanden',
]);
