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

class acp_csshash_module
{
	public $u_action;

	function main(string $id, string $mode)
	{
		global $phpbb_container;

		$this->tpl_name		= 'csshash';
		$this->page_title	= $phpbb_container->get('language')->lang('ACP_STYLES_CSS');

		// Get an instance of the admin controller
		$admin_controller = $phpbb_container->get('wintstar.csshash.admin.controller');

		// Make the $u_action url available in the data controller
		$admin_controller->set_page_url($this->u_action);

		$admin_controller->display_output();
	}
}
