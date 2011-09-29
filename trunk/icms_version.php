<?php
/**
 * imPolling version infomation
 *
 * This file holds the configuration information of this module
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		Sina Asghari <stranger@impresscms.org>
 * @package		impolling
 * @version		$Id$
 */

defined("ICMS_ROOT_PATH") or die("ICMS root path not defined");

/**  General Information  */
$modversion = array(
	"name"						=> _MI_IMPOLLING_MD_NAME,
	"version"					=> 1.0,
	"description"				=> _MI_IMPOLLING_MD_DESC,
	"author"					=> "Sina Asghari",
	"credits"					=> "LupusC, Kazumi Ono and wellwine.",
	"help"						=> "",
	"license"					=> "GNU General Public License (GPL)",
	"official"					=> 0,
	"dirname"					=> basename(dirname(__FILE__)),
	"modname"					=> "impolling",

/**  Images information  */
	"iconsmall"					=> "images/icon_small.png",
	"iconbig"					=> "images/icon_big.png",
	"image"						=> "images/icon_big.png", /* for backward compatibility */

/**  Development information */
	"status_version"			=> "1.0",
	"status"					=> "Beta",
	"date"						=> "Unreleased",
	"author_word"				=> "",
	"warning"					=> _CO_ICMS_WARNING_BETA,

/** Contributors */
	"developer_website_url"		=> "http://community.impresscms.org/",
	"developer_website_name"	=> "ImpressCMS community",
	"developer_email"			=> "stranger@impresscms.org",

/** Administrative information */
	"hasAdmin"					=> 1,
	"adminindex"				=> "admin/index.php",
	"adminmenu"					=> "admin/menu.php",

/** Install and update informations */
	"onInstall"					=> "include/onupdate.inc.php",
	"onUpdate"					=> "include/onupdate.inc.php",

/** Search information */
	"hasSearch"					=> true,
	"search"					=> array("file" => "include/search.inc.php", "func" => "impolling_search"),

/** Menu information */
	"hasMain"					=> 1,

/** Comments information */
	"hasComments"				=> 1,
	"comments"					=> array(
									"itemName" => "poll_id",
									"pageName" => "poll.php",
									"callbackFile" => "include/comment.inc.php",
									"callback" => array("approve" => "impolling_com_approve",
														"update" => "impolling_com_update")));

/** other possible types: testers, translators, documenters and other */
$modversion['people']['developers'][] = "[url=http://community.impresscms.org/userinfo.php?uid=392]Sina Asghari[/url]";

/** Manual */
$modversion['manual']['wiki'][] = "<a href='http://wiki.impresscms.org/index.php?title=imPolling' target='_blank'>English</a>";

/** Database information */
$modversion['object_items'][1] = 'poll';
$modversion['object_items'][] = 'log';

$modversion["tables"] = icms_getTablesArray($modversion['dirname'], $modversion['object_items']);

/** Templates information */
$modversion['templates'] = array(
	array("file" => "impolling_admin_poll.html", "description" => "poll Admin Index"),
	array("file" => "impolling_poll.html", "description" => "poll Index"),
	array("file" => "impolling_admin_log.html", "description" => "log Admin Index"),
	array("file" => "impolling_log.html", "description" => "log Index"),
	array("file" => "impolling_index.html", "description" => "Module Index"),
	array("file" => "impolling_single_poll.html", "description" => "Single Poll"),

	array('file' => 'impolling_header.html', 'description' => 'Module Header'),
	array('file' => 'impolling_footer.html', 'description' => 'Module Footer'));

/** Blocks information */
/** To come soon in imBuilding... */

/** Preferences information */

// Retrieve the group user list, because the automatic group_multi config formtype does not include Anonymous group :-(
$member_handler = icms::handler('icms_member');
$groups_array = $member_handler->getGroupList();
foreach($groups_array as $k=>$v) {
	$select_groups_options[$v] = $k;
}

$modversion['config'][1] = array(
  'name' => 'poller_groups',
  'title' => '_MI_IMPOLLING_POSTERGR',
  'description' => '_MI_IMPOLLING_POSTERGRDSC',
  'formtype' => 'select_multi',
  'valuetype' => 'array',
  'options' => $select_groups_options,
  'default' =>  '1');

$modversion['config'][] = array(
  'name' => 'polls_limit',
  'title' => '_MI_IMPOLLING_LIMIT',
  'description' => '_MI_IMPOLLING_LIMITDSC',
  'formtype' => 'textbox',
  'valuetype' => 'text',
  'default' => 5);


/** Notification information */
/** To come soon in imBuilding... */