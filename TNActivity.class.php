<?php
require_once 'lib/bootstrap.php';

/**
 * ContactForm.class.php
 *
 * ...
 *
 * @author  Annelene Sudau <asudau@uos.de>
 * @version 0.1a
 */

class TNActivity extends StudIPPlugin implements StandardPlugin
{

    public function __construct()
    {
        parent::__construct();
    }

    public function initialize ()
    {
        PageLayout::addStylesheet($this->getPluginURL().'/assets/style.css');
        PageLayout::addScript($this->getPluginURL().'/assets/application.js');
        PageLayout::addScript($this->getPluginURL().'/assets/jquery.tablesorter.js');
    }

    public function getTabNavigation($course_id)
    {
        global $perm;
        if ($perm->have_studip_perm('tutor', $course_id) || $GLOBALS['perm']->have_perm('root')){
            return array(
                'contact' => new Navigation(
                    'Teilnehmeraktivität',
                    PluginEngine::getURL($this, array(), 'index')
                )
            );
        }
        else return null;
    }

    public function getNotificationObjects($course_id, $since, $user_id)
    {
        return array();
    }

    public function getIconNavigation($course_id, $last_visit, $user_id)
    {
        // ...
    }

    public function getInfoTemplate($course_id)
    {
        // ...
    }

    public function perform($unconsumed_path)
    {
        $this->setupAutoload();
        $dispatcher = new Trails_Dispatcher(
            $this->getPluginPath(),
            rtrim(PluginEngine::getLink($this, array(), null), '/'),
            'show'
        );
        $dispatcher->plugin = $this;
        $dispatcher->dispatch($unconsumed_path);
        
        //for current user check prerequisites
        //if all existing excercises done, ajax call of zertifikats-action
        
    }

    private function setupAutoload()
    {
        if (class_exists('StudipAutoloader')) {
            StudipAutoloader::addAutoloadPath(__DIR__ . '/models');
        } else {
            spl_autoload_register(function ($class) {
                include_once __DIR__ . $class . '.php';
            });
        }
    }
}
