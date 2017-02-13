<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Module;
use AppBundle\Form\Type\ModuleType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ModuleController.
 *
 * @Route("/admin/module")
 *
 * @Security("has_role('ROLE_ADMIN')")
 */
class ModuleController extends Controller
{
    /**
     * @Route("/", name="admin.module.index")
     * @Method("GET")
     *
     * @throws \LogicException
     *
     * @return Response
     */
    public function indexAction(): Response
    {
        $modules = $this->getDoctrine()->getManager()->getRepository('AppBundle:Module')->findAll();

        return $this->render(
            '@App/Admin/Module/index.html.twig',
            [
                'modules' => $modules,
            ]
        );
    }

    /**
     * @Route("/show/{id}", name="admin.module.show")
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
        $module = $this->getDoctrine()->getManager()->getRepository('AppBundle:Module')->find($id);

        if (!$module) {
            return $this->createNotFoundException(sprintf('Module with ID: %s not found', $id));
        }

        return $this->render(
            '@App/Admin/Module/show.html.twig',
            [
                'module' => $module,
            ]
        );
    }

    /**
     * @Route("/create", name="admin.module.create")
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
        $module = new Module('');

        $form = $this
            ->createForm(ModuleType::class, $module, []);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $module = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($module);
            $em->flush();

            return $this->redirectToRoute('admin.module.show', [
                'id' => $module->getId(),
            ]);
        }

        return $this->render(
            '@App/Admin/Module/form.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/edit/{id}", name="admin.module.edit")
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

        $module = $em->getRepository('AppBundle:Module')->find($id);

        if (!$module) {
            return $this->createNotFoundException(sprintf('Module with ID: %s not found', $id));
        }

        $form = $this
            ->createForm(ModuleType::class, $module, []);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $module = $form->getData();

            $em->persist($module);
            $em->flush();

            return $this->redirectToRoute('admin.module.show', [
                'id' => $module->getId(),
            ]);
        }

        return $this->render(
            '@App/Admin/Module/form.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
