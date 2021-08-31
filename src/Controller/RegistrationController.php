<?php

namespace App\Controller;

use App\Entity\TodosList;
use App\Entity\User;
use App\Entity\UserRole;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    private $user;
    private $form;
    private $userRole;
    private $newList;

    /**
     * @Route("/register", name="app_register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        try {
            $this->createNewUser();
            $this->generateForm($request);
            if ($this->form->isSubmitted() && $this->form->isValid())
            {
                $this->setUserGenericRole();
                $this->setUserPassword($passwordEncoder);
                $this->saveNewUser();
                $this->createNewUserList();
                $this->createSuccessfullyRegisteredNewUser();
            }
            return $this->render('registration/register.html.twig', [
                'registrationForm' => $this->form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->createErrorMessage($exception);
            return $this->render('registration/register.html.twig', [
                'registrationForm' => $this->form->createView(),
            ]);
        }



    }

    private function createNewUser()
    {
        $this->user = new User();
    }

    private function generateForm(Request $request)
    {
        $this->form = $this->createForm(RegistrationFormType::class, $this->user);
        $this->form->handleRequest($request);
    }

    private function setUserPassword($passwordEncoder)
    {
        $this->user->setPassword(
            $passwordEncoder->encodePassword(
                $this->user,
                $this->form->get('plainPassword')->getData()
            )
        );
    }

    private function setUserGenericRole()
    {
        $this->userRole = $this->getUserRole();
        $this->setRole();
    }

    private function getUserRole()
    {
        return $this->getDoctrine()
            ->getRepository(UserRole::class)
            ->findOneBy(['id'=>UserRole::ROLE_USER]);
    }

    private function setRole()
    {
        $this->user->setRoles(["ROLE_" . $this->userRole->getName()]);
    }

    private function saveNewUser()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($this->user);
        $entityManager->flush();
    }

    private function createNewUserList()
    {
        $this->createNewList();
        $listName = $this->createListName();
        $this->setListName($listName);
        $this->setUserId();
        $this->saveNewUserList();
    }

    private function createNewList()
    {
        $this->newList = new TodosList();
    }

    private function createListName()
    {
        $email = explode("@", $this->user->getEmail());
        return $email[0];
    }

    private function setListName($listName)
    {

        $this->newList->setName($listName . "_list");
    }
    private function setUserId()
    {
        $this->newList->setUserId($this->user->getId());
    }
    private function saveNewUserList()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($this->newList);
        $entityManager->flush();
    }

    private function createSuccessfullyRegisteredNewUser()
    {
        $this->addFlash('success', 'El nuevo usuario se ha registrado con éxito');
    }

    private function createErrorMessage(\Exception $exception)
    {
        $this->addFlash('error', 'Se ha producido un error al registrar el usuario');
    }
}
