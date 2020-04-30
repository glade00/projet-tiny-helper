<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Sites;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    // nombre d'articles
    const N_POSTS = 10;
    // liste des catégories
    const CATEGORY = [
        'Monde',
        'Société',
        'Politique',
        'Culturel',
        'Tech&internet',
        'Médias',
        'Boire & manger',
        'Sciences',
        'Santé',
        'Sport'
    ];

    public function load(ObjectManager $manager)
    {

        $this->loadCategory($manager);
        $this->loadSites($manager);
    }


    /**
     * Alimenter l'entité Category.
     */
    public function loadCategory(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 1; $i <= count(self::CATEGORY); $i++) {
            // créer une entité Category
            $category = new Category();
            $category->setTitle(self::CATEGORY[$i - 1]);
            $category->setCreatedAt($faker->dateTimeInInterval('-20 days', '+10 days'));
            // persister
            $manager->persist($category);
            // référencer l'illustration
            $this->addReference("category-$i", $category);
        }
        $manager->flush();
    }

    /**
     * Alimenter l'entité Post.
     */
    public function loadSites(ObjectManager $manager)
    {
        // On configure faker pour distribuer des données en francais
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 1; $i <= self::N_POSTS; $i++) {
            $post = new Sites();
            $post->setTitle($faker->sentence($faker->numberBetween(1, 2)))
                ->setDescription($faker->paragraph($faker->numberBetween(1, 3)))
                ->setIllustration($faker->imageUrl(640,  480))
                ->setLink($faker->url())
                ->setPublishedAt($faker->dateTimeInInterval('-20 days', '+10 days'))
                ->setCategory($this->getReference('category-' . $faker->numberBetween(1, count(self::CATEGORY))));
            $manager->persist($post);
        }
        $manager->flush();
    }
}
