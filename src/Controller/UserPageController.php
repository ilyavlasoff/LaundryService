<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserPageController extends AbstractController
{
    /**
     * @Route("/me", name="my_page")
     */
    public function displayUserPersonalPage()
    {
        /** @var User $user */
        $user = $this->getUser();

        /** @var UserRepository $userRepos */
        $userRepos = $this->getDoctrine()->getRepository(User::class);


    }
}