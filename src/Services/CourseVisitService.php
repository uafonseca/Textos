<?php
namespace App\Service;

use App\Entity\Book;
use App\Entity\CourseVsit;
use App\Entity\User;
use App\Repository\CourseVsitRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class CourseVisitService
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var CourseVsitRepository
     */
    protected $repository;

       
    /**
     * Method __construct
     *
     * @param EntityManagerInterface $entityManager [explicite description]
     *
     * @return void
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(CourseVsit::class);
    }

        
    /**
     * Method createVisit
     *
     * @return CourseVsit
     */
    public function createVisit(){
        $visit = new CourseVsit();
        $visit->setMoment(new DateTime('now'));
        return $visit;
    }
    

    public function update(CourseVsit $visit, $andFlush = true){
        $this->entityManager->persist($visit);
        if ($andFlush ) 
            $this->entityManager->flush();
    }
       
    /**   
     * Method getLastVisit
     *
     * @param User $user [explicite description]
     * @param $book_uuid $book_uuid [explicite description]
     *
     * @return []
     */
    public function getLastVisit(User $user, Book $book_uuid){
        return $this->repository->getLastVisit($user, $book_uuid);
    }
    
    /**
     * Method getBook
     *
     * @param $uuid $uuid [explicite description]
     *
     * @return Book
     */
    public function getBook($uuid):Book{
        return  $this->entityManager->getRepository(Book::class)->findOneBy([
            'uuid' => $uuid
        ]);
    }


}        