<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Services\EventService;
use App\Services\ReservationService;
use App\Repositories\Interfaces\EventRepositoryInterface;
use App\Repositories\Interfaces\ReservationRepositoryInterface;

class EventController extends Controller
{    
    /**
     * eventService
     *
     * @var EventService
     */
    protected $eventService;
        
    /**
     * eventRepo
     *
     * @var EventRepositoryInterface
     */
    protected $eventRepo;

    /**
     * __construct
     *
     * @param  EventService
     * @param  EventRepositoryInterface
     * @return void
     */
    public function __construct(
        EventService $eventService,
        EventRepositoryInterface $eventRepository
    )
    {
        $this->eventService = $eventService;
        $this->eventRepo = $eventRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = $this->eventService->getFutureEvents();
        return view('managers.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('managers.events.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreEventRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEventRequest $request)
    {
        $check = EventService::checkEventDuplication(
            $request['event_date'],
            $request['start_time'],
            $request['end_time']
        );

        if($check){
            session()->flash('status', 'この時間帯はすでに他の予約が存在します');
            return to_route('managers.events.create');
        }
        
        $event = $this->eventService->create($request);

        session()->flash('status', 'イベントが登録されました');

        return to_route('managers.events.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        $today = Carbon::today()->format('Y年m月d日');
        $event = $this->eventRepo->getById($event->id);
        $reservedUsers = $this->eventRepo->getReservedUsers($event->id);
        $reservations = ReservationService::createReservationArrayByUsers($reservedUsers);
        $eventDate = $event->eventDate;
        $startTime = $event->startTime;
        $endTime = $event->endTime;
        
        return view('managers.events.show', 
            compact([
                'event',
                'reservedUsers',
                'reservations',
                'eventDate',
                'startTime',
                'endTime',
                'today'
            ]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        $event = $this->eventRepo->getById($event->id);

        #reformat from Japanese to Y-m-d. It's for UpdateEventRequest validation.
        $eventDate = $event->editEventDate;
        $startTime = $event->startTime;
        $endTime = $event->endTime;
        
        return view('managers.events.edit', 
            compact([
                'event',
                'eventDate',
                'startTime',
                'endTime'
            ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEventRequest  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        $duplicatedEventNumber = EventService::countEventDuplication(
            $request['event_date'],
            $request['start_time'],
            $request['end_time']
        );

        if($duplicatedEventNumber > 1){
            session()->flash('status', 'この時間帯はすでに他の予約が存在します');
            return to_route('managers.events.edit', $event->id);
        }

        $event = $this->eventService->update($request, $event->id);

        session()->flash('status', 'イベントを更新しました');

        return to_route('managers.events.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        //
    }
    
    /**
     * past
     * @return \Illuminate\Http\Response
     */
    public function past()
    {
        $events = $this->eventService->getPastEvents();
        return view('managers.events.past', compact('events'));
    }
}
