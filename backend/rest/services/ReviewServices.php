<?php

require_once __DIR__ . '/../dao/BaseDao.php';

require_once __DIR__ . '/../dao/ReviewDao.php';

class ReviewServices extends BaseService {

    public function __construct() {
        $dao = new ReviewDao();
        parent::__construct($dao);
    }

    public function getReviewsByAgent($agent_id){
        return $this->dao->getReviewsByAgent($agent_id);
    }

    public function getReviewsByProperty($property_id){
        return $this->dao->getReviewsByProperty($property_id);
    }

    public function getReviewsByUser($user_id){
        return $this->dao->getReviewsByUser($user_id);
    }

    public function getAverageRatingByAgent($agent_id){
        return $this->dao->getAverageRatingByAgent($agent_id);
    }
}
