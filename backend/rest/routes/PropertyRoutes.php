<?php

/**
 * @OA\Get(
 *     path="/appointments",
 *     summary="Get all appointments",
 *     tags={"Appointments"},
 *     @OA\Response(
 *         response=200,
 *         description="List of all appointments"
 *     )
 * )
 */
Flight::route('GET /appointments', function () {
    Flight::json(Flight::appointmentService()->getAll());
});

/**
 * @OA\Get(
 *     path="/appointments/{id}",
 *     summary="Get appointment by ID",
 *     tags={"Appointments"},
 *     @OA\Parameter(
 *         name="id", in="path", required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Appointment object"
 *     )
 * )
 */
Flight::route('GET /appointments/@id', function ($id) {
    Flight::json(Flight::appointmentService()->getById($id));
});

/**
 * @OA\Get(
 *     path="/appointments/user/{user_id}",
 *     summary="Get appointments for a specific user",
 *     tags={"Appointments"},
 *     @OA\Parameter(
 *         name="user_id", in="path", required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of user appointments"
 *     )
 * )
 */
Flight::route('GET /appointments/user/@user_id', function ($user_id) {
    Flight::json(Flight::appointmentService()->getAppointmentsByUser($user_id));
});

/**
 * @OA\Get(
 *     path="/appointments/agent/{agent_id}",
 *     summary="Get appointments for a specific agent",
 *     tags={"Appointments"},
 *     @OA\Parameter(
 *         name="agent_id", in="path", required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of agent appointments"
 *     )
 * )
 */
Flight::route('GET /appointments/agent/@agent_id', function ($agent_id) {
    Flight::json(Flight::appointmentService()->getAppointmentsByAgent($agent_id));
});

/**
 * @OA\Get(
 *     path="/appointments/upcoming",
 *     summary="Get upcoming appointments",
 *     tags={"Appointments"},
 *     @OA\Response(
 *         response=200,
 *         description="List of upcoming appointments"
 *     )
 * )
 */
Flight::route('GET /appointments/upcoming', function () {
    Flight::json(Flight::appointmentService()->getUpcomingAppointments());
});

/**
 * @OA\Post(
 *     path="/appointments",
 *     summary="Create a new appointment",
 *     tags={"Appointments"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id","agent_id","property_id","scheduled_time"},
 *             @OA\Property(property="user_id", type="integer"),
 *             @OA\Property(property="agent_id", type="integer"),
 *             @OA\Property(property="property_id", type="integer"),
 *             @OA\Property(property="scheduled_time", type="string", format="date-time")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Appointment created"
 *     )
 * )
 */
Flight::route('POST /appointments', function () {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::appointmentService()->insert($data));
});

/**
 * @OA\Put(
 *     path="/appointments/{id}",
 *     summary="Update appointment",
 *     tags={"Appointments"},
 *     @OA\Parameter(
 *         name="id", in="path", required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent()
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Appointment updated"
 *     )
 * )
 */
Flight::route('PUT /appointments/@id', function ($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::appointmentService()->update($id, $data));
});

/**
 * @OA\Delete(
 *     path="/appointments/{id}",
 *     summary="Delete appointment",
 *     tags={"Appointments"},
 *     @OA\Parameter(
 *         name="id", in="path", required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Appointment deleted"
 *     )
 * )
 */
Flight::route('DELETE /appointments/@id', function ($id) {
    Flight::json(Flight::appointmentService()->delete($id));
});

?>
