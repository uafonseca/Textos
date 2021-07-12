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
        $this->loadDefaultRoles($manager);
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

    public function loadDefaultRoles(ObjectManager $manager){
        $role = new Role();
        $role->setRolename('ROLE_USER');
        $manager->persist($role);

        print "Generating role: {$role->getRolename()} " . PHP_EOL;


        $role1 = new Role();
        $role1->setRolename('ROLE_ADMIN');
        $manager->persist($role1);

        print "Generating role: {$role1->getRolename()} " . PHP_EOL;

        $role2 = new Role();
        $role2->setRolename('ROLE_PROFESOR');
        $manager->persist($role2);

        print "Generating role: {$role2->getRolename()} " . PHP_EOL;

        $role3 = new Role();
        $role3->setRolename('ROLE_CAPACITADOR_EXTERNO');
        $manager->persist($role3);

        print "Generating role: {$role3->getRolename()} " . PHP_EOL;

        $role4 = new Role();
        $role4->setRolename('ROLE_CAPACITADOR_INTERNO');
        $manager->persist($role4);

        print "Generating role: {$role4->getRolename()} " . PHP_EOL;

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
