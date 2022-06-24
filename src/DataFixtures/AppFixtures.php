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
        $generator = Factory::create('fr_FR');


        for ($i = 0; $i < 10; $i++) {

            $serie = new Serie();

            $serie->setBackdrop($generator->word)
                ->setDateCreated($generator->dateTimeBetween("-2 years"))
                ->setFirstAirDate($generator->dateTimeBetween($serie->getDateCreated()))
                ->setGenres($generator->randomElement(['SF', 'Romantic', 'Far West', 'Comedy']))
                ->setLastAirDate($generator->dateTimeBetween($serie->getFirstAirDate()))
                ->setName(implode(" ", $generator->words(3)))
                ->setPopularity($generator->numberBetween(100, 950))
                ->setVote($generator->numberBetween(1, 10))
                ->setPoster($generator->word)
                ->setStatus($generator->randomElement(['Canceled', 'Returning', 'Ended']))
                ->setTmdbId($generator->randomDigitNotNull);

            $manager->persist($serie);
        }

        $manager->flush();
    }
}
