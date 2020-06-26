<?php
namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }


    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('jmboehme@hps.es')
            ->setUsername('admin')
            ->setName('admin')
            ->setRoles(['ROLE_ADMIN'])
            ->setSurname('administrator')
            ->setActive(1)
            ->setDatecreate(new \DateTime());
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'admin123'
        ))->setApiToken(bin2hex(password_hash(bin2hex(random_bytes(60)).$user->getId(), PASSWORD_BCRYPT, ['cost'=>12])."\n"));
        $manager->persist($user);
        $manager->flush();
        return $user;
    }
}