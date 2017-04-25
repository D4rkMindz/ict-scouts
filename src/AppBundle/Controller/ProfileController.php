<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Person;
use AppBundle\Form\Type\PersonPicType;
use AppBundle\Form\Type\PersonType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Class ProfileController.
 *
 * @Route("/profile")
 *
 * @Security("has_role('ROLE_TALENT')")
 */
class ProfileController extends Controller
{
    /**
     * @Route("/{person}", defaults={"person" = null}, name="profile_show")
     * @Method("GET")
     *
     * @param Person|null $person
     *
     * @return Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \LogicException
     */
    public function showAction(Person $person = null): Response
    {
        if ( !$person ) {
            $person = $this->getUser()->getPerson();
        }

        if ( !$this->isGranted('ROLE_ADMIN') && $person->getId() !== $this->getUser()->getPerson()->getId() ){
            throw $this->createNotFoundException('Person not Found / Access denied');
        }

        return $this->render(
            '@App/Profile/show.html.twig',
            [
                'person' => $person,
            ]
        );
    }

    /**
     * @Route("/edit/{person}", name="profile_edit")
     * @Method({"GET","POST"})
     *
     * @param Request $request
     * @param Person  $person
     *
     * @return Response
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \LogicException
     * @internal param $id
     *
     */
    public function editAction(Request $request, Person $person): Response
    {
        if ( !$this->isGranted('ROLE_ADMIN') && $person->getId() !== $this->getUser()->getPerson()->getId() ){
            throw $this->createNotFoundException('Person not Found / Access denied');
        }

        $form = $this->createForm(PersonType::class, $person, []);

        if ($request->isMethod('post')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($person);
                $entityManager->flush();

                return $this->redirectToRoute('profile_show', ['id' => $this->getUser()->getPerson()->getId() === $person->getId() ? null : $person->getId() ]);
            }
        }

        $personPic = 'https://placehold.it/350';
        if ($person->getPic() && file_exists($this->getParameter('person_pic_location').'/'.$person->getPic())) {
            $personPic = 'data:image/gif;base64,'.base64_encode(file_get_contents($this->getParameter('person_pic_location').'/'.$person->getPic()));
        }

        return $this->render(
            '@App/Profile/edit.html.twig',
            [
                'person' => $person,
                'form'   => $form->createView(),
                'person_pic'    => $personPic,
            ]
        );
    }

    /**
     * @Route("/portfolio/pdf", name="profile_portfolio_pdf")
     * @Method("GET")
     *
     * @return StreamedResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \LogicException
     */
    public function portfolioPdfAction(): StreamedResponse
    {
        /** @var Person $person */
        $person = $this->getUser()->getPerson();

        if (!$person->getTalent()) {
            throw $this->createNotFoundException(sprintf('Person %s %s (ID: %d) is not a Talent', $person->getGivenName(), $person->getFamilyName(), $person->getId()));
        }

        $portfolioPdfService = $this->get('app.service.portfolio.pdf');
        /** @var \TCPDF $pdf */
        $pdf = $portfolioPdfService->initPdf('Portfolio - '.$person->getGivenName().' '.$person->getFamilyName());
        $pdf = $portfolioPdfService->createPortfolio($pdf, $person);

        return new StreamedResponse($pdf->Output( 'ict_scouts-portfolio.pdf', 'I'));
    }
}
