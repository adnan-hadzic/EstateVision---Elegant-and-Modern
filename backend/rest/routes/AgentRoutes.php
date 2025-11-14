<?php

// GET all agents
Flight::route('GET /agents', function () {
    Flight::json(Flight::agentService()->getAll());
});

// GET agents by ID
Flight::route('GET /agents/@id', function ($id) {
    Flight::json(Flight::agentService()->getById($id));
});

// GET agents for specific user
Flight::route('GET /agents/user/@user_id', function ($user_id) {
    Flight::json(Flight::agentService()->getAgentByUserId($user_id));
});

// GET agents by best(top) experience
Flight::route('GET /agents/top', function () {
    $limit = intval(Flight::request()->query['limit'] ?? 3);
    Flight::json(Flight::agentService()->getTopAgents($limit));
});

// POST agents
Flight::route('POST /agents', function () {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::agentService()->insert($data));
});

// PUT agents by ID
Flight::route('PUT /agents/@id', function ($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::agentService()->update($id, $data));
});

// DELETE agents by ID
Flight::route('DELETE /agents/@id', function ($id) {
    Flight::json(Flight::agentService()->delete($id));
});

?>
