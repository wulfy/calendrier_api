<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\FOSRestController;
use StoreBundle\Entity\Reservations;
use StoreBundle\Entity\Params;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
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
        
        $reservations1 = $this->get('doctrine')
                         ->getManager('events')
                         ->getRepository('StoreBundle:Reservations')
                         ->findAllByUserIdWithDetailsForClient($userId,$clientId);

        $view = $this->view($reservations1, 200)
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

        $view = $this->view($connectedUser, 200)
            ->setTemplate('default/getReservations.html.twig')
            ->setTemplateVar('reservations');

            return $this->handleView($view);
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
       
        
        if($this->isFree($dateStart,$dateEnd,$userId))
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
     * @return array
     *
     */
    public function postParamsAction(Request $request){
        $bookable_periode = $request->request->get('bookable_period');
        $bookable = $request->request->get('bookable');
        $duree = $request->request->get('duree');
        $message = $request->request->get('message');

        $user = new User();
        //encode password
        $encoder = $this->container->get('security.password_encoder');
        $encoded = $encoder->encodePassword($user,$password);

        $user->setUsername($username);
        $user->setPassword($encoded);
        $user->setRoles($roles);

                
        $em = $this->getDoctrine()->getManager();

        $em->persist($user);
        $em->flush();

        $data = "ok";
        $view = $this->view($user, 200)
            ->setTemplate('default/getUsers.html.twig')
            ->setTemplateVar('users');

        return $this->handleView($view);
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
            'tel' => array("regex"=>"/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/","error"=>"Tel error")
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

        $data = "ok";
        $view = $this->view($user, 200)
            ->setTemplate('default/getUsers.html.twig')
            ->setTemplateVar('users');

        return $this->handleView($view);

    }
}
