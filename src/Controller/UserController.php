<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("/")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/utilisateurs", name="user_index", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */



    public function index(UserRepository $userRepository, EntityManagerInterface $em)
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
            'categories' => $em->getRepository(Category::class)->findAll(),
        ]);
    }

    /**
     * @Route("/inscription", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $user->getRoles();
        // instancie le formulaire avec les contraintes par défaut, + la contrainte registration pour que la saisie du mot de passe soit obligatoire
        $form = $this->createForm(UserType::class, $user, [
            'validation_groups' => array('User', 'registration'),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode le mot de passe
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }




    /**
     * @Route("/utilisateurs/{id}", name="user_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}
