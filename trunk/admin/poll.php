<?php
/**
 * Admin page to manage polls
 *
 * List, add, edit and delete poll objects
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		Sina Asghari <stranger@impresscms.org>
 * @package		impolling
 * @version		$Id$
 */

/**
 * Edit a Poll
 *
 * @param int $poll_id Pollid to be edited
*/
function editpoll($poll_id = 0) {
	global $impolling_poll_handler, $icmsModule, $icmsAdminTpl;

	$pollObj = $impolling_poll_handler->get($poll_id);

	if (!$pollObj->isNew()){
		$icmsModule->displayAdminMenu(0, _AM_IMPOLLING_POLLS . " > " . _CO_ICMS_EDITING);
		$sform = $pollObj->getForm(_AM_IMPOLLING_POLL_EDIT, "addpoll");
		$sform->assign($icmsAdminTpl);
	} else {
		$icmsModule->displayAdminMenu(0, _AM_IMPOLLING_POLLS . " > " . _CO_ICMS_CREATINGNEW);
		$sform = $pollObj->getForm(_AM_IMPOLLING_POLL_CREATE, "addpoll");
		$sform->assign($icmsAdminTpl);

	}
	$icmsAdminTpl->display("db:impolling_admin_poll.html");
}

include_once "admin_header.php";

$impolling_poll_handler = icms_getModuleHandler("poll", basename(dirname(dirname(__FILE__))), "impolling");
/** Use a naming convention that indicates the source of the content of the variable */
$clean_op = "";
/** Create a whitelist of valid values, be sure to use appropriate types for each value
 * Be sure to include a value for no parameter, if you have a default condition
 */
$valid_op = array ("mod", "changedField", "addpoll", "del", "view", "");

if (isset($_GET["op"])) $clean_op = htmlentities($_GET["op"]);
if (isset($_POST["op"])) $clean_op = htmlentities($_POST["op"]);

/** Again, use a naming convention that indicates the source of the content of the variable */
$clean_poll_id = isset($_GET["poll_id"]) ? (int)$_GET["poll_id"] : 0 ;

/**
 * in_array() is a native PHP function that will determine if the value of the
 * first argument is found in the array listed in the second argument. Strings
 * are case sensitive and the 3rd argument determines whether type matching is
 * required
*/
if (in_array($clean_op, $valid_op, TRUE)) {
	switch ($clean_op) {
		case "mod":
		case "changedField":
			icms_cp_header();
			editpoll($clean_poll_id);
			break;
		case "del":
			$controller = new icms_ipf_Controller($impolling_poll_handler);
			$controller->handleObjectDeletion();
			break;

		case "view" :
			$pollObj = $impolling_poll_handler->get($clean_poll_id);
			icms_cp_header();
			$pollObj->displaySingleObject();
			break;

		default:
			icms_cp_header();
			$icmsModule->displayAdminMenu(0, _AM_IMPOLLING_POLLS);
			$objectTable = new icms_ipf_view_Table($impolling_poll_handler);
			$objectTable->addColumn(new icms_ipf_view_Column("poll_id"));
			$objectTable->addColumn(new icms_ipf_view_Column('user_id', false, false, 'getPollSender'));
			$objectTable->addColumn(new icms_ipf_view_Column('question', _GLOBAL_LEFT, false, 'getPollTitle'));
			$objectTable->addColumn(new icms_ipf_view_Column('begin_time'));
			$objectTable->addColumn(new icms_ipf_view_Column('expire_time'));
			$objectTable->addQuickSearch(array('question', 'description'));
			$objectTable->setDefaultSort('begin_time');
			$objectTable->setDefaultOrder('DESC');
//			$objectTable->addIntroButton("addpoll", "poll.php?op=mod", _AM_IMPOLLING_POLL_CREATE);

			$icmsAdminTpl->assign("impolling_poll_table", $objectTable->fetch());
			$icmsAdminTpl->display("db:impolling_admin_poll.html");
			break;
	}
	icms_cp_footer();
}
/**
 * If you want to have a specific action taken because the user input was invalid,
 * place it at this point. Otherwise, a blank page will be displayed
 */