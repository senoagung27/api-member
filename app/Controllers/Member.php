<?php namespace App\Controllers;

use App\Models\MemberModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use Firebase\JWT\JWT;
 
class Products extends ResourceController
{ 
    use ResponseTrait;
    // private function verifyToken($token)
    // {
    //     try {
    //         return JWT::decode($token, $this->secretKey, ['HS256']);
    //     } catch (\Exception $e) {
    //         return null;
    //     }
    // }

    public function index()
    {
        $model = new MemberModel();
        $data = $model->findAll();
        return $this->respond($data, 200);
    }
 
    // get single product
    public function show($id = null)
    {
        $model = new MemberModel();
        $data = $model->getWhere(['member_id' => $id])->getResult();
        if($data){
            return $this->respond($data);
        }else{
            return $this->failNotFound('No Data Found with id '.$id);
        }
    }
 
    // create a product
    public function create()
    {
        $model = new MemberModel();
        $data = [
            'nama' => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'hobby' => $this->request->getPost('hobby')
        ];
        $data = json_decode(file_get_contents("php://input"));
        //$data = $this->request->getPost();
        $model->insert($data);
        $response = [
            'status'   => 201,
            'error'    => null,
            'messages' => [
                'success' => 'Data Saved'
            ]
        ];
         
        return $this->respondCreated($data, 201);
    }
 
    // update product
    public function update($id = null)
    {
        $model = new MemberModel();
        $json = $this->request->getJSON();
        if($json){
            $data = [
                'nama' => $json->nama,
                'email' => $json->email,
                'phone' => $json->phone,
                'hobby' => $json->hobby
            ];
        }else{
            $input = $this->request->getRawInput();
            $data = [
                'nama' => $input['nama'],
                'email' => $input['email'],
                'phone' => $input['phone'],
                'hobby' => $input['hobby']
            ];
        }
        // Insert to Database
        $model->update($id, $data);
        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => [
                'success' => 'Data Updated'
            ]
        ];
        return $this->respond($response);
    }
 
    // delete product
    public function delete($id = null)
    {
        $model = new MemberModel();
        $data = $model->find($id);
        if($data){
            $model->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Data Deleted'
                ]
            ];
             
            return $this->respondDeleted($response);
        }else{
            return $this->failNotFound('No Data Found with id '.$id);
        }
         
    }
 
}