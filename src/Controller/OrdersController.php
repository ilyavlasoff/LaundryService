<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Order;
use App\Form\OrderCreationForm;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OrdersController extends AbstractController
{
    /**
     * @Route("/orders/create", name="order_creation")
     */
    public function createOrder(Request $request)
    {
        $order = new Order();
        $creationForm = $this->createForm(OrderCreationForm::class, $order);
        $creationForm->handleRequest($request);

        if ($creationForm->isSubmitted() && $creationForm->isValid())
        {

        }

        return $this->render('pages/create_order_page.html.twig', [
            'creationForm' => $creationForm
        ]);
    }

    /**
     * @Route("/order/{id}", name="order_full_page")
     */
    public function fullOrderPage(string $id)
    {
        $order = $this->getDoctrine()->getRepository(Order::class)
            ->find($id);

        if (! $order)
        {
            /**
             * TODO: Кастомные классы исключений
             */
            throw new Exception('Заказ с таким номером не найден');
        }

        return $this->render('pages/order_page.html.twig');
    }

    /**
     * @Route("/orders/history", name="orders_history")
     */
    public function clientOrdersHistory()
    {
        /** @var OrderRepository $ordersRepository */
        $ordersRepository = $this->getDoctrine()->getRepository(Order::class);

        /** @var Client $client */
        $client = $this->getUser();
        if (! $client)
        {
            throw new \Exception('Пользователь не найден');
        }

        $ordersHistory = $ordersRepository->getOrdersByClientId($client);

        return $this->render('pages/orders_history.html.twig', [
            'ordersHistory' => $ordersHistory
        ]);
    }
}