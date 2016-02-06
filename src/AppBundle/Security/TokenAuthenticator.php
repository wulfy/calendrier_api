<?php

// src/AppBundle/Security/TokenAuthenticator.php
namespace AppBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Doctrine\ORM\EntityManager;
use StoreBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    private $em;
    private $encoder;
    private $tokenStorage;

    public function __construct(EntityManager $em, UserPasswordEncoderInterface $encoder, TokenStorage $tokenStorage)
    {
        $this->em = $em;
        $this->encoder = $encoder;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Called on every request. Return whatever credentials you want,
     * or null to stop authentication.
     */
    public function getCredentials(Request $request)
    {
        $login = null;
        $password = null;
        $token = null;


        if (!$token = $request->headers->get('X-AUTH-TOKEN')) {
            // no token? Try login/password
            $login = $request->headers->get('login');
            $password = $request->headers->get('password');

            if (!$login || !$password ) {
                
                if(!$connectedUser = $this->tokenStorage->getToken())
                {
                    // no valid identification, byebye

                    return;
                }
                
            }
        }


        

        // What you return here will be passed to getUser() as $credentials
        return array(
            'token' => $token,
            'login' =>$login,
            'password'=>$password,
        );
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {

        if($credentials['token'])
        {
            $apiKey = $credentials['token'];

            // if null, authentication will fail
         // if a User object, checkCredentials() is called
         return $this->em->getRepository('StoreBundle:User')
                ->findOneBy(array('apiKey' => $apiKey));
        }else if($credentials['login'])
        {
            $login = $credentials['login'];

            $user = $this->em->getRepository('StoreBundle:User')
                ->findOneBy(array('username' => $login));

            $plainPassword = $credentials['password'];
            //$encoder = $this->container->get('security.password_encoder');

            if($user){
                if (password_verify($plainPassword, $user->getPassword())) {
                        return $user;
                }
            }
        }else if($this->tokenStorage->getToken())
        {
            if($connectedUser = $this->tokenStorage->getToken()->getUser())
                return $connectedUser;
        }

        return null;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // check credentials - e.g. make sure the password is valid
        // no credential check is needed in this case

        // return true to cause authentication success
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        /*$username = ...;
        $password = ...;

        $unauthenticatedToken = new UsernamePasswordToken(
            $username,
            $password,
            $this->providerKey
        );

        $authenticatedToken = $this
            ->authenticationManager
            ->authenticate($unauthenticatedToken);*/

        $this->tokenStorage->setToken($token);

        // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = array(
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        );

        return new JsonResponse($data, 403);
    }

    /**
     * Called when authentication is needed, but it's not sent
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = array(
            // you might translate this message
            'message' => 'Authentication Required'
        );

        return new JsonResponse($data, 401);
    }

    public function supportsRememberMe()
    {
        return false;
    }
}