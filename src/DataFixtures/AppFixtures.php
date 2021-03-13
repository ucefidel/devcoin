<?php

namespace App\DataFixtures;

use App\Entity\Annonce;
use App\Entity\Category;
use App\Entity\Favoris;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use DateTime;

class AppFixtures extends Fixture
{
    private UserPasswordEncoderInterface $encoder;

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
            ->setAdress($faker->address)
            ->setUsername($faker->userName)
            ->setCreatedAt(new DateTime())
            ->setUpdatedAt(new DateTime())
            ->setPhoneNumber($faker->phoneNumber)
            ->setCity($faker->city)
            ->setCountry($faker->country)
            ->setBirthAt($faker->dateTime)
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
                ->setPassword($hash)
                ->setUsername($faker->userName)
                ->setCreatedAt(new DateTime())
                ->setUpdatedAt(new DateTime())
                ->setCountry($faker->country)
                ->setAdress($faker->address)
                ->setPhoneNumber($faker->phoneNumber)
                ->setCity($faker->city)
                ->setBirthAt($faker->dateTime);

            $users[] = $user;

            $manager->persist($user);
        }
        $categories = [];
        for ($k = 0; $k < 10; $k++) {
            $category = new Category();
            $category->setName($faker->company);

            $categories[] = $category;
            $manager->persist($category);
        }
        for ($j = 0; $j < 20; $j++) {
            $annonce = new Annonce();

            $annonce->setTitle($faker->name)
                ->setUser($faker->randomElement($users))
                ->setDescription($faker->paragraph)
                ->setPicture("https://loremflickr.com/320/240?lock=" . $faker->numberBetween(1, 99))
                ->setPrice($faker->randomFloat())
                ->setShowing($faker->randomElement([true, false]))
                ->setLocalisation($faker->country)
                ->setCategory($faker->randomElement($categories));

            $manager->persist($annonce);

            $favoris = new Favoris();
            $favoris->setAnnonce($annonce);
            $favoris->setUser($faker->randomElement($users));

            $manager->persist($favoris);
        }
        $manager->flush();
    }
}
