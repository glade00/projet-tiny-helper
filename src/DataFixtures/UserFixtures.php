<?php
// src/DataFixtures/UserFixtures.php
namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
// utiliser la classe pour crypter le mot de passe
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;
    // injecter la classe de cryptage dans le service
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    /**
     * Ajouter 2 admins :
     * boss@mmi.edu / boss en ROLE_ADMIN
     * marcel@mmi.edu / bouzigue en ROLE_WRITER
     *
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        // un boss@gmail.com / boss en ROLE_ADMIN
        $user = new User();
        $user->setEmail('gwladys.sette@gmail.com')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->passwordEncoder->encodePassword($user, 'glade'));
        $manager->persist($user);
        // un writer@gmail.com / writer en ROLE_WRITER
        $user = new User();
        $user->setEmail('test@mmi.edu')
            ->setRoles(['ROLE_WRITER'])
            ->setPassword($this->passwordEncoder->encodePassword($user, 'tests'));
        $manager->persist($user);

        $manager->flush();
    }
}
