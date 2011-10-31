<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('Tour_model');
		$this->is_admin();
	}
	
	/****************** ADMIN LOAD VIEWS ******************/
	public function edit_tournament($bracketId){
		$this->is_admin();
		$data['view'] = 'edit_tournament';
		$data['bracket'] = $this->Tour_model->get_active_bracket($bracketId);
		$this->load->view('index', $data);
	}
	
	public function supervise_tournament($bracketId){
		$this->is_admin();
		$data['teams'] = $this->Tour_model->get_verified_teams($bracketId);
		$data['bracket'] = $this->Tour_model->get_bracket_by_id($bracketId);
		$data['appliedteam'] = $this->Tour_model->get_applied_teams($bracketId);
		$data['view'] = 'supervise_tournament';
		$this->load->view('index', $data);
	}
	
	public function page($view){
		$data['view'] = $view;
		$this->load->view('index', $data);
	}
	
	/****************** ADMIN GENERAL ******************/
	
	function update_tournament($bracketId){
		$this->Tour_model->edit_tournament($bracketId);
		//$arena = $this->Tour_model->get_arena($bracketId);
		redirect('admin/supervise_tournament/'.$bracketId, 'refresh');
	}

	function delete_tournament($bracketId){
		$this->Tour_model->delete_tournament($bracketId);
		$this->index();
	}
		
	private function is_admin(){
		if($this->Tour_model->session_manager('logged_in') && $this->Tour_model->session_manager('authority') == 5){
			return;
		}
		$this->page('not_allowed');
	}
	public function get_bracket($ajax, $id){
		$bracket = $this->Tour_model->get_bracket_by_id($id);
		$teams = $this->Tour_model->get_teams_by_bracket_id($id);
		$appliedTeams = $this->Tour_model->get_applied_team($arena);
		$result = array($bracket, $teams, $appliedTeams);
		if($ajax){
			echo json_encode($result);
		}else{
			return $result;
		}
	}
	
	/****************** ADMIN AJAX ******************/
	//Calculate match stats for all matches on men arena	
	//Delete team from tournament position
	public function  delete_team_position($matchId, $bracketId){
		$this->Tour_model->delete_team_position($matchId, $bracketId);
		redirect('admin/supervise_tournament/'.$bracketId, 'refresh');
	}
	
	//Remove team from the tournament to applying status
	public function undo_team_position($matchId, $bracketId){
		$this->Tour_model->undo_team_position($matchId, $bracketId);
		redirect('admin/supervise_tournament/'.$bracketId, 'refresh');
	}
	
	//Approve team applicant
	public function place_team(){
		$bracketId = $this->input->post('bracketId');
		$appliedTeam = $this->Tour_model->get_applied_teams($bracketId);
	
		for($i = 0; $i < count($appliedTeam); $i++){
			$position = $this->input->post('position_'.$i);
			$teamId = $this->input->post('teamId_'.$i);
			
			if($position != 0){
				$check = $this->Tour_model->get_team_by_position($position, $bracketId);
				if(empty($check)){
					$this->Tour_model->place_team($position, $teamId, $bracketId);
				}
			}
		}
		redirect('admin/supervise_tournament/'.$bracketId, 'refresh');
	}
	
	
	//Random teams in the beginning of the tournament to make it fair
	public function random_teams($bracketId){
		$this->is_admin();
		$teams = $this->Tour_model->get_verified_teams($bracketId);
		shuffle($teams);
		foreach($teams as $i => $team){
			$this->Tour_model->update_team_position($bracketId, $team['id'], ($i+1));
		}
		//$arena = $this->Tour_model->get_arena_by_bracket_id($bracketId);
		redirect('admin/supervise_tournament/'.$bracketId, 'refresh');
	}
	
	function match_stats($bracketId, $base, $current = 1){
		$this->is_admin();
		
		$team1Points = $this->input->post('team_'.$current);
		$team2Points = $this->input->post('team_'.($current+1));
	
		$team1Id = $this->input->post('teamId_'.$current);
		$team2Id = $this->input->post('teamId_'.($current+1));
		
		//the jump
		$position = (($base-(floor($current/2)))+$current);
		
		if(!empty($team1Points) && !empty($team2Points) && $team2Points != $team1Points){
				
			if($team1Points > $team2Points){
				$teamId = $team1Id;
			}else{
				$teamId = $team2Id;
			}
			
			$this->Tour_model->advance_team($teamId, $position); 
			$this->Tour_model->update_points($team1Points, $team1Id);
			$this->Tour_model->update_points($team2Points, $team2Id);
		}
		$current+=2;
		if($current < ($base*2)){
			$this->match_stats($bracketId, $base, $current);
		}else{
			redirect('admin/supervise_tournament/'.$bracketId, 'refresh');
		}
	} 
}
