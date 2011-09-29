<?php
/**
 * User index page of the module
 *
 * Including the poll page
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

$xoopsOption['template_main'] = 'impolling_index.html';
/** Include the ICMS header file */
include_once ICMS_ROOT_PATH . '/header.php';

// At which record shall we start display
$clean_start = isset($_GET['start']) ? intval($_GET['start']) : 0;
$clean_user_id = isset($_GET['uid']) ? intval($_GET['uid']) : false;
$impolling_poll_handler = icms_getModuleHandler('poll');
$icmsTpl->assign('impolling_polls', $impolling_poll_handler->getPolls($clean_start, $icmsModuleConfig['polls_limit'], $clean_user_id));
/**
 * Create Navbar
 */
include_once ICMS_ROOT_PATH . '/class/pagenav.php';
$polls_count = $impolling_poll_handler->getPollsCount($clean_user_id);
$extr_argArray = array();
$category_pathArray = array();

if ($clean_user_id) {
	$impolling_poller_link = icms_getLinkedUnameFromId($clean_user_id);
	$icmsTpl->assign('impolling_rss_url', IMPOLLING_URL . 'rss.php?uid=' . $clean_user_id);
	$icmsTpl->assign('impolling_rss_info', _MD_IMPOLLING_RSS_POSTER);
	$extr_arg = 'uid=' . $clean_user_id;
} else {
	$icmsTpl->assign('impolling_rss_url', IMPOLLING_URL . 'rss.php');
	$icmsTpl->assign('impolling_rss_info', _MD_IMPOLLING_RSS_GLOBAL);
	$extr_arg = '';
}
if ($clean_user_id) {
	$extr_argArray[] = 'uid=' . $clean_user_id;
	$category_pathArray[] = sprintf(_CO_IMPOLLING_POST_FROM_USER, icms_getLinkedUnameFromId($clean_user_id));
}

	$config_handler =& xoops_gethandler('config');
	$icmsConfig =& $config_handler->getConfigsByCat(1);

$extr_arg = count($extr_argArray) > 0 ? implode('&amp;', $extr_argArray) : '';

$pagenav = new icms_view_PageNav ($polls_count, $icmsModuleConfig['polls_limit'], $clean_start, 'start', $extr_arg);
$icmsTpl->assign('navbar', $pagenav->renderNav());

$icmsTpl->assign('impolling_module_home', icms_getModuleName(true, true));

$icmsTpl->assign('impolling_showSubmitLink', true);

/** Include the module's footer */
include_once 'footer.php';
