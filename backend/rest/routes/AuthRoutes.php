<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

Flight::group('/auth', function() {
   /**
     * @OA\Post(
     *     path="/auth/register",
     *     tags={"auth"},
     *     summary="Register new user"
     * )
     */
   Flight::route('POST /register', function() {
        $data = json_decode(Flight::request()->getBody(), true);

        if (!is_array($data)) {
            Flight::halt(400, 'Invalid request payload');
        }

        $response = Flight::authService()->register($data);

        if ($response['success']) {
            Flight::json([
                'message' => 'User registered successfully',
                'data' => $response['data']
            ]);
        } else {
            Flight::halt(400, json_encode(['message' => $response['error']]));
        }
   });

   /**
    * @OA\Post(
    *      path="/auth/login",
    *      tags={"auth"},
    *      summary="Login to system"
    * )
    */
   Flight::route('POST /login', function() {
        $data = json_decode(Flight::request()->getBody(), true);

        if (!is_array($data)) {
            Flight::halt(400, 'Invalid request payload');
        }

        $response = Flight::authService()->login($data);

        if ($response['success']) {
            Flight::json([
                'message' => 'User logged in successfully',
                'token' => $response['data']['token'],
                'data' => $response['data']
            ]);
        } else {
            Flight::halt(401, json_encode(['message' => $response['error']]));
        }
   });
});
?>
