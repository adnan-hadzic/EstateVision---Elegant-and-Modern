<?php

require_once __DIR__ . '/../dao/BaseDao.php';

require_once __DIR__ . '/../dao/UserDao.php';

class UserServices extends BaseService{

    public function __construct(){

    $dao = new UserDao();
    parent::__construct($dao);

    }

    public function getByEmail($email){
        return $this->dao->getByEmail($email);
    }

    public function getAgents(){
        return $this->dao->getAgents();
    }

    public function getRecentUsers($days){
        return $this->dao->getRecentUsers($days);
    }

}