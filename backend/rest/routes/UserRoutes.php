<?php

// GET all users
Flight::route('GET /users', function(){
    Flight::json(Flight::userService()->getAll());
});

// GET by id

Flight::route('GET /users/@id', function($id){
    Flight::json(Flight::userService()->getById($id));
});

// GET by email

Flight::route('GET /users/by-email', function(){
    $email = Flight::request()->query['email'] ?? null;

    if (!$email) {
        Flight::json(['error' => 'Query param "email" is required'], 400);
        return;
    }

    Flight::json(Flight::userService()->getByEmail($email));
});

// GET users which are agents

Flight::route('GET /users/agents', function(){
    Flight::json(Flight::userService()->getAgents());
});

// GET users which are created in last 7 days or x days

Flight::route('GET /users/recent', function () {
    $days = intval(Flight::request()->query['days'] ?? 7);
    Flight::json(Flight::userService()->getRecentUsers($days));
});

// POST users for creating new user
Flight::route('POST /users', function () {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::userService()->insert($data));
});

// PUT users for updating user
Flight::route('PUT /users/@id', function ($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::userService()->update($id, $data));
});

// DELETE users 
Flight::route('DELETE /users/@id', function ($id) {
    Flight::json(Flight::userService()->delete($id));
});

?>