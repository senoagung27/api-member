<?php

namespace App\Controllers;

use \Firebase\JWT\JWT;
use App\Libraries\JWTCI4;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;
use App\Controllers\BaseController;

class Login extends BaseController
{
    // use ResponseTrait;
     
    // public function index()
    // {
    //     $userModel = new UserModel();
  
    //     $email = $this->request->getVar('email');
    //     $password = $this->request->getVar('password');
          
    //     $user = $userModel->where('email', $email)->first();
  
    //     if(is_null($user)) {
    //         return $this->respond(['error' => 'Invalid username or password.'], 401);
    //     }
  
    //     $pwd_verify = password_verify($password, $user['password']);
  
    //     if(!$pwd_verify) {
    //         return $this->respond(['error' => 'Invalid username or password.'], 401);
    //     }
 
    //     $key = getenv('JWT_SECRET');
    //     $iat = time(); // current timestamp value
    //     $exp = $iat + 3600;
 
    //     $payload = array(
    //         "iss" => "Issuer of the JWT",
    //         "aud" => "Audience that the JWT",
    //         "sub" => "Subject of the JWT",
    //         "iat" => $iat, //Time the JWT issued at
    //         "exp" => $exp, // Expiration time of token
    //         "email" => $user['email'],
    //     );
         
    //     $token = JWT::encode($payload, $key, 'HS256');
 
    //     $response = [
    //         'message' => 'Login Succesful',
    //         'token' => $token
    //     ];
         
    //     return $this->respond($response, 200);
    // }
    public function login()
	{
		if( !$this->validate([
			'email' 	=> 'required',
			'password' 	=> 'required|min_length[6]',
		]))
		{
			return $this->response->setJSON(['success' => false, 'data' => null, "message" => \Config\Services::validation()->getErrors()]);
		}

		$db = new UserModel;
		$user  = $db->where('email', $this->request->getVar('email'))->first();
		if( $user )
		{
			if( password_verify($this->request->getVar('password'), $user['password']) )
			{
				$jwt = new JWTCI4;
				$token = $jwt->token();

				return $this->response->setJSON( ['token'=> $token ] );
			}
		}else{

			return $this->response->setJSON( ['success'=> false, 'message' => 'User not found' ] )->setStatusCode(409);
		}
		
		
	}
}
