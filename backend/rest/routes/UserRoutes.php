<?php

function trim_user_input($data) {
    foreach ($data as $key => $value) {
        if (is_string($value)) {
            $data[$key] = trim($value);
        }
    }
    return $data;
}

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
    $users = Flight::userService()->getAll();
    $filtered = array_map(function($user) {
        return [
            'id' => $user['user_id'] ?? $user['id'] ?? null,
            'full_name' => $user['full_name'] ?? null,
            'email' => $user['email'] ?? null,
            'role' => $user['role'] ?? null,
            'created_at' => $user['created_at'] ?? null
        ];
    }, $users);
    Flight::json($filtered);
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
    if (is_string($email)) {
        $email = trim($email);
    }

    if (!$email) {
        Flight::json(['error' => 'Query param "email" is required'], 400);
        return;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        Flight::json(['error' => 'Invalid email format'], 400);
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
    
    $data = trim_user_input(Flight::request()->data->getData());
    if (isset($data['name']) && empty($data['full_name'])) {
        $data['full_name'] = $data['name'];
    }

    $fullName = $data['full_name'] ?? null;
    $email = $data['email'] ?? null;
    $passwordHash = $data['password_hash'] ?? null;

    if (!$fullName || !$email || !$passwordHash) {
        Flight::json(['error' => 'full_name, email, and password_hash are required'], 400);
        return;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        Flight::json(['error' => 'Invalid email format'], 400);
        return;
    }

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
        $data = trim_user_input(Flight::request()->data->getData());
        if (isset($data['email']) && (!filter_var($data['email'], FILTER_VALIDATE_EMAIL))) {
            Flight::json(['error' => 'Invalid email format'], 400);
            return;
        }
        if (array_key_exists('full_name', $data) && $data['full_name'] === '') {
            Flight::json(['error' => 'full_name is required'], 400);
            return;
        }
        if (array_key_exists('name', $data) && $data['name'] === '') {
            Flight::json(['error' => 'name is required'], 400);
            return;
        }
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
