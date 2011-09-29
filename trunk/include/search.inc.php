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

function impolling_search($queryarray, $andor, $limit, $offset, $userid) {

	$impolling_poll_handler = icms_getModuleHandler("poll", basename(dirname(dirname(__FILE__))), "impolling");
	$pollsArray = $impolling_poll_handler->getPollsForSearch($queryarray, $andor, $limit, $offset, $userid);

	$ret = array();

/*	foreach ($pollsArray as $pollArray) {
		$item['image'] = "images/poll.png";
		$item['link'] = str_replace(IMPOLLING_URL, '', $pollArray['itemUrl']);
		$item['title'] = $pollArray['poll_title'];
		$item['time'] = strtotime($pollArray['poll_published_date']);
		$item['uid'] = $pollArray['poll_pollerid'];
		$ret[] = $item;
		unset($item);
	}
*/
	return $ret;
}