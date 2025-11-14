<?php

// GET all reviews
Flight::route('GET /reviews', function () {
    Flight::json(Flight::reviewService()->getAll());
});

// GET review by ID
Flight::route('GET /reviews/@id', function ($id) {
    Flight::json(Flight::reviewService()->getById($id));
});

// GET reviews for specific agent
Flight::route('GET /reviews/agent/@agent_id', function ($agent_id) {
    Flight::json(Flight::reviewService()->getReviewsByAgent($agent_id));
});

// GET reviews for specific property
Flight::route('GET /reviews/property/@property_id', function ($property_id) {
    Flight::json(Flight::reviewService()->getReviewsByProperty($property_id));
});

// GET reviews for specific user
Flight::route('GET /reviews/user/@user_id', function ($user_id) {
    Flight::json(Flight::reviewService()->getReviewsByUser($user_id));
});

// GET average rating for specific agent
Flight::route('GET /reviews/agent/@agent_id/average-rating', function ($agent_id) {
    Flight::json(Flight::reviewService()->getAverageRatingByAgent($agent_id));
});

// POST /reviews
Flight::route('POST /reviews', function () {
    $data = Flight::request()->data->getData();
    // in ReviewDao i have addReview($review), but in BaseService i already have insert()
    Flight::json(Flight::reviewService()->insert($data));
});

// PUT reviews by ID
Flight::route('PUT /reviews/@id', function ($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::reviewService()->update($id, $data));
});

// DELETE reviews by ID
Flight::route('DELETE /reviews/@id', function ($id) {
    Flight::json(Flight::reviewService()->delete($id));
});

?>
