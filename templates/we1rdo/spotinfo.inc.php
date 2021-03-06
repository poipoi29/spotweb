<?php
	$spot = $tplHelper->formatSpot($spot);
?>
        <div id="details" class="details <?php echo $tplHelper->cat2color($spot) ?>">
            <table class="spotheader">
                <tbody>
                    <tr>
                    	<th class="back"> <a class="closeDetails" title="Ga terug naar het overzicht (esc / u)">&lt;&lt;</a> </th>
                        <th class="category"><span><?php echo $spot['formatname'];?></span></th>
                        <th class="title"><?php echo $spot['title'];?></th>
                        <th class="nzb"><a class="search" href="<?php echo $spot['searchurl'];?>" title="NZB zoeken">Zoeken</a>
<?php if (!empty($spot['nzb']) && $spot['stamp'] > 1290578400 && $settings->get('show_nzbbutton')) { ?>
                            <a class="nzb" href="<?php echo $tplHelper->makeNzbUrl($spot); ?>" title="Download NZB <?php if ($tplHelper->hasBeenDownloaded($spot)) {echo '(deze spot is al gedownload)';} echo " (n)"; ?>">NZB<?php if ($tplHelper->hasBeenDownloaded($spot)) {echo '*';} else { echo '&nbsp;';} ?></a>
<?php } ?></th>
<?php if ($settings->get('keep_watchlist')) {
echo "<th class='watch'>";
echo "<a class='remove watchremove_".$spot['id']."' onclick=\"toggleWatchSpot('".$spot['messageid']."','remove',".$spot['id'].")\""; if($tplHelper->isBeingWatched($spot) == false) { echo " style='display: none;'"; } echo " title='Verwijder uit watchlist (w)'> </a>";
echo "<a class='add watchadd_".$spot['id']."' onclick=\"toggleWatchSpot('".$spot['messageid']."','add',".$spot['id'].")\""; if($tplHelper->isBeingWatched($spot) == true) { echo " style='display: none;'"; } echo " title='Plaats in watchlist (w)'> </a>";
echo "</th>";
} ?>                     
<?php if ((!empty($spot['nzb'])) && (!empty($spot['sabnzbdurl']))) { ?>
<?php if ($tplHelper->hasBeenDownloaded($spot)) { ?>
                        <th class="sabnzbd"><a onclick="downloadSabnzbd(<?php echo "'".$spot['id']."','".$spot['sabnzbdurl']."'"; ?>)" class="<?php echo "sab_".$spot['id'].""; ?> sabnzbd-button succes" title="Add NZB to SabNZBd queue (you already downloaded this spot) (s)"> </a></th>
<?php } else { ?>
                        <th class="sabnzbd"><a onclick="downloadSabnzbd(<?php echo "'".$spot['id']."','".$spot['sabnzbdurl']."'"; ?>)" class="<?php echo "sab_".$spot['id'].""; ?> sabnzbd-button" title="Add NZB to SabNZBd queue (s)"> </a></th>
<?php } } ?>
                    </tr>
                </tbody>
            </table>
            <table class="spotdetails">
            	<tr>
                	<td class="img">
                        <a onclick="toggleImageSize('<?php echo $spot['image']; ?>')" class="postimage">
                            <img class="spotinfoimage" src="<?php echo $tplHelper->makeImageUrl($spot, 300, 300); ?>" alt="<?php echo $spot['title'];?>">
                        </a>
					</td>
					<td class="info">
<?php if (!$spot['verified'] || $tplHelper->isModerated($spot)) {
	echo "<div class='warning'>";
	if (!$spot['verified']) {
		echo "Deze spot is niet geverifi&euml;erd, de naam van de poster is niet bevestigd!<br>";
	}
	if ($tplHelper->isModerated($spot)) {
		echo "Deze spot is als mogelijk onwenselijk gemodereerd!";
	}
	echo "</div>";
} ?>
                        <table class="spotinfo">
                            <tbody>
                                <tr><th> Categorie </th> <td> <?php echo $spot['catname']; ?> </td> </tr>
<?php
	if (!empty($spot['subcatlist'])) {
		foreach($spot['subcatlist'] as $sub) {
			$subcatType = substr($sub, 0, 1);
			echo "\t\t\t\t\t\t<tr><th> " . SpotCategories::SubcatDescription($spot['category'], $subcatType) .  "</th> <td> " . SpotCategories::Cat2Desc($spot['category'], $sub) . " </td> </tr>\r\n";
		} # foreach
	} # if
?>
                                <tr><th> Omvang </th> <td> <?php echo $tplHelper->format_size($spot['filesize']); ?> </td> </tr>
                                <tr><td class="break" colspan="2">&nbsp;</td> </tr>
                                <tr><th> Website </th> <td> <a href='<?php echo $spot['website']; ?>'><?php echo $spot['website'];?></a> </td> </tr>
                                <tr> <td class="break" colspan="2">&nbsp;</td> </tr>
                                <tr> <th> Afzender </th> <td> <?php echo $spot['poster']; ?> (<a href="<?php echo $tplHelper->makeUserIdUrl($spot); ?>" title='Zoek naar spots van "<?php echo $spot['poster']; ?>"'><?php echo $spot['userid']; ?></a>) </td> </tr>
                                <tr> <th> Tag </th> <td> <?php echo $spot['tag']; ?> </td> </tr>
                                <tr> <td class="break" colspan="2">&nbsp;</td> </tr>
                                <tr> <th> Zoekmachine </th> <td> <a href='<?php echo $spot['searchurl']; ?>'>Zoek</a> </td> </tr>
<?php if (!empty($spot['nzb'])) { ?>		
                        		<tr> <th> NZB </th> <td> <a href='<?php echo $tplHelper->makeNzbUrl($spot); ?>' title='Download NZB (n)'>NZB</a> </td> </tr>
<?php } ?>
						
                            </tbody>
                        </table>
					</td>
				</tr>
			</table>
            <div class="description">
            	<h4>Post Description</h4>
                <pre><?php echo $spot['description']; ?></pre>
            </div>
            <div class="comments" id="comments">
            	<h4>Comments <span class="commentcount"># 0</span></h4>
				<ul id="commentslist"></ul>
            </div>
		</div>
        
        <input type="hidden" id="messageid" value="<?php echo $spot['messageid'] ?>" />
        <script type="text/javascript">
			$(document).ready(function(){
				$("#details").addClass("external");
				
				$("a.closeDetails").click(function(){ 
					window.close();
				});
				
				var messageid = $('#messageid').val();
				loadSpotImage();
				loadComments(messageid,'5','0');
			});
		</script>