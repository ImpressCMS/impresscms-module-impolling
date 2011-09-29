<?php
/**
 * Footer page included at the end of each page on user side of the mdoule
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		Sina Asghari <stranger@impresscms.org>
 * @package		impolling
 * @version		$Id$
 */

defined("ICMS_ROOT_PATH") or die("ICMS root path not defined");

$icmsTpl->assign("impolling_adminpage", "<a href='" . ICMS_URL . "/modules/" . icms::$module->getVar("dirname") . "/admin/index.php'>" ._MD_IMPOLLING_ADMIN_PAGE . "</a>");
$icmsTpl->assign("impolling_is_admin", icms_userIsAdmin(IMPOLLING_DIRNAME));
$icmsTpl->assign('impolling_url', IMPOLLING_URL);
$icmsTpl->assign('impolling_images_url', IMPOLLING_IMAGES_URL);

$xoTheme->addStylesheet(IMPOLLING_URL . 'module' . ((defined("_ADM_USE_RTL") && _ADM_USE_RTL) ? '_rtl' : '') . '.css');

include_once ICMS_ROOT_PATH . '/footer.php';