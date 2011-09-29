<?php
/**
 * Common file of the module included on all pages of the module
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		Sina Asghari <stranger@impresscms.org>
 * @package		impolling
 * @version		$Id$
 */

defined("ICMS_ROOT_PATH") or die("ICMS root path not defined");

if (!defined("IMPOLLING_DIRNAME")) define("IMPOLLING_DIRNAME", $modversion["dirname"] = basename(dirname(dirname(__FILE__))));
if (!defined("IMPOLLING_URL")) define("IMPOLLING_URL", ICMS_URL."/modules/".IMPOLLING_DIRNAME."/");
if (!defined("IMPOLLING_ROOT_PATH")) define("IMPOLLING_ROOT_PATH", ICMS_ROOT_PATH."/modules/".IMPOLLING_DIRNAME."/");
if (!defined("IMPOLLING_IMAGES_URL")) define("IMPOLLING_IMAGES_URL", IMPOLLING_URL."images/");
if (!defined("IMPOLLING_ADMIN_URL")) define("IMPOLLING_ADMIN_URL", IMPOLLING_URL."admin/");

// Include the common language file of the module
icms_loadLanguageFile("impolling", "common");