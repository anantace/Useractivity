

<? use Studip\LinkButton; ?>

<div style="width:45%; float:left">

<?
echo "<h1>".htmlReady($GLOBALS['SessSemName']["header_line"]). "</h1>";
    if ($GLOBALS['SessSemName'][3]) {
        echo "<b>" . _("Untertitel:") . " </b>";
        echo htmlReady($GLOBALS['SessSemName'][3]);
        echo "<br>";
    }
if($description){
	echo "<b>Kursbeschreibung:</b><br> " . htmlReady($description);
	echo "<br><br>";
}

$dozenten = $sem->getMembers('dozent');
    $num_dozenten = count($dozenten);
    $show_dozenten = array();
    foreach($dozenten as $dozent) {
        $show_dozenten[] = '<a href="'.URLHelper::getLink("dispatch.php/profile?username=".$dozent['username']).'">'
                            . htmlready($num_dozenten > 10 ? get_fullname($dozent['user_id'], 'no_title_short') : $dozent['fullname'])
                            . '</a>';
    }
    printf("<b>%s: </b>%s<br><br>", get_title_for_status('dozent', $num_dozenten), implode(', ', $show_dozenten));

?>

<b><?= _("Zeit / Veranstaltungsort") ?>:</b><br>
        <?
        $show_link = ($GLOBALS["perm"]->have_studip_perm('autor', $course_id) && $modules['schedule']);
        echo $sem->getDatesTemplate('dates/seminar_html', array('link_to_dates' => $show_link, 'show_room' => true));
        ?>

        <br>
        <br>

        <?
        $next_date = $sem->getNextDate();
        if ($next_date) {
            echo '<section class=contentbox><header><h1>'._("Nächster Termin").':</h1></header>';
            echo '<article>' . $next_date . '</article></section>';
        } else if ($first_date = $sem->getFirstDate()) {
            echo '<section class=contentbox><header><h1>'._("Erster Termin").':</h1></header>';
            echo '<article>' .$first_date . '</article></section>';
        } else {
            echo '<section class=contentbox><header><h1>'._("Erster Termin").':</h1></header>';
            echo '<article>' . _("Die Zeiten der Veranstaltung stehen nicht fest."). '</article></section>';
        }

    

    ?>
        <br>
     
  <?if ($perm || $news): ?>
<section class="contentbox">
    <header>
        <h1>
            <?= Assets::img('icons/16/black/news.png') ?>

            <?= _('Ankündigungen') ?>
        </h1>
           </header>
    <? foreach ($news as $new): ?>
    <? $is_new = ($new['chdate'] >= object_get_visit($new->id, 'news', false, false))
            && ($new['user_id'] != $GLOBALS['user']->id); ?>
    <article class="<?= ContentBoxHelper::classes($new->id, $is_new) ?>" id="<?= $new->id ?>" data-visiturl="<?=URLHelper::getScriptLink('dispatch.php/news/visit')?>">
        <header>
            <h1>
                <?= Assets::img('icons/16/grey/news.png'); ?>
                <a href="<?= ContentBoxHelper::href($new->id, array('contentbox_type' => 'news')) ?>">
                    <?= htmlReady($new['topic']); ?>
                </a>
            </h1>
                   </header>
        <section>
            <?= formatReady($new['body']) ?>

        </section>
            </article>
    <? endforeach; ?>
   </section>
 <br>



<?endif;?>
    	

       

</div>

<div style="width:45%; float:right">

<? foreach ($user as $u){
	echo $u['vorname'] . " " . $u['nachname'] . " | " . $u['lifesign'] . "<br>";
} ?>







</div>



			

	
