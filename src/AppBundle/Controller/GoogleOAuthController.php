<?php

namespace AppBundle\Controller;

use AppBundle\Service\GoogleService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
     *
     * @return Response
     */
    public function loginAction(): Response
    {
        $googleService = $this->get('app.service.google');
        $client = $googleService->auth($googleService::USER);
        $client->setHostedDomain($this->getParameter('google_apps_domain'));

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
    public function loginCallbackAction(Request $request): Response
    {
        if ($request->query->get('code')) {
            $code = $request->query->get('code');
            $googleUserService = $this->get('app.service.google.user');
            $client = $googleUserService->getGoogleService()->auth(GoogleService::USER);
            $client->authenticate($code);
            $userData = (new \Google_Service_Oauth2($client))->userinfo_v2_me->get();
            if ($userData->getHd() != $this->getParameter('google_apps_domain')) {
                return new Response('<h2>Error</h2><hr />Wrong Google Account<hr />');
            }
            if (!$googleUserService->updateUserAccessToken($userData->getId(), $client->getAccessToken())) {
                return new Response('<h2>Error</h2><hr />Account not Found - If you think this is an error please contact an Administrator<hr />');
            }
            $request->getSession()->set('access_token', $client->getAccessToken()['access_token']);
            $request->getSession()->save();
            $this->addFlash('success', 'Login Successful');

            return $this->redirect('/');
        } else {
            return new Response('<h2>Error:</h2><br /><pre>'.$request->query->get('error').'</pre>');
        }
    }
}
