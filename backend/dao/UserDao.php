<?php
require_once __DIR__ . '/BaseDao.php';

class UserDao extends BaseDao {
    public function __construct() {
        parent::__construct('users');
    }

    public function getByEmail($email) {
        $stmt = $this->connection->prepare("SELECT * FROM {$this->table} WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAgents() {
        $stmt = $this->connection->prepare("SELECT * FROM {$this->table} WHERE role = 'agent'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRecentUsers($days = 7) {
        $stmt = $this->connection->prepare("SELECT * FROM {$this->table} WHERE created_at >= NOW() - INTERVAL :days DAY");
        $stmt->bindParam(':days', $days, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
