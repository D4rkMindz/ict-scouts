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
 * @Route("/admin/user")
 *
 * @Security("has_role('ROLE_ADMIN')")
 */
class UserController extends Controller
{
    /**
     * @Route("/", name="admin_user_index")
     * @Method("GET")
     *
     * @return Response
     * @throws \LogicException
     */
    public function indexAction(): Response
    {
        $persons = $this->getDoctrine()->getRepository('AppBundle:Person')->findAllUsers();

        return $this->render(
            '@App/Admin/User/index.html.twig',
            [
                'persons' => $persons,
            ]
        );
    }
}
