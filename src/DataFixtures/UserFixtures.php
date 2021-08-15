<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        for ($nbrUser = 1; $nbrUser <= 4; $nbrUser++) {
            // create user
            $user = new User();
            $user->setNickname('user' . $nbrUser)    //user1, user2, ...
                ->setRoles(['ROLE_USER'])
                ->setEmail('user' . $nbrUser . '@test.com')
                ->setPassword($this->encoder->encodePassword($user, 'user' . $nbrUser))  //user1, user2, ...
                ->setIsVerified(true);

            $manager->persist($user);

            // we save the reference of the user
            $this->addReference('user' . $nbrUser, $user);
        }

        $manager->flush();
    }
}