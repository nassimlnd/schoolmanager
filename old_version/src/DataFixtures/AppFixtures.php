<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $adminUser = new User();

        $adminUser->setFirstName('Nassim');
        $adminUser->setLastName('LOUNADI');
        $adminUser->setEmail('nassimlnd37@gmail.com');
        $adminUser->setRoles(['ROLE_ADMIN']);
        $adminUser->setPassword('$2y$13$Udp3wFdmy5kQBA8gXx/MHO.bKmNpxbGWcZ/48P8J4Ro71LX0/M9jW'); // password

        $user = new User();
        $user->setFirstName('John');
        $user->setLastName('Doe');
        $user->setEmail('john.doe@mail.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword('$2y$13$Udp3wFdmy5kQBA8gXx/MHO.bKmNpxbGWcZ/48P8J4Ro71LX0/M9jW'); // password

        $studentUser = new User();
        $studentUser->setFirstName('Jane');
        $studentUser->setLastName('Doe');
        $studentUser->setEmail('jane.doe@mail.com');
        $studentUser->setRoles(['ROLE_STUDENT']);
        $studentUser->setPassword('$2y$13$Udp3wFdmy5kQBA8gXx/MHO.bKmNpxbGWcZ/48P8J4Ro71LX0/M9jW'); // password

        $manager->persist($adminUser);
        $manager->persist($user);
        $manager->persist($studentUser);
        $manager->flush();
    }
}
