<?php


/**
 * Useractivity.class.php
 *
 * ...
 *
 * @author  <asudau@uos.de>
 */

require_once 'lib/classes/DBManager.class.php';

class Useractivity extends StudIPPlugin implements StandardPlugin
{
    /**
     * @var Container
     */
    private $container;

    

    public function __construct() {
        parent::__construct();
	
		global $perm;
	
		//if (Navigation::hasItem('/course') && ($perm->have_studip_perm('dozent', Request::get('cid')) || $perm->have_perm('admin')) ) {
            //$url = PluginEngine::getURL($this);
            //$item = new Navigation(_('Teilnehmeraktivität'), $url);
            //Navigation::addItem('/course/useractivity', $item);

            //$scormOverviewItem = new Navigation(_('Übersicht'), $url);
            //Navigation::addItem('/course/useractivity/overview', $scormOverviewItem);
        //}

    }

    public function initialize () {
        if (Navigation::hasItem('/course')) {
            Navigation::activateItem('/course/useractivity');
        }   
		$this->setupAutoload();
    }

	public function perform($unconsumed_path)
	{
		$dispatcher = new Trails_Dispatcher(
		$this->getPluginPath(),
		rtrim(PluginEngine::getLink($this, array(), null), '/'),
		'show'
		);
		$dispatcher->plugin = $this;
		$dispatcher->dispatch($unconsumed_path);
	}
	
   

    private function setupAutoload() {
        if (class_exists("StudipAutoloader")) {
            StudipAutoloader::addAutoloadPath(__DIR__ . '/models');
        } else {
            spl_autoload_register(function ($class) {
                include_once __DIR__ . $class . '.php';
            });
        }
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


    private function getSemClass($semID)
    {
        $seminar = Seminar::getInstance($semID);
	 $status = $seminar->getStatus();
	 $type = new SemType(intval($status));
	 $class = SemClass::object2Array($type->getClass());
	 return $class['data']['id'];
	 
    }


    private function getMiniCourseNavigation($course_id = NULL)
    {

        $navigation = new Navigation('Teilnehmeraktivität', PluginEngine::getURL($this));
        $navigation->setImage('icons/16/white/group3.png');
        $navigation->setActiveImage('icons/16/black/group3.png');

        return $navigation;
    }

    private function setupCourseNavigation(){
	 
	 global $perm;
	 $stmt = DBManager::get()->prepare("SELECT su.seminar_id FROM seminar_user su
					WHERE su.user_id = ?");
	 $stmt->execute(array($GLOBALS['user']->id));
	 $count = $stmt->rowCount();
	 if($count == 1){
		$result = $stmt->fetch();
		

			
	
			if (Navigation::hasItem('/course')){
        			
				if($this->getContext()){
					/** @var Navigation $courseNavigation */
        				$courseNavigation = Navigation::getItem('/course');
        				//$overviewNavigation = $courseNavigation::getItem('/course/overview');
					$it = $courseNavigation->getIterator();

        				Navigation::insertItem('/course/mini_course', $this->getMiniCourseNavigation(), $it->count() === 0 ? null : $it->key());
            				Navigation::activateItem('/course/mini_course');
				}
				Navigation::getItem('/course')->setURL("/el4/vhs-3.1/public/plugins.php/minicourse/show?cid=". $result['seminar_id']);
				Navigation::getItem('/course')->setTitle("Mein Kurs");
				
			}

			Navigation::getItem('/browse')->setURL("/el4/vhs-3.1/public/plugins.php/minicourse/show?cid=". $result['seminar_id']);
			Navigation::getItem('/browse')->setTitle("Mein Kurs");
			

		

	 }
	 if($count == 0 && $my_about->auth_user['perms'] == 'autor'){
		Navigation::removeItem('/browse');	
	 }

    }
   
    public function getInfoTemplate($course_id){
	return null;
    }
    public function getIconNavigation($course_id, $last_visit, $user_id){
	return null;
    }
    public function getTabNavigation($course_id){
		return $this->getMiniCourseNavigation($course_id);
    }
    function getNotificationObjects($course_id, $since, $user_id){
    }

    public function getContext()
    {
        return Request::option('cid') ?: $GLOBALS['SessionSeminar'];
    }

   
}
