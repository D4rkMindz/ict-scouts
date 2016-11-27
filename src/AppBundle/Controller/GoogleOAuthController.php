<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Helper\GoogleHelper;
use Doctrine\ORM\EntityManager;
use HappyR\Google\ApiBundle\Services\GoogleClient;
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
        /** @var GoogleHelper $googleHelper */
        $googleHelper = $this->container->get('app.helper.google');

        /** @var GoogleClient $client */
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
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        if ($request->query->get('code')) {
            $code = $request->query->get('code');

            /** @var GoogleHelper $googleHelper */
            $googleHelper = $this->container->get('app.helper.google');

            /** @var GoogleClient $client */
            $client = $googleHelper->initClient(false);
            $client->getGoogleClient()->setHostedDomain($this->container->getParameter('google_apps_domain'));
            $client->getGoogleClient()->setScopes($googleHelper->getUserScopes());
            $client->authenticate($code);

            $accessToken = $client->getGoogleClient()->getAccessToken();

            $oauth2 = new \Google_Service_Oauth2($client->getGoogleClient());
            /** @var \Google_Service_Oauth2_Userinfoplus $userData */
            $userData = $oauth2->userinfo_v2_me->get();

            if ($userData->getHd() != $this->container->getParameter('google_apps_domain')) {
                echo '<h2>Error</h2><hr />';
                echo 'Wrong Google Account';
                echo '<hr />';
            }

            /** @var User $user */
            $user = $em->getRepository('AppBundle:User')->findOneBy(['googleId' => $userData->getId()]);

            /** @var \DateTime $accessTokenExpireDate */
            $accessTokenExpireDate = (new \DateTime())->add(new \DateInterval('PT'.($accessToken['expires_in'] - 5).'S'));

            if ($user) {
                $user->setAccessToken($accessToken['access_token']);
                $user->setAccessTokenExpireDate($accessTokenExpireDate);
                $em->persist($user);
            } else {
                $user = new User();
                $user->setGoogleId($userData->getId());
                $user->setGivenName($userData->getGivenName());
                $user->setFamilyName($userData->getFamilyName());
                $user->setEmail($userData->getEmail());
                $user->setAccessToken($accessToken['access_token']);
                $user->setAccessTokenExpireDate($accessTokenExpireDate);
                $em->persist($user);
            }
            $em->flush();

            $request->getSession()->set('access_token', $accessToken['access_token']);
            $request->getSession()->save();

            $this->addFlash('success', 'Login Successful');
        } else {
            $error = $request->query->get('error');
            echo '<b>Error:</b><br /><pre>';
            echo $error;
            echo '</pre>';
        }

        return $this->redirect('/');
    }

    /**
     * @Route(
     *     "/google/updateUsers",
     *     name="google.update_users"
     * )
     *
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function updateUsersAction()
    {
        /** @var GoogleHelper $googleHelper */
        $googleHelper = $this->container->get('app.helper.google');

        $googleHelper->getAllUsers($this->container->getParameter('google_apps_domain'));

        return new Response();
    }
}
