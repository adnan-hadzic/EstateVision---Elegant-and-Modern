<?php

/**
 * @OA\Get(
 *     path="/agents",
 *     summary="Get all agents",
 *     tags={"Agents"},
 *     @OA\Response(
 *         response=200,
 *         description="List of all agents"
 *     )
 * )
 */
Flight::route('GET /agents', function () {
    Flight::json(Flight::agentService()->getAll());
});

/**
 * @OA\Get(
 *     path="/agents/{id}",
 *     summary="Get agent by ID",
 *     tags={"Agents"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Agent information"
 *     )
 * )
 */
Flight::route('GET /agents/@id', function ($id) {
    Flight::json(Flight::agentService()->getById($id));
});

/**
 * @OA\Get(
 *     path="/agents/user/{user_id}",
 *     summary="Get agent by user_id",
 *     tags={"Agents"},
 *     @OA\Parameter(
 *         name="user_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Agent linked to a specific user"
 *     )
 * )
 */
Flight::route('GET /agents/user/@user_id', function ($user_id) {
    Flight::json(Flight::agentService()->getAgentByUserId($user_id));
});

/**
 * @OA\Get(
 *     path="/agents/top",
 *     summary="Get top agents by experience",
 *     tags={"Agents"},
 *     @OA\Parameter(
 *         name="limit",
 *         in="query",
 *         required=false,
 *         description="Number of agents to return",
 *         @OA\Schema(type="integer", default=3)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Top agents list"
 *     )
 * )
 */
Flight::route('GET /agents/top', function () {
    $limit = intval(Flight::request()->query['limit'] ?? 3);
    Flight::json(Flight::agentService()->getTopAgents($limit));
});

/**
 * @OA\Post(
 *     path="/agents",
 *     summary="Create a new agent",
 *     tags={"Agents"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id","agency_name","license_number","years_of_experience"},
 *             @OA\Property(property="user_id", type="integer"),
 *             @OA\Property(property="agency_name", type="string"),
 *             @OA\Property(property="license_number", type="string"),
 *             @OA\Property(property="years_of_experience", type="integer")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Agent created successfully"
 *     )
 * )
 */
Flight::route('POST /agents', function () {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::agentService()->insert($data));
});

/**
 * @OA\Put(
 *     path="/agents/{id}",
 *     summary="Update agent information",
 *     tags={"Agents"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=false,
 *         @OA\JsonContent(
 *             @OA\Property(property="agency_name", type="string"),
 *             @OA\Property(property="license_number", type="string"),
 *             @OA\Property(property="years_of_experience", type="integer")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Agent updated"
 *     )
 * )
 */
Flight::route('PUT /agents/@id', function ($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::agentService()->update($id, $data));
});

/**
 * @OA\Delete(
 *     path="/agents/{id}",
 *     summary="Delete an agent",
 *     tags={"Agents"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Agent deleted"
 *     )
 * )
 */
Flight::route('DELETE /agents/@id', function ($id) {
    Flight::json(Flight::agentService()->delete($id));
});

?>
