<?php
namespace App\Domain\Services;
use App\Entity\Todo;
use App\Entity\TodosList;

class getTodosService
{
    private $todo;
    private $savedTodosData;
    private $userId;
    public function __construct($entityManager, $userId)
    {
        $this->entityManager = $entityManager;
        $this->userId = $userId;
    }
    public function execute ()
    {
        try {
            $userLists = $this->getUserLists();
            foreach ($userLists as $list) {
                $todos = $this->getAllTodos($list);
                foreach($todos as $this->todo) {
                    $this->savedTodosData = $this->getSavedTodosData();
                }
            }
            return $this->savedTodosData;
        } catch (\Exception $exception) {
            return $this->createErrorMessage($exception);
        }
    }
    private function getUserLists()
    {
        return $this->entityManager
            ->getRepository(TodosList::class)
            ->findBy(['userId'=> $this->userId]);
    }
    private function getAllTodos($list)
    {
        return $this->entityManager
            ->getRepository(Todo::class)
            ->findBy(['List'=> $list]);
    }

    private function getSavedTodosData()
    {
        $this->savedTodosData[$this->todo->getId()] = [
            'name' => $this->todo->getName(),
            'created_at' => $this->todo->getCreatedAt()->format('d-m-Y'),
            'expires_at' => $this->todo->getExpiresAt()->format('d-m-Y'),
            'status' => $this->todo->getStatus(),
            'id' => $this->todo->getId()
        ];
        return $this->savedTodosData;
    }
    private function createErrorMessage(\Exception $exception)
    {
        $errorMessage=$exception->getMessage();
        return $errorMessage;
    }
}