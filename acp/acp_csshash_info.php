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

namespace wintstar\csshash\acp;

class acp_csshash_info
{
	function module()
	{
		return [
			'filename'	=> '\wintstar\csshash\acp\acp_csshash_module',
			'title'		=> 'ACP_STYLES_CSS',
			'modes'		=> [
				'main'	=> [
					'title' => 'ACP_STYLES_CSS', 
					'auth' => 'ext_wintstar/csshash && acl_a_styles', 
					'cat' => ['ACP_STYLE_MANAGEMENT']
				],
			],
		];
	}
}
