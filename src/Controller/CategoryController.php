<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("admin/category")
 */

class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="category_index", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('backoffice/category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),

        ]);
    }

    /**
     * @Route("/new", name="category_new", methods={"GET","POST"})
     */
    public function new(Request $request,  EntityManagerInterface $em): Response
    {
        $category = new Category();
        $category->setCreatedAt(new \DateTime());
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('category_index');
        }

        return $this->render('backoffice/category/new.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
            'categories' => $em->getRepository(Category::class)->findAll(),
        ]);
    }


    /**
     * @Route("/{id}/edit", name="category_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Category $category,  EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('category_index');
        }

        return $this->render('backoffice/category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
            'categories' => $em->getRepository(Category::class)->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="category_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Category $category): Response
    {
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('category_index');
    }
}
