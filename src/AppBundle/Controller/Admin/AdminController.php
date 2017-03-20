<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @return Response
     */
    public function indexAction(): Response
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
     * @return RedirectResponse
     * @throws \Exception
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     */
    public function userSyncAction(): RedirectResponse
    {
        $googleService = $this->get('app.service.google.user');
        $googleService->getAllUsers($this->getParameter('google_apps_domain'));

        return $this->redirectToRoute('admin_home');
    }
}
