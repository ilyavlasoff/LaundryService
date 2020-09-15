<?php

namespace App\Controller;

use App\Entity\Complexity;
use App\Entity\Urgency;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class OptionsController extends AbstractController
{
    /**
     * @Route("/options", name="display_options_page")
     */
    public function displayOptionsPage() {
        $complexity = $this->getDoctrine()->getRepository(Complexity::class)->findAll();
        $urgency = $this->getDoctrine()->getRepository(Urgency::class)->findAll();

        return $this->render('pages/options_page.html.twig', [
            'complexity' => $complexity,
            'urgency' => $urgency
        ]);
    }
}