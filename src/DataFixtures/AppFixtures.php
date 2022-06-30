<?php

namespace App\DataFixtures;

use App\Entity\Serie;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private Generator $generator;
    private ObjectManager $manager;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->generator = Factory::create('fr_FR');
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        //$this->addSeries();
        $this->addUsers();

        //recupérer mes séries
        //$series = $this->manager->getRepository(Serie::class)->findAll();
    }

    private function addUsers(){

        for($i = 0; $i < 10; $i++){

            $user = new User();
            $user->setRoles(["ROLE_USER"])
                ->setEmail($this->generator->email)
                ->setFirstname($this->generator->firstName)
                ->setLastname($this->generator->lastName);
            $password = $this->passwordHasher->hashPassword($user, "123456");

            $user->setPassword($password);

            $this->manager->persist($user);
        }

        $this->manager->flush();

    }
    private function addSeries()
    {
        for ($i = 0; $i < 10; $i++) {

            $serie = new Serie();

            $serie->setBackdrop($this->generator->word)
                ->setDateCreated($this->generator->dateTimeBetween("-2 years"))
                ->setFirstAirDate($this->generator->dateTimeBetween($serie->getDateCreated()))
                ->setGenres($this->generator->randomElement(['SF', 'Romantic', 'Far West', 'Comedy']))
                ->setLastAirDate($this->generator->dateTimeBetween($serie->getFirstAirDate()))
                ->setName(implode(" ", $this->generator->words(3)))
                ->setPopularity($this->generator->numberBetween(100, 950))
                ->setVote($this->generator->numberBetween(1, 10))
                ->setPoster($this->generator->word)
                ->setStatus($this->generator->randomElement(['Canceled', 'Returning', 'Ended']))
                ->setTmdbId($this->generator->randomDigitNotNull);

            $this->manager->persist($serie);
        }

        $this->manager->flush();
    }


}
