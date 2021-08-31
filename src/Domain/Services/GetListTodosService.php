<?php


namespace App\Domain\Services;


use App\Entity\TodosList;
use Doctrine\ORM\EntityManagerInterface;

class GetListTodosService
{
    private $listId;
    private $entityManager;
    private $savedTodosData;
    /**
     * GetListTodosService constructor.
     * @param EntityManagerInterface $entityManager
     * @param $listId
     */
    public function __construct(\Doctrine\ORM\EntityManagerInterface $entityManager, $listId)
    {
        $this->entityManager = $entityManager;
        $this->listId = $listId;
    }
    public function execute ()
    {
        try {
            $list = $this->getList();
            $todos = $this->getAllTodos($list);
            foreach($todos as $todo) {
                $this->savedTodosData = $this->getSavedTodosData($todo);
            }
            return $this->savedTodosData;
        } catch (\Exception $exception) {
            return $this->createErrorMessage($exception);
        }
    }

    private function getList()
    {
        return $this->entityManager
        ->getRepository(TodosList::class)
        ->findOneBy(['id'=> $this->listId]);
    }

    private function getAllTodos($list)
    {
        return $list->getTodos();
    }

    private function getSavedTodosData($todo)
    {
        $this->savedTodosData[$todo->getId()] = [
            'name' => $todo->getName(),
            'created_at' => $todo->getCreatedAt()->format('d-m-Y'),
            'expires_at' => $todo->getExpiresAt()->format('d-m-Y'),
            'status' => $todo->getStatus(),
            'id' => $todo->getId()
        ];
        return $this->savedTodosData;
    }

    private function createErrorMessage(\Exception $exception)
    {
        $errorMessage=$exception->getMessage();
        return $errorMessage;
    }
}