<?php

/**
 * @OA\Get(
 *     path="/properties",
 *     summary="Get all properties",
 *     tags={"Properties"},
 *     @OA\Response(response=200, description="List of all properties")
 * )
 */
Flight::route('GET /properties', function(){
    Flight::json(Flight::propertyService()->getAll());
});

/**
 * @OA\Get(
 *     path="/properties/{id}",
 *     summary="Get property by ID",
 *     tags={"Properties"},
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Property details")
 * )
 */
Flight::route('GET /properties/@id', function($id){
    Flight::json(Flight::propertyService()->getById($id));
});

/**
 * @OA\Post(
 *     path="/properties",
 *     summary="Add new property (Admin & Agent)",
 *     tags={"Properties"},
 *     @OA\RequestBody(required=true, @OA\JsonContent()),
 *     @OA\Response(response=200, description="Property created")
 * )
 */
Flight::route('POST /properties', function () {
    authorizeRoles([Roles::ADMIN, Roles::AGENT]);
    
    $data = Flight::request()->data->getData();
    
    $user = Flight::get('user');
    if ($user->role === Roles::AGENT) {
        $data['agent_id'] = $user->user_id;
    }
    
    Flight::json(Flight::propertyService()->insert($data));
});

/**
 * @OA\Put(
 *     path="/properties/{id}",
 *     summary="Update property (Admin & Agent)",
 *     tags={"Properties"},
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent()),
 *     @OA\Response(response=200, description="Property updated")
 * )
 */
Flight::route('PUT /properties/@id', function ($id) {
    authorizeRoles([Roles::ADMIN, Roles::AGENT]);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::propertyService()->update($id, $data));
});

/**
 * @OA\Delete(
 *     path="/properties/{id}",
 *     summary="Delete property (Admin only)",
 *     tags={"Properties"},
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Property deleted")
 * )
 */
Flight::route('DELETE /properties/@id', function ($id) {
    authorizeRole(Roles::ADMIN);
    Flight::json(Flight::propertyService()->delete($id));
});

?>
