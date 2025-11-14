<?php

/**
 * @OA\Get(
 *     path="/reviews",
 *     summary="Get all reviews",
 *     tags={"Reviews"},
 *     @OA\Response(
 *         response=200,
 *         description="List of all reviews"
 *     )
 * )
 */
Flight::route('GET /reviews', function () {
    Flight::json(Flight::reviewService()->getAll());
});

/**
 * @OA\Get(
 *     path="/reviews/{id}",
 *     summary="Get review by ID",
 *     tags={"Reviews"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Review object"
 *     )
 * )
 */
Flight::route('GET /reviews/@id', function ($id) {
    Flight::json(Flight::reviewService()->getById($id));
});

/**
 * @OA\Get(
 *     path="/reviews/agent/{agent_id}",
 *     summary="Get reviews for specific agent",
 *     tags={"Reviews"},
 *     @OA\Parameter(
 *         name="agent_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of reviews for the agent"
 *     )
 * )
 */
Flight::route('GET /reviews/agent/@agent_id', function ($agent_id) {
    Flight::json(Flight::reviewService()->getReviewsByAgent($agent_id));
});


/**
 * @OA\Get(
 *     path="/reviews/property/{property_id}",
 *     summary="Get reviews for specific property",
 *     tags={"Reviews"},
 *     @OA\Parameter(
 *         name="property_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of property reviews"
 *     )
 * )
 */
Flight::route('GET /reviews/property/@property_id', function ($property_id) {
    Flight::json(Flight::reviewService()->getReviewsByProperty($property_id));
});

/**
 * @OA\Get(
 *     path="/reviews/user/{user_id}",
 *     summary="Get reviews written by specific user",
 *     tags={"Reviews"},
 *     @OA\Parameter(
 *         name="user_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of user reviews"
 *     )
 * )
 */
Flight::route('GET /reviews/user/@user_id', function ($user_id) {
    Flight::json(Flight::reviewService()->getReviewsByUser($user_id));
});

/**
 * @OA\Get(
 *     path="/reviews/agent/{agent_id}/average-rating",
 *     summary="Get average rating for agent",
 *     tags={"Reviews"},
 *     @OA\Parameter(
 *         name="agent_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Average rating value"
 *     )
 * )
 */
Flight::route('GET /reviews/agent/@agent_id/average-rating', function ($agent_id) {
    Flight::json(Flight::reviewService()->getAverageRatingByAgent($agent_id));
});





/**
 * @OA\Post(
 *     path="/reviews",
 *     summary="Create a new review",
 *     tags={"Reviews"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id","property_id","rating","comment"},
 *             @OA\Property(property="user_id", type="integer"),
 *             @OA\Property(property="property_id", type="integer"),
 *             @OA\Property(property="rating", type="integer"),
 *             @OA\Property(property="comment", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Review successfully created"
 *     )
 * )
 */
Flight::route('POST /reviews', function () {
    $data = Flight::request()->data->getData();
    // in ReviewDao i have addReview($review), but in BaseService i already have insert()
    Flight::json(Flight::reviewService()->insert($data));
});

/**
 * @OA\Put(
 *     path="/reviews/{id}",
 *     summary="Update review",
 *     tags={"Reviews"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent()
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Review updated"
 *     )
 * )
 */
Flight::route('PUT /reviews/@id', function ($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::reviewService()->update($id, $data));
});

/**
 * @OA\Delete(
 *     path="/reviews/{id}",
 *     summary="Delete review",
 *     tags={"Reviews"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Review deleted"
 *     )
 * )
 */
Flight::route('DELETE /reviews/@id', function ($id) {
    Flight::json(Flight::reviewService()->delete($id));
});

?>
