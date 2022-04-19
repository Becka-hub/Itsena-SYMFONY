<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\RegistrationFormType;
use App\Service\Service;

class LoginController extends AbstractController
{

    private Service $service;


    public function __construct(Service $service) {

      $this->service = $service;
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $panierWithData = $this->service->getFullCart();
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class,$user);

        if ($this->getUser()) {
            return $this->redirectToRoute('app_payment');
        }
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', 
        [
        'last_username' => $lastUsername, 
        'error' => $error,
        'registrationForm' => $form->createView(),
        'panier'=>$panierWithData
        ]
    );
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
