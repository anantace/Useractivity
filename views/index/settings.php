<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Studip\Button, Studip\LinkButton;

$message_types = array('msg' => "success", 'error' => "error", 'info' => "info");
?>

<? if (is_array($flash['msg'])) foreach ($flash['msg'] as $msg) : ?>
    <?= MessageBox::$message_types[$msg[0]]($msg[1]) ?>
<? endforeach ?>

<form name="settings" method="post" action="<?= $controller->url_for('index/set') ?>" <?= $dialog_attr ?> class="default collapsable">
    <?= CSRFProtection::tokenTag() ?>
    <fieldset  data-open="visible_tutor">
        <legend><?= _('Sichtbar für ') . get_title_for_status('tutor', 2) ?></legend>
        <ul style='list-style: none'>
            <li><input type='checkbox' name='visibility[tutor][]' value ='autor' <?= (in_array('autor', $settings['tutor'])) ? 'checked' : '' ?>> <?= get_title_for_status('autor', 2) ?> </li>
            <li><input type='checkbox' name='visibility[tutor][]' value ='tutor' <?= (in_array('tutor', $settings['tutor'])) ? 'checked' : '' ?>> <?= get_title_for_status('tutor', 2) ?> </li>
            <li><input type='checkbox' name='visibility[tutor][]' value ='dozent' <?= (in_array('dozent', $settings['tutor'])) ? 'checked' : '' ?>> <?= get_title_for_status('dozent', 2) ?> </li>
        </ul>     
       
    </fieldset>
    
    <fieldset  data-open="visible_dozent">
        <legend><?= _('Sichtbar für ') . get_title_for_status('dozent', 2) ?></legend>
        <ul style='list-style: none'>
            <li><input type='checkbox' name='visibility[dozent][]' value ='autor' <?= (in_array('autor', $settings['dozent'])) ? 'checked' : '' ?>> <?= get_title_for_status('autor', 2) ?> </li>
            <li><input type='checkbox' name='visibility[dozent][]' value ='tutor' <?= (in_array('tutor', $settings['dozent'])) ? 'checked' : '' ?>> <?= get_title_for_status('tutor', 2) ?> </li>
            <li><input type='checkbox' name='visibility[dozent][]' value ='dozent' <?= (in_array('dozent', $settings['dozent'])) ? 'checked' : '' ?>> <?= get_title_for_status('dozent', 2) ?> </li>
        </ul>     
       
    </fieldset>
    
    <footer data-dialog-button>
        <?= Button::create(_('Übernehmen')) ?>
    </footer>
</form>