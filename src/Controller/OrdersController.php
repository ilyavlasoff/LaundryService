<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Complexity;
use App\Entity\Employee;
use App\Entity\Material;
use App\Entity\Order;
use App\Entity\Service;
use App\Entity\Urgency;
use App\Entity\User;
use App\Form\OrderCreationForm;
use App\Repository\EmployeeRepository;
use App\Repository\MaterialRepository;
use App\Repository\OrderRepository;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
            $order->setReceiveDate(new \DateTime('now'));
            $order->setEndingDate(date_add(new \DateTime('now'), new \DateInterval('P1DT1H')));
            $order->setActive(true);
            $order->setCompleted(false);

            $service = $order->getServiceName();
            /** @var ServiceRepository $serviceRepos */
            $serviceRepos = $this->getDoctrine()->getRepository(Service::class);
            $serviceStandardPrice = $serviceRepos->getTotalSumPriceForService($service);
            $serviceAdditionalPrice = $serviceStandardPrice *
                $order->getComplexity()->getPricingCoefficient() *
                $order->getUrgency()->getPricingCoefficient();
            $order->setSumPrice(floatval($serviceAdditionalPrice));

            /** @var EmployeeRepository $employeeRepos */
            $employeeRepos = $this->getDoctrine()->getRepository(Employee::class);
            $employee = $employeeRepos->getLessBusyEmployee();
            $order->setEmployee($employee);

            /** @var User $client */
            $user = $this->getUser();
            /** @var Client $client */
            $client = $this->getDoctrine()->getRepository(Client::class)->findOneBy(['user'=> $user]);
            $order->setClient($client);

            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();

            return $this->render('pages/order_success.html.twig', [
                'order' => $order
            ]);
        }
        else {
            $complexityId = $request->query->get('complexity');
            $urgencyId = $request->query->get('urgency');
            $serviceId = $request->query->get('service');

            $doctrine = $this->getDoctrine();
            if ($complexityId) {
                $complexityRepos = $doctrine->getRepository(Complexity::class);
                /** @var Complexity $complexityItem */
                $complexityItem = $complexityRepos->find($complexityId);
                if ($complexityItem) {
                    $creationForm->get('complexity')->setData($complexityItem);
                }
            }
            if ($urgencyId) {
                $urgencyRepos = $doctrine->getRepository(Urgency::class);
                /** @var Urgency $complexityItem */
                $urgencyItem = $urgencyRepos->find($urgencyId);
                if ($urgencyItem) {
                    $creationForm->get('urgency')->setData($urgencyItem);
                }
            }
            if ($serviceId) {
                $serviceRepos = $doctrine->getRepository(Service::class);
                /** @var Service $complexityItem */
                $serviceItem = $serviceRepos->find($serviceId);
                if ($serviceItem) {
                    $creationForm->get('serviceName')->setData($serviceItem);
                }
            }
        }

        return $this->render('pages/create_order_page.html.twig', [
            'creationForm' => $creationForm->createView()
        ]);
    }

    /**
     * @Route("/order/{id}", name="order_full_page")
     */
    public function fullOrderPage(string $id)
    {
        /** @var Order $order */
        $order = $this->getDoctrine()->getRepository(Order::class)->find($id);

        /** @var ServiceRepository $serviceRepos */
        $serviceRepos = $this->getDoctrine()->getRepository(Service::class);
        $materials = $serviceRepos->getMaterialsForService($order->getServiceName());

        $renderArgs = [];
        $renderArgs['materials'] = $materials;
        $renderArgs['order'] = $order;
        $renderArgs['employee'] = $order->getEmployee();
        if ($order->getEndingDate()->format('Y-m-d') === (new \DateTime('now'))->format('Y-m-d')) {
            $acceptCompleteForm = $this->createFormBuilder()
                ->add('clientMark', ChoiceType::class, [
                    'choices' => ['Отвратительно' => 1, 'Плохо' => 2, 'Удовлетворительно' => 3, 'Хорошо' => 4, 'Отлично' => 5],
                    'label' => 'Оцените работу'
                ])
                ->add('submit', SubmitType::class, [
                    'label' => 'Закрыть заказ'
                ])
                ->getForm();
            $renderArgs['acceptOrder'] = $acceptCompleteForm;
        }
        return $this->render('pages/order_page.html.twig', $renderArgs);
    }

    /**
     * @Route("/orders/history", name="orders_history")
     */
    public function clientOrdersHistory()
    {
        /** @var OrderRepository $ordersRepository */
        $ordersRepository = $this->getDoctrine()->getRepository(Order::class);

        /** @var User $client */
        $user = $this->getUser();
        /** @var Client $client */
        $client = $this->getDoctrine()->getRepository(Client::class)->findOneBy(['user' => $user]);

        $ordersHistory = $ordersRepository->getOrdersByClientId($client);

        return $this->render('pages/orders_history.html.twig', [
            'ordersHistory' => $ordersHistory
        ]);
    }

    /**
     * @Route("/confirm", name="confirm_order_ajax", methods={"POST"})
     */
    public function confirmOrder(Request $request)
    {
        $id = $request->request->get('id');

        if(!($id && is_numeric($id))) {
            return new JsonResponse(json_encode(['success' => false, 'error' => 'Bad arguments']), Response::HTTP_BAD_REQUEST);
        }

        /** @var Order $order */
        $order = $this->getDoctrine()->getRepository(Order::class)->find($id);

        if(! $order) {
            return new JsonResponse(json_encode(['success' => false, 'error' => 'Not found']), Response::HTTP_NOT_FOUND);
        }
        if ($order->getClient()->getUser()->getId() != $this->getUser()->getId()) {
            return new JsonResponse(json_encode(['success' => false, 'error' => 'Unauthorized']), Response::HTTP_UNAUTHORIZED);
        }

        $order->setActive(false);
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return new JsonResponse(json_encode(['success' => true]), Response::HTTP_OK);
    }
}