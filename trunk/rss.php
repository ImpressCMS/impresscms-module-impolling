<?php
/**
 * Generating an RSS feed
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		Sina Asghari <stranger@impresscms.org>
 * @package		impolling
 * @version		$Id$
 */

/** Include the module's header for all pages */
include_once 'header.php';
include_once ICMS_ROOT_PATH . '/header.php';

$clean_poll_uid = isset($_GET['uid']) ? intval($_GET['uid']) : FALSE;

$impolling_feed = new icms_feeds_Rss();

$impolling_feed->title = $icmsConfig['sitename'] . ' - ' . $icmsModule->name();
$impolling_feed->url = XOOPS_URL;
$impolling_feed->description = $icmsConfig['slogan'];
$impolling_feed->language = _LANGCODE;
$impolling_feed->charset = _CHARSET;
$impolling_feed->category = $icmsModule->name();

$impolling_poll_handler = icms_getModuleHandler("poll", basename(dirname(__FILE__)), "impolling");
//ImpollingPollHandler::getPolls($start = 0, $limit = 0, $poll_uid = FALSE, $year = FALSE, $month = FALSE
$pollsArray = $impolling_poll_handler->getPolls(0, 10, $clean_poll_uid);

foreach($pollsArray as $pollArray) {
	$impolling_feed->feeds[] = array (
	  'title' => $pollArray['question'],
	  'link' => str_replace('&', '&amp;', $pollArray['itemUrl']),
	  'description' => htmlspecialchars(str_replace('&', '&amp;', $pollArray['description']), ENT_QUOTES),
	  'pubdate' => $pollArray['creation_time'],
	  'guid' => str_replace('&', '&amp;', $pollArray['itemUrl']),
	);
}

$impolling_feed->render();
