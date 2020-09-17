<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\User;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
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
        /** @var ClientRepository $clientRepos */
        $clientRepos = $this->getDoctrine()->getRepository(Client::class);
        $client = $clientRepos->findOneBy(['user' => $user->getId()]);
        if (! $client) {
            throw new Exception('Can not find user');
        }
        $ordersTotalProperties = $clientRepos->getUserOrdersProperties($client);
        $ordersTotalCount = $ordersTotalProperties[0]['count'];
        $ordersTotalSum = $ordersTotalProperties[0]['sumPrice'];
        $ordersMonthProperties = $clientRepos->getUserOrdersProperties($client, 'month');
        $ordersMonthCount = $ordersMonthProperties[0]['count'];
        $ordersMonthSum = $ordersMonthProperties[0]['sumPrice'];

        $lastOrders = $clientRepos->getLastNOrdersOfClient($client, 5);

        return $this->render('pages/user_page.html.twig', [
            'client' => $client,
            'ordersTotalCount' => $ordersTotalCount,
            'ordersTotalSum' => $ordersTotalSum,
            'ordersMonthCount' => $ordersMonthCount,
            'ordersMonthSum' => $ordersMonthSum,
            'lastOrders' => $lastOrders
        ]);
    }
}