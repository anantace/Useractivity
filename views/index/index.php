
<div id="sidebar">


</div>

<h1>AutorInnen:</h1>
<table id="keywords" class="tablesorter">
    <thead>
		<tr>
        <th style='width:25%'><span>Name</span></th>
        <th><span>Zuletzt online vor</span></th>
        <th style='display:none; width:25%'><span>Zuletzt online vor</span></th>
        <th style='width:5%'><span>Anzahl Forenbeiträge</span></th>
        <th style='width:45%'><span>Badges</span></th>
        <!--<th>Courseware besucht?</th>-->
    </tr>
    </thead>
    
    <tbody>
    <?
    
    $currentTime = time();
    $badge_content;
    
    foreach ($tn_data as $tn){ 
    
        $difference = $currentTime - $tn['last_lifesign'];
        $last_online = round($difference/(60*60*24),0) . ' Tagen, '. round($difference%(60*60*24)/(60*60), 0) . ' Stunden und ' . round($difference%(60*60)/60, 0) . ' Minuten';
        if (round($difference/(60*60*24),0) > 1000){
            $last_online = 'noch nie';
        }

        if($badges[$tn['user_id']]){
            foreach ($badges[$tn['user_id']] as $badge){
                $block = new \Mooc\DB\Block($badge['badge_block_id']);
                $field = current(Mooc\DB\Field::findBySQL('block_id = ? AND name = ?', array($block->id, 'file_id')));
                $file_id= $field->content;
                $field = current(\Mooc\DB\Field::findBySQL('block_id = ? AND name = ?', array($block->id, 'file_name')));
                $file_name = $field->content;
                $badge_content[$tn['user_id']] .= 
                '<img title=\'' . date('d.m.Y', $badge['mkdate']) . '\' style=\'max-width:10%\' src=\'../../sendfile.php?type=0&file_id=' . $file_id . '&file_name=' . $file_name . '\'/>';
            }
        }
        ?>
        <tr>
            <td><?= $tn['Vorname'] . ' ' . $tn['Nachname']?></td>
            <td style='display:none'><?= $tn['last_lifesign']?></td>
            <td><?= $last_online ?></td>
            <td><?= $tn['Forenbeitraege']?></td>
            <td><?= $badge_content[$tn['user_id']]?></td>
            <!--<td>Courseware besucht?</td>-->
        </tr>
        <?
    }
    ?>
     </tbody>
</table>
    
<h1>DozentInnen:</h1>
<table id="keywordsdz" class="tablesorter">
    <thead>
		<tr>
                    <th><span>Name</span></th>
        <th><span>Zuletzt online vor</span></th>
        <th style='display:none'><span>Zuletzt online vor</span></th>
        <th><span>Anzahl Forenbeiträge</span></th>
        <!--<th>Courseware besucht?</th>-->
    </tr>
    </thead>
    
    <tbody>
    <?
    
    $currentTime = time();
    
    foreach ($dz_data as $dz){ 
    
        $difference = $currentTime - $dz['last_lifesign'];
        $last_online = round($difference/(60*60*24),0) . ' Tagen, '. round($difference%(60*60*24)/(60*60), 0) . ' Stunden und ' . round($difference%(60*60)/60, 0) . ' Minuten';
        if (round($difference/(60*60*24),0) > 1000){
            $last_online = 'noch nie';
        }
        ?>
        <tr>
            <td><?= $dz['Vorname'] . ' ' . $dz['Nachname']?></td>
            <td style='display:none'><?= $dz['last_lifesign']?></td>
            <td><?= $last_online ?></td>
            <td><?= $dz['Forenbeitraege']?></td>
            <!--<td>Courseware besucht?</td>-->
        </tr>
        <?
    }
    ?>
     </tbody>
</table>

<script type="text/javascript">

    
$(function(){
  $('#keywords').tablesorter(); 
});
$(function(){
  $('#keywordsdz').tablesorter(); 
});


</script>