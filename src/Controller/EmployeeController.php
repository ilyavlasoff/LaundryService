<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Complexity;
use App\Entity\Employee;
use App\Entity\Material;
use App\Entity\Order;
use App\Entity\Service;
use App\Entity\User;
use App\Repository\MaterialRepository;
use App\Repository\OrderRepository;
use App\Repository\ServiceRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeController extends AbstractController
{
    /**
     * @Route("/employee/orders/list", name="display_attached_orders")
     */
    public function displayListOfAttachedOrders(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();
        /** @var Employee $employee */
        $employee = $this->getDoctrine()->getRepository(Employee::class)->findOneBy(['user' => $user]);

        $filterForm = $this->createFormBuilder()
            ->add('orderStatus', ChoiceType::class, [
                'label' => 'Статус заказа',
                'choices' => [
                    'Активные' => 'active',
                    'Выполненные' => 'completed',
                    'Все' => 'all'
                ]
            ])
            ->add('filterParams', ChoiceType::class, [
                'label' => 'Сортировка',
                'choices' => [
                    'По времени выполнения' => 'ending',
                    'По времени создания' => 'creation',
                    'По цене' => 'price'
                ]
            ])
            ->add('filter', SubmitType::class, [
                'label' => 'Найти'
            ])
            ->getForm();
        $filterForm->handleRequest($request);

        $renderArgs = [];
        $renderArgs['filter'] = $filterForm->createView();

        /** @var OrderRepository $orderRepos */
        $orderRepos = $this->getDoctrine()->getRepository(Order::class);
        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $filterFormData = $filterForm->getData();
            /** @var Order[] $items */
            $items = $orderRepos->getOrdersAttachedToEmployee($employee, $filterFormData['orderStatus'], $filterFormData['filterParams']);
            $renderArgs['orders'] = $items;
        }
        else {
            $items = $orderRepos->getOrdersAttachedToEmployee($employee);
            $renderArgs['orders'] = $items;
        }

        return $this->render('pages/employee_order_list.html.twig', $renderArgs);
    }

    /**
     * @Route("/employee/order/{id}", name="employee_order_page")
     */
    public function displayOrderPage(string $id, Request $request)
    {
        $doctrine = $this->getDoctrine();
        /** @var Order $order */
        $order = $doctrine->getRepository(Order::class)->find($id);
        $service = $order->getServiceName();
        /** @var ServiceRepository $serviceRepos */
        $serviceRepos = $doctrine->getRepository(Service::class);
        $materials = $serviceRepos->getMaterialsForService($service);
        $entityManager = $doctrine->getManager();
        $messages = [];

        $changeEndingDateForm = $this->createFormBuilder($order)
            ->add('endingDate', DateType::class, [
                'label' => 'Новая дата'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Изменить'
            ])
            ->getForm();
        $changeEndingDateForm->handleRequest($request);
        if ($changeEndingDateForm->isSubmitted() && $changeEndingDateForm->isValid()) {
            $entityManager->persist($order);
            $entityManager->flush();
        }

        $changeComplexityForm = $this->createFormBuilder($order)
            ->add('complexity', EntityType::class, [
                'label' => 'Сложность',
                'class' => Complexity::class,
                'choice_label' => 'name'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Изменить'
            ])
            ->getForm();
        $changeComplexityForm->handleRequest($request);
        if ($changeComplexityForm->isSubmitted() && $changeComplexityForm->isValid()) {
            $entityManager->persist($order);
            $entityManager->flush();
        }

        $confirmOrderCompleted = $this->createFormBuilder($order)
            ->add('confirmCompleted', SubmitType::class, [
                'label' => 'Подтвердить выполнение'
            ])
            ->getForm();
        if ($confirmOrderCompleted->isSubmitted() && $confirmOrderCompleted->isValid()) {
            $order->setCompleted(true);
        }

        return $this->render('pages/employee_order_page.html.twig', [
            'changeEndingDateForm' => $changeEndingDateForm->createView(),
            'confirmOrderCompleted' => $confirmOrderCompleted->createView(),
            'changeComplexityForm' => $changeComplexityForm->createView(),
            'order' => $order,
            'service' => $service,
            'materials' => $materials,
            'messages' => $messages
        ]);
    }
}