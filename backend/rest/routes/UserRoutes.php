<?php

/**
 * @OA\Get(
 *     path="/users",
 *     summary="Get all users",
 *     tags={"Users"},
 *     @OA\Response(
 *         response=200,
 *         description="List of all users"
 *     )
 * )
 */

Flight::route('GET /users', function(){
    Flight::json(Flight::userService()->getAll());
});

/**
 * @OA\Get(
 *     path="/users/{id}",
 *     summary="Get user by ID",
 *     tags={"Users"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User object"
 *     )
 * )
 */

Flight::route('GET /users/@id', function($id){
    Flight::json(Flight::userService()->getById($id));
});

/**
 * @OA\Get(
 *     path="/users/by-email",
 *     summary="Get user by email",
 *     tags={"Users"},
 *     @OA\Parameter(
 *         name="email",
 *         in="query",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User object"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Missing query param"
 *     )
 * )
 */

Flight::route('GET /users/by-email', function(){
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
 *     summary="Get all users that are agents",
 *     tags={"Users"},
 *     @OA\Response(
 *         response=200,
 *         description="List of agent users"
 *     )
 * )
 */

Flight::route('GET /users/agents', function(){
    Flight::json(Flight::userService()->getAgents());
});

/**
 * @OA\Get(
 *     path="/users/recent",
 *     summary="Get users created in the last X days",
 *     tags={"Users"},
 *     @OA\Parameter(
 *         name="days",
 *         in="query",
 *         required=false,
 *         description="Number of days (default = 7)",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of recent users"
 *     )
 * )
 */

Flight::route('GET /users/recent', function () {
    $days = intval(Flight::request()->query['days'] ?? 7);
    Flight::json(Flight::userService()->getRecentUsers($days));
});

/**
 * @OA\Post(
 *     path="/users",
 *     summary="Create a new user",
 *     tags={"Users"},
 *     @OA\RequestBody(
 *        required=true,
 *        @OA\JsonContent(
 *            required={"full_name","email","password_hash"},
 *            @OA\Property(property="full_name", type="string"),
 *            @OA\Property(property="email", type="string"),
 *            @OA\Property(property="password_hash", type="string"),
 *            @OA\Property(property="phone", type="string"),
 *            @OA\Property(property="role", type="string")
 *        )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User created successfully"
 *     )
 * )
 */

Flight::route('POST /users', function () {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::userService()->insert($data));
});

/**
 * @OA\Put(
 *     path="/users/{id}",
 *     summary="Update user",
 *     tags={"Users"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *        required=true,
 *        @OA\JsonContent()
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User updated"
 *     )
 * )
 */

Flight::route('PUT /users/@id', function ($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::userService()->update($id, $data));
});

/**
 * @OA\Delete(
 *     path="/users/{id}",
 *     summary="Delete user",
 *     tags={"Users"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User deleted"
 *     )
 * )
 */

Flight::route('DELETE /users/@id', function ($id) {
    Flight::json(Flight::userService()->delete($id));
});

?>