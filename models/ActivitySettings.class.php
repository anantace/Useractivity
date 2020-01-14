<?php

/** 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ActivitySettings extends SimpleORMap
{

    public $errors = array();

    
    protected static function configure($config = array())
    {
        $config['db_table'] = 'activity_settings';
        parent::configure($config);
    }

}
    