<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    
    public function load(ObjectManager $manager)
    {
        $this->geograpich($manager);
        $role = new Role();
        $role->setRolename('ROLE_SUPER_ADMIN');
        $manager->persist($role);
        $user = new User();

        $password = $this->encoder->encodePassword($user, 'asd123');
        $role->addUser($user);
        $user
        ->setUsername('admin')
        ->setName('Ubel')
        ->setFirstName('Fonseca')
        ->setPassword($password)
        ->setEmail('ubelangelfonseca@gmail.com')
        ->addRoleObj($role)
        ;

        $manager->persist($user);
        $manager->flush();
    }


    public function geograpich(ObjectManager $manager)
    {
        $finder = new Finder();
        $finder->in(__DIR__ . '/Data');
        $finder->name('*.sql');
        $finder->files();
        // $finder->sortByName();

        foreach( $finder as $file ){
            print "Importing: {$file->getBasename()} " . PHP_EOL;

            $sql = $file->getContents();

            $manager->getConnection()->exec($sql);


            $manager->flush();
        }

        $manager->flush();
    }
}
