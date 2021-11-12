<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Picture;
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
        // we create an administrator 
        $pictureAdmin = new Picture();
        $pictureAdmin->setPictureFileName('persona.png');
        $pictureAdmin->setAlt('pictureAdmin');
        $manager->persist($pictureAdmin);

        $admin = new User();
        $admin->setNickname('admin')
            ->setRoles(['ROLE_ADMIN'])
            ->setEmail('adminTchenio@test.com')
            ->setPicture($pictureAdmin)
            ->setPassword($this->encoder->encodePassword($admin, 'tcheni@_re@9741975'))
            ->setIsVerified(true);

        $manager->persist($admin);

        // we create the users 
        for ($nbrUser = 1; $nbrUser <= 4; $nbrUser++) {

            // we create the Persona picture (default picture of users registered on the site) (the image file "persona.png" is already in the "public > pictures > site" folder )
            $picture = new Picture();
            $picture->setPictureFileName('persona.png');
            $picture->setAlt('pictureDefaultPersona' . $nbrUser);
            $manager->persist($picture);

            // create user
            $user = new User();
            $user->setNickname('user' . $nbrUser)    //user1, user2, ...
                // ->setRoles(['ROLE_USER'])
                ->setEmail('user' . $nbrUser . '@test.com')
                ->setPicture($picture)
                ->setPassword($this->encoder->encodePassword($user, 'user' . $nbrUser))  //user1, user2, ...
                ->setIsVerified(true);

            $manager->persist($user);

            // we save the reference of the user
            $this->addReference('user' . $nbrUser, $user);
        }

        $manager->flush();
    }
}
