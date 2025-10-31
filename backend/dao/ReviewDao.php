<?php
require_once 'BaseDao.php';

class ReviewDao extends BaseDao {
    public function __construct() {
        parent::__construct('reviews');
    }

    public function addReview($review) {
        $sql = "INSERT INTO {$this->table} (user_id, agent_id, property_id, rating, comment) 
                VALUES (:user_id, :agent_id, :property_id, :rating, :comment)";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($review);
        return $this->connection->lastInsertId();
    }

    public function getAllReviews() {
        $stmt = $this->connection->prepare("SELECT * FROM {$this->table} ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReviewsByAgent($agent_id) {
        $stmt = $this->connection->prepare("SELECT * FROM {$this->table} WHERE agent_id = :agent_id ORDER BY created_at DESC");
        $stmt->bindParam(':agent_id', $agent_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReviewsByProperty($property_id) {
        $stmt = $this->connection->prepare("SELECT * FROM {$this->table} WHERE property_id = :property_id ORDER BY created_at DESC");
        $stmt->bindParam(':property_id', $property_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReviewsByUser($user_id) {
        $stmt = $this->connection->prepare("SELECT * FROM {$this->table} WHERE user_id = :user_id ORDER BY created_at DESC");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAverageRatingByAgent($agent_id) {
    $stmt = $this->connection->prepare("
        SELECT AVG(rating) as average_rating 
        FROM {$this->table} 
        WHERE agent_id = :agent_id
    ");
    $stmt->bindParam(':agent_id', $agent_id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

}
?>
