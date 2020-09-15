<?php

namespace App\Controller;

use App\Entity\Material;
use App\Entity\Service;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class ServiceController extends AbstractController
{
    /** @Route ("/services", name="display_available_services") */
    public function displayAvailableServicesPage() {
        /** @var ServiceRepository $servicesRepos */
        $servicesRepos = $this->getDoctrine()->getRepository(Service::class);

        /** @var Service[] $available */
        $available = $servicesRepos->getAvailableServices();

        /** @var Service[] $unavailable */
        $unavailable = $servicesRepos->getUnavailableServices();

        $availableServiceProperties = [];
        foreach ($available as $service) {
            $serviceProps = [];
            $serviceProps['materials'] = $servicesRepos->getMaterialsForService($service);
            $serviceProps['total'] = $servicesRepos->getTotalSumPriceForService($service);
            $availableServiceProperties[$service->getId()] = $serviceProps;
        }
        return $this->render('pages/service_list_page.html.twig', [
            'available' => $available,
            'properties' => $availableServiceProperties,
            'unavailable' => $unavailable
        ]);
    }

    /**
     * @Route("/service/{id}", name="display_service_page")
     */
    public function displayServicePage(string $id) {
        /** @var ServiceRepository $servicesRepos */
        $servicesRepos = $this->getDoctrine()->getRepository(Service::class);
        /** @var Service $service */
        $service = $servicesRepos->find($id);
        /** @var Material[] $materials */
        $materials = $servicesRepos->getMaterialsForService($service);

        return $this->render('pages/service_full_page.html.twig', [
            'service' => $service,
            'materials' => $materials
        ]);
    }

    /**
     * @Route("/materials", name="available_materials_page")
     */
    public function displayAvailableMaterialsPage() {
        $materials = $this->getDoctrine()->getRepository(Material::class)->findAll();
        return $this->render('pages/materials_list_page.html.twig', [
            'materials' => $materials
        ]);
    }

    /**
     * @Route("/about", name="about_page")
     */
    public function displayAboutPage() {
        return $this->render('pages/about_page.html.twig');
    }
}