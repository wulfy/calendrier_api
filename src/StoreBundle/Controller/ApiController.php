<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\FOSRestController;

class ApiController 
{

    /**
     * @return array
     * 
     */
    public function getUsersAction()
    {

        return array('users' => "hello");
    }
}
