<?php

/**
 * @OA\Get(
 *     path="/agents",
 *     summary="Get all agents",
 *     tags={"Agents"},
 *     @OA\Response(response=200, description="List of all agents")
 * )
 */
Flight::route('GET /agents', function(){
    // Svi autentifikovani korisnici mogu videti listu agenata
    Flight::json(Flight::agentService()->getAll());
});

/**
 * @OA\Get(
 *     path="/agents/{id}",
 *     summary="Get agent by ID",
 *     tags={"Agents"},
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Agent details")
 * )
 */
Flight::route('GET /agents/@id', function($id){
    Flight::json(Flight::agentService()->getById($id));
});

/**
 * @OA\Post(
 *     path="/agents",
 *     summary="Add a new agent (Admin only)",
 *     tags={"Agents"},
 *     @OA\RequestBody(required=true, @OA\JsonContent()),
 *     @OA\Response(response=200, description="Agent created successfully")
 * )
 */
Flight::route('POST /agents', function () {
    authorizeRole(Roles::ADMIN); // ← Samo ADMIN može dodavati agente
    $data = Flight::request()->data->getData();
    Flight::json(Flight::agentService()->insert($data));
});

/**
 * @OA\Put(
 *     path="/agents/{id}",
 *     summary="Update agent profile",
 *     tags={"Agents"},
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent()),
 *     @OA\Response(response=200, description="Agent updated")
 * )
 */
Flight::route('PUT /agents/@id', function ($id) {
    $user = Flight::get('user');
    
    // Admin može menjati sve, Agent može menjati samo svoj profil
    // Pretpostavljamo da agent_id u tabeli odgovara user_id
    if ($user->role === Roles::ADMIN || ($user->role === Roles::AGENT && $user->user_id == $id)) {
        $data = Flight::request()->data->getData();
        Flight::json(Flight::agentService()->update($id, $data));
    } else {
        Flight::halt(403, json_encode(['error' => 'You can only update your own profile']));
    }
});

/**
 * @OA\Delete(
 *     path="/agents/{id}",
 *     summary="Delete agent (Admin only)",
 *     tags={"Agents"},
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Agent deleted")
 * )
 */
Flight::route('DELETE /agents/@id', function ($id) {
    authorizeRole(Roles::ADMIN); // ← Samo ADMIN može brisati agente
    Flight::json(Flight::agentService()->delete($id));
});

?>
