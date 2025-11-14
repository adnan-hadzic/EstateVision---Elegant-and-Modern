<?php

// GET all appointments
Flight::route('GET /appointments', function () {
    Flight::json(Flight::appointmentService()->getAll());
});

// GET appointment by ID
Flight::route('GET /appointments/@id', function ($id) {
    Flight::json(Flight::appointmentService()->getById($id));
});

// GET appointments for specific user
Flight::route('GET /appointments/user/@user_id', function ($user_id) {
    Flight::json(Flight::appointmentService()->getAppointmentsByUser($user_id));
});

// GET appointments for specific agent
Flight::route('GET /appointments/agent/@agent_id', function ($agent_id) {
    Flight::json(Flight::appointmentService()->getAppointmentsByAgent($agent_id));
});

// GET upcoming appointments
Flight::route('GET /appointments/upcoming', function () {
    Flight::json(Flight::appointmentService()->getUpcomingAppointments());
});

// POST appointments
Flight::route('POST /appointments', function () {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::appointmentService()->insert($data));
});

// PUT by ID
Flight::route('PUT /appointments/@id', function ($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::appointmentService()->update($id, $data));
});

// DELETE by ID
Flight::route('DELETE /appointments/@id', function ($id) {
    Flight::json(Flight::appointmentService()->delete($id));
});

?>
