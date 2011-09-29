<?php
/**
 * Comment include file
 *
 * File holding functions used by the module to hook with the comment system of ImpressCMS
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		Sina Asghari <stranger@impresscms.org>
 * @package		impolling
 * @version		$Id$
 */

function impolling_com_update($item_id, $total_num) {
    $impolling_post_handler = icms_getModuleHandler("post", basename(dirname(dirname(__FILE__))), "impolling");
    $impolling_post_handler->updateComments($item_id, $total_num);
}

function impolling_com_approve(&$comment) {
    // notification mail here
}