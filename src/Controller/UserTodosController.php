<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Entity\TodosList;
use App\Form\AddTodoFormType;
use Doctrine\DBAL\Driver\PDO\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Domain\Services\getTodosService;
class UserTodosController extends AbstractController
{

    private $todo;
    private $form;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("user/{userId}/todos", name="user_todos")
     */
    public function index(Request $request): Response
    {
        try {
            $this->generateForm($request);
            $this->checkIfUserHasAList();
            if ($this->form->isSubmitted() && $this->form->isValid())
            {
                $this->createNewTodoService($request);
                $this->createSuccessfullyCreatedTodoMessage();
            }
            $userId= $this->getUser()->getId();

            $savedTodos = new GetTodosService($this->entityManager, $userId);
            return $this->render('user_todos/index.html.twig', [
                'form' => $this->form->createView(),
                'savedTodos' => $savedTodos->execute()
            ]);
        } catch (\Exception $exception) {
            $this->createErrorMessage($exception);
            return $this->render('user_todos/index.html.twig', [
                'form' => $this->form->createView(),
            ]);
        }
    }


    private function createErrorMessage($exception)
    {
        $errorMessage=$exception->getMessage();
        $this->addFlash('error', $errorMessage);
    }

    private function generateForm($request)
    {
        $this->form = $this->createForm(AddTodoFormType::class, $this->todo);
        $this->form->handleRequest($request);
    }

    private function createNewTodoService($request)
    {
        $this->createNewTodo();
        $this->setTodoName();
        $this->setTodoCreationDate();
        $this->setTodoExpirationDate();
        $this->setTodoStatus();
        $todoList = $this->getTodoList();
        $this->setTodoList($todoList);
        $this->saveNewTodo();
    }
    private function createNewTodo()
    {
        $this->todo = new Todo();
    }
    private function setTodoName()
    {
        $this->todo->setName($this->form->get('name')->getData());
    }
    private function setTodoCreationDate()
    {
        $this->todo->setCreatedAt(new \DateTime());
    }
    private function setTodoExpirationDate()
    {
        $this->todo->setExpiresAt($this->form->get('expires_at')->getData());
    }
    private function setTodoStatus()
    {
        $this->todo->setStatus(TODO::INCOMPLETE_STATUS);
    }

    private function getTodoList()
    {
        $email = explode("@", $this->getUser()->getEmail());
        return $this->getDoctrine()
            ->getRepository(TodosList::class)
            ->findOneBy(['name'=> $email[0]."_list", 'userId'=> $this->getUser()->getId()]);
    }

    private function setTodoList($todoList)
    {
        $this->todo->setList($todoList);
    }
    private function saveNewTodo()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($this->todo);
        $entityManager->flush();
    }

    private function createSuccessfullyCreatedTodoMessage()
    {
        $this->addFlash('success', 'Se ha añadido una nueva tarea');
    }

    private function throwNoListForThisUsermessage()
    {
        $this->addFlash('error', "La lista de este usuario se ha transferido a otro usuario. Contacta con 
        el administrador para que te asigne una lista");
    }

    private function checkIfUserHasAList()
    {
        $userList = $this->getDoctrine()
            ->getRepository(TodosList::class)
            ->findOneBy(['userId'=> $this->getUser()->getId()]);
        if ($userList === null) {
            $this->throwUserDoesNotHaveAListMessage();
        }
    }

    private function throwUserDoesNotHaveAListMessage()
    {
        $this->addFlash('error', 'El administrador ha asignado tu lista a otro usuario. Por favor, contacta con el administrador para que se te asigne una nueva lista.');
    }


}
