<?php
namespace App\Domain\Services;
use App\Entity\Todo;
use App\Entity\User;
use Proxies\__CG__\App\Entity\TodosList;

class getAllUserListsService
{
    private $entityManager;
    private $listData;
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function execute ()
    {
        try {
            $lists = $this->getAllLists();
            foreach ($lists as $list)
            {
                $this->listData = $this->getListData($list);
            }
            return $this->listData;
        } catch (\Exception $exception) {
            return $this->createErrorMessage($exception);
        }
    }
    private function createErrorMessage(\Exception $exception)
    {
        $errorMessage=$exception->getMessage();
        return $errorMessage;
    }

    private function getAllLists()
    {
        return $this->entityManager
            ->getRepository(\App\Entity\TodosList::class)
            ->findAll();
    }

    private function getListData($list)
    {
        $this->listData[$list->getId()] = [
            'listId' => $list->getId(),
            'listName' => $list->getName(),
            'listUserId' =>$list->getUserId(),
            'listUserEmail' => $this->getListUserEmail($list),
        ];
        return $this->listData;
    }

    private function getListUserEmail($list)
    {
        $user = $this->entityManager
        ->getRepository(User::class)
        ->findOneBy(['id' => $list->getUserId()]);
        return $user->getEmail();

    }

}