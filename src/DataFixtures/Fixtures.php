<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Complexity;
use App\Entity\Employee;
use App\Entity\Material;
use App\Entity\Order;
use App\Entity\Service;
use App\Entity\Urgency;
use App\Entity\User;
use App\Entity\UsesMaterial;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Fixtures extends Fixture implements ContainerAwareInterface
{
    private function generateRandomString($length = 10, $param = 'mixed') {
        if($param == 'mixed') {
            $x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        elseif ($param == 'digit') {
            $x='0123456789';
        }
        return substr(str_shuffle(str_repeat($x, ceil($length/strlen($x)) )),1,$length);
    }

    private function generateName(): string {
        $names = [
            'Иван',
            'Василий',
            'Сергей',
            'Владимир',
            'Николай',
            'Илья',
            'Алексей',
            'Павел',
        ];
        return $names[array_rand($names)];
    }

    private function generateSurname(): string {
        $surnames = [
            'Иванов',
            'Петров',
            'Сидоров',
            'Павлов',
            'Сергеев',
            'Алексеев',
        ];
        return $surnames[array_rand($surnames)];
    }

    private function generatePatronymics(): string {
        return $this->generateSurname() . 'ич';
    }

    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $params = [
            'users' => 20,
            'employees' => 5,
            'materials' => 10,
            'services' => 8,
            'orders' => 100
        ];

        $users = [];
        for($i=0; $i != $params['users']; ++$i) {
            $user = new User();
            $user->setRoles(['ROLE_USER']);
            $user->setEmail($this->generateRandomString(random_int(5, 15)));
            $user->setPassword( $this->container->get('security.password_encoder')->encodePassword($user, 'password'));
            $users[] = $user;
            $manager->persist($user);
            $manager->flush();
        }

        $employees = [];
        for($i=0; $i != $params['employees']; ++$i) {
            $employee = new Employee();
            $employee->setFirstName($this->generateName());
            $employee->setLastName($this->generateSurname());
            $employee->setPassport($this->generateRandomString(10, 'digit'));
            $employee->setPatronymic($this->generatePatronymics());
            $employee->setSalary(random_int(15000, 100000));
            $employee->setUser($users[$i]);
            $employees[] = $employee;
            $manager->persist($employee);
            $manager->flush();
        }

        $clients = [];
        for($i=$params['employees']; $i != $params['users']; ++$i) {
            $client = new Client();
            $client->setUser($users[$i]);
            $client->setFirstName($this->generateName());
            $client->setLastName($this->generateSurname());
            $client->setPatronymic($this->generatePatronymics());
            $client->setIsPermanent(boolval(round(random_int(0, 10) / 10)));
            $client->setPhone($this->generateRandomString(11, 'digit'));
            $client->setRegistrationDate(new \DateTime('now'));
            $clients[] = $client;
            $manager->persist($client);
            $manager->flush();
        }

        $complexity = [
            'easy' => 1,
            'medium' => 1,
            'hard' => 1.15,
            'hardest' => 1.3
        ];
        $urgency = [
            'ordinary' => 1,
            'faster' => 1.2,
            'fast' => 1.35,
            'fastest' => 1.5
        ];

        $compls = [];
        foreach ($complexity as $key => $value) {
            $compl = new Complexity();
            $compl->setName($key);
            $compl->setPricingCoefficient($value);
            $compls[] = $compl;
            $manager->persist($compl);
            $manager->flush();
        }

        $urgencies = [];
        foreach ($urgency as $key => $value) {
            $urg = new Urgency();
            $urg->setName($key);
            $urg->setPricingCoefficient($value);
            $urgencies[] = $urg;
            $manager->persist($urg);
            $manager->flush();
        }

        $materials = [];
        for($i=0; $i != $params['materials']; ++$i) {
            $material = new Material();
            $material->setName("Material $i");
            $material->setPrice(random_int(100, 900));
            $material->setAvailable(random_int(1, 100));
            $materials[] = $material;
            $manager->persist($material);
            $manager->flush();
        }

        $services = [];
        for($i=0; $i != $params['services']; ++$i) {
            $service = new Service();
            $service->setName("Service $i");
            $service->setStandardPricing(random_int(200, 2000));
            $step = random_int(1, 10);
            $services[] = $service;
            $manager->persist($service);
            $manager->flush();
        }

        for($j=0; $j != $params['services']; ++$j) {
            $coef = random_int(1, 10);
            for($i=0; $i != count($materials); ++$i)
            {
                if ($i % $coef === 0) {
                    $usesMaterial = new UsesMaterial();
                    $usesMaterial->setMaterials($materials[$i]);
                    $usesMaterial->setService($services[$j]);
                    $usesMaterial->setUsesQuantity(random_int(1, 100) / 10);
                    $manager->persist($usesMaterial);
                    $manager->flush();
                }
            }
        }

        for($i=0; $i != $params['orders']; ++$i) {
            $order = new Order();
            $order->setActive(true);
            $order->setCompleted(boolval(round(random_int(1, 10) / 10)));
            $order->setClient($clients[array_rand($clients)]);
            $order->setComplexity($compls[array_rand($compls)]);
            $order->setEmployee($employees[array_rand($employees)]);
            $order->setServiceName($services[array_rand($services)]);
            $order->setUrgency($urgencies[array_rand($urgencies)]);
            $order->setReceiveDate(\DateTime::createFromFormat('Y-m-d', "2020-" . strval(random_int(1, 9)) . '-' . strval(random_int(1, 28))));
            $order->setEndingDate(\DateTime::createFromFormat('Y-m-d', "2020-" . strval(random_int(10, 12)) . '-' . strval(random_int(1, 28))));
            $order->setSumPrice(random_int(1000, 2000));
            $manager->persist($order);
            $manager->flush();
        }
    }

}
