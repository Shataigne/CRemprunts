<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Form\QuickSearchFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app')]
    public function home(): Response
    {
        return $this->redirectToRoute('app_home');
    }
    #[Route('/home', name: 'app_home')]
    public function index(Request $request, RouterInterface $router): Response
    {
        $form = $this->createForm(QuickSearchFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $action = $form->get('action')->getData();
            $url = $router->generate('app_' . $action . '_catalogue', ['q' => $form->get('q')->getData(),'centres' => $form->get('centres')->getData(),'dispoNow' => $form->get('dispoNow')->getData()], UrlGeneratorInterface::ABSOLUTE_URL);
            return $this->redirect($url);

        } else {
            return $this->render('home/index.html.twig', [
                'form' => $form
            ]);
        }
    }
}