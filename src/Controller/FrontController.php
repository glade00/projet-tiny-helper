<?php

namespace App\Controller;

use App\Entity\Sites;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(EntityManagerInterface $em)
    {
        return $this->render('front/index.html.twig', [
            'categories' => $em->getRepository(Category::class)->findAll(),
            'sites' => $em->getRepository(Sites::class)->findAll(),
        ]);
    }
    /**
     * @Route("/latest", name="index_latest")
     */
    public function index_latest(EntityManagerInterface $em)
    {
        return $this->render('front/index2.html.twig', [
            'categories' => $em->getRepository(Category::class)->findAll(),
            'sites' => $em->getRepository(Sites::class)->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="tag_show", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function show(Category $category, EntityManagerInterface $em): Response
    {
        return $this->render('front/tag.html.twig', [
            'category' => $category,
            'categories' => $em->getRepository(Category::class)->findAll(),
        ]);
    }

    /**
     * @Route("/{id}/latest", name="tag_show_latest", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function show_latest(Category $category, EntityManagerInterface $em): Response
    {
        return $this->render('front/tag2.html.twig', [
            'category' => $category,
            'categories' => $em->getRepository(Category::class)->findAll(),
        ]);
    }
}
