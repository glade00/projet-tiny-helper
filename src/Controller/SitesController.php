<?php

namespace App\Controller;

use App\Entity\Sites;
use App\Form\SitesType;
use App\Repository\SitesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("admin")
 */
class SitesController extends AbstractController
{
    /**
     * @Route("/", name="sites_index", methods={"GET"})
     */
    public function index(SitesRepository $sitesRepository, EntityManagerInterface $em)
    {
        return $this->render('backoffice/sites/index.html.twig', [
            'sites' => $sitesRepository->findAll(),

            'categories' => $em->getRepository(Category::class)->findAll(),
        ]);
    }
    /**
     * @Route("admin/latest", name="sites_index_latest", methods={"GET"})
     */
    public function index_latest(SitesRepository $sitesRepository, EntityManagerInterface $em)
    {
        return $this->render('backoffice/sites/index2.html.twig', [
            'sites' => $sitesRepository->findAll(),
            'categories' => $em->getRepository(Category::class)->findAll(),
        ]);
    }
    /**
     * @Route("/new", name="sites_new", methods={"GET","POST"})
     */
    public function new(Request $request, EntityManagerInterface $em)
    {
        $site = new Sites();
        $site->setPublishedAt(new \DateTime());
        $form = $this->createForm(SitesType::class, $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $illustration = $form->get('illustration')->getData();
            if ($illustration) {
                $originalFilename = pathinfo($illustration->getClientOriginalName(), PATHINFO_FILENAME);
                //$safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $illustration->guessExtension();
                try {
                    $illustration->move(
                        $this->getParameter('illustration_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $site->setIllustration($newFilename);
            }

            $entityManager =  $this->getDoctrine()->getManager();
            $entityManager->persist($site);
            $entityManager->flush();

            return $this->redirectToRoute('sites_index');
        }

        return $this->render('backoffice/sites/new.html.twig', [

            'form' => $form->createView(),
            'categories' => $em->getRepository(Category::class)->findAll(),
        ]);
    }



    /**
     * @Route("/{id}/edit", name="sites_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Sites $site,  EntityManagerInterface $em)
    {
        $site->setPublishedAt(new \DateTime());
        $form = $this->createForm(SitesType::class, $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $illustration = $form->get('illustration')->getData();
            if ($illustration) {
                $originalFilename = pathinfo($illustration->getClientOriginalName(), PATHINFO_FILENAME);
                //$safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $illustration->guessExtension();
                try {
                    $illustration->move(
                        $this->getParameter('illustration_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $site->setIllustration($newFilename);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('sites_index');
        }

        return $this->render('backoffice/sites/edit.html.twig', [
            'site' => $site,
            'form' => $form->createView(),
            'categories' => $em->getRepository(Category::class)->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="sites_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Sites $site): Response
    {
        if ($this->isCsrfTokenValid('delete' . $site->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($site);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sites_index');
    }

    /**
     * @Route("/{id}/ordre", name="sites_tag_show", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function show(Category $category, EntityManagerInterface $em)
    {
        return $this->render('backoffice/sites/tag.html.twig', [
            'category' => $category,
            'categories' => $em->getRepository(Category::class)->findAll(),
        ]);
    }

    /**
     * @Route("/{id}/latest", name="sites_tag_show_latest", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function show_latest(Category $category, EntityManagerInterface $em)
    {
        return $this->render('backoffice/sites/tag2.html.twig', [
            'category' => $category,
            'categories' => $em->getRepository(Category::class)->findAll(),
        ]);
    }
}
