<?php
class AddSettingsTable extends DBMigration {

    public function description () {
        return 'create tables for the Useractivity-plugin to save settings';
    }

    public function up () {
        $db = DBManager::get();
        $db->exec("CREATE  TABLE IF NOT EXISTS `activity_settings` (
            `sem_id` VARCHAR(64) NULL ,
            `settings` mediumtext NULL ,
            PRIMARY KEY (`sem_id`)
        )");
		
		
        SimpleORMap::expireTableScheme();
    }

    public function down () {
		DBManager::get()->exec("DROP TABLE activity_settings");
        SimpleORMap::expireTableScheme();
    }
}
