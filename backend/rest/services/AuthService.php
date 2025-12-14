<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/AuthDao.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthService extends BaseService {
   private $auth_dao;
   
   public function __construct() {
       $this->auth_dao = new AuthDao();
       parent::__construct($this->auth_dao); 
   }

   public function get_user_by_email($email){
       return $this->auth_dao->get_user_by_email($email);
   }

   public function register($entity) {  
       if (empty($entity['email']) || empty($entity['password'])) {
           return ['success' => false, 'error' => 'Email and password are required.'];
       }

       $email_exists = $this->auth_dao->get_user_by_email($entity['email']);
       if($email_exists){
           return ['success' => false, 'error' => 'Email already registered.'];
       }

       if(isset($entity['name'])) {
           $entity['full_name'] = $entity['name'];
           unset($entity['name']);
       }
       
       $entity['password_hash'] = password_hash($entity['password'], PASSWORD_BCRYPT);
       unset($entity['password']);

       if(!isset($entity['role']) || $entity['role'] == 'user') {
           $entity['role'] = 'buyer'; 
       }

       try {
           $new_user_id = parent::insert($entity);
           
           $entity['user_id'] = $new_user_id;
           unset($entity['password_hash']);
           
           return ['success' => true, 'data' => $entity];             
       } catch (Exception $e) {
           return ['success' => false, 'error' => 'Database error: ' . $e->getMessage()];
       }
   }

   public function login($entity) {  
       if (empty($entity['email']) || empty($entity['password'])) {
           return ['success' => false, 'error' => 'Email and password are required.'];
       }

       $user = $this->auth_dao->get_user_by_email($entity['email']);
       
       if(!$user){
           return ['success' => false, 'error' => 'Invalid email or password.'];
       }

       
       if(!password_verify($entity['password'], $user['password_hash'])) {
           return ['success' => false, 'error' => 'Invalid email or password.'];
       }

      
       unset($user['password_hash']);
       unset($user['password']); // Za svaki slucaj
      
       $jwt_payload = [
           'user' => $user,
           'iat' => time(),
           'exp' => time() + (60 * 60 * 24) // 24h
       ];

       $token = JWT::encode(
           $jwt_payload,
           Config::JWT_SECRET(),
           'HS256'
       );

       return ['success' => true, 'data' => array_merge($user, ['token' => $token])];             
   }
}
?>
