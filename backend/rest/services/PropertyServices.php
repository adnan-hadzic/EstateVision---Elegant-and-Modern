<?php

require_once __DIR__ . '/../dao/BaseDao.php';

require_once __DIR__ . '/../dao/PropertyDao.php';

class PropertyServices extends BaseService{

    public function __construct(){

    $dao = new PropertyDao();
    parent::__construct($dao);

    }
    
    public function getByAgentId($agentId){
        return $this->dao->getByAgentId($agentId);
    }

    public function getRecentProperties($limit){
        return $this->dao->getRecentProperties($limit);
    }



}