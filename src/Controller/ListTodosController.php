<?php

namespace App\Controller;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Domain\Services\GetListTodosService;
class ListTodosController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/list/{listId}", name="list_todos")
     * @param $listId
     */
    public function index($listId): Response
    {
        try {
            $savedTodos = new GetListTodosService($this->entityManager, $listId);
            return $this->render('list_todos/index.html.twig', [
                'savedTodos' => $savedTodos->execute()
            ]);
        } catch (\Exception $exception) {
            $this->createErrorMessage($exception);
            return $this->render('list_todos/index.html.twig', [

            ]);
        }
    }

    private function createErrorMessage(\Exception $exception)
    {
        $errorMessage=$exception->getMessage();
        $this->addFlash('error', $errorMessage);
    }
}
