<?php
//require_once 'lib/classes/DBManager.class.php';


class IndexController extends StudipController {

    public function __construct($dispatcher)
    {
        parent::__construct($dispatcher);
        $this->plugin = $dispatcher->plugin;
        Navigation::activateItem('course/contact');
    }

    public function before_filter(&$action, &$args)
    {
        parent::before_filter($action, $args);

        $this->course = Course::findCurrent();
        global $perm;
        
        PageLayout::setTitle($this->course->getFullname()." - " ._("Teilnehmeraktivität"));

        // $this->set_layout('layouts/base');
        $this->set_layout($GLOBALS['template_factory']->open('layouts/base'));
            
        $navcreate = new LinksWidget();
        $navcreate->setTitle('Navigation');
        $navcreate->addLink("Übersicht", PluginEngine::getLink($this->plugin, array(), 'index'));
        
        Sidebar::get()->addWidget($navcreate);
        
        if($perm->have_studip_perm('dozent', $this->course->id)){
            $actions = new ActionsWidget();
            $actions->setTitle(_('Aktionen'));

            $actions->addLink(
            'Einstellungen',
            PluginEngine::GetURL('TNActivity/index/settings'), Icon::create('admin', 'clickable')); 

            Sidebar::get()->addWidget($actions);
        }
    }

    public function index_action()
    {
        global $perm;
        $this->perm = $perm->get_studip_perm($this->course->id);
        
        $this->settings = array();
        $settings = ActivitySettings::find(Course::findCurrent()->id);
        if ($settings){
            $this->settings = json_decode($settings->settings, true);
        }

        if (!$this->settings){
            //default-eintrag
            $this->settings = new ActivitySettings(Course::findCurrent()->id);
            $this->settings->settings = '{"dozent":["autor","tutor"]}';
            $this->settings->store();
            $this->settings = array('dozent' => array('autor', 'tutor'));
        }
        if (in_array($this->perm, ['dozent', 'tutor'])){
            $this->perm_autor = in_array('autor', $this->settings[$this->perm]);
            $this->perm_tutor = in_array('tutor', $this->settings[$this->perm]);
            $this->perm_dozent = in_array('dozent', $this->settings[$this->perm]);
        }
        
        
        $this->tn_data = $this->get_user_data($this->course->id, 'autor');
    
        //TN Badges
        try{
            foreach ($this->tn_data as $tn){ 
                $values = array('user_id' => $tn['user_id']);
                $query = "SELECT * FROM `mooc_badges` WHERE `user_id` LIKE :user_id ORDER BY sem_id ASC" ;
                $statement = \DBManager::get()->prepare($query);
                $statement->execute($values);
                $this->badges[$tn['user_id']] = $statement->fetchAll(\PDO::FETCH_ASSOC);
            }
        }catch (Exception $e){}
    
        $this->dz_data = $this->get_user_data($this->course->id, 'dozent');
        $this->tt_data = $this->get_user_data($this->course->id, 'tutor');
        
    }
    
    public function settings_action()
    {
        $this->settings = array();
        $settings = ActivitySettings::find(Course::findCurrent()->id);
        if ($settings){
            $this->settings = json_decode($settings->settings, true);
        }
        
    }
    
    public function set_action()
    {
        $visibility = Request::getArray('visibility');
        if (!$this->settings = ActivitySettings::find(Course::findCurrent()->id)){
            $this->settings = new ActivitySettings(Course::findCurrent()->id);
        }
        $this->settings->settings = json_encode(studip_utf8encode($visibility));
        $this->settings->store();
        $this->redirect($this->url_for('index/settings'));
        
    }
    
    public function object_get_visit($course_id, $user_id){
        $query = "SELECT visitdate
                  FROM object_user_visits
                  WHERE object_id = ? AND user_id = ? AND type IN ('sem')";
        $statement = DBManager::get()->prepare($query);
        $statement->execute(array($course_id, $user_id));
        $temp = $statement->fetch(PDO::FETCH_ASSOC);
        return $temp;
    }
    
    public function lastonline_to_string($time){
        $difference = time() - $time;
        if ($difference == time()) {
            return 'noch nie';
        }
        $last_online = round($difference/(60*60*24),0) . ' Tagen, '. round(($difference%(60*60*24))/(60*60), 0) . ' Stunden und ' . round(($difference%(60*60))/60, 0) . ' Minuten';
        if (round($difference/(60*60*24),0) > 1000){
            $last_online = 'noch nie';
        }
        return $last_online;
    }
    
    private function get_user_data($course_id, $status){
        $db = DBManager::get();
        $query = "SELECT u.username, u.user_id, u.Vorname, u.Nachname, uo.last_lifesign, COUNT(fe.topic_id) AS Forenbeitraege
			FROM seminar_user su 
                        LEFT JOIN auth_user_md5 u ON u.user_id = su.user_id
			LEFT JOIN user_online uo ON u.user_id = uo.user_id
                        LEFT JOIN forum_entries fe ON (u.user_id = fe.user_id AND fe.seminar_id = :sem_id)
                        WHERE su.Seminar_id = :sem_id
                        AND su.status = :status
			GROUP BY u.user_id";
                            
        $statement = $db->prepare($query);
        $statement->execute(array('sem_id' => $course_id, 'status' =>$status));
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    // customized #url_for for plugins
    public function url_for($to)
    {
        $args = func_get_args();

        # find params
        $params = array();
        if (is_array(end($args))) {
            $params = array_pop($args);
        }

        # urlencode all but the first argument
        $args = array_map('urlencode', $args);
        $args[0] = $to;

        return PluginEngine::getURL($this->dispatcher->plugin, $params, join('/', $args));
    }
}
