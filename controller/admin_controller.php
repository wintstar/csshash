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

namespace wintstar\csshash\controller;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * admin controller
 */
class admin_controller
{
	protected string $styles_path;
	protected string $styles_path_absolute = 'styles';

	/** @var \phpbb\db\driver\driver_interface */
	protected object $db;

	/** @var \phpbb\language\language */
	protected mixed $language;

	/** @var \phpbb\request\request */
	protected mixed $request;

	/** @var \phpbb\template\template */
	protected mixed $template;

	/** @var \phpbb\user */
	protected mixed $user;

	/** @var string phpBB root path */
	protected string $phpbb_root_path;

	/** @var string Custom form action */
	public mixed $u_action;

	/**
	 * Constructor
	 *
	 * @param \phpbb\db\driver\driver_interface     $this->db
	 * @param \phpbb\language\language              $language
	 * @param \phpbb\request\request                $request
	 * @param \phpbb\template\template              $template
	 * @param \phpbb\user                           $user
	 * @param string                                $root_path
	 */
	public function __construct(\phpbb\db\driver\driver_interface $db,
		\phpbb\language\language $language,
		\phpbb\request\request $request,
		\phpbb\template\template $template,
		\phpbb\user $user,
		$phpbb_root_path)
	{
		$this->db = $db;
		$this->language = $language;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->phpbb_root_path = $phpbb_root_path;
	}

		/**
	* Display the output from this extension
	*
	* @return null
	* @access public
	*/
	public function display_output(): void
	{
		global $cache;

		// Add the language files;
		$this->user->add_lang_ext('wintstar/csshash', 'acp_common');
		$this->user->add_lang('acp/styles');

		$is_hash = null;
		$display = false;
		$can_hash = false;
		$newhash = 0;

		$action = $this->request->variable('action', '');
		$submit2 = $this->request->variable('submit2', false);
		$style_id = $this->request->variable('stylechanger', 0);

		$this->styles_path = $this->phpbb_root_path . $this->styles_path_absolute . '/';

		$s_hidden_fields = build_hidden_fields([
			'stylechanger' => $style_id,
		]);

		// Execute actions
		switch ($action)
		{
			case 'check':
				// Create a form key for preventing CSRF attacks
				add_form_key('form_csshash');

				// Is the form being submitted to us?
				if (empty('stylechanger'))
				{
					trigger_error($this->user->lang['ERROR_STYLECHANGER'] . adm_back_link($this->u_action), E_USER_WARNING);
				}

				// Is the form being submitted to us?
				if ($this->request->is_set_post('submit'))
				{
					// Test if the submitted form is valid
					if (!check_form_key('form_csshash'))
					{
						trigger_error($this->user->lang['FORM_INVALID'] . adm_back_link($this->u_action), E_USER_WARNING);
					}
				}

				// Is the form being submitted to us?
				if ($this->request->is_set_post('submit2'))
				{
					// Test if the submitted form is valid
					if (!check_form_key('form_csshash'))
					{
						trigger_error($this->user->lang['FORM_INVALID'] . adm_back_link($this->u_action), E_USER_WARNING);
					}
				}

				$row = $this->get_styles($style_id);
				$style_cfg = $this->read_style_cfg($row['style_path']);
		
				$filepath = $this->styles_path . $row['style_path'] . '/theme/stylesheet.css';
				$css_dir = $this->styles_path . $row['style_path'] . '/theme/';
		
				if (file_exists($filepath))
				{
					$file = file_get_contents($filepath);
					$old = $file;
		
					preg_match_all('(^@import\\s+url\\([\'"](?<basename>\\w++\\.css)\\?\\K(?:hash|v)=[^\'"]++)m', $file, $old_match);

					// Set output vars for display in the template
					$this->template->assign_vars([
						'S_DISPLAY'		=> true,

						'CSS_FILE_OLD'	=> nl2br($old),
					]);
				}
				else
				{
					$file = '';
					$old = '';
					$old_match = '';
				}
		
				if (!empty($old_match['basename']))
				{
					$new = preg_replace_callback(
						'(^@import\\s+url\\([\'"](?<basename>\\w++\\.css)\\?\\K(?:hash|v)=[^\'"]++)m',
						function ($match) use ($filepath)
						{
							$path = dirname($filepath) . DIRECTORY_SEPARATOR . $match['basename'];
							$hash = sprintf('%08x', crc32(file_get_contents($path)));
		
							return 'hash=' . $hash;
						},
		
						$old
					);

					if ($new !== $old)
					{
						// Set output vars for display in the template
						$this->template->assign_vars([
							'S_ERROR'		=> true,
							'S_CAN_HASH'	=> true,

							'U_UPDATE'		=> $this->u_action . '&amp;action=check',
							'ERROR_MSG'		=> $this->user->lang['HASH_VALUE_NOT_UP_TO_DATE'],
						]);
					}
					else
					{
						// Set output vars for display in the template
						$this->template->assign_vars([
							'S_DISPLAY'		=> false,
							'S_INFO'		=> true,
							'S_CAN_HASH'	=> false,

							'HASH_INFO'		=> $this->user->lang['HASH_VALUE_UP_TO_DATE'],
						]);
					}
		
					if ($submit2 === true)
					{
						if ($new !== $old)
						{
							file_put_contents($filepath, $new);

							// Set output vars for display in the template
							$this->template->assign_vars([
								'S_ERROR'			=> false,
								'S_INFO_SUCCESS'	=> true,
								'S_CAN_HASH'		=> false,
								'S_UPDATE'			=> true,

								'CSS_FILE_NEW'		=> nl2br($new),
								'HASH_INFO'			=> $this->user->lang['HASH_VALUE_IS_UPDATED'],
							]);
						}
					}

					if (isset($old_match['basename']))
					{
						foreach($old_match['basename'] as $c_file)
						{
							$files[] = $css_dir.$c_file;
						}

						array_multisort(array_map('fileatime', $files), SORT_NUMERIC, SORT_DESC, $files);

						foreach($files as $file)
						{
							$filetime = fileatime($file);

							$this->template->assign_block_vars('cssfile', [
									'FILE'			=> htmlspecialchars(basename($file), ENT_COMPAT),
									'LAST_CHANGE'	=> $this->user->format_date($filetime),
								]
							);
						}
					}

					$diff = $this->diff_stylesheet($old, $new);

					if (!empty($diff))
					{
						sort($diff);

						foreach($diff as $newhash)
						{
							$this->template->assign_block_vars('cssdiff', [
									'DIFF'	=> htmlspecialchars($newhash, ENT_COMPAT),
								]
							);
						}
					}
				}
				else
				{
					// Set output vars for display in the template
					$this->template->assign_vars([
						'S_INFO'		=> true,
						'S_CAN_HASH'	=> false,
						'S_DISPLAY'		=> false,

						'HASH_INFO'		=> $this->user->lang['NO_HASH_VALUE'],
					]);
				}

				$this->template->assign_block_vars('details', [
						'STYLE_NAME'			=> htmlspecialchars($row['style_name'], ENT_COMPAT),
						'STYLE_PATH'			=> htmlspecialchars($row['style_path'], ENT_COMPAT),
						'STYLE_PHPBB_VERSION'	=> htmlspecialchars($style_cfg['phpbb_version'], ENT_COMPAT),
						'STYLE_VERSION'			=> htmlspecialchars($style_cfg['style_version'], ENT_COMPAT),
						'STYLE_COPYRIGHT'		=> strip_tags($row['style_copyright']),
						'STYLE_PARENT'			=> $row['style_parent_tree'],
						'STYLE_ACTIVE'			=> !empty($row['style_active']) ? $this->user->lang['YES'] : $this->user->lang['NO'],
					]
				);

				// Set output vars for display in the template
				$this->template->assign_vars([
					'L_TITLE'					=> $this->user->lang['ACP_STYLE_SELECTED'],
					'L_TITLE_EXPLAIN'			=> $this->user->lang['ACP_STYLE_SELECTED_EXPLAIN'],

					'S_STYLE_SELECT'			=> false,
					'S_STYLE_CHECK'				=> true,

					'S_HIDDEN_FIELDS'			=> $s_hidden_fields,

					'U_ACTION'					=> $this->u_action . '&amp;action=check',
					'U_START'					=> $this->u_action,
				]);
				break;

			default:
				// Create a form key for preventing CSRF attacks
				add_form_key('form_csshash');

				// Is the form being submitted to us?
				if ($this->request->is_set_post('submit'))
				{
					// Is the form being submitted to us?
					if (empty('stylechanger'))
					{
						trigger_error($this->user->lang['ERROR_STYLECHANGER'] . adm_back_link($this->u_action), E_USER_WARNING);
					}

					// Test if the submitted form is valid
					if (!check_form_key('form_csshash'))
					{
						trigger_error($this->user->lang['FORM_INVALID'] . adm_back_link($this->u_action), E_USER_WARNING);
					}
				}

				$style_select = style_select($style_id);

				// Set output vars for display in the template
				$this->template->assign_vars([
					'L_TITLE'					=> $this->user->lang['ACP_STYLES_CSS'],
					'L_TITLE_EXPLAIN'			=> $this->user->lang['ACP_STYLES_CSS_EXPLAIN'],

					'S_CAN_HASH'				=> false,
					'S_STYLE_CHECK'				=> false,
					'S_STYLE_SELECT'			=> true,
					'S_STYLECHANGER_OPTIONS'	=> $style_select,

					'U_ACTION'					=> $this->u_action . '&amp;action=check',
				]);
				break;
		}
	}

	/**
	* Read style configuration file
	*
	* @param string $dir style directory
	* @return array|bool Style data, false on error
	*/
	protected function read_style_cfg(string $dir): array
	{
		// This should never happen, we give them a red warning because of its relevance.
		if (!file_exists($this->styles_path . $dir . '/style.cfg'))
		{
			trigger_error($this->user->lang('NO_STYLE_CFG', $dir), E_USER_WARNING);
		}

		static $required = ['name', 'phpbb_version', 'copyright'];

		$cfg = parse_cfg_file($this->styles_path . $dir . '/style.cfg');

		// Check if it is a valid file
		foreach ($required as $key)
		{
			if (!isset($cfg[$key]))
			{
				return false;
			}
		}

		// Check data
		if (!isset($cfg['parent']) || !is_string($cfg['parent']) || $cfg['parent'] == $cfg['name'])
		{
			$cfg['parent'] = '';
		}
		if (!isset($cfg['template_bitfield']))
		{
			$cfg['template_bitfield'] = $this->default_bitfield();
		}

		return $cfg;
	}

	/**
	* Lists all styles
	*
	* @return array Rows with styles data
	*/
	protected function get_styles(int $style_id): array
	{
		$sql = 'SELECT *
			FROM ' . STYLES_TABLE . "
			WHERE style_id = '" . $this->db->sql_escape($style_id) . "'";
		$result = $this->db->sql_query($sql);

		$rows = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		return $rows;
	}

	/**
	* Generates default bitfield
	*
	* This bitfield decides which bbcodes are defined in a template.
	*
	* @return string Bitfield
	*/
	protected function default_bitfield(): mixed
	{
		static $value;
		if (isset($value))
		{
			return $value;
		}

		// Hardcoded template bitfield to add for new templates
		$default_bitfield = '1111111111111';

		$bitfield = new \bitfield();
		for ($i = 0; $i < strlen($default_bitfield); $i++)
		{
			if ($default_bitfield[$i] == '1')
			{
				$bitfield->set($i);
			}
		}

		return $bitfield->get_base64();
	}

	protected function diff_stylesheet(string $oldfile, string $newfile): array
	{
		preg_match_all ("/url\(\"([^)]+)\"\)/", $oldfile, $match);
		$old = $match[1];

		preg_match_all ("/url\(\"([^)]+)\"\)/", $newfile, $match);
		$new = $match[1];

		return array_diff($new, $old);
	}

	/**
	* Set page url
	*
	* @param string $u_action Custom form action
	* @return null
	* @access public
	*/
	public function set_page_url(string $u_action): void
	{
		$this->u_action = $u_action;
	}
}
