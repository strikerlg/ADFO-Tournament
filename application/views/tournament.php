<?php
echo '<div id="tournamentWrapper">';
$this->load->view('tabs');
foreach($tournament[0] as $tourindex => $bracketInfo){
	if
	(
		$bracketInfo['size'] == 2 ||
		$bracketInfo['size'] == 4 || 
		$bracketInfo['size'] == 8 || 
		$bracketInfo['size'] == 16 ||   
		$bracketInfo['size'] == 32  
	){
		if($tourindex != 0){
			echo '<div class="tournamentInfo tourInfo'.$bracketInfo['arena'].'">';
		}else{
			echo '<div class="tournamentInfo current tourInfo'.$bracketInfo['arena'].'">';
		}
		
		echo
		'
				<table class="tabTable">
					<tr>
						<td>Name: </td>
						<td class="bigmac bracketName">'.$tournament[0][$tourindex]['name'].'</td>
					</tr>
					<tr>
						<td>Size: </td>
						<td class="bigmac bracketSize">'.$tournament[0][$tourindex]['size'].'</td>
					</tr>
					<tr>
						<td>Team size: </td>
						<td class="bigmac bracketTeamSize">'.$tournament[0][$tourindex]['team_size'].'</td>
					</tr>
					<tr>
						<td>Type: </td>
						<td class="bigmac bracketType">'.$tournament[0][$tourindex]['type'].'</td>
					</tr>
					<tr>
						<td>Start: </td>
						<td class="bigmac bracketStart">'.date('l H:i',strtotime($tournament[0][$tourindex]['start_time'])).'</td>
					</tr>
					<tr>
						<td>End: </td>
						<td class="bigmac bracketEnd">'.date('l H:i',strtotime($tournament[0][$tourindex]['end_time'])).'</td>
					</tr>
				</table>
			</div>';
		
		if($tourindex != 0){
			echo '<div class="bracketHolder arena'.$bracketInfo['arena'].'">';
		}else{
			echo '<div class="bracketHolder current arena'.$bracketInfo['arena'].'">';
		}
	
		echo '<div id="tournament"';
		if(!empty($bracketInfo['special_image'])){
			echo 'style="background: url(\'';
			echo base_url().'img/'.$bracketInfo['special_image'];
			echo '\')"';
		}
		echo '>';
		//echo 'style="backround: url(\''.base_url().'img/'.$bracketInfo['special_image'].'\')"';	
		
		$colo = log($bracketInfo['size'],2)+1;
		$base = 0;
		for($i = 0; $i < $colo; $i++){
			echo '<div class="colo'.' c'.($i+1).'" style="width: '.(960/$colo).'px">';
			for($e = 1; $e <= $bracketInfo['size']/pow(2,$i);$e++){
				
				$playerMargin = ((610-($bracketInfo['size']*(240/pow(2,($colo-2)))))/$bracketInfo['size']);
				$playerHeight = (240/pow(2,($colo-2)));
			
				echo '<div class="player" id="mn'.$e.'"
				style="
						font-size: '.(48/$colo).'px;
						width: '.((960/$colo)-30).'px;
						height: '.$playerHeight.'px; 
						margin-top: ';
				if($i == 0){
					echo $playerMargin;
				}else{
					if($e == 1){
						echo (($playerMargin*(pow(2,($i-1))+0.5))+($playerHeight*((pow(2, ($i-1))-0.5))));
					}else{
						echo (($playerMargin*(pow(2, $i)-0.8))+($playerHeight*(pow(2, $i)-0.8)));
					}
				}
				echo 'px;">';
				echo '<div class="playerinfo">';
				//loop tru all the teams and check if positions i matching
				//alternative is to have boxarray with exact index, will try this method later
				if(!empty($tournament[1][$tourindex])){
					foreach($tournament[1][$tourindex] as $index => $team){
						if(!empty($tournament[1][$tourindex]) && $tournament[1][$tourindex][$index]['position'] == ($base+$e)){
							echo 
							'
								<p style="font: '.(48/$colo).'px Helvetica, Arial, sans-serif">'.$tournament[1][$tourindex][$index]['name'].'</p>
							';
							
							if(!empty($tournament[1][$tourindex][$index]['points']) && 20 <= (240/pow(2,($colo-2)))){
								echo 
								'
									<p id="points'.($base+$e).'"> Points: '.$tournament[1][$tourindex][$index]['points'].'</p>
								';
							}
						
						}
					}
				}
				echo
				'
					</div>
				';
				if(50 <= (240/pow(2,($colo-2)))){
					echo
					'
						<div class="frame">
							<img src="" />
						</div>
					';
				}
				echo
				'
				</div>
				';
				if($e == $bracketInfo['size']/pow(2,$i)){
					$base+=$e;
				}
			}
			echo '</div>';
		}
		
	}
	echo '</div>';
	echo
	'
		<div id="tourFooter">
	';
	if(!empty($userdata['logged_in']) && $userdata['logged_in'] == true){
		echo
		'
			<a href="'.base_url().'home/apply_to_tournament/'.$bracketInfo['id'].'">Ansök till turneringen</a>
		';
		if($userdata['authority'] == 5){
			echo '<a href="'.base_url().'admin/edit_tournament/'.$bracketInfo['id'].'">Editera turnering</a>';
			echo '<a class="supervise delete" href="'.base_url().'admin/supervise_tournament/'.$bracketInfo['id'].'">Administrera turnering</a>';
			echo '<a class="delete" href="'.base_url().'admin/delete_tournament/'.$bracketInfo['id'].'">Ta bort turnering</a>';
		}
	}
	echo
	'
		</div>
		<div id="teamApply">
		
	';
	if(!empty($tournament[2][$tourindex])){
		echo 
		'
			<h3>Ansökande lag</h3>
		';
		
		foreach($tournament[2][$tourindex] as $teamInfoIndex => $teamInfo){
			echo '<a href="'.base_url().'home/team/'.$teamInfo['id'].'">'.$teamInfo['name'].' </a>';
		}
	}
	
	echo '</div>';
	
	echo '</div>';
}
echo '</div>';

