<?php

function trim_property_input($data) {
    foreach ($data as $key => $value) {
        if (is_string($value)) {
            $data[$key] = trim($value);
        }
    }
    return $data;
}

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
    
    $data = trim_property_input(Flight::request()->data->getData());
    
    $user = Flight::get('user');
    if ($user->role === Roles::AGENT) {
        $agent = Flight::agentService()->getAgentByUserId($user->user_id);
        if (!$agent || empty($agent['agent_id'])) {
            $created = Flight::agentService()->insert(['user_id' => $user->user_id]);
            if (!$created) {
                Flight::json(['error' => 'Agent profile not found for this user'], 400);
                return;
            }
            $agent = Flight::agentService()->getAgentByUserId($user->user_id);
        }
        if (empty($agent['agent_id'])) {
            Flight::json(['error' => 'Agent profile not found for this user'], 400);
            return;
        }
        $data['agent_id'] = $agent['agent_id'];
    }

    $title = $data['title'] ?? null;
    $price = $data['price'] ?? null;
    $location = $data['location'] ?? null;
    $propertyType = $data['property_type'] ?? null;

    if (!$title || !$price || !$location || !$propertyType) {
        Flight::json(['error' => 'title, price, location, and property_type are required'], 400);
        return;
    }
    if ($user->role === Roles::ADMIN && empty($data['agent_id'])) {
        Flight::json(['error' => 'agent_id is required for admin-created properties'], 400);
        return;
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
    $data = trim_property_input(Flight::request()->data->getData());
    if (array_key_exists('title', $data) && $data['title'] === '') {
        Flight::json(['error' => 'title is required'], 400);
        return;
    }
    if (array_key_exists('price', $data) && $data['price'] === '') {
        Flight::json(['error' => 'price is required'], 400);
        return;
    }
    if (array_key_exists('location', $data) && $data['location'] === '') {
        Flight::json(['error' => 'location is required'], 400);
        return;
    }
    if (array_key_exists('property_type', $data) && $data['property_type'] === '') {
        Flight::json(['error' => 'property_type is required'], 400);
        return;
    }
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
