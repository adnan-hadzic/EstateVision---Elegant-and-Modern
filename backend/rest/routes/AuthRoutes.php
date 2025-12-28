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
            Flight::json(['error' => 'Invalid request payload'], 400);
            return;
        }

        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data[$key] = trim($value);
            }
        }

        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;
        $fullName = $data['full_name'] ?? $data['name'] ?? null;
        if (!$email || !$password || !$fullName) {
            Flight::json(['error' => 'full_name, email, and password are required'], 400);
            return;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Flight::json(['error' => 'Invalid email format'], 400);
            return;
        }

        $response = Flight::authService()->register($data);

        if ($response['success']) {
            Flight::json([
                'message' => 'User registered successfully',
                'data' => $response['data']
            ]);
        } else {
            Flight::json(['error' => $response['error']], 400);
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
            Flight::json(['error' => 'Invalid request payload'], 400);
            return;
        }

        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data[$key] = trim($value);
            }
        }

        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;
        if (!$email || !$password) {
            Flight::json(['error' => 'email and password are required'], 400);
            return;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Flight::json(['error' => 'Invalid email format'], 400);
            return;
        }

        $response = Flight::authService()->login($data);

        if ($response['success']) {
            Flight::json([
                'message' => 'User logged in successfully',
                'token' => $response['data']['token'],
                'data' => $response['data']
            ]);
        } else {
            Flight::json(['error' => $response['error']], 401);
        }
   });
});
?>
