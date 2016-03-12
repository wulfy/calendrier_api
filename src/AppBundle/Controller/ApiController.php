<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\FOSRestController;
use StoreBundle\Entity\Reservations;
use StoreBundle\Entity\Params;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use StoreBundle\Entity\User;

class ApiController extends FOSRestController
{

    /**
     * @return array
     *
     */
    public function getEventsAction()
    {
        $reservations = $this->get('doctrine')
                         ->getManager('events')
                         ->getRepository('StoreBundle:Reservations')
                         ->findAll();
        //$data = array('users' => 'hello', 'tara' => 'tata');
        $view = $this->view($reservations, 200)
            ->setTemplate('default/getReservations.html.twig')
            ->setTemplateVar('reservations');

            return $this->handleView($view);
    }

    /**
     * @return array
     *@Get("/params/user/{id}")
     */
    public function getCalendarParams($id)
    {
        $params = $this->get('doctrine')
                         ->getManager('events')
                         ->getRepository('StoreBundle:Params')
                         ->findOneBy(array('idUser' => intval($id)));


        //$data = array('users' => 'hello', 'tara' => 'tata');
        $view = $this->view($params, 200)
            ->setTemplate('default/getReservations.html.twig')
            ->setTemplateVar('reservations');

            return $this->handleView($view);
    }

    /**
     * @return array
     *@Get("/params/user")
     *@Security("has_role('ROLE_USER')")
     */
    public function getCalendarParamsForConnectedUser()
    {
        $connectedUser = $this->getUser();
        if($connectedUser)
        {
            $id = $connectedUser->getId();
            return $this->getCalendarParams($id);
        }else
        {
        
        $view = $this->view('Not connected', 403)
            ->setTemplate('default/getReservations.html.twig')
            ->setTemplateVar('reservations');

            return $this->handleView($view);
        }
    }

    /**
     * @return array
     *@Get("/reservations/client/{id}")
     */
    public function getReservationsWidthDetailedForClientAction($id)
    {
        $reservations = $this->get('doctrine')
                         ->getManager('events')
                         ->getRepository('StoreBundle:Reservations')
                         ->findAllByClientIdJoinedToClient($id);
        //$data = array('users' => 'hello', 'tara' => 'tata');
        $view = $this->view($reservations, 200)
            ->setTemplate('default/getReservations.html.twig')
            ->setTemplateVar('reservations');

            return $this->handleView($view);
    }

    /**
     * @return array
     *
     */
    /**public function getReservationsWithoutDetailsForUser($id)
    {
        $reservations = $this->get('doctrine')
                         ->getManager('events')
                         ->getRepository('StoreBundle:Reservations')
                         ->findAllWithoutDetailsByUserId($id);
        //$data = array('users' => 'hello', 'tara' => 'tata');
        $view = $this->view($reservations, 200)
            ->setTemplate('default/getReservations.html.twig')
            ->setTemplateVar('reservations');

            return $this->handleView($view);
    }**/

    /**
     * @return array
     *@Get("/reservations/user/{userId}/client/{clientId}")
     *@Security("has_role('ROLE_ADMIN')")
     */
    public function getReservationsAndClientByClientAction($userId,$clientId)
    {

        $reservations1 = $this->get('doctrine')
                         ->getManager('events')
                         ->getRepository('StoreBundle:Reservations')
                         ->findAllByUserIdWithDetailsForClient($userId,$clientId);

        /*$reservations2 = $this->get('doctrine')
                         ->getManager('events')
                         ->getRepository('StoreBundle:Reservations')
                         ->findClientReservationsByClientAndUserId($userId,$clientId);*/

        //$reservations = array_merge($reservations1,$reservations2);

        //$data = array('users' => 'hello', 'tara' => 'tata');
        $view = $this->view($reservations1, 200)
            ->setTemplate('default/getReservations.html.twig')
            ->setTemplateVar('reservations');

            return $this->handleView($view);
    }

    /**
     * @return array
     *@Get("/reservation/details/{reservationId}")
     *@Security("has_role('ROLE_USER')")
     */
    public function getReservationDetails($reservationId){
        $connectedUser = $this->getUser();
        $userId = null;
        $returnCode = 200;

        if($connectedUser)
            $userId= $connectedUser->getId();

        $reservationData = $this->get('doctrine')
                         ->getManager('events')
                         ->getRepository('StoreBundle:Reservations')
                         ->findClientDataForReservationId($reservationId,$userId);
       
        if(sizeof($reservationData)<1)
            $returnCode = 400;

        $view = $this->view($reservationData, $returnCode)
            ->setTemplate('default/getReservations.html.twig')
            ->setTemplateVar('reservations');

            return $this->handleView($view);

        
    }   

    /**
     * @return array
     *@Get("/reservations/user/{userId}")
     *
     */
    public function getReservationsForUserDetailsForConnectedClient($userId)
    {

        $connectedUser = $this->getUser();
        if($connectedUser)
            $clientId = $connectedUser->getId();
        else
            $clientId = null;
       
        if($clientId == $userId)
        {
            $reservations = $this->get('doctrine')
                         ->getManager('events')
                         ->getRepository('StoreBundle:Reservations')
                         ->findAllByUserIdWithDetails($userId);
        }else
        {
         $reservations = $this->get('doctrine')
                         ->getManager('events')
                         ->getRepository('StoreBundle:Reservations')
                         ->findAllByUserIdWithDetailsForClient($userId,$clientId);
        }


        $view = $this->view($reservations, 200)
            ->setTemplate('default/getReservations.html.twig')
            ->setTemplateVar('reservations');

            return $this->handleView($view);
    }

    /**
     * @return array
     *@Get("/reservations/user/")
     *@Security("has_role('ROLE_USER')")
     */
    public function getAllReservationsForUserAction()
    {
        $connectedUser = $this->getUser();  
        $reservations = $this->get('doctrine')
                         ->getManager('events')
                         ->getRepository('StoreBundle:Reservations')
                         ->findAllByUser($connectedUser->getId());
        //$data = array('users' => 'hello', 'tara' => 'tata');
        $view = $this->view($reservations, 200)
            ->setTemplate('default/getReservations.html.twig')
            ->setTemplateVar('reservations');

            return $this->handleView($view);
    }

    /**
     * @return array
     *@Get("/auth/user/")
     *@Security("has_role('ROLE_CLIENT')")
     */
    public function getAuthenticateUserData()
    {
        /*$user = $this->get('doctrine')
                         ->getManager('events')
                         ->getRepository('StoreBundle:User')
                         ->selectOne($id);*/
        /*$user = new User();
        $encoder = $this->container->get('security.password_encoder');
        $encoded = $encoder->encodePassword($user, "test");*/
        $connectedUser = $this->getUser();    
         $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT u.id,u.username,u.email,u.roles FROM StoreBundle:User u
            WHERE  u.id = :userId');
        $query->setParameter('userId', $connectedUser->getId());
        $user = $query->getSingleResult();
     

        $view = $this->view($user, 200)
            ->setTemplate('default/getReservations.html.twig')
            ->setTemplateVar('reservations');

            return $this->handleView($view);
    }

    /**
     *
     *@Put("/edit/user")
     *@Security("has_role('ROLE_CLIENT')")
     */
    public function putUsersAction(Request $request)
    {
        $connectedUser = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        if(isset($connectedUser))
        {

            $checker_array = [
            'username' => array("regex"=>"/^[a-zA-Z0-9]+([_\s\-]?[a-zA-Z0-9])*$/","error"=>"Login must be alphanumerical"),
            'password' => array("regex"=>"((?=.*\d)(?=.*[a-z])(?=.*[@#$%.]).{6,20})","error"=>"Password must be at least 6 character , contain 1 number and 1 special char"),
            'tel' => array("regex"=>"/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/","error"=>"Tel error"),
            'email' => array("regex"=>"/[A-Z0-9a-z._%+-]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,6}/","error"=>"Email is not valid"),
            ];

            $username = $request->request->get('username');
            $password = $request->request->get('password');
            $confirmpassword = $request->request->get('confirmpassword');
            $email = $request->request->get('email');
            $existingUser = false;

            $error = $this->checker($checker_array,$request);
            $email = ($connectedUser->getEmail() != $email)?$email:null;

            $username = ($connectedUser->getUsername() != $username)?$username:null;

            if($username || $email)
                $existingUser = $this->findEmailOrLogin($email,$username);
            
            if(count($error)>0)
            {

                $data = $error;
                $view = $this->view($data, 400)
                    ->setTemplate('default/getUsers.html.twig')
                    ->setTemplateVar('users');

                return $this->handleView($view);
            }

            if($existingUser)
            {
                $data = [];
                if($email == $existingUser->getEmail())
                    $data["email"]= "Email already exists";
                if($username == $existingUser->getUsername())
                    $data["username"]= "Login already exists";

                $view = $this->view($data, 400)
                    ->setTemplate('default/getUsers.html.twig')
                    ->setTemplateVar('users');

                return $this->handleView($view);
            }

            if($confirmpassword != $password)
            {

                $data = "Password and confirm password are not the same";

                $view = $this->view($data, 400)
                    ->setTemplate('default/getUsers.html.twig')
                    ->setTemplateVar('users');

                return $this->handleView($view);
            }


            $email?$connectedUser->setEmail($email):null;
            $username?$connectedUser->setUsername($username):null;
            
            if($password && strlen($password)>=6)
            {
                $encoder = $this->container->get('security.password_encoder');
                $encoded = $encoder->encodePassword($connectedUser,$password);
                $connectedUser->setPassword($encoded);
            }


            try {
                 $em->flush();  
                 $data = "ok";
                 $code = 200;
             } catch (Exception $e) {
                
                 $data = "Error " . $e->getMessage();
                $code = 500;
             }

            $view = $this->view($data, $code)
                ->setTemplate('default/getUsers.html.twig')
                ->setTemplateVar('users');

            return $this->handleView($view);

        }else
        {
        
            $view = $this->view('Not connected', 403)
            ->setTemplate('default/getReservations.html.twig')
            ->setTemplateVar('reservations');

            return $this->handleView($view);
        }

    }

    


    protected function isFree($dateStart,$dateEnd,$userId){
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery('SELECT r.id FROM StoreBundle:Reservations r 
            WHERE   ((r.date_start >= :dateStart AND r.date_start < :dateEnd) OR (r.date_end >  :dateStart AND r.date_end <= :dateEnd)  OR 
            (r.date_start >=  :dateStart AND r.date_end <= :dateEnd) OR (r.date_start <=  :dateStart AND r.date_end >= :dateEnd)) AND r.id_user = :userId');
        $query->setParameter('dateStart', new \DateTime($dateStart));
        $query->setParameter('dateEnd', new \DateTime($dateEnd));
        $query->setParameter('userId', $userId);
        $ids = $query->getResult();

        try {

            return (count($ids)>0)?false:true;
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            return false;
        } 
    }

    protected function isEventValid($dateStart,$dateEnd,$userId){

        $params = $this->get('doctrine')
                         ->getManager('events')
                         ->getRepository('StoreBundle:Params')
                         ->findOneBy(array('idUser' => intval($userId)));

        $bookablePeriods = $params->getBookablePeriods();
        $duree = $params->getDuree();
        $start = date_create($dateStart);
        $format = 'Y-m-d H:i:s';
        $end = date_create($dateEnd);
        $timestampstart = date_timestamp_get($start);
        $timestampend = date_timestamp_get($end);
        $startday = date('w',$timestampstart);
        $endday = date('w',$timestampend);
        $hourstart = date('H',$timestampstart);
        $hourend = date('H',$timestampend);
        $currentPeriod = $bookablePeriods[$startday];
        $periodstartAM = date_create();
        $periodendAM = date_create();
        $periodstartPM = date_create();
        $periodendPM = date_create();
        /*echo date_format($start,$format);
        echo date_format($end,$format);*/

        $periods = explode(";",$currentPeriod);
        if(isset($periods[0]) && strlen($periods[0])>0)
        {
            $AMfromarray = explode(":",$periods[0]);
            $AMtoarray = explode(":",$periods[1]);
            $periodstartAM = clone $start;
            date_time_set ($periodstartAM,$AMfromarray[0],$AMfromarray[1]);
            $periodendAM = clone $start;
            date_time_set ($periodendAM,$AMtoarray[0],$AMtoarray[1]);
        }
        if(isset($periods[2]) && strlen($periods[2])>0 )
        {
            $PMfromarray = explode(":",$periods[2]);
            $PMtoarray = explode(":",$periods[3]);
            $periodstartPM = clone $end;
            date_time_set ($periodstartPM,$PMfromarray[0],$PMfromarray[1]);
            $periodendPM = clone $end;
            date_time_set ($periodendPM,$PMtoarray[0],$PMtoarray[1]);
            /*echo ("^^^^^^^^^".$currentPeriod."--->".$PMfromarray[0].":".$PMfromarray[1]."<-----");
            echo ("--->".$PMtoarray[0].":".$PMtoarray[1]."<-----");
            echo "********";*/
        }

        
        

        $checkDay = ($startday==$endday);
        $checkstartend = $start < $end;
        
        $checkPeriod = ($start >= $periodstartAM && $end <= $periodendAM) || ($start >= $periodstartPM && $end <= $periodendPM);
        /*echo "(".date_format($start,$format) .">=". date_format($periodstartAM,$format) ."&&". date_format($end,$format).
        " <= ".date_format($periodendAM,$format)." || (".date_format($start,$format)." >= ".date_format($periodstartPM,$format)." && ".
        date_format($end,$format)." <= ".date_format($periodendPM,$format)."";*/
        $subTime = $timestampend - $timestampstart;

        $minutes = ($subTime/60);
        /*echo "minutes: $minutes $subTime";*/
        $checkDuree = ($minutes <= $duree);
        /*echo ("res : ".$checkDay ."&&". $checkstartend ."&&". $checkPeriod ."&&". $checkDuree);*/
        return $checkDay && $checkstartend && $checkPeriod && $checkDuree;


    }

    /**
     * Book an event by posting form data
     * check if event is free, then book it
     * @return array
     * @Security("has_role('ROLE_CLIENT')")
     */
    public function postEventsAction(Request $request)
    {
        $connectedUser = $this->getUser();  
        $clientId = $connectedUser->getId();

        if($connectedUser->getRoles()[0] == 'ROLE_USER')
        {
            $userId = $clientId;
        }else
        {
            $userId = $request->request->get('userid');
        }
        $dateStart = $request->request->get('dateStart');
        $dateEnd = $request->request->get('dateEnd');
        $title = $request->request->get('title');
       

       if(!$this->isEventValid($dateStart,$dateEnd,$userId))
       {
                $view = $this->view("Invalid reservation", 400);
                return $this->handleView($view);
       }else if($this->isFree($dateStart,$dateEnd,$userId))
        {
                $reservation = new Reservations();
                echo("after");
                $dateTimeStart = new \DateTime($dateStart);
                $dateTimeEnd = new \DateTime($dateEnd);
                $reservation->setDateStart($dateTimeStart);
                $reservation->setDateEnd($dateTimeEnd);
                $reservation->setTitle($title);
                $reservation->setIdUser($userId);
                $reservation->setIdClient($clientId);
                $reservation->setDuree(30);
                
                $em = $this->getDoctrine()->getManager();

                $em->persist($reservation);
                $em->flush();

                $data = "ok";
                $view = $this->view($data, 200)
                    ->setTemplate('default/getUsers.html.twig')
                    ->setTemplateVar('users');

                return $this->handleView($view);
            }else
            {
                $view = $this->view("Date is not free", 400);
                return $this->handleView($view);
            }
        
    }

    /**
     * TODO
     * @return array
     *
     */
    public function putEventsAction()
    {
        $data = array('users' => 'hello', 'tara' => 'tata');
        $view = $this->view($data, 200)
            ->setTemplate('default/getUsers.html.twig')
            ->setTemplateVar('users');

            return $this->handleView($view);
    }

    /**
     * TODO
     * @return array
     *
     */
    public function deleteEventsAction()
    {
        $data = array('users' => 'hello', 'tara' => 'tata');
        $view = $this->view($data, 200)
            ->setTemplate('default/getUsers.html.twig')
            ->setTemplateVar('users');

            return $this->handleView($view);
    }

    /**
     * TODO
     * 
     * @Put("/params/user")
     * @Security("has_role('ROLE_USER')")
     */
    public function putParamsAction(Request $request){
        $connectedUser = $this->getUser();
        if($connectedUser)
        {
            $em = $this->getDoctrine()->getManager();
            $idUser = $connectedUser->getId();
            $bookable_periode = $request->request->get('bookable_period');
            $bookable = 1;
            $duree = $request->request->get('duree');
            $message = $request->request->get('message');

            //check si il y a une entree pour le user id
            $params = $em->getRepository('StoreBundle:Params')->findOneByidUser($idUser);
            //récupère par son ID le params a modifier (nécessaire il semble pour faire un PUT dans doctrine)
            $params = $em->getRepository('StoreBundle:Params')->find($params->getId());

            if (!$params) {
                throw $this->createNotFoundException(
                    'No params found for userid : '.$idUser
                );
            }

            $params->setDuree($duree);
            $params->setBookablePeriods(array($bookable_periode));
            $params->setMessage($message);

            try {
                 $em->flush();  
                 $data = "ok";
                 $code = 200;
             } catch (Exception $e) {

                 $data = $e->getMessage();
                $code = 500;
             }

            
            $view = $this->view($data, $code)
                ->setTemplate('default/getUsers.html.twig')
                ->setTemplateVar('users');

            return $this->handleView($view);
        }else
        {
        
        $view = $this->view('Not connected', 403)
            ->setTemplate('default/getReservations.html.twig')
            ->setTemplateVar('reservations');

            return $this->handleView($view);
        }
        
    }

    public function findEmailOrLogin($email,$username){
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery('SELECT u FROM StoreBundle:User u WHERE u.email = :email OR u.username = :username');
        $query->setParameter('email', $email);
        $query->setParameter('username', $username);

        $user = $query->getOneOrNullResult();

        return $user;

    }

    protected function checker($checker,$request){
        $error = [];
        foreach($checker as $key => $value)
        {
            if($data = $request->request->get($key))
            {
                $regex = $value["regex"];
                (preg_match($regex, $data)===1)?null:$error[$key]=$value["error"];
            }
        }

        return $error;
    }
    /**
     * TODO
     * @return array
     *
     */
    public function postUsersAction(Request $request){
        //((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%]).{6,20})
        $checker_array = [
            'username' => array("regex"=>"/^[a-zA-Z0-9]+([_\s\-]?[a-zA-Z0-9])*$/","error"=>"Login must be alphanumerical"),
            'password' => array("regex"=>"((?=.*\d)(?=.*[a-z])(?=.*[@#$%.]).{6,20})","error"=>"Password must be at least 6 character , contain 1 number and 1 special char"),
            'tel' => array("regex"=>"/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/","error"=>"Tel error"),
            'email' => array("regex"=>"/[A-Z0-9a-z._%+-]@[A-Za-z0-9.-]\\.[A-Za-z]{2,6}/","error"=>"Email is not valid"),
            ];

        $username = $request->request->get('username');
        $password = $request->request->get('password');
        $email = $request->request->get('email');
        $roles = explode(",",$request->request->get('roles'));
        $error = $this->checker($checker_array,$request);
        $existingUser = $this->findEmailOrLogin($email,$username);
        if(count($error)>0)
        {

            $data = $error;
            $view = $this->view($data, 400)
                ->setTemplate('default/getUsers.html.twig')
                ->setTemplateVar('users');

            return $this->handleView($view);
        }

        if($existingUser)
        {
            $data = [];
            if($email == $existingUser->getEmail())
                $data["email"]= "Email already exists";
            if($username == $existingUser->getUsername())
                $data["username"]= "Login already exists";

            $view = $this->view($data, 400)
                ->setTemplate('default/getUsers.html.twig')
                ->setTemplateVar('users');

            return $this->handleView($view);
        }

        $user = new User();
        //encode password
        $encoder = $this->container->get('security.password_encoder');
        $encoded = $encoder->encodePassword($user,$password);

        $user->setUsername($username);
        $user->setPassword($encoded);
        $user->setRoles($roles);
        $user->setEmail($email);
                
        $em = $this->getDoctrine()->getManager();

        $em->persist($user);
        $em->flush();

        $params = New Params();
        $params->setDuree("30");
        $params->setBookablePeriods(";;;,08:00;12:00;14:30;18:30,08:00;12:00;14:30;18:30,08:00;12:00;14:30;18:30,08:00;12:00;14:30;18:30,08:00;12:00;14:30;18:30,;;;");
        $params->setMessage("");
        $params->setBookable(1);
        $params->setIdUser($user->getId());
        $em->flush();

        $data = "ok";
        $view = $this->view($user, 200)
            ->setTemplate('default/getUsers.html.twig')
            ->setTemplateVar('users');

        return $this->handleView($view);

    }
}
