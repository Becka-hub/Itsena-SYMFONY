<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use App\Service\Service;

class RegistrationController extends AbstractController
{
    private Service $service;
    private EntityManagerInterface $entityManager;
    private FlashBagInterface $flashMessage;
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $hasher;

    public function __construct(
        Service $service,
        EntityManagerInterface $entityManager,
        FlashBagInterface $flashMessage,
        UserRepository $userRepository,
        UserPasswordHasherInterface $hasher
    ) {
        $this->service = $service;
        $this->entityManager = $entityManager;
        $this->flashMessage = $flashMessage;
        $this->userRepository=$userRepository;
        $this->hasher=$hasher;
    }


    #[Route('/register', name: 'app_register')]
    public function register(Request $request): Response
    {
 
        $name = $request->request->get('registration_form')['name'];
        $fname = $request->request->get('registration_form')['firstName'];
        $adress = $request->request->get('registration_form')['adress'];
        $email = $request->request->get('registration_form')['email'];
        $password = $request->request->get('registration_form')['plainPassword']['first'];
        $Cpassword = $request->request->get('registration_form')['plainPassword']['second'];
        $photo = $request->files->get('registration_form')['photo'];

        $extension = $photo->guessExtension();

        $user = $this->userRepository->findOneBy(["email" => $email]);

        if ($user) {

            $this->flashMessage->add("error", "Email already used");

            return $this->redirectToRoute('app_login');
        }
 
        

        if(strlen($password) < 6){
            $this->flashMessage->add("error", "Accepted password min character 6 !");
            return $this->redirectToRoute('app_login');
        }

        if (isset($extension) && ($extension !== "png" && $extension !== "jpg" && $extension !== "jpeg")) {

            $this->flashMessage->add("error", "Invalid format file! Format photo accepted png, jpg, jpeg");

            return $this->redirectToRoute('app_login');
        } else {

            if($password !== $Cpassword){
                $this->flashMessage->add("error", "Accepted confirm password equal password ");
                return $this->redirectToRoute('app_login');
            }else{
                $photoName = $this->service->uploadFile($photo, $this->getParameter('user_directory'));

                $users = new User();
                $users->setName($name);
                $users->setFirstName($fname);
                $users->setAdress($adress);
                $users->setEmail($email);
                $users->setRoles(["ROLE_USER"]);
                $users->setPassword(
                    $this->hasher->hashPassword(
                        $users,
                        $password
                    )
                    );
                $users->setPhoto($photoName);
    
                $this->entityManager->persist($users);
                $this->entityManager->flush();

                $this->flashMessage->add("success", "Register success!!!");
                return $this->redirectToRoute('app_login');
            }
        }
    }
}
