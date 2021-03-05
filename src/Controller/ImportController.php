<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Category;
use App\Entity\User;
use App\Form\ImportType;
use App\Repository\CategoryRepository;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImportController extends DefaultController
{
    /**
     * @Route("/upload-excel", name="import_annonce")
     * @param Request $request
     * @param CategoryRepository $categoryRepository
     * @return Response
     * @throws Exception
     */
    public function index(Request $request, CategoryRepository $categoryRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        /** @var Category $catTest */
        $catTest = $categoryRepository->find(1);

        $form = $this->createForm(ImportType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $data */
            $fileName = $form->get("import")->getData();
            if ($fileName) {

                $originalFilename = pathinfo($fileName->getClientOriginalName(), PATHINFO_FILENAME);
                $fileFolder = $this->getParameter('spread_directory');
                $safeFilename = $this->slugger->slug($originalFilename);
                $filePathName = $safeFilename . '-' . uniqid() . '.' . $fileName->guessExtension();
                try {
                    $fileName->move($fileFolder, $filePathName);
                } catch (FileException $e) {
                    dd($e);
                }

                $spreadsheet = IOFactory::load($fileFolder . $filePathName);
                $row = $spreadsheet->getActiveSheet()->removeRow(1);
                $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

                foreach ($sheetData as $row) {
                    $title = $row['A'];
                    $description = $row['B'];
                    $price = $row['C'];
                    $localisation = $row['D'];

                    $annonce = new Annonce();

                    $annonce->setTitle($title)
                        ->setUser($user)
                        ->setDescription($description)
                        ->setPicture("https://loremflickr.com/320/240?lock=" . mt_rand(1, 10))
                        ->setCategory($catTest)
                        ->setPrice($price)
                        ->setLocalisation($localisation);

                    $this->manager->persist($annonce);

                }
                $this->manager->flush();

                $this->addFlash(
                    'success',
                    "L'import des annonces est fait "
                );

                return $this->redirectToRoute("annonce_show_all");
            }

        }

        return $this->render('import/index.html.twig', [
            "form" => $form->createView()
        ]);
    }
}
