<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Event;
use App\Models\Reservation;
use App\Services\eventService;
use App\Repositories\Interfaces\UserRepositoryInterface;

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
     * __construct
     *
     * @param  MyPageService
     * @return void
     */
    public function __construct(
        eventService $eventService,
        UserRepositoryInterface $userRepo
    )
    {
        $this->eventService = $eventService;
        $this->userRepo = $userRepo;
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
    public function show()
    {
        
    }
}
