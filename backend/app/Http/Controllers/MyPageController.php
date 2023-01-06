<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Services\eventService;

class MyPageController extends Controller
{   
    /**
     * eventService
     *
     * @var eventService
     */
    protected $eventService;

    /**
     * __construct
     *
     * @param  MyPageService
     * @return void
     */
    public function __construct(eventService $eventService)
    {
        $this->eventService = $eventService;
    }
        
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $user = User::findOrFail(Auth::id());
        $events = $user->events;
        $eventsFromToday = $this->eventService->getEventsFromToday($events);
        $pastEvents = $this->eventService->getPastEventsByUser($events);

        return view('users.mypages.index', compact([
            'eventsFromToday',
            'pastEvents'
        ]));
    }
}
