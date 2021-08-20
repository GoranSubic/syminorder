<?php

namespace App\Controller;

use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
       if ($this->getUser()) {
           return $this->redirectToRoute('app_indications');
       }

        $configDisplaySocialLogin = $this->getParameter('syminorder.login.configDisplaySocialLogin');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
          'last_username' => $lastUsername,
          'configDisplaySocialLogin' => $configDisplaySocialLogin,
          'error' => $error
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/loginget/{uname}/$u53rm4n3@/{pass}/12345678", name="app_login_get")
     *
     */
    public function loginGet(Request $request, $uname, $pass, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $formAuthenticator, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('App\Entity\User');
        $user = $repo->findOneBy(['username' => $uname]);

        if ($user && ($passwordEncoder->isPasswordValid($user, $pass))) {
            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $formAuthenticator,
                'main'
            );
        }

        $error = "Žao nam je, ipak niste ulogovani!";
        return $this->redirectToRoute('app_indications', ['error' => $error]);
    }

}
