<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Person;
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
     * @Route("/{id}", defaults={"id" = null}, name="profile_show")
     * @Method("GET")
     *
     * @param $id
     *
     * @return Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \LogicException
     */
    public function showAction($id)
    {
        if ($id) {
            /** @var Person $person */
            $person = $this->getDoctrine()->getRepository('AppBundle:Person')->find($id);
        } else {
            /** @var Person $person */
            $person = $this->getUser()->getPerson();
        }


        if (!$person){
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
     * @Route("/edit/{id}", name="profile_edit")
     * @Method({"GET","POST"})
     *
     * @param Request $request
     * @param         $id
     *
     * @return Response
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \LogicException
     */
    public function editAction(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();

        /** @var Person $person */
        $person = $entityManager->getRepository('AppBundle:Person')->find($id);

        if (!$person || ($person->getId() !== $this->getUser()->getPerson()->getId() && !$this->isGranted('ROLE_ADMIN'))){
            throw $this->createNotFoundException('Person not Found / Access denied');
        }

        $form = $this->createForm(PersonType::class, $person, []);

        if ($request->isMethod('post')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $entityManager->persist($person);
                $entityManager->flush();

                return $this->redirectToRoute('profile_show', ['id' => $this->getUser()->getPerson()->getId() === $person->getId() ? null : $person->getId() ]);
            }
        }

        return $this->render(
            '@App/Profile/edit.html.twig',
            [
                'person' => $person,
                'form'   => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/portfolio/pdf", name="profile_portfolio_pdf")
     * @Method("GET")
     *
     * @return Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \LogicException
     */
    public function portfolioPdfAction()
    {
        /** @var Person $person */
        $person = $this->getUser()->getPerson();

        if (!$person->getTalent()) {
            throw $this->createNotFoundException(sprintf('Person %s %s (ID: %d) is not a Talent', $person->getGivenName(), $person->getFamilyName(), $person->getId()));
        }

        $pdf = $this->get( 'white_october.tcpdf' )->create(
            'PORTRAIT',
            PDF_UNIT,
            PDF_PAGE_FORMAT,
            true,
            'UTF-8',
            false
        );
        $pdf->SetAuthor( 'ICT Scouts / Campus' );
        $pdf->SetTitle( 'Portfolio - '.$person->getGivenName().' '.$person->getFamilyName());
        $pdf->SetSubject( 'Your client' );
        $pdf->SetKeywords( 'ICT Scouts, Talent, Portfolio' );
        $pdf->setFontSubsetting( true );

        $pdf->SetFont( 'helvetica', '', 11, '', true );
        $pdf->AddPage();

        $html = '<h1>ICT Scouts / Campus</h1><h2>'.$person->getGivenName().' '.$person->getFamilyName().'</h2>';

        $html .= '<table><tr><th><b>Modul</b></th><th><b>Datum</b></th><th><b>Kommentar</b></th></tr><tr><td>Scratch - Tutorial</td><td>28.02.2017</td><td>-</td></tr><tr><td>Scratch - Spiel</td><td>28.02.2017</td><td>-</td></tr><tr><td>Scratch - Fussball</td><td>28.02.2017</td><td>-</td></tr><tr><td>HTML - Tutorial</td><td>01.04.2017</td><td>-</td></tr>';

        $pdf->writeHTMLCell(
            $w = 0,
            $h = 0,
            $x = '',
            $y = '',
            $html,
            $border = 0,
            $ln = 1,
            $fill = 0,
            $reseth = true,
            $align = '',
            $autopadding = true
        );

        return new StreamedResponse($pdf->Output( 'ict_scouts-portfolio.pdf', 'I'));
    }
}
