<?php

namespace triguk\AuthenticationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use AppBundle\Entity\User;

class AuthenticationController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {

        
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $items=['_username'=>$lastUsername,'_password'=>''];
        $form = $this->createForm('triguk\AuthenticationBundle\Form\LoginType', $items);
        /*
        $form=$this->get('form.factory')
            ->createNamedBuilder(null, FormType::class, $items)
            ->add('_username', TextType::class)
            ->add('_password', PasswordType::class)
            ->add('Войти', ButtonType::class)
            ->getForm();
        */
        return $this->render('trigukAuthenticationBundle:login:login.html.twig', array(
            'form'=>$form->createView(),
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }
    
    /**
     * @Route("/register", name="register")
     */
    public function registerAction(Request $request)
    {

        
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $items=[];
        $form = $this->createForm('triguk\AuthenticationBundle\Form\RegisterType', $items);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user=new User();
            $data = $form->getData();
            $user->setUsername($data['username']);
            $user->setPassword($this->get('security.password_encoder')
                ->encodePassword($user, $data['plainPassword']));
            $user->setEmail($data['email']);     
            $user->setIsActive(true);           
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();            
            return $this->redirectToRoute('login');
        }
        return $this->render('trigukAuthenticationBundle:register:register.html.twig', array(
            'form'=>$form->createView(),
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }    
}
