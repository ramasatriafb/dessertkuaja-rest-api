<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MyModel extends CI_Model {

    var $client_service = "frontend-client";
    var $auth_key       = "dessertkuajaapi";

    public function check_auth_client(){
        $client_service = $this->input->get_request_header('Client-Service', TRUE);
        $auth_key  = $this->input->get_request_header('Auth-Key', TRUE);
        if($client_service == $this->client_service && $auth_key == $this->auth_key){
            return true;
        } else {
            return json_output(401,array('status' => 401,'message' => 'Unauthorized.'));
        }
    }

    public function login($username,$password)
    {
        $q  = $this->db->select('password,user_id')->from('user')->where('username',$username)->get()->row();
        if($q == ""){
            return array('status' => 204,'message' => 'Username not found.');
        } else {
            $hashed_password = $q->password;
            $id              = $q->user_id;
            if (hash_equals($hashed_password, crypt($password, $hashed_password))) {
               $last_login = date('Y-m-d H:i:s');
               $token = crypt(substr( md5(rand()), 0, 7));
               $expired_at = date("Y-m-d H:i:s", strtotime('+12 hours'));
               $this->db->trans_start();
               $this->db->where('user_id',$id)->update('user',array('last_login' => $last_login));
               $this->db->insert('users_authentication',array('users_id' => $id,'token' => $token,'expired_at' => $expired_at));
               if ($this->db->trans_status() === FALSE){
                  $this->db->trans_rollback();
                  return array('status' => 500,'message' => 'Internal server error.');
               } else {
                  $this->db->trans_commit();
                  return array('status' => 200,'message' => 'Successfully login.','user_id' => $id, 'token' => $token);
               }
            } else {
               return array('status' => 204,'message' => 'Wrong password.');
            }
        }
    }

    public function logout()
    {
        $users_id  = $this->input->get_request_header('User-ID', TRUE);
        $token     = $this->input->get_request_header('Authorization', TRUE);
        $this->db->where('users_id',$users_id)->where('token',$token)->delete('users_authentication');
        return array('status' => 200,'message' => 'Successfully logout.');
    }

    public function auth()
    {
        $users_id  = $this->input->get_request_header('User-ID', TRUE);
        $token     = $this->input->get_request_header('Authorization', TRUE);
        $q  = $this->db->select('expired_at')->from('users_authentication')->where('users_id',$users_id)->where('token',$token)->get()->row();
        if($q == ""){
            return json_output(401,array('status' => 401,'message' => 'Unauthorized.'));
        } else {
            if($q->expired_at < date('Y-m-d H:i:s')){
                return json_output(401,array('status' => 401,'message' => 'Your session has been expired.'));
            } else {
                $updated_at = date('Y-m-d H:i:s');
                $expired_at = date("Y-m-d H:i:s", strtotime('+12 hours'));
                $this->db->where('users_id',$users_id)->where('token',$token)->update('users_authentication',array('expired_at' => $expired_at,'updated_at' => $updated_at));
                return array('status' => 200,'message' => 'Authorized.');
            }
        }
    }

    public function create_user($params)
    {
        $last_login = '';
        $created = date("Y-m-d H:i:s");
        $pass = crypt($params['password']);
        // $this->db->insert('user',$params);
        $sql = "INSERT INTO user (username,password,last_login,created)"
        . "VALUES('".$params['username']."','".$pass."','".$last_login."','".$created."')";
        $query = $this->db->query($sql);
        // return $query;
        return $this->db->insert_id();
        
    }

    public function create_user_profile($data)
    {
        $this->db->insert('user_profile',$data);
        $user_profile_id = $this->db->insert_id();
        return array('status' => 201,'message' => 'Data has been created.',$user_profile_id);
    }


    public function create_user_gejala($input)
    {
        $sql1 ="INSERT INTO user_gejala(user_profile_id,gejala)"."VALUE ('".$input['user_profile_id']."','".$input['kolesterol']."')";
        $sql2 ="INSERT INTO user_gejala(user_profile_id,gejala)"."VALUE ('".$input['user_profile_id']."','".$input['hipertensi']."')";
        $sql3 ="INSERT INTO user_gejala(user_profile_id,gejala)"."VALUE ('".$input['user_profile_id']."','".$input['asam_urat']."')";
        $query1 = $this->db->query($sql1);
        $query2 = $this->db->query($sql2);
        $query3 = $this->db->query($sql3);
        
        return array('status' => 200,'message' => 'Data has been created.');
    }

    public function create_user_gejala2($input)
    {
        $sql1 ="INSERT INTO user_gejala(user_profile_id,gejala)"."VALUE ('".$input['user_profile_id']."','".$input['kolesterol']."')";
        $sql2 ="INSERT INTO user_gejala(user_profile_id,gejala)"."VALUE ('".$input['user_profile_id']."','".$input['hipertensi']."')";
        $query1 = $this->db->query($sql1);
        $query2 = $this->db->query($sql2);
        
        return array('status' => 200,'message' => 'Data has been created.');
    }
    public function create_user_gejala3($input)
    {
        $sql1 ="INSERT INTO user_gejala(user_profile_id,gejala)"."VALUE ('".$input['user_profile_id']."','".$input['kolesterol']."')";
        $query1 = $this->db->query($sql1);
        
        return array('status' => 200,'message' => 'Data has been created.');
    }

    public function resep_all_data($users_id)
    {
        $users_id  = $this->input->get_request_header('User-ID', TRUE);
        // $sql = "SELECT distinct b.nama_resep, b.gambar, b.keterangan,b.bahan, b.tata_cara FROM kategori_resep a, resep_dessert b, kategori c, user_profile d, user_gejala e
        //  WHERE a.resep_id = b.resep_id AND a.kategori_id = c.kategori_id AND d.user_profile_id = e.user_profile_id AND e.gejala = c.kategori and d.user_id = '".$users_id."' ";
        return $this->db->distinct()->select('b.resep_id,b.nama_resep, b.gambar, b.keterangan,b.bahan, b.tata_cara')-> from ('kategori_resep a, resep_dessert b, kategori c, user_profile d, user_gejala e')
        ->where('a.resep_id = b.resep_id')->where('a.kategori_id = c.kategori_id')->where('d.user_profile_id = e.user_profile_id')->where('e.gejala = c.kategori')->where('d.user_id',$users_id)->get()->result();
    }

    public function resep_detail_data($id)
    {
        return $this->db->select('resep_id,nama_resep, gambar, keterangan,bahan, tata_cara')->from('resep_dessert')->where('resep_id',$id)->order_by('resep_id','desc')->get()->row();
    }

    public function user_detail_data($id)
    {
        $users_id  = $this->input->get_request_header('User-ID', TRUE);
        return $this->db->select('a.username,a.password, b.nama, b.jenis_kelamin,b.tgl_lahir, b.diabet, b.asam_urat, b.gula_darah,b.hdl, b.ldl,b.trigliserida')-> from ('user a')
        ->join('user_profile b', 'a.user_id = b.user_id')->where('a.user_id',$users_id)->get()->result();
    }

    public function user_update_data($id,$data)
    {
        $this->db->where('user_id', $id);
        $this->db->update('user_profile', $data);
        
        return array('status' => 200,'message' => 'Data has been updated.');
    }

    public function get_user_profile_id()
    {
        $users_id  = $this->input->get_request_header('User-ID', TRUE);
        return $this->db->select('b.user_profile_id')-> from ('user a')
        ->join('user_profile b', 'a.user_id = b.user_id')->where('a.user_id',$users_id)->get()->row('user_profile_id');;
    }

    public function update_user_gejala_byid($user_profile_id)
    {
        $users_id  = $this->input->get_request_header('User-ID', TRUE);
        $this->db->where('user_profile_id', $user_profile_id);
        return $this->db->delete('user_gejala');
    }


    public function book_all_data()
    {
        return $this->db->distinct()->select('id,title,author')->from('books')->order_by('id','desc')->get()->result();
    }

    public function book_detail_data($id)
    {
        return $this->db->select('id,title,author')->from('books')->where('id',$id)->order_by('id','desc')->get()->row();
    }

    public function book_create_data($data)
    {
        $this->db->insert('books',$data);
        return array('status' => 201,'message' => 'Data has been created.');
    }

    public function book_update_data($id,$data)
    {
        $this->db->where('id',$id)->update('books',$data);
        return array('status' => 200,'message' => 'Data has been updated.');
    }

    public function book_delete_data($id)
    {
        $this->db->where('id',$id)->delete('books');
        return array('status' => 200,'message' => 'Data has been deleted.');
    }

}
