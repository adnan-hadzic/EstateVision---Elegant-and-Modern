<?php

require_once __DIR__ . '/../dao/BaseDao.php';

require_once __DIR__ . '/../dao/AppointmentDao.php';

class AppointmentServices extends BaseService {

    public function __construct(){

    $dao = new AppointmentDao();
    parent::__construct($dao);
    
    }

    public function getAppointmentsByUser($user_id){
        return $this->dao->getAppointmentsByUser($user_id);
    }
    
    public function getAppointmentsByAgent($agent_id){
        return $this->dao->getAppointmentsByAgent($agent_id);
    }

    public function getUpcomingAppointments(){
        return $this->dao->getUpcomingAppointments();
    }
}

