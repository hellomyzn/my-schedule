<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Models\User;
use App\Models\Event;
use App\Models\Reservation;
use App\Services\eventService;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\EventRepositoryInterface;
use App\Repositories\Interfaces\ReservationRepositoryInterface;

class MyPageController extends Controller
{   
    /**
     * eventService
     *
     * @var eventService
     */
    protected $eventService;
    
    /**
     * userRepo
     *
     * @var UserRepositoryInterface
     */
    protected $userRepo;
    
    /**
     * eventRepo
     *
     * @var EventRepositoryInterface
     */    
    /**
     * eventRepo
     *
     * @var mixed
     */
    protected $eventRepo;
    
    /**
     * reservationRepo
     *
     * @var ReservationRepositoryInterface
     */
    protected $reservationRepo;

    /**
     * __construct
     *
     * @param  MyPageService
     * @return void
     */
    public function __construct(
        eventService $eventService,
        UserRepositoryInterface $userRepository,
        EventRepositoryInterface $eventRepository,
        ReservationRepositoryInterface $reservationRepository
    )
    {
        $this->eventService = $eventService;
        $this->userRepo = $userRepository;
        $this->eventRepo = $eventRepository;
        $this->reservationRepo = $reservationRepository;
    }
        
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $user = $this->userRepo->getAuthUser();
        $events = $user->events;
        $eventsFromToday = $this->eventService->getEventsFromToday($events);
        $pastEvents = $this->eventService->getPastEventsByUser($events);

        return view('users.mypages.index', compact([
            'eventsFromToday',
            'pastEvents'
        ]));
    }
    
    /**
     * show
     *
     * @return void
     */
    public function show($id)
    {
        $event = $this->eventRepo->getById($id);
        $eventDate = $event->eventDate;
        $startTime = $event->startTime;
        $endTime = $event->endTime;
        $today = Carbon::today()->format('Y年m月d日');
        $user = $this->userRepo->getAuthUser();
        $reservation = $this->reservationRepo->getReservationByUserIdAndEventId($user->id, $event->id);

        return view('users.mypages.show', compact([
            'event',
            'reservation',
            'eventDate',
            'startTime',
            'endTime',
            'today'
        ]));
    }
}
