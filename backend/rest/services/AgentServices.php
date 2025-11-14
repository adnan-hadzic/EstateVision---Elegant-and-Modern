<?php

require_once __DIR__ . '/../dao/BaseDao.php';

require_once __DIR__ . '/../dao/AgentDao.php';

class AgentServices extends BaseService {

    public function __construct(){

        $dao = new AgentDao();
        parent::__construct($dao);

    }

    public function getAgentByUserId($user_id){
        return $this->dao->getAgentByUserId($user_id);
    }

    public function getTopAgents($limit){
        return $this->dao->getTopAgents($limit);
    }
}