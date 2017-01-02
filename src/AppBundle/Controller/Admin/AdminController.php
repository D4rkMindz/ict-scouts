<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AdminController.
 *
 * @Route("/admin")
 *
 * @Security("has_role('ROLE_ADMIN')")
 */
class AdminController extends Controller
{
    /**
     * @Route("/", name="admin_home")
     * @Method("GET")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request): Response
    {
        return $this->render(
            '@App/Admin/index.html.twig',
            [
            ]
        );
    }

    /**
     * @Route("/user/sync", name="admin_user_sync")
     * @Method("GET")
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function userSyncAction(Request $request): RedirectResponse
    {
        $googleHelper = $this->container->get('app.helper.google');
        $googleHelper->getAllUsers($this->container->getParameter('google_apps_domain'));

        return $this->redirectToRoute('admin_home');
    }
}
