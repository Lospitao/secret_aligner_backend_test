<?php
namespace App\Domain\Services;
use App\Entity\Todo;
class getTodosService
{
    private $todo;
    private $savedTodosData;
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function execute ()
    {
        try {
            $todos = $this->getAllTodos();
            foreach($todos as $this->todo) {
                $this->savedTodosData = $this->getSavedTodosData();
            }
            return $this->savedTodosData;
        } catch (\Exception $exception) {
            return $this->createErrorMessage($exception);
        }
    }

    private function getAllTodos()
    {
        return $this->entityManager
            ->getRepository(Todo::class)
            ->findAll();
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