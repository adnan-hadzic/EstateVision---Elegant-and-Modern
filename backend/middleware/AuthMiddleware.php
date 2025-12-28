<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
class AuthMiddleware {
   private function getAuthToken() {
       $token = Flight::request()->getHeader("Authorization");
       if (!$token && isset($_SERVER['HTTP_AUTHORIZATION'])) {
           $token = $_SERVER['HTTP_AUTHORIZATION'];
       }
       if (!$token && isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
           $token = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
       }
       if (!$token && function_exists('getallheaders')) {
           $allHeaders = getallheaders();
           if (isset($allHeaders['Authorization'])) {
               $token = $allHeaders['Authorization'];
           } elseif (isset($allHeaders['authorization'])) {
               $token = $allHeaders['authorization'];
           }
       }
       if ($token) {
           $token = str_replace('Bearer ', '', $token);
       }
       return $token;
   }

   private function ensureUser() {
       $user = Flight::get('user');
       if ($user) {
           return $user;
       }
       $token = $this->getAuthToken();
       if ($token) {
           $this->verifyToken($token);
           return Flight::get('user');
       }
       return null;
   }

   public function verifyToken($token){
       if(!$token)
           Flight::halt(401, "Missing authentication header");
       $decoded_token = JWT::decode($token, new Key(Config::JWT_SECRET(), 'HS256'));
       Flight::set('user', $decoded_token->user);
       Flight::set('jwt_token', $token);
       return TRUE;
   }
   public function authorizeRole($requiredRole) {
       $user = $this->ensureUser();
       if (!$user) {
           Flight::halt(401, 'Missing authentication header');
       }
       if ($user->role !== $requiredRole) {
           Flight::halt(403, 'Access denied: insufficient privileges');
       }
   }
   public function authorizeRoles($roles) {
       $user = $this->ensureUser();
       if (!$user) {
           Flight::halt(401, 'Missing authentication header');
       }
       if (!in_array($user->role, $roles)) {
           Flight::halt(403, 'Forbidden: role not allowed');
       }
   }
   function authorizePermission($permission) {
       $user = $this->ensureUser();
       if (!$user) {
           Flight::halt(401, 'Missing authentication header');
       }
       if (!in_array($permission, $user->permissions)) {
           Flight::halt(403, 'Access denied: permission missing');
       }
   }   
}
