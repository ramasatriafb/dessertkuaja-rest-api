<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Resep extends CI_Controller {

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
					$resp = $this->MyModel->resep_all_data($users_id);
					// var_dump($resp);die();
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
		        	$resp = $this->MyModel->resep_detail_data($id);
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
					if ($params['nama_resep'] == "" || $params['gambar'] == ""
					|| $params['keterangan'] == ""|| $params['bahan'] == ""|| $params['tata_cara'] == "") {
						$respStatus = 400;
						$resp = array('status' => 400,'message' =>  'Data resep tidak boleh ada yang kosong !');
					} else {
		        		$resp = $this->MyModel->resep_create_data($params);
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
					$params = json_decode(file_get_contents('php://input'), TRUE);
					$params['updated_at'] = date('Y-m-d H:i:s');
					if ($params['nama_resep'] == "" || $params['gambar'] == ""
					|| $params['keterangan'] == ""|| $params['bahan'] == ""|| $params['tata_cara'] == "") {
						$respStatus = 400;
						$resp = array('status' => 400,'message' =>  'Data resep tidak boleh ada yang kosong !');
					} else {
		        		$resp = $this->MyModel->resep_update_data($id,$params);
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
		        	$resp = $this->MyModel->resep_delete_data($id);
					json_output($response['status'],$resp);
		        }
			}
		}
	}

}