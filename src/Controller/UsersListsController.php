<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Domain\Services\getAllUserListsService;
class UsersListsController extends AbstractController
{
    private $usersData;
    private $user;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/users/lists", name="users_lists")
     */
    public function index(): Response
    {
        try {
           $userLists = new getAllUserListsService($this->entityManager);
            return $this->render('users_lists/index.html.twig', [
                'controller_name' => 'UsersListsController',
                'userListsData' => $userLists->execute(),
            ]);
        }
        catch (\Exception $exception) {
            $this->createErrorMessage($exception);
        return $this->render('users_lists/index.html.twig', [
            'controller_name' => 'UsersListsController',
        ]);
    }
}

    private function createErrorMessage(\Exception $exception)
    {
        $errorMessage=$exception->getMessage();
        $this->addFlash('error', $errorMessage);
    }

    private function getAllUsers()
    {
        return $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();
    }

    private function getUserData()
    {
        $this->usersData[$this->user->getId()] = [
            'name' => $this->user->getEmail(),
        ];
    }
}
