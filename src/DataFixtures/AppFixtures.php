<?php

namespace App\DataFixtures;

use App\Entity\Annonce;
use App\Entity\Favoris;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create("fr_FR");
        $users = [];

        // User Admin --------
        $user = new User();
        $hash = $this->encoder->encodePassword($user, "password");
        $user->setFirstName("Youssef")
            ->setLastName("Idelhadj")
            ->setPassword($hash)
            ->setEmail("yidelhadj@ilyeum.com");

        $users[] = $user;
        $manager->persist($user);
        // -------------------

        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $hash = $this->encoder->encodePassword($user, "password");
            $user->setEmail($faker->email)
                ->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setPassword($hash);

            $users[] = $user;

            $manager->persist($user);
        }

        for ($j = 0; $j < 20; $j++) {
            $annonce = new Annonce();

            $annonce->setTitle($faker->name)
                ->setUser($faker->randomElement($users))
                ->setDescription($faker->paragraph)
                ->setPicture($faker->domainName);
            $manager->persist($annonce);

            $favoris = new Favoris();
            $favoris->setAnnonce($annonce);
            $favoris->setUser($faker->randomElement($users));

            $manager->persist($favoris);
        }
        $manager->flush();
    }
}
