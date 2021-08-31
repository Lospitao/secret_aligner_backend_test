<?php

namespace App\Controller\api\v1\lists;

use App\Entity\Todo;
use App\Entity\TodosList;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


Class UpdateListUserController extends AbstractController
{
    private $list;
    private $newUser;
    /**
     * @Route("api/v1/lists/{listId}", name="updateUserList" )
     * @param $listId,
     * @return JsonResponse
     */
    function UpdateListUser (Request $request, $listId)
    {
        try {
            $this->list = $this->getList($listId);
            $this->checkIfNewUserExists($request);
            $this->newUser = $this->getNewUser($request);
            $this->setNewUserToList();
            $this->updateListWithNewUser();
            $successResponse = $this->createJsonResponse();
            return $successResponse;
        } catch (\Exception $exception) {
            $jsonResponseWithError = $this->createJsonResponseWithError($exception);
            return $jsonResponseWithError;
        }
    }
    private function createJsonResponse()
    {
        $response = new JsonResponse;
        return $response->setStatusCode(JsonResponse::HTTP_NO_CONTENT);

    }
    private function createJsonResponseWithError(\Exception $exception)
    {
        $response = new JsonResponse();
        $response->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);
        return $response;
    }

    private function getList($listId)
    {
        return $this->getDoctrine()
            ->getRepository(TodosList::class)
            ->findOneBy(['id'=> $listId]);
    }

    private function getNewUser($request)
    {
        return $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['email'=> $request->request->get('newUserEmail')]);
    }

    private function setNewUserToList()
    {
        $this->list->setUserId($this->newUser->getId());
    }

    private function updateListWithNewUser()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($this->list);
        $entityManager->flush();
    }

    private function addUswerDoesNotExistMessage()
    {
        $this->addFlash('error', 'El correo especificado no pertenece a ningún usuario');
    }

    private function checkIfNewUserExists($request)
    {
        if ($this->userDoesNotExist($request)) {
            $this->addUswerDoesNotExistMessage();
        }
    }

    private function userDoesNotExist($request)
    {
        return $this->getDoctrine()
                ->getRepository(User::class)
                ->findOneBy(['email'=> $request->request->get('newUserEmail')]) === null;
    }
}