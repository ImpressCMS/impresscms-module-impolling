<?php
/**
* Poll page
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		Sina Asghari <stranger@impresscms.org>
* @package		impolling
* @version		$Id$
*/

/**
 * Edit a Poll Poll
 *
 * @param object $pollObj ImpollinPoll object to be edited
*/
function editpoll($pollObj)
{
	global $impolling_poll_handler, $xoTheme, $icmsTpl, $icmsUser;

	$icmsTpl->assign('hideForm', $hideForm);
	if (!$pollObj->isNew()){
		if (!$pollObj->userCanEditAndDelete()) redirect_header($pollObj->getItemLink(true), 3, _NOPERM);
		$pollObj->hideFieldFromForm(array('creation_time', 'user_id', 'meta_keywords', 'meta_description', 'short_url'));
		$sform = $pollObj->getSecureForm($hideForm ? '' :_MD_IMPOLLING_POLL_EDIT, 'addpoll');
		$sform->assign($icmsTpl, 'impolling_pollform');
		$icmsTpl->assign('impolling_poll_path', $pollObj->getVar('question') . ' > ' . _EDIT);
	} else {
		if (!$impolling_poll_handler->userCanSubmit()) {
			redirect_header(IMPOLLING_URL, 3, _NOPERM);
		}
		$pollObj->setVar('user_id', $icmsUser->uid());
		$pollObj->setVar('creation_time', time());
		$pollObj->hideFieldFromForm(array('poll_published_date', 'poll_uid', 'meta_keywords', 'meta_description', 'short_url'));
		$sform = $pollObj->getSecureForm($hideForm ? '' :_MD_IMPOLLING_POLL_CREATE, 'addpoll');
		$sform->assign($icmsTpl, 'impolling_pollform');
		$icmsTpl->assign('impolling_category_path', _SUBMIT);
	}
}

include_once "header.php";

$xoopsOption["template_main"] = "impolling_poll.html";
include_once ICMS_ROOT_PATH . "/header.php";

$impolling_poll_handler = icms_getModuleHandler("poll", basename(dirname(__FILE__)), "impolling");


/** Use a naming convention that indicates the source of the content of the variable */
$clean_op = '';

if (isset($_GET['op'])) $clean_op = $_GET['op'];
if (isset($_POST['op'])) $clean_op = $_POST['op'];

/** Again, use a naming convention that indicates the source of the content of the variable */
$clean_poll_id = isset($_GET['poll_id']) ? intval($_GET['poll_id']) : 0 ;
$real_uid = is_object(icms::$user) ? (int)icms::$user->getVar('uid') : 0;
$clean_uid = isset($_GET['uid']) ? (int)$_GET['uid'] : $real_uid ;

/** Create a whitelist of valid values, be sure to use appropriate types for each value
 * Be sure to include a value for no parameter, if you have a default condition
 */
$valid_op = array ('mod','addpoll','del','');
/**
 * Only proceed if the supplied operation is a valid operation
 */
if (in_array($clean_op,$valid_op,true)){
  switch ($clean_op) {
	case "mod":
		if ($clean_poll_id > 0 && $pollObj->isNew()) redirect_header(icms_getPreviousPage('index.php'), 3, _NOPERM);
		editpoll($pollObj);
		break;
	case "addpoll":
		if (!icms::$security->check()) {
			redirect_header(icms_getPreviousPage('index.php'), 3, _MD_IMPOLLING_SECURITY_CHECK_FAILED . implode('<br />', icms::$security->getErrors()));
		}

		$controller = new icms_ipf_Controller($profile_poll_handler);
		$controller->storeFromDefaultForm(_MD_IMPOLLING_POLLS_CREATED, _MD_IMPOLLING_POLLS_MODIFIED, IMPOLLING_URL.basename(__FILE__));
		break;
	case "del":
		if ($pollObj->isNew() || !$pollObj->userCanEditAndDelete()) redirect_header(IMPOLLING_URL.basename(__FILE__), 3, _NOPERM);

		if (isset($_POST['confirm'])) {
		    if (!icms::$security->check()) {
		    	redirect_header(icms_getPreviousPage('index.php'), 3, _MD_IMPOLLING_SECURITY_CHECK_FAILED . implode('<br />', icms::$security->getErrors()));
		    }
		}

		$controller = new icms_ipf_Controller($profile_poll_handler);
		$controller->handleObjectDeletionFromUserSide();
		$icmsTpl->assign('profile_category_path', $pollObj->getVar('title') . ' > ' . _DELETE);
		break;
	default:
		$pollArray = $impolling_poll_handler->getPoll($clean_poll_id);
		$impolling_poll_handler->updateCounter($clean_poll_id);
		$icmsTpl->assign('impolling_poll', $pollArray);
		$icmsTpl->assign('impolling_category_path', $pollArray['question']);

		$icmsTpl->assign('impolling_showSubmitLink', true);
		$icmsTpl->assign('impolling_rss_url', IMPOLLING_URL . 'rss.php');
		$icmsTpl->assign('impolling_rss_info', _MD_IMPOLLING_RSS_GLOBAL);

		if ($icmsModuleConfig['com_rule'] && $pollArray['poll_cancomment']) {
			$icmsTpl->assign('impolling_poll_comment', true);
  			include_once ICMS_ROOT_PATH . '/include/comment_view.php';
		}
		/**
		 * Generating meta information for this page
		 */
		$icms_metagen = new IcmsMetagen($pollArray['question'], $pollArray['meta_keywords'], $pollArray['meta_description']);
		$icms_metagen->createMetaTags();

		break;
	}
}

/* @ Todo: Integrate this code to the module

	// public
	function renderForm() {
		$content = "<form action='".XOOPS_URL."/modules/umfrage/index.php' method='post'>";
		$content .= "<table width='100%' border='0' cellpadding='4' cellspacing='1'>\n";
		$content .= "<tr class='bg3'><td align='center' colspan='2'><input type='hidden' name='poll_id' value='".$this->poll->getVar("poll_id")."' />\n";
		$content .= "<b>".$this->poll->getVar("question")."</b></td></tr>\n";
		$options_arr = & UmfrageOption :: getAllByPollId($this->poll->getVar("poll_id"));
		$option_type = "radio";
		$option_name = "option_id";
		if ($this->poll->getVar("multiple") == 1) {
			$option_type = "checkbox";
			$option_name .= "[]";
		}
		foreach ($options_arr as $option) {
			$content .= "<tr class='bg1'><td align='center'><input type='$option_type' name='$option_name' value='".$option->getVar("option_id")."' /></td><td align='left'>".$option->getVar("option_text")."</td></tr>\n";
		}

		$content .= "<tr class='bg3'><td align='center' colspan='2'><input type='submit' value='"._PL_VOTE."' />&nbsp;";
		$content .= "<input type='button' value='"._PL_RESULTS."' class='button' onclick='location=\"".XOOPS_URL."/modules/umfrage/pollresults.php?poll_id=".$this->poll->getVar("poll_id")."\"' />";
		$content .= "</td></tr></table></form>\n";
		return $content;
	}

	function assignForm(& $tpl) {
		$options_arr = & UmfrageOption :: getAllByPollId($this->poll->getVar("poll_id"));
		$option_type = "radio";
		$option_name = "option_id";
		if ($this->poll->getVar("multiple") == 1) {
			$option_type = "checkbox";
			$option_name .= "[]";
		}
		$i = 0;
		foreach ($options_arr as $option) {
			$options[$i]['input'] = "<input type='$option_type' name='$option_name' value='".$option->getVar("option_id")."' />";
			$options[$i]['text'] = $option->getVar("option_text");
			$i ++;
		}
		//$tpl->assign('poll', array('question' => $this->poll->getVar("question"), 'pollId' => $this->poll->getVar("poll_id"), 'viewresults' => XOOPS_URL."/modules/umfrage/pollresults.php?poll_id=".$this->poll->getVar("poll_id"), 'action' => XOOPS_URL."/modules/umfrage/index.php", 'options' => $options));
		// wellwine
		$tpl->assign('poll', array ('description' => $this->poll->getVar("description"), 'question' => $this->poll->getVar("question"), 'pollId' => $this->poll->getVar("poll_id"), 'viewresults' => XOOPS_URL."/modules/umfrage/pollresults.php?poll_id=".$this->poll->getVar("poll_id"), 'action' => XOOPS_URL."/modules/umfrage/index.php", 'options' => $options, 'polltype' => $this->poll->getVar("polltype")));
	}

	// public
	function renderResults() {
		if (!$this->poll->hasExpired()) {
			$end_text = sprintf(_PL_ENDSAT, formatTimestamp($this->poll->getVar("end_time"), "m"));
		} else {
			$end_text = sprintf(_PL_ENDEDAT, formatTimestamp($this->poll->getVar("end_time"), "m"));
		}
		echo "<div style='text-align:center'><table width='60%' border='0' cellpadding='4' cellspacing='0'><tr class='bg3'><td><span style='font-weight:bold;'>".$this->poll->getVar("question")."</span></td></tr><tr class='bg1'><td align='right'>$end_text</td></tr></table>";

		echo "<table width='60%' border='0' cellpadding='4' cellspacing='0'>";
		$options_arr = & UmfrageOption :: getAllByPollId($this->poll->getVar("poll_id"));
		$total = $this->poll->getVar("votes");
		foreach ($options_arr as $option) {
			if ($total > 0) {
				$percent = 100 * $option->getVar("option_count") / $total;
			} else {
				$percent = 0;
			}
			echo "<tr class='bg1'><td width='30%' align='left'>".$option->getVar("option_text")."</td><td width='70%' align='left'>";
			if ($percent > 0) {
				$width = intval($percent) * 2;
				echo "<img src='".XOOPS_URL."/modules/umfrage/images/colorbars/".$option->getVar("option_color", "E")."' height='14' width='".$width."' align='middle' alt='".intval($percent)." %' />";
			}
			printf(" %d %% (%d)", $percent, $option->getVar("option_count"));
			echo "</td></tr>";
		}
		echo "<tr class='bg1'><td colspan='2' align='center'><br /><b>".sprintf(_PL_TOTALVOTES, $total)."<br />".sprintf(_PL_TOTALVOTERS, $this->poll->getVar("voters"))."</b>";
		if (!$this->poll->hasExpired()) {
			echo "<br />[<a href='".XOOPS_URL."/modules/umfrage/index.php?poll_id=".$this->poll->getVar("poll_id")."'>"._PL_VOTE."</a>]";
		}
		echo "</td></tr></table></div><br />";
	}

	function assignResults(& $tpl) {
		global $xoopsUser;
		if (!$this->poll->hasExpired()) {
			$end_text = sprintf(_PL_ENDSAT, formatTimestamp($this->poll->getVar("end_time"), "m"));
		} else {
			$end_text = sprintf(_PL_ENDEDAT, formatTimestamp($this->poll->getVar("end_time"), "m"));
		}
		$options_arr = & UmfrageOption :: getAllByPollId($this->poll->getVar("poll_id"));
		$total = $this->poll->getVar("votes");
		$i = 0;
		foreach ($options_arr as $option) {
			if ($total > 0) {
				$percent = 100 * $option->getVar("option_count") / $total;
			} else {
				$percent = 0;
			}
			$options[$i]['text'] = $option->getVar("option_text");
			if ($percent > 0) {
				$width = intval($percent) * 2;
				$options[$i]['image'] = "<img src='".XOOPS_URL."/modules/umfrage/images/colorbars/".$option->getVar("option_color", "E")."' height='14' width='".$width."' align='middle' alt='".intval($percent)." %' />";
			}
			$options[$i]['percent'] = sprintf(" %d %% (%d)", $percent, $option->getVar("option_count"));
			$options[$i]['total'] = $option->getVar("option_count");
			$i ++;
		}

		if (!$this->poll->hasExpired() && $xoopsUser && !(UmfrageLog :: hasVoted($this->poll->getVar("poll_id"), xoops_getenv('REMOTE_ADDR'), $uid)))  {
			//			$vote = "<a href='".XOOPS_URL."/modules/umfrage/index.php?poll_id=".$this->poll->getVar("poll_id")."'>"._PL_VOTE."</a>";
			$vote = "<input type='button' value='"._PL_VOTE."' onclick='location=\"".XOOPS_URL."/modules/umfrage/index.php?poll_id=".$this->poll->getVar("poll_id")."\"' />";
		} else {
			$vote = "";
		}
		//$tpl->assign('poll', array('question' => $this->poll->getVar("question"),'end_text' => $end_text,'totalVotes' => sprintf(_PL_TOTALVOTES, $total), 'totalVoters' => sprintf(_PL_TOTALVOTERS, $this->poll->getVar("voters")),'vote' => $vote, 'options' => $options));
		// wellwine
		$tpl->assign('poll', array ('description' => $this->poll->getVar("description"), 'question' => $this->poll->getVar("question"), 'end_text' => $end_text, 'totalVotes' => sprintf(_PL_TOTALVOTES, $total), 'totalVoters' => sprintf(_PL_TOTALVOTERS, $this->poll->getVar("voters")), 'vote' => $vote, 'options' => $options));
	}

*/

$icmsTpl->assign("impolling_module_home", '<a href="' . ICMS_URL . "/modules/" . icms::$module->getVar("dirname") . '/">' . icms::$module->getVar("name") . "</a>");

include_once "footer.php";