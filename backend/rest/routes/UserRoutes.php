<?php

/**
 * @OA\Get(
 *     path="/users",
 *     summary="Get all users (Admin only)",
 *     tags={"Users"},
 *     @OA\Response(response=200, description="List of all users"),
 *     @OA\Response(response=403, description="Forbidden")
 * )
 */
Flight::route('GET /users', function(){
    authorizeRole(Roles::ADMIN);
    Flight::json(Flight::userService()->getAll());
});

/**
 * @OA\Get(
 *     path="/users/{id}",
 *     summary="Get user by ID",
 *     tags={"Users"},
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="User object")
 * )
 */
Flight::route('GET /users/@id', function($id){
    $user = Flight::get('user');
    
    if ($user->role === Roles::ADMIN || $user->user_id == $id) {
        Flight::json(Flight::userService()->getById($id));
    } else {
        Flight::halt(403, json_encode(['error' => 'You can only view your own profile']));
    }
});

/**
 * @OA\Get(
 *     path="/users/by-email",
 *     summary="Get user by email (Admin only)",
 *     tags={"Users"},
 *     @OA\Parameter(name="email", in="query", required=true, @OA\Schema(type="string")),
 *     @OA\Response(response=200, description="User object")
 * )
 */
Flight::route('GET /users/by-email', function(){
    authorizeRole(Roles::ADMIN);
    
    $email = Flight::request()->query['email'] ?? null;

    if (!$email) {
        Flight::json(['error' => 'Query param "email" is required'], 400);
        return;
    }

    Flight::json(Flight::userService()->getByEmail($email));
});

/**
 * @OA\Get(
 *     path="/users/agents",
 *     summary="Get all agent users",
 *     tags={"Users"},
 *     @OA\Response(response=200, description="List of agent users")
 * )
 */
Flight::route('GET /users/agents', function(){
    Flight::json(Flight::userService()->getAgents());
});

/**
 * @OA\Get(
 *     path="/users/recent",
 *     summary="Get recent users (Admin only)",
 *     tags={"Users"},
 *     @OA\Parameter(name="days", in="query", required=false, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="List of recent users")
 * )
 */
Flight::route('GET /users/recent', function () {
    authorizeRole(Roles::ADMIN);
    
    $days = intval(Flight::request()->query['days'] ?? 7);
    Flight::json(Flight::userService()->getRecentUsers($days));
});

/**
 * @OA\Post(
 *     path="/users",
 *     summary="Create a new user (Admin only)",
 *     tags={"Users"},
 *     @OA\RequestBody(required=true, @OA\JsonContent()),
 *     @OA\Response(response=200, description="User created successfully")
 * )
 */
Flight::route('POST /users', function () {
    authorizeRole(Roles::ADMIN);
    
    $data = Flight::request()->data->getData();
    Flight::json(Flight::userService()->insert($data));
});

/**
 * @OA\Put(
 *     path="/users/{id}",
 *     summary="Update user",
 *     tags={"Users"},
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent()),
 *     @OA\Response(response=200, description="User updated")
 * )
 */
Flight::route('PUT /users/@id', function ($id) {
    $user = Flight::get('user');
    
    if ($user->role === Roles::ADMIN || $user->user_id == $id) {
        $data = Flight::request()->data->getData();
        Flight::json(Flight::userService()->update($id, $data));
    } else {
        Flight::halt(403, json_encode(['error' => 'You can only update your own profile']));
    }
});

/**
 * @OA\Delete(
 *     path="/users/{id}",
 *     summary="Delete user (Admin only)",
 *     tags={"Users"},
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="User deleted")
 * )
 */
Flight::route('DELETE /users/@id', function ($id) {
    authorizeRole(Roles::ADMIN);
    Flight::json(Flight::userService()->delete($id));
});

?>
