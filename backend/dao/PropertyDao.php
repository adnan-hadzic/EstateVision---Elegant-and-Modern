<?php
require_once __DIR__ . '/BaseDao.php';

class PropertyDao extends BaseDao {
    public function __construct() {
        parent::__construct('properties');
    }

    public function getAllProperties() {
        return parent::getAll();
    }

    public function getPropertyById($id) {
        return parent::getById($id);
    }

    public function getByAgentId($agentId) {
        $stmt = $this->connection->prepare("SELECT * FROM {$this->table} WHERE agent_id = :agent_id");
        $stmt->bindParam(':agent_id', $agentId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRecentProperties($limit = 5) {
        $stmt = $this->connection->prepare("SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT :limit");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
