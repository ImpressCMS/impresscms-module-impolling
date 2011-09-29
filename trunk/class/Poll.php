<?php
/**
 * Class representing imPolling poll objects
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		Sina Asghari <stranger@impresscms.org>
 * @package		impolling
 * @version		$Id$
 */

defined("ICMS_ROOT_PATH") or die("ICMS root path not defined");

class mod_impolling_Poll extends icms_ipf_seo_Object {
	/**
	 * Constructor
	 *
	 * @param mod_impolling_Poll $handler Object handler
	 */
	public function __construct(&$handler) {
		icms_ipf_object::__construct($handler);

		$this->quickInitVar("poll_id", XOBJ_DTYPE_INT, TRUE);
		$this->quickInitVar("question", XOBJ_DTYPE_TXTBOX, TRUE);
		$this->quickInitVar("description", XOBJ_DTYPE_TXTAREA, FALSE);
		$this->quickInitVar("user_id", XOBJ_DTYPE_INT, TRUE);
		$this->quickInitVar("creation_time", XOBJ_DTYPE_LTIME, TRUE);
		$this->quickInitVar("begin_time", XOBJ_DTYPE_LTIME, TRUE);
		$this->quickInitVar("expire_time", XOBJ_DTYPE_LTIME, TRUE);
		$this->quickInitVar("votes", XOBJ_DTYPE_INT, FALSE);
		$this->quickInitVar("voters", XOBJ_DTYPE_INT, FALSE);
		//$this->quickInitVar("multiple", XOBJ_DTYPE_INT, FALSE);
		$this->quickInitVar("mail_status", XOBJ_DTYPE_INT, FALSE);
		$this->quickInitVar("polltype", XOBJ_DTYPE_INT, TRUE);
		$this->quickInitVar("autoblockremove", XOBJ_DTYPE_INT, FALSE);
		$this->quickInitVar("weight", XOBJ_DTYPE_INT, FALSE);
		$this->quickInitVar("option1_text", XOBJ_DTYPE_TXTAREA, TRUE);
		$this->quickInitVar("option1_colour", XOBJ_DTYPE_OTHER, TRUE);
		$this->quickInitVar("option2_text", XOBJ_DTYPE_TXTAREA, TRUE);
		$this->quickInitVar("option2_colour", XOBJ_DTYPE_OTHER, TRUE);
		$this->quickInitVar("option3_text", XOBJ_DTYPE_TXTAREA, FALSE);
		$this->quickInitVar("option3_colour", XOBJ_DTYPE_OTHER, FALSE);
		$this->quickInitVar("option4_text", XOBJ_DTYPE_TXTAREA, FALSE);
		$this->quickInitVar("option4_colour", XOBJ_DTYPE_OTHER, FALSE);
		$this->quickInitVar("option5_text", XOBJ_DTYPE_TXTAREA, FALSE);
		$this->quickInitVar("option5_colour", XOBJ_DTYPE_OTHER, FALSE);
		$this->quickInitVar("option6_text", XOBJ_DTYPE_TXTAREA, FALSE);
		$this->quickInitVar("option6_colour", XOBJ_DTYPE_OTHER, FALSE);
		$this->quickInitVar("option7_text", XOBJ_DTYPE_TXTAREA, FALSE);
		$this->quickInitVar("option7_colour", XOBJ_DTYPE_OTHER, FALSE);
		$this->quickInitVar("option8_text", XOBJ_DTYPE_TXTAREA, FALSE);
		$this->quickInitVar("option8_colour", XOBJ_DTYPE_OTHER, FALSE);
		$this->quickInitVar('poll_comments', XOBJ_DTYPE_INT);
		$this->quickInitVar('poll_cancomment', XOBJ_DTYPE_INT, false, false, false, true);
		$this->quickInitVar('poll_notification_sent', XOBJ_DTYPE_INT);
		$this->initCommonVar('counter', false);
		$this->initCommonVar("doimage");
		$this->initCommonVar("docxode");
		$this->setControl('option1_text', 'text');
		$this->setControl('option2_text', 'text');
		$this->setControl('option3_text', 'text');
		$this->setControl('option4_text', 'text');
		$this->setControl('option5_text', 'text');
		$this->setControl('option6_text', 'text');
		$this->setControl('option7_text', 'text');
		$this->setControl('option9_text', 'text');
		//$this->setControl('multiple', 'yesno');
		$this->setControl('description', 'dhtmltextarea');
		$this->setControl('autoblockremove', 'yesno');
		$this->setControl('polltype', array('itemHandler' => 'poll', 'method' => 'getPoll_optionsArray', 'module' => 'impolling'));
		$this->setControl('option1_colour', array('itemHandler' => 'poll', 'method' => 'getoption_coloursArray', 'module' => 'impolling'));
		$this->setControl('option2_colour', array('itemHandler' => 'poll', 'method' => 'getoption_coloursArray', 'module' => 'impolling'));
		$this->setControl('option3_colour', array('itemHandler' => 'poll', 'method' => 'getoption_coloursArray', 'module' => 'impolling'));
		$this->setControl('option4_colour', array('itemHandler' => 'poll', 'method' => 'getoption_coloursArray', 'module' => 'impolling'));
		$this->setControl('option5_colour', array('itemHandler' => 'poll', 'method' => 'getoption_coloursArray', 'module' => 'impolling'));
		$this->setControl('option6_colour', array('itemHandler' => 'poll', 'method' => 'getoption_coloursArray', 'module' => 'impolling'));
		$this->setControl('option7_colour', array('itemHandler' => 'poll', 'method' => 'getoption_coloursArray', 'module' => 'impolling'));
		$this->setControl('option8_colour', array('itemHandler' => 'poll', 'method' => 'getoption_coloursArray', 'module' => 'impolling'));
		$this->hideFieldFromForm('poll_comments');
		$this->hideFieldFromForm('poll_notification_sent');
		$this->hideFieldFromForm('creation_time');
		$this->hideFieldFromForm('user_id');
		$this->hideFieldFromForm('votes');
		$this->hideFieldFromForm('voters');
		$this->hideFieldFromForm('mail_status');
		$this->hideFieldFromForm('counter');
		$this->setControl('poll_cancomment', 'yesno');


		$this->initiateSEO();
	}

	/**
	 * Overriding the icms_ipf_Object::getVar method to assign a custom method on some
	 * specific fields to handle the value before returning it
	 *
	 * @param str $key key of the field
	 * @param str $format format that is requested
	 * @return mixed value of the field that is requested
	 */
	public function getVar($key, $format = "s") {
		if ($format == "s" && in_array($key, array())) {
			return call_user_func(array ($this,	$key));
		}
		return parent::getVar($key, $format);
	}

	/**
	 * return poll sender
	 *
	 * @return str linked username
	 */
	public function getPollSender() {
		return icms_member_user_Handler::getUserLink($this->getVar('user_id', 'e'));
	}

	/**
	 * return poll title
	 *
	 * @return str poll title
	 */
	public function getPollTitle() {
		return $this->getVar('question');
	}

}