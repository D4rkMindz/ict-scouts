<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\School;
use AppBundle\Form\SchoolType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SchoolController.
 *
 * @Route("/admin/school")
 *
 * @Security("has_role('ROLE_ADMIN')")
 */
class SchoolController extends Controller
{
    /**
     * @Route("/", name="admin.school.index")
     * @Method("GET")
     *
     * @throws \LogicException
     *
     * @return Response
     */
    public function indexAction(): Response
    {
        $schools = $this->getDoctrine()->getManager()->getRepository('AppBundle:School')->findAll();

        return $this->render(
            '@App/Admin/School/index.html.twig',
            [
                'schools' => $schools,
            ]
        );
    }

    /**
     * @Route("/show/{id}", name="admin.school.show")
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
        $school = $this->getDoctrine()->getManager()->getRepository('AppBundle:School')->find($id);

        if (!$school) {
            return $this->createNotFoundException(sprintf('School with ID: %s not found', $id));
        }

        return $this->render(
            '@App/Admin/School/show.html.twig',
            [
                'school' => $school,
            ]
        );
    }

    /**
     * @Route("/create", name="admin.school.create")
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
        $school = new School('');

        $form = $this
            ->createForm(SchoolType::class, $school, []);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $school = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($school);
            $em->flush();

            return $this->redirectToRoute('admin.school.show', [
                'id' => $school->getId(),
            ]);
        }

        return $this->render(
            '@App/Admin/School/form.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/edit/{id}", name="admin.school.edit")
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

        $school = $em->getRepository('AppBundle:School')->find($id);

        if (!$school) {
            return $this->createNotFoundException(sprintf('School with ID: %s not found', $id));
        }

        $form = $this
            ->createForm(SchoolType::class, $school, []);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $school = $form->getData();

            $em->persist($school);
            $em->flush();

            return $this->redirectToRoute('admin.school.show', [
                'id' => $school->getId(),
            ]);
        }

        return $this->render(
            '@App/Admin/School/form.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
