<?php

namespace App\Controller\api\v1\todos;

use App\Entity\Todo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


Class UpdateTodoCompletionStatusController extends AbstractController
{
    private $todo;

    /**
     * @Route("api/v1/todos/{todoId}", name="updateTodoCompletionStatus" )
     * @param $todoId,
     * @return JsonResponse
     */
    function UpdateTodoCompletionStatus ($todoId)
    {
        try {
           $this->todo = $this->getTodo($todoId);
           if ($this->checkIfStatusIsIncomplete()) {
               $this->setTodoStatusToComplete();
               $this->updateTodoStatus();
               $successResponse = $this->createJsonResponse();
               return $successResponse;
           }
           $this->setTodoStatusToIncomplete();
           $this->updateTodoStatus();
           $successResponse = $this->createJsonResponse();
            return $successResponse;
        } catch (\Exception $exception) {
            $jsonResponseWithError = $this->createJsonResponseWithError($exception);
            return $jsonResponseWithError;
        }
    }
    private function createJsonResponseWithError(\Exception $exception)
    {
        $response = new JsonResponse();
        $response->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);
        return $response;
    }

    private function getTodo($todoId)
    {
        return $this->getDoctrine()
            ->getRepository(Todo::class)
            ->findOneBy(['id'=> $todoId]);
    }

    private function checkIfStatusIsIncomplete()
    {
        return $this->todo->getStatus() == "incomplete";
    }

    private function createJsonResponse()
    {
        $response = new JsonResponse;
        return $response->setStatusCode(JsonResponse::HTTP_NO_CONTENT);

    }

    private function setTodoStatusToComplete()
    {
        $this->todo->setStatus(Todo::COMPLETE_STATUS);
    }

    private function setTodoStatusToIncomplete()
    {
        $this->todo->setStatus(Todo::INCOMPLETE_STATUS);
    }

    private function updateTodoStatus()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($this->todo);
        $entityManager->flush();
    }
}