
<div id="sidebar">


</div>

<h1>AutorInnen:</h1>
<table id="keywords" class="tablesorter">
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
    
    foreach ($tn_data as $tn){ 
    
        $difference = $currentTime - $tn['last_lifesign'];
        $last_online = round($difference/(60*60*24),0) . ' Tagen, '. round($difference%(60*60*24)/(60*60), 0) . ' Stunden und ' . round($difference%(60*60)/60, 0) . ' Minuten';
        if (round($difference/(60*60*24),0) > 1000){
            $last_online = 'noch nie';
        }
        ?>
        <tr>
            <td><?= $tn['Vorname'] . ' ' . $tn['Nachname']?></td>
            <td style='display:none'><?= $tn['last_lifesign']?></td>
            <td><?= $last_online ?></td>
            <td><?= $tn['Forenbeitraege']?></td>
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
            <!--<td><?= date("d.m.Y - G:i",$dz['last_lifesign'])?></td>-->
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