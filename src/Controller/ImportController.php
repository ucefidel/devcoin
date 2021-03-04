<?php

namespace App\Controller;

use App\Form\ImportType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImportController extends DefaultController
{
    /**
     * @Route("/import", name="import_annonce")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(ImportType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $data */
            $data = $form->get("import")->getData();

            dump($data);

        }

        return $this->render('import/index.html.twig', [
            "form" => $form->createView()
        ]);
    }
}
