<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class GoogleOAuthController.
 */
class GoogleOAuthController extends Controller
{
    /**
     * Login action.
     *
     * @Route("/login", name="login")
     * @Method("GET")
     */
    public function loginAction()
    {
        $googleHelper = $this->container->get('app.helper.google');
        $client = $googleHelper->setScope($googleHelper->getUserScopes());
        $client->setHostedDomain($this->container->getParameter('google_apps_domain'));

        return $this->redirect($client->createAuthUrl());
    }

    /**
     * Login callback action.
     *
     * @Route("/oauth/google/redirect", name="google.login_callback")
     * @Method("GET")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function loginCallbackAction(Request $request)
    {
        if ($request->query->get('code')) {
            $code = $request->query->get('code');
            $googleHelper = $this->container->get('app.helper.google');
            $client = $googleHelper->getClient();
            $client->authenticate($code);
            $userData = (new \Google_Service_Oauth2($client))->userinfo_v2_me->get();
            if ($userData->getHd() != $this->container->getParameter('google_apps_domain')) {
                return new Response('<h2>Error</h2><hr />Wrong Google Account<hr />');
            }
            $googleHelper->updateUserData($userData, $client->getAccessToken());
            $request->getSession()->set('access_token', $client->getAccessToken()['access_token']);
            $request->getSession()->save();
            $this->addFlash('success', 'Login Successful');

            return $this->redirect('/');
        } else {
            return new Response('<h2>Error:</h2><br /><pre>'.$request->query->get('error').'</pre>');
        }
    }

    /**
     * @Route("/google/updateUsers", name="google.update_users")
     * @Security("has_role('ROLE_ADMIN')")
     * @Method("GET")
     *
     * @return Response
     */
    public function updateUsersAction()
    {
        $googleHelper = $this->container->get('app.helper.google');
        $googleHelper->getAllUsers($this->container->getParameter('google_apps_domain'));

        return new Response();
    }
}
