<?php

namespace AppBundle\Controller\Ajax;

use AppBundle\Entity\Person;
use AppBundle\Form\Type\PersonPicType;
use AppBundle\Service\FileUploadService;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProfileController.
 *
 * @Route("/ajax/profile")
 *
 * @Security("has_role('ROLE_TALENT')")
 */
class ProfileController extends Controller
{
    /**
     * @Route("/edit/{person}/upload_profile_pic", name="ajax_upload_profile_pic")
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Person  $person
     *
     * @return JsonResponse
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileException
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \LogicException
     */
    public function editAction(Request $request, Person $person): JsonResponse
    {
        if ( !$this->isGranted('ROLE_ADMIN') && $person->getId() !== $this->getUser()->getPerson()->getId() ){
            throw $this->createNotFoundException('Person not Found / Access denied');
        }

        /** @var EntityManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();

        /** @var UploadedFile $file */
        $file = $request->files->get('person_pic')['pic'];

        if (!$file instanceof UploadedFile) {
            return new JsonResponse([
                'status' => 'error',
                'error'  => 'Dateiupload nicht erfolgreich.',
            ]);
        }

        /** @var FileUploadService $fileUploader */
        $fileUploader = $this->get('app.service.person_pic_uploader');
        $fileName = $fileUploader->upload($file, $person->getId(), $person->getPic() ?? '');

        $person->setPic($fileName);

        $entityManager->persist($person);
        $entityManager->flush();

        return new JsonResponse([
            'status' => 'success',
            'pic' => 'data:image/gif;base64,'.base64_encode(file_get_contents($this->getParameter('person_pic_location').'/'.$person->getPic())),
        ]);
    }
}
