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

namespace wintstar\csshash\migrations;

class v_1_0_0 extends \phpbb\db\migration\migration
{
	public function update_data()
	{
		return [
			['module.add', [
				'acp', 'ACP_STYLE_MANAGEMENT', [
					'module_basename'	=> '\wintstar\csshash\acp\acp_csshash_module',
					'modes'				=> ['main'],
				],
			]],
		];
	}
}
