<?php
/**
 * Classes responsible for managing imPolling poll objects
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		Sina Asghari <stranger@impresscms.org>
 * @package		impolling
 * @version		$Id$
 */

defined("ICMS_ROOT_PATH") or die("ICMS root path not defined");

// Poll options definitions
define('POLLS_OPTION_1', 1);
define('POLLS_OPTION_2', 2);
define('POLLS_OPTION_3', 3);

// Poll options colours definitions
define('POLLS_OPTION_COLOUR_1', 'aqua');
define('POLLS_OPTION_COLOUR_2', 'blue');
define('POLLS_OPTION_COLOUR_3', 'brown');
define('POLLS_OPTION_COLOUR_4', 'darkgreen');
define('POLLS_OPTION_COLOUR_5', 'gold');
define('POLLS_OPTION_COLOUR_6', 'green');
define('POLLS_OPTION_COLOUR_7', 'grey');
define('POLLS_OPTION_COLOUR_8', 'orange');
define('POLLS_OPTION_COLOUR_9', 'pink');
define('POLLS_OPTION_COLOUR_10', 'purple');
define('POLLS_OPTION_COLOUR_11', 'red');
define('POLLS_OPTION_COLOUR_12', 'yellow');

class mod_impolling_PollHandler extends icms_ipf_Handler {
	private $_polls_optionArray = array();
	private $_option_coloursArray = array();
	/**
	 * Constructor
	 *
	 * @param icms_db_legacy_Database $db database connection object
	 */
	public function __construct(&$db) {
		parent::__construct($db, "poll", "poll_id", "question", "description", "impolling");

	}

	/**
	 * Retreive the possible options of a poll object
	 *
	 * @return array of status
	 */
	public function getPoll_optionsArray() {
		if (!$this->_polls_optionArray) {
			$this->_polls_optionArray[POLLS_OPTION_1] = _CO_IMPOLLING_POLLS_OPTION_1;
			$this->_polls_optionArray[POLLS_OPTION_2] = _CO_IMPOLLING_POLLS_OPTION_2;
			$this->_polls_optionArray[POLLS_OPTION_3] = _CO_IMPOLLING_POLLS_OPTION_3;
		}
		return $this->_polls_optionArray;
	}

	/**
	 * Retreive the possible colour options of a poll object
	 *
	 * @return array of status
	 */
	public function getoption_coloursArray() {
		if (!$this->_option_coloursArray) {
			$this->_option_coloursArray[POLLS_OPTION_COLOUR_1] = _CO_IMPOLLING_POLLS_POLLS_OPTION_COLOUR_1;
			$this->_option_coloursArray[POLLS_OPTION_COLOUR_2] = _CO_IMPOLLING_POLLS_POLLS_OPTION_COLOUR_2;
			$this->_option_coloursArray[POLLS_OPTION_COLOUR_3] = _CO_IMPOLLING_POLLS_POLLS_OPTION_COLOUR_3;
			$this->_option_coloursArray[POLLS_OPTION_COLOUR_4] = _CO_IMPOLLING_POLLS_POLLS_OPTION_COLOUR_4;
			$this->_option_coloursArray[POLLS_OPTION_COLOUR_5] = _CO_IMPOLLING_POLLS_POLLS_OPTION_COLOUR_5;
			$this->_option_coloursArray[POLLS_OPTION_COLOUR_6] = _CO_IMPOLLING_POLLS_POLLS_OPTION_COLOUR_6;
			$this->_option_coloursArray[POLLS_OPTION_COLOUR_7] = _CO_IMPOLLING_POLLS_POLLS_OPTION_COLOUR_7;
			$this->_option_coloursArray[POLLS_OPTION_COLOUR_8] = _CO_IMPOLLING_POLLS_POLLS_OPTION_COLOUR_8;
			$this->_option_coloursArray[POLLS_OPTION_COLOUR_9] = _CO_IMPOLLING_POLLS_POLLS_OPTION_COLOUR_9;
			$this->_option_coloursArray[POLLS_OPTION_COLOUR_10] = _CO_IMPOLLING_POLLS_POLLS_OPTION_COLOUR_10;
			$this->_option_coloursArray[POLLS_OPTION_COLOUR_11] = _CO_IMPOLLING_POLLS_POLLS_OPTION_COLOUR_11;
			$this->_option_coloursArray[POLLS_OPTION_COLOUR_12] = _CO_IMPOLLING_POLLS_POLLS_OPTION_COLOUR_12;
		}
		return $this->_option_coloursArray;
	}

	/**
	 * Create the criteria that will be used by getPolls and getPollCount
	 *
	 * @param int $start to which record to start
	 * @param int $limit limit of poll to return
	 * @param int $user_id if specifid, only the poll of this user will be returned
	 * @param int $poll_id ID of a single poll to retrieve
	 * @return icms_db_criteria_Compo $criteria
	 */
	private function getPollCriteria($start = 0, $limit = 0, $user_id = false, $poll_id = false) {
		$criteria = new icms_db_criteria_Compo();
		if ($start) $criteria->setStart((int)$start);
		if ($limit) $criteria->setLimit((int)$limit);
		if ($user_id) $criteria->add(new icms_db_criteria_Item('user_id', (int)$user_id));
		if ($poll_id) $criteria->add(new icms_db_criteria_Item('poll_id', (int)$poll_id));
		$criteria->setSort('creation_time');
		$criteria->setOrder('DESC');

		return $criteria;
	}

	/**
	 * Get poll as array, ordered by creation_time DESC
	 *
	 * @param int $start to which record to start
	 * @param int $limit max poll to display
	 * @param int $user_id if specifid, only the poll of this user will be returned
	 * @param int $poll_id ID of a single poll to retrieve
	 * @return array of poll objects
	 */
	public function getPolls($start = 0, $limit = 0, $user_id = false, $poll_id = false) {
		$criteria = $this->getPollCriteria($start, $limit, $user_id, $poll_id);
		$ret = $this->getObjects($criteria, true, false);
		return $ret;
	}


	/**
	 * Get single poll object
	 *
	 * @param int $poll_id
	 * @return object ImbloggingPoll object
	 */
	function getPoll($poll_id) {
		$ret = $this->getPolls(0, 0, false, $poll_id);
		return isset($ret[$poll_id]) ? $ret[$poll_id] : false;
	}

	/**
	 * Update number of comments on a poll
	 *
	 * This method is triggered by impolling_com_update in include/functions.php which is
	 * called by ImpressCMS when updating comments
	 *
	 * @param int $poll_id id of the poll to update
	 * @param int $total_num total number of comments so far in this poll
	 * @return VOID
	 */
	function updateComments($poll_id, $total_num) {
		$pollObj = $this->get($poll_id);
		if ($pollObj && !$pollObj->isNew()) {
			$pollObj->setVar('poll_comments', $total_num);
			$this->insert($pollObj, true);
		}
	}


	/**
	 * Get a list of users
	 *
	 * @return array list of users
	 */
	function getPollersArray() {
		$member_handler = xoops_getHandler('member');
		return $member_handler->getUserList();
	}

	/**
	 * Get polls count
	 *
	 * @param int $poll_uid if specifid, only the poll of this user will be returned
	 * @param int $cid if specifid, only the poll related to this category will be returned
	 * @return array of polls
	 * @param int $year of polls to display
	 * @param int $month of polls to display
	 */
	function getPollsCount($poll_uid) {
		$criteria = $this->getPollCriteria(false, false, $poll_uid);
		return $this->getCount($criteria);
	}

	/**
	 * Get Polls requested by the global search feature
	 *
	 * @param array $queryarray array containing the searched keywords
	 * @param bool $andor wether the keywords should be searched with AND or OR
	 * @param int $limit maximum results returned
	 * @param int $offset where to start in the resulting dataset
	 * @param int $userid should we return polls by specific poller ?
	 * @return array array of polls
	 */
	function getPollsForSearch($queryarray, $andor, $limit, $offset, $userid) {
		$criteria = new CriteriaCompo();

		$criteria->setStart($offset);
		$criteria->setLimit($limit);

		if ($userid != 0) {
			$criteria->add(new Criteria('user_id', $userid));
		}
		if ($queryarray) {
			$criteriaKeywords = new CriteriaCompo();
			for ($i = 0; $i < count($queryarray); $i++) {
				$criteriaKeyword = new CriteriaCompo();
				$criteriaKeyword->add(new Criteria('question', '%' . $queryarray[$i] . '%', 'LIKE'), 'OR');
				$criteriaKeyword->add(new Criteria('description', '%' . $queryarray[$i] . '%', 'LIKE'), 'OR');
				$criteriaKeywords->add($criteriaKeyword, $andor);
				unset ($criteriaKeyword);
			}
			$criteria->add($criteriaKeywords);
		}
		return $this->getObjects($criteria, true, false);
	}

	/**
	 * Check wether the current user can submit a new poll or not
	 *
	 * @return bool true if he can false if not
	 */
	function userCanSubmit() {
		global $icmsUser, $impolling_isAdmin;
		$impollingModuleConfig = icms_getModuleConfig('impolling');

		if (!is_object($icmsUser)) {
			return false;
		}
		if ($impolling_isAdmin) {
			return true;
		}
		$user_groups = $icmsUser->getGroups();
		return count(array_intersect($impollingModuleConfig['poller_groups'], $user_groups)) > 0;
	}

	/**
	 * Update the counter field of the poll object
	 *
	 * @todo add this in directly in the IPF
	 * @param int $poll_id
	 *
	 * @return VOID
	 */
	function updateCounter($id) {
		$sql = 'UPDATE ' . $this->table . ' SET counter = counter + 1 WHERE ' . $this->keyName . ' = ' . $id;
		$this->query($sql, null, true);
	}
}