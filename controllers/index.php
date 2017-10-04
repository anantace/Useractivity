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

        $this->course_id       = Request::get('cid');
        $this->course          = Course::find($this->course_id);

        PageLayout::setTitle($this->course->getFullname()." - " ._("Teilnehmeraktivität"));

        // $this->set_layout('layouts/base');
        $this->set_layout($GLOBALS['template_factory']->open('layouts/base'));
    }

    public function index_action()
    {
        global $perm;
        $db = DBManager::get();
        $query = "SELECT u.user_id, u.Vorname, u.Nachname, uo.last_lifesign, COUNT(fe.topic_id) AS Forenbeitraege
			FROM seminar_user su 
                        LEFT JOIN auth_user_md5 u ON u.user_id = su.user_id
			LEFT JOIN user_online uo ON u.user_id = uo.user_id
                        LEFT JOIN forum_entries fe ON (u.user_id = fe.user_id AND fe.seminar_id = :sem_id)
                        WHERE su.Seminar_id = :sem_id
                        AND su.status = 'autor'
			GROUP BY u.user_id";
                            
        $statement = $db->prepare($query);
	$statement->execute(array('sem_id' => $this->course_id));
	$this->tn_data = $statement->fetchAll(PDO::FETCH_ASSOC);
    
    //TN Badges
    foreach ($this->tn_data as $tn){ 
        $values = array('user_id' => $tn['user_id']);
        $query = "SELECT * FROM `mooc_badges` WHERE `user_id` LIKE :user_id ORDER BY sem_id ASC" ;
        $statement = \DBManager::get()->prepare($query);
        $statement->execute($values);
        $this->badges[$tn['user_id']] = $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    
        
        $db = DBManager::get();
        $query = "SELECT u.user_id, u.Vorname, u.Nachname, uo.last_lifesign, COUNT(fe.topic_id) AS Forenbeitraege
			FROM seminar_user su 
                        LEFT JOIN auth_user_md5 u ON u.user_id = su.user_id
			LEFT JOIN user_online uo ON u.user_id = uo.user_id
                        LEFT JOIN forum_entries fe ON (u.user_id = fe.user_id AND fe.seminar_id = :sem_id)
                        WHERE su.Seminar_id = :sem_id
                        AND su.status = 'dozent'
			GROUP BY u.user_id";
                            
        $statement = $db->prepare($query);
	$statement->execute(array('sem_id' => $this->course_id));
	$this->dz_data = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        $actions = new ActionsWidget();
        $actions->setTitle(_('Aktionen'));

        //$actions->addLink(
        //'Neuen Vorfall anlegen',
        //PluginEngine::GetURL('rein/show/create_event/'.$provider_id),'icons/16/blue/add.png'); 

        Sidebar::get()->addWidget($actions);
        

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
