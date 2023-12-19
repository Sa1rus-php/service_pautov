<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminFixtures extends Fixture
{
    private $encoder;

    private $em;

    public function __construct(UserPasswordHasherInterface $encoder, EntityManagerInterface $entityManager)
    {
        $this->encoder = $encoder;
        $this->em = $entityManager;
    }

    public function load(\Doctrine\Persistence\ObjectManager $manager)
    {
        $adminsData = [
            0 => [
                'email' => 'admin@example.com',
                'role' => ['ROLE_ADMIN'],
                'password' => 123654
            ]
        ];

        foreach ($adminsData as $admin) {
            $newAdmin = new Admin();
            $newAdmin->setEmail($admin['email']);
            $newAdmin->setPassword($this->encoder->hashPassword($newAdmin, $admin['password']));
            $newAdmin->setRoles($admin['role']);
            $this->em->persist($newAdmin);
        }

        $this->em->flush();
    }
}
