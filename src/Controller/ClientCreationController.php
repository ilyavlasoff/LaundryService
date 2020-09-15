<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\User;
use App\Form\ClientCreationForm;
use App\Repository\ClientRepository;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClientCreationController extends AbstractController
{
    /**
     * @Route("/make-client", name="make_client")
     */
    public function makeClientForNewUser(Request $request) {
        /** @var ClientRepository $clientRepos */
        $clientRepos = $this->getDoctrine()->getRepository(Client::class);

        /** @var User $user */
        $user = $this->getUser();

        if ($clientRepos->isClientExistsForUser($user)) {
            return new RedirectResponse($this->generateUrl('app_home'));
        }

        $client = new Client();
        $form = $this->createForm(ClientCreationForm::class, $client);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $client->setRegistrationDate(new \DateTime('now'));
            $client->setIsPermanent(false);
            $client->setUser($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($client);
            $entityManager->flush();
            return new RedirectResponse($this->generateUrl('app_home'));
        }

        return $this->render('pages/create_client_page.html.twig', [
            'form' => $form->createView()
        ]);
    }
}