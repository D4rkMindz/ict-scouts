<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Event;
use AppBundle\Form\Type\EventType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SchoolController.
 *
 * @Route("/admin/event")
 *
 * @Security("has_role('ROLE_ADMIN')")
 */
class EventController extends Controller
{
    /**
     * @Route("/", name="admin.event.index")
     * @Method("GET")
     *
     * @throws \LogicException
     *
     * @return Response
     */
    public function indexAction(): Response
    {
        $events = $this->getDoctrine()->getManager()->getRepository('AppBundle:Event')->findAll();

        return $this->render(
            '@App/Admin/Event/index.html.twig',
            [
                'events' => $events,
            ]
        );
    }

    /**
     * @Route("/show/{id}", name="admin.event.show")
     * @Method("GET")
     *
     * @param int $id
     *
     * @throws \LogicException
     *
     * @return Response|\Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function showAction($id)
    {
        $event = $this->getDoctrine()->getManager()->getRepository('AppBundle:Event')->find($id);

        if (!$event) {
            return $this->createNotFoundException(sprintf('Event with ID: %s not found', $id));
        }

        return $this->render(
            '@App/Admin/Event/show.html.twig',
            [
                'event' => $event,
            ]
        );
    }

    /**
     * @Route("/create", name="admin.event.create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \Symfony\Component\Form\Exception\UnexpectedTypeException
     * @throws \Symfony\Component\Form\Exception\LogicException
     * @throws \Symfony\Component\Form\Exception\AlreadySubmittedException
     *
     * @return Response
     */
    public function createAction(Request $request)
    {
        $event = new Event();

        $form = $this
            ->createForm(EventType::class, $event, []);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('admin.event.show', [
                'id' => $event->getId(),
            ]);
        }

        return $this->render(
            '@App/Admin/Event/form.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/edit/{id}", name="admin.event.edit")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param int     $id
     *
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \Symfony\Component\Form\Exception\UnexpectedTypeException
     * @throws \Symfony\Component\Form\Exception\LogicException
     * @throws \Symfony\Component\Form\Exception\AlreadySubmittedException
     *
     * @return Response|\Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $event = $em->getRepository('AppBundle:Event')->find($id);

        if (!$event) {
            return $this->createNotFoundException(sprintf('Event with ID: %s not found', $id));
        }

        $form = $this
            ->createForm(EventType::class, $event, []);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event = $form->getData();

            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('admin.event.show', [
                'id' => $event->getId(),
            ]);
        }

        return $this->render(
            '@App/Admin/Event/form.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
