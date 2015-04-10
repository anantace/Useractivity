<?php

require_once 'lib/classes/DBManager.class.php';

class ShowController extends StudipController {

    public $group_licenses = false;		



    public function __construct($dispatcher)
    {
        parent::__construct($dispatcher);
        $this->plugin = $dispatcher->plugin;

    }

    public function before_filter(&$action, &$args) {

        $this->set_layout($GLOBALS['template_factory']->open('layouts/base_without_infobox'));
	 PageLayout::setTitle($GLOBALS['SessSemName']["header_line"]);

	 $this->course = Course::findCurrent();
	 if (!$this->course) {
            throw new CheckObjectException(_('Sie haben kein Objekt gewählt.'));
        }

	 $this->course_id = $this->course->id;
	 $this->sem = Seminar::getInstance($this->course_id);
        $sem_class = $GLOBALS['SEM_CLASS'][$GLOBALS['SEM_TYPE'][$this->sem->status]['class']];
        $sem_class || $sem_class = SemClass::getDefaultSemClass();
        $this->studygroup_mode = $SEM_CLASS[$SEM_TYPE[$this->sem->status]["class"]]["studygroup_mode"];

    }

    public function index_action() {
	$stmt = DBManager::get()->prepare("SELECT su.user_id,  uo.last_lifesign FROM seminar_user su LEFT JOIN user_online uo ON (su.user_id = uo.user_id)
					WHERE su.status = 'autor' AND su.Seminar_id = ?");
	 $stmt->execute(array($this->getSeminarID()));
	 //$count = $stmt->rowCount();
	 $result = $stmt->fetch();
	$this->user = $this->getUser();


	

    }
	
    private function getUser() {
	$user;
	$stmt = DBManager::get()->prepare("SELECT su.user_id, FROM_UNIXTIME(uo.last_lifesign) as lifesign, au.Vorname, au.Nachname FROM seminar_user su 
					       LEFT JOIN user_online uo ON (su.user_id = uo.user_id)
						LEFT JOIN auth_user_md5 au ON (au.user_id = su.user_id)
					WHERE su.status IN ('autor', 'dozent') 
       			       AND su.Seminar_id = ? 
					ORDER BY uo.last_lifesign DESC");
	 $stmt->execute(array($this->getSeminarID()));
	 $result = $stmt->fetchAll();
	 foreach($result as $rs){
	 	$user[] = array('id' => $rs['user_id'],
				  'lifesign' => $rs['lifesign'],
				  'vorname' => $rs['Vorname'],
				  'nachname' => $rs['Nachname']);
	}
	//$count = $stmt->rowCount();
	 return $user;
	 //$result['seminar_id']

    }

    static function getSeminarId()
    {
        if (!Request::option('cid')) {
            if ($GLOBALS['SessionSeminar']) {
                URLHelper::bindLinkParam('cid', $GLOBALS['SessionSeminar']);
                return $GLOBALS['SessionSeminar'];
            }

            return false;
        }

        return Request::option('cid');
    }


}
