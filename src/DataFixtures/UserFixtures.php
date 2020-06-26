<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();

        $user->setActive(1)
        ->setUsername('userAdmin')
            ->setName('User admin')
            ->setSurname('Admin')
            ->setEmail('foodieangel@foodieangel.com')
            ->setRoles(['ROLE_ADMIN'])
            ->setActive(1)
            ->setDatecreate(new \DateTime());
        // ...

        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'contraseña'
        ))
            ->setApiToken(bin2hex(password_hash(bin2hex(random_bytes(60)).$user->getId(), PASSWORD_BCRYPT, ['cost'=>12])."\n"));

        $manager->persist($user);
        $manager->flush();


    }
}