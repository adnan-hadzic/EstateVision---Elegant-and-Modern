<?php

function trim_review_input($data) {
    foreach ($data as $key => $value) {
        if (is_string($value)) {
            $data[$key] = trim($value);
        }
    }
    return $data;
}

/**
 * @OA\Get(
 *     path="/reviews",
 *     summary="Get all reviews",
 *     tags={"Reviews"},
 *     @OA\Response(response=200, description="List of reviews")
 * )
 */
Flight::route('GET /reviews', function(){
    Flight::json(Flight::reviewService()->getAll());
});

/**
 * @OA\Post(
 *     path="/reviews",
 *     summary="Add a review (Authenticated users)",
 *     tags={"Reviews"},
 *     @OA\RequestBody(required=true, @OA\JsonContent()),
 *     @OA\Response(response=200, description="Review added")
 * )
 */
Flight::route('POST /reviews', function () {
    $data = trim_review_input(Flight::request()->data->getData());
    
    $user = Flight::get('user');
    $data['user_id'] = $user->user_id;

    $propertyId = $data['property_id'] ?? null;
    $rating = $data['rating'] ?? null;
    if (!$propertyId || $rating === null) {
        Flight::json(['error' => 'property_id and rating are required'], 400);
        return;
    }
    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        Flight::json(['error' => 'rating must be between 1 and 5'], 400);
        return;
    }
    
    Flight::json(Flight::reviewService()->insert($data));
});

/**
 * @OA\Delete(
 *     path="/reviews/{id}",
 *     summary="Delete review (Admin only)",
 *     tags={"Reviews"},
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Review deleted")
 * )
 */
Flight::route('DELETE /reviews/@id', function ($id) {
    authorizeRole(Roles::ADMIN);
    Flight::json(Flight::reviewService()->delete($id));
});

?>
