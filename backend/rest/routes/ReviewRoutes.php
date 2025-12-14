<?php

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
    $data = Flight::request()->data->getData();
    
    $user = Flight::get('user');
    $data['user_id'] = $user->user_id;
    
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
