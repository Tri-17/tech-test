<?php


namespace AppBundle\DataFixtures;

use AppBundle\Entity\Newsletter;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i < 4; $i++) {
            $newsletter = new Newsletter();
            $newsletter->setName('Newsletter '.$i);

            $manager->persist($newsletter);
        }

        $manager->flush();
    }
}