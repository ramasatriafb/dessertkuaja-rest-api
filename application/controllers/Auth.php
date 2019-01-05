<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function login()
	{
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'POST'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->MyModel->check_auth_client();
			if($check_auth_client == true){
				$params = json_decode(file_get_contents('php://input'), TRUE);
		        $username = $params['username'];
		        $password = $params['password'];
		        
		        $response = $this->MyModel->login($username,$password);
				json_output($response['status'],$response);
			}
		}
	}

	public function logout()
	{
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'POST'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->MyModel->check_auth_client();
			if($check_auth_client == true){
		        $response = $this->MyModel->logout();
				json_output($response['status'],$response);
			}
		}
	}

	public function signup()
	{
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'POST'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			//$check_auth_client = $this->MyModel->check_auth_client();
				$response = array('status' => 200,'message' => 'Authorized.');
		        $respStatus = $response['status'];
		        if($response['status'] == 200){
					$params = json_decode(file_get_contents('php://input'), TRUE);
					$params_for_profile = json_decode(file_get_contents('php://input'), TRUE);
					if ($params['username'] == "" || $params['password'] == "" || $params_for_profile['nama'] == "" 
					|| $params_for_profile['jenis_kelamin'] == "" || $params_for_profile['tgl_lahir'] == "" || $params_for_profile['diabet'] == "" 
					|| $params_for_profile['gula_darah'] == "" || $params_for_profile['hdl'] == "" || $params_for_profile['ldl'] == "" || $params_for_profile['trigliserida'] == "") {
						$respStatus = 400;
						$resp = array('status' => 400,'message' =>  'Tidak Boleh Ada Data Yang Kosong');
					} else {
						$user_id = $this->MyModel->create_user($params);
						$data = array(
							'user_id' => $user_id,
							'nama' => $params_for_profile['nama'],
							'jenis_kelamin' => $params_for_profile['jenis_kelamin'],
							'tgl_lahir' => $params_for_profile['tgl_lahir'],
							'diabet' => $params_for_profile['diabet'],
							'gula_darah' => $params_for_profile['gula_darah'],
							'hdl' => $params_for_profile['hdl'],
							'ldl' => $params_for_profile['ldl'],
							'trigliserida' => $params_for_profile['trigliserida']
						);
						$batas_gula_darah = 10;
						$resp = $this->MyModel->create_user_profile($data);
					}
					json_output($respStatus,$resp);
		        }
			
		}
	}
	
}
