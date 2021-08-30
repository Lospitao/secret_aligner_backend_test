<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Form\AddTodoFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Domain\Services\getTodosService;
class TodosListsController extends AbstractController
{

    private $todo;
    private $form;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/list/todos", name="todos")
     */
    public function index(Request $request): Response
    {
        try {
            $this->generateForm($request);
            if ($this->form->isSubmitted() && $this->form->isValid())
            {
                $this->createNewTodoService($request);
                $this->createSuccessfullyCreatedTodoMessage();
            }
            $savedTodos = new GetTodosService($this->entityManager);
            return $this->render('todos_lists/index.html.twig', [
                'form' => $this->form->createView(),
                'savedTodos' => $savedTodos->execute()
            ]);
        } catch (\Exception $exception) {
            $this->createErrorMessage($exception);
            return $this->render('todos_lists/index.html.twig', [
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
}
