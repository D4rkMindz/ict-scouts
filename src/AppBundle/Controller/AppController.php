<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AppController.
 */
class AppController extends Controller
{
    /**
     * @Route("/", name="home")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request): Response
    {
        // replace this example code with whatever you need
        return $this->render(
            '@App/App/index.html.twig',
            [
                'user' => $this->getUser(),
            ]
        );
    }
}
