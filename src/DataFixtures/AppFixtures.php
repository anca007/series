<?php

namespace App\DataFixtures;

use App\Entity\Serie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->addSeries($manager);
    }

    private function addSeries(ObjectManager $manager)
    {

        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 50; $i++) {

            $serie = new Serie();
            $serie
                ->setBackdrop("default.png")
                ->setDateCreated($faker->dateTimeBetween(new \DateTime("-6 month"), new \DateTime()))
                ->setGenres($faker->randomElement(["SF", "Comedy", "Thiller", "Action"]))
                ->setName($faker->word())
                ->setFirstAirDate($faker->dateTimeBetween(new \DateTime("-2 year"), $serie->getDateCreated()))
                ->setPopularity($faker->numberBetween(0, 1000))
                ->setPoster('poster.png')
                ->setStatus($faker->randomElement(["Ending", "Returning", "Canceled"]))
                ->setTmdbId($faker->randomDigitNotNull)
                ->setVote($faker->numberBetween(0, 10));

            $manager->persist($serie);
        }

        $manager->flush();

    }
}
