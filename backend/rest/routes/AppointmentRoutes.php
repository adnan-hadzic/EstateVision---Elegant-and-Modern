<?php

function trim_appointment_input($data) {
    foreach ($data as $key => $value) {
        if (is_string($value)) {
            $data[$key] = trim($value);
        }
    }
    return $data;
}

/**
 * @OA\Get(
 *     path="/appointments",
 *     summary="Get all appointments (Admin only)",
 *     tags={"Appointments"},
 *     @OA\Response(response=200, description="List of appointments")
 * )
 */
Flight::route('GET /appointments', function(){
    authorizeRole(Roles::ADMIN); 
    Flight::json(Flight::appointmentService()->getAll());
});

/**
 * @OA\Get(
 *     path="/appointments/{id}",
 *     summary="Get appointment by ID",
 *     tags={"Appointments"},
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Appointment details")
 * )
 */
Flight::route('GET /appointments/@id', function($id){
    $user = Flight::get('user');
    $appointment = Flight::appointmentService()->getById($id);
    
    if ($user->role === Roles::ADMIN) {
        Flight::json($appointment);
        return;
    }
    
    if ($appointment['agent_id'] == $user->user_id || $appointment['user_id'] == $user->user_id) {
        Flight::json($appointment);
    } else {
        Flight::halt(403, json_encode(['error' => 'Forbidden']));
    }
});

/**
 * @OA\Post(
 *     path="/appointments",
 *     summary="Book a new appointment",
 *     tags={"Appointments"},
 *     @OA\RequestBody(required=true, @OA\JsonContent()),
 *     @OA\Response(response=200, description="Appointment booked")
 * )
 */
Flight::route('POST /appointments', function () {
    $data = trim_appointment_input(Flight::request()->data->getData());
    $user = Flight::get('user');
    if ($user->role !== Roles::ADMIN) {
        $data['user_id'] = $user->user_id;
        $data['status'] = 'pending'; // Default status
    }

    $propertyId = $data['property_id'] ?? null;
    $scheduledDate = $data['scheduled_date'] ?? null;
    if (!$propertyId || !$scheduledDate) {
        Flight::json(['error' => 'property_id and scheduled_date are required'], 400);
        return;
    }
    if ($user->role === Roles::ADMIN && empty($data['user_id'])) {
        Flight::json(['error' => 'user_id is required for admin-created appointments'], 400);
        return;
    }
    
    Flight::json(Flight::appointmentService()->insert($data));
});

/**
 * @OA\Put(
 *     path="/appointments/{id}",
 *     summary="Update appointment (Admin & Agent)",
 *     tags={"Appointments"},
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent()),
 *     @OA\Response(response=200, description="Appointment updated")
 * )
 */
Flight::route('PUT /appointments/@id', function ($id) {
    authorizeRoles([Roles::ADMIN, Roles::AGENT]); // ← Admin i Agent mogu menjati (npr. status)
    $data = trim_appointment_input(Flight::request()->data->getData());
    if (isset($data['scheduled_date']) && $data['scheduled_date'] === '') {
        Flight::json(['error' => 'scheduled_date is required'], 400);
        return;
    }
    if (isset($data['status']) && !in_array($data['status'], ['pending', 'confirmed', 'cancelled'], true)) {
        Flight::json(['error' => 'Invalid status value'], 400);
        return;
    }
    Flight::json(Flight::appointmentService()->update($id, $data));
});

/**
 * @OA\Delete(
 *     path="/appointments/{id}",
 *     summary="Cancel appointment",
 *     tags={"Appointments"},
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Appointment cancelled")
 * )
 */
Flight::route('DELETE /appointments/@id', function ($id) {
    $user = Flight::get('user');
    $appointment = Flight::appointmentService()->getById($id);
    
    if ($user->role === Roles::ADMIN || $appointment['user_id'] == $user->user_id) {
        Flight::json(Flight::appointmentService()->delete($id));
    } else {
        Flight::halt(403, json_encode(['error' => 'Forbidden']));
    }
});

?>
