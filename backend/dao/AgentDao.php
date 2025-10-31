<?php
require_once 'BaseDao.php';

class AgentDao extends BaseDao {
    public function __construct() {
        parent::__construct('agents');
    }

    public function getAllAgents() {
        $stmt = $this->connection->prepare("SELECT * FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAgentById($agent_id) {
        $stmt = $this->connection->prepare("SELECT * FROM {$this->table} WHERE agent_id = :agent_id");
        $stmt->bindParam(':agent_id', $agent_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAgentByUserId($user_id) {
        $stmt = $this->connection->prepare("SELECT * FROM {$this->table} WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTopAgents($limit = 3) {
        $stmt = $this->connection->prepare("SELECT * FROM {$this->table} ORDER BY years_of_experience DESC LIMIT :limit");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
