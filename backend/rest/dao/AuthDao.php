<?php
require_once __DIR__ . '/BaseDao.php';

class AuthDao extends BaseDao {
    
    public function __construct() {
        parent::__construct('users', 'id');
    }

    // Get user by email
    public function get_user_by_email($email) {
        $stmt = $this->connection->prepare("SELECT * FROM {$this->table} WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
