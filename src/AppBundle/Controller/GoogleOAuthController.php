<?php

namespace AppBundle\Controller;

use AppBundle\Helper\GoogleHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GoogleOAuthController extends Controller
{
    /**
     * Login action.
     *
     * @Route(
     *     "/login",
     *     name="login"
     * )
     * @Method("GET")
     */
    public function loginAction()
    {
        $googleHelper = $this->container->get('app.helper.google');
        $client = $googleHelper->initClient(false);
        $client->getGoogleClient()->setHostedDomain($this->container->getParameter('google_apps_domain'));
        $client->getGoogleClient()->setScopes($googleHelper->getUserScopes());

        return $this->redirect($client->createAuthUrl());
    }

    /**
     * Login callback action.
     *
     * @param Request $request
     *
     * @return Response
     * @Route(
     *     "/oauth/google/redirect",
     *     name="google.login_callback"
     * )
     * @Method("GET")
     */
    public function loginCallbackAction(Request $request)
    {
        if ($request->query->get('code')) {
            $code = $request->query->get('code');
            $googleHelper = $this->container->get('app.helper.google');
            $client = $googleHelper->initClient(GoogleHelper::SCOPE_USER, false);
            $client->authenticate($code);
            $userData = (new \Google_Service_Oauth2($client->getGoogleClient()))->userinfo_v2_me->get();
            if ($userData->getHd() != $this->container->getParameter('google_apps_domain')) {
                return new Response('<h2>Error</h2><hr />Wrong Google Account<hr />');
            }
            $googleHelper->updateUserData($userData, $client->getGoogleClient()->getAccessToken());
            $request->getSession()->set('access_token', $client->getGoogleClient()->getAccessToken()['access_token']);
            $request->getSession()->save();
            $this->addFlash('success', 'Login Successful');

            return $this->redirect('/');
        } else {
            return new Response('<h2>Error:</h2><br /><pre>'.$request->query->get('error').'</pre>');
        }
    }

    /**
     * @Route(
     *     "/google/updateUsers",
     *     name="google.update_users"
     * )
     * @Method("GET")
     *
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function updateUsersAction()
    {
        $googleHelper = $this->container->get('app.helper.google');
        $googleHelper->getAllUsers($this->container->getParameter('google_apps_domain'));

        return new Response();
    }
}