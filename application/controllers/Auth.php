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
					|| $params_for_profile['asam_urat'] == "" || $params_for_profile['gula_darah'] == "" || $params_for_profile['hdl'] == "" || $params_for_profile['ldl'] == "" || $params_for_profile['trigliserida'] == "") {
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
							'asam_urat' => $params_for_profile['asam_urat'],
							'gula_darah' => $params_for_profile['gula_darah'],
							'hdl' => $params_for_profile['hdl'],
							'ldl' => $params_for_profile['ldl'],
							'trigliserida' => $params_for_profile['trigliserida']
						);

						//Cek Kolesterol dari LDL dan Trigliserida
						if ($data['diabet'] == "Yes")
						{
							if ($data['ldl'] > 100 || $data['trigliserida'] > 150 || $data['gula_darah' > 199 ]){
								$user_gejala = "Kolesterol";
							}else{
								$user_gejala = " Normal";
							}

						} else
						{
							if($data['ldl'] > 130 ||  $data['trigliserida'] > 200 || $data['gula_darah' > 100 ]){
								$user_gejala = "Kolesterol";
							}else{
								$user_gejala = "Normal";
							}
						}

						//Cek Kolesterol dari HDL Saja

						if ($data['hdl'] < 60 ) {
							$user_gejala = "Kolesterol";
						}else{
							$user_gejala = " Normal";
						}

						// Cek Asam Urat
						$tgl_lhr = $data['tgl_lahir'];
						$tgl_lhr = explode("/", $tgl_lhr);

						$umur = (date("md", date("U", mktime(0, 0, 0, $tgl_lhr[0], $tgl_lhr[1], $tgl_lhr[2]))) > date("md")
						? ((date("Y") - $tgl_lhr[2]) - 1)
						: (date("Y") - $tgl_lhr[2]));
					 // echo "Age is:" . $umur;
						switch ($umur) {
							case $umur < 18:
								if ($data['jenis_kelamin'] == 'Pria' ){
									if($data['asam_urat'] > 5.6){
										$user_gejala = "Asam Urat";
									}else{
										$user_gejala = "Normal";
									}
								}else{
									if ($data['asam_urat']  > 4.1){
										$user_gejala = "Asam Urat";
									}else{
										$user_gejala = "Normal";
									}
								}
								break;
								case $umur > 17 && $umur < 41 :
								if ($data['jenis_kelamin'] == 'Pria' ){
									if($data['asam_urat'] > 7.6){
										$user_gejala = "Asam Urat";
									}else{
										$user_gejala = "Normal";
									}
								}else{
									if ($data['asam_urat']  > 6.6){
										$user_gejala = "Asam Urat";
									}else{
										$user_gejala = "Normal";
									}
								}
								break;
								case $umur > 40:
								if ($data['jenis_kelamin'] == 'Pria' ){
									if($data['asam_urat'] > 8.6){
										$user_gejala = "Asam Urat";
									}else{
										$user_gejala = "Normal";
									}
								}else{
									if ($data['asam_urat']  > 8.1){
										$user_gejala = "Asam Urat";
									}else{
										$user_gejala = "Normal";
									}
								}
								break;
						}
						$resp = $this->MyModel->create_user_profile($data);
					}
					json_output($respStatus,$resp);
		        }
			
		}
	}
	
}
