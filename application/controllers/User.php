<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        /*
        $check_auth_client = $this->MyModel->check_auth_client();
		if($check_auth_client != true){
			die($this->output->get_output());
		}
		*/
    }

	public function index()
	{
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'GET'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->MyModel->check_auth_client();
			if($check_auth_client == true){
		        $response = $this->MyModel->auth();
		        if($response['status'] == 200){
		        	$resp = $this->MyModel->user_all_data();
	    			json_output($response['status'],$resp);
		        }
			}
		}
	}

	public function detail($id)
	{
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'GET' || $this->uri->segment(3) == '' || is_numeric($this->uri->segment(3)) == FALSE){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->MyModel->check_auth_client();
			if($check_auth_client == true){
		        $response = $this->MyModel->auth();
		        if($response['status'] == 200){
		        	$resp = $this->MyModel->user_detail_data($id);
					json_output($response['status'],$resp);
		        }
			}
		}
	}

	public function create()
	{
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'POST'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->MyModel->check_auth_client();
			if($check_auth_client == true){
		        $response = $this->MyModel->auth();
		        $respStatus = $response['status'];
		        if($response['status'] == 200){
					$params = json_decode(file_get_contents('php://input'), TRUE);
					if ($params['title'] == "" || $params['author'] == "") {
						$respStatus = 400;
						$resp = array('status' => 400,'message' =>  'Title & Author can\'t empty');
					} else {
		        		$resp = $this->MyModel->book_create_data($params);
					}
					json_output($respStatus,$resp);
		        }
			}
		}
	}

	public function update($id)
	{
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'PUT' || $this->uri->segment(3) == '' || is_numeric($this->uri->segment(3)) == FALSE){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->MyModel->check_auth_client();
			if($check_auth_client == true){
		        $response = $this->MyModel->auth();
		        $respStatus = $response['status'];
		        if($response['status'] == 200){
					$params_for_profile = json_decode(file_get_contents('php://input'), TRUE);
					//$params['updated_at'] = date('Y-m-d H:i:s');
					if ($params_for_profile['diabet'] == "" || $params_for_profile['asam_urat'] == "" || $params_for_profile['gula_darah'] == "" || $params_for_profile['hdl'] == "" 
					|| $params_for_profile['ldl'] == "" || $params_for_profile['trigliserida'] == "") {
						$respStatus = 400;
						$resp = array('status' => 400,'message' =>  'Data Kesehatan Tidak Boleh Kosong');
					} else {
						$data = array(
							'diabet' => $params_for_profile['diabet'],
							'asam_urat' => $params_for_profile['asam_urat'],
							'gula_darah' => $params_for_profile['gula_darah'],
							'hdl' => $params_for_profile['hdl'],
							'ldl' => $params_for_profile['ldl'],
							'trigliserida' => $params_for_profile['trigliserida']
						);
					
						$resp = $this->MyModel->user_update_data($id,$data);
						
						if ($data['diabet'] == "Ya")
						{
							if ($data['ldl'] > 100 || $data['trigliserida'] > 150 || $data['gula_darah'] > 199 ){
								$kolesterol = "Kolesterol";
								$hipertensi = "Hipertensi";
							}else{
								$kolesterol = "Normal";
								$hipertensi = "Normal";
							}

						} else
						{
							if($data['ldl'] > 130 ||  $data['trigliserida'] > 200 || $data['gula_darah'] > 100 ){
								$kolesterol = "Kolesterol";
								$hipertensi = "Hipertensi";
							}else{
								$kolesterol = "Normal";
								$hipertensi = "Normal";
							}
						}

						//Cek Kolesterol dari HDL Saja

						if ($data['hdl'] < 60 ) {
							$kolesterol = "Kolesterol";
						}else{
							$kolesterol = " Normal";
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
										$asam_urat = "Asam Urat";
									}else{
										$asam_urat = "Normal";
									}
								}else{
									if ($data['asam_urat']  > 4.1){
										$asam_urat = "Asam Urat";
									}else{
										$asam_urat = "Normal";
									}
								}
								break;
								case $umur > 17 && $umur < 41 :
								if ($data['jenis_kelamin'] == 'Pria' ){
									if($data['asam_urat'] > 7.6){
										$asam_urat = "Asam Urat";
									}else{
										$asam_urat = "Normal";
									}
								}else{
									if ($data['asam_urat']  > 6.6){
										$asam_urat = "Asam Urat";
									}else{
										$asam_urat = "Normal";
									}
								}
								break;
								case $umur > 40:
								if ($data['jenis_kelamin'] == 'Pria' ){
									if($data['asam_urat'] > 8.6){
										$asam_urat = "Asam Urat";
									}else{
										$asam_urat = "Normal";
									}
								}else{
									if ($data['asam_urat']  > 8.1){
										$asam_urat = "Asam Urat";
									}else{
										$asam_urat = "Normal";
									}
								}
								break;
						}
						$user_profile_id = $this->MyModel->get_user_profile_id();
						$this->MyModel->update_user_gejala_byid($user_profile_id);
						if ($kolesterol == 'Kolesterol' && $hipertensi == "Hipertensi" && $asam_urat == "Asam Urat"){
							$input = array(
								'user_profile_id' => $user_profile_id,
								'kolesterol' => $kolesterol,
								'hipertensi' => $hipertensi,
								'asam_urat' => $asam_urat
							);
							$this->MyModel->create_user_gejala($input);
						}else if ($kolesterol == 'Kolesterol' && $hipertensi == "Hipertensi" && $asam_urat == "Normal"){
							$input = array(
								'user_profile_id' => $user_profile_id,
								'kolesterol' => $kolesterol,
								'hipertensi' => $hipertensi
							);
							$this->MyModel->create_user_gejala2($input);
						}else if ($kolesterol == 'Kolesterol' && $hipertensi == "Normal" && $asam_urat == "Normal"){
							$input = array(
								'user_profile_id' => $user_profile_id,
								'kolesterol' => $kolesterol
							);
							$this->MyModel->create_user_gejala3($input);
						}else{
							$input = array(
								'user_profile_id' => $user_profile_id,
								'kolesterol' => $kolesterol
							);
							$this->MyModel->create_user_gejala3($input);
					}
					json_output($respStatus,$resp);
		        }
			}
		}
	}

	public function delete($id)
	{
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'DELETE' || $this->uri->segment(3) == '' || is_numeric($this->uri->segment(3)) == FALSE){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->MyModel->check_auth_client();
			if($check_auth_client == true){
		        $response = $this->MyModel->auth();
		        if($response['status'] == 200){
		        	$resp = $this->MyModel->book_delete_data($id);
					json_output($response['status'],$resp);
		        }
			}
		}
	}

}
