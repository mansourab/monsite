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
        // $product = new Product();
        $user = new User();
        $user->setEmail('abdoulaye.mansour@gmail.com');
        $user->setFullName('mansour abdoulaye');
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'admin'
        ));
        // $manager->persist($product);
        $manager->persist($user);

        $manager->flush();
    }
}
