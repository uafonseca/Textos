<?php

namespace App\EventListener;

use App\AppEvents;
use App\Entity\Book;
use App\Service\CourseVisitService;
use App\Event\VisitEvent;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * VisitListener
 */
class VisitListener implements EventSubscriberInterface
{


    private CourseVisitService $service;

    public function __construct(
        CourseVisitService $service
    ) {
        $this->service = $service;
    }

    /**
     * Method getSubscribedEvents
     *
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            AppEvents::COURSE_VISITED => 'onCourseVisited'
        ];
    }

    public function onCourseVisited(VisitEvent $visitEvent)
    {
        $uuid = $visitEvent->getRouteParams()['uuid'];
        $course = $this->service->getBook($uuid);
        $last = $this->service->getLastVisit($visitEvent->getUser(), $course);

        if (count($last) > 0 && isset($last[0])) {
            $last = $last[0];
            $minutes = 30;
            $maxAge = new DateTime('now');
            $maxAge->modify("-{$minutes} minutes");

            if ($last->getMoment() < $maxAge) {
                
                $visit = $this->service->createVisit();

                $course = $this->service->getBook($uuid);

                $visit
                    ->setUser($visitEvent->getUser())
                    ->setCourse($course);

                $visitEvent->getUser()->addCourseVsit($visit);
                $course->addCourseVsit($visit);
                
                $this->service->update($visit);
            } else {
                return;
            }
        }else{

            $visit = $this->service->createVisit();

            $course = $this->service->getBook($uuid);

            $visit
                ->setUser($visitEvent->getUser())
                ->setCourse($course);

            $visitEvent->getUser()->addCourseVsit($visit);
            $course->addCourseVsit($visit);
            
            $this->service->update($visit);
        }
    }
}
