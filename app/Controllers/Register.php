<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use Firebase\JWT\JWT;

class Register extends BaseController
{
    private $secretKey = 'JWT_SECRET'; 
    use ResponseTrait;

    private function generateToken($data)
    {
        $issuedAt   = time();
        $expiration = $issuedAt + 60 * 60; // Token berlaku selama 1 jam
        $token      = JWT::encode($data, $this->secretKey, 'HS256');

        return [
            'token'      => $token,
            'expires_at' => $expiration
        ];
    }
    
 
    public function index()
    {
        $rules = [
            'email' => ['rules' => 'required|min_length[4]|max_length[255]|valid_email|is_unique[users.email]'],
            'password' => ['rules' => 'required|min_length[8]|max_length[255]'],
            'confirm_password'  => [ 'label' => 'confirm password', 'rules' => 'matches[password]']
        ];
            
  
        if($this->validate($rules)){
            $model = new UserModel();
            $data = [
                'email'    => $this->request->getVar('email'),
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)
            ];
            $model->save($data);
            $token = $this->generateToken($data);
             
            return $this->respond([
                'message' => 'Registered Successfully',
                'data' => $data,
                'token' => $token
            ], 200);
        }else{
            $response = [
                'errors' => $this->validator->getErrors(),
                'message' => 'Invalid Inputs'
            ];
            return $this->fail($response , 409);
             
        }
            
    }
}
