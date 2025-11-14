<?php
require_once 'BaseDao.php';

class AppointmentDao extends BaseDao {
    public function __construct() {
        parent::__construct('appointments', "appointment_id");
    }

     public function getAllAppointments() {
        return parent::getAll();
    }
    
    public function getAppointmentById($id) {
        return parent::getById($id);
    }

    public function getAppointmentsByUser($user_id) {
        $stmt = $this->connection->prepare("SELECT * FROM {$this->table} WHERE user_id = :user_id ORDER BY scheduled_date DESC");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAppointmentsByAgent($agent_id) {
        $stmt = $this->connection->prepare("SELECT * FROM {$this->table} WHERE agent_id = :agent_id ORDER BY scheduled_date DESC");
        $stmt->bindParam(':agent_id', $agent_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUpcomingAppointments() {
        $stmt = $this->connection->prepare("SELECT * FROM {$this->table} WHERE scheduled_date >= CURDATE() ORDER BY scheduled_date ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

