<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

use Carbon\Carbon;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Repositories\Interfaces\EventRepositoryInterface;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use App\Models\Event;


class EventService
{    
    
    /**
     * eventRepo
     *
     * @var EventRepositoryInterface
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
     * @param  EventRepositoryInterface
     * @param  ReservationRepositoryInterface
     * @return void
     */
    public function __construct(
        EventRepositoryInterface $eventRepository,
        ReservationRepositoryInterface $reservationRepo
    )
    {  
        $this->eventRepo = $eventRepository;
        $this->reservationRepo = $reservationRepo;
    }

    /**
     * checkEventDuplication
     *
     * @param  string $eventDate
     * @param  string $start_date
     * @param  string $end_date
     * @return bool
     */
    public static function checkEventDuplication(string $eventDate, string $startTime, string $endTime): bool
    {
        $check = DB::table('events')
        ->whereDate('start_date', $eventDate)
        ->whereTime('end_date', '>', $startTime)
        ->whereTime('start_date', '<', $endTime)
        ->exists();
        
        return $check;
    }
    
    /**
     * countEventDuplication
     *
     * @param  string $eventDate
     * @param  string $startTime
     * @param  string $endTime
     * @return int
     */
    public static function countEventDuplication(string $eventDate, string $startTime, string $endTime): int
    {
        $duplicatedEventNumber = DB::table('events')
        ->whereDate('start_date', $eventDate)
        ->whereTime('end_date', '>', $startTime)
        ->whereTime('start_date', '<', $endTime)
        ->count();
        
        return $duplicatedEventNumber;
    }

    
    /**
     * getWeekEvents
     *
     * @param  mixed $startDate
     * @param  mixed $endDate
     * @return Collection
     */
    public static function getWeekEvents(string $startDate, string $endDate): Collection
    {
        $reservedPeople = DB::table('reservations')
            ->select('event_id', DB::raw('sum(number_of_people) as number_of_people'))
            ->whereNull('canceled_date')
            ->groupBy('event_id');
        
        $events = DB::table('events')
            ->leftJoinSub($reservedPeople, 'reservedPeople', function($join){
                $join->on('events.id', '=', 'reservedPeople.event_id');
            })
            ->whereBetween('events.start_date', [$startDate, $endDate])
            ->orderBy('start_date', 'asc')
            ->get();

        return $events;
    }
    
    /**
     * joinDateAndTime
     *
     * @param  string $date
     * @param  string $time
     * @return Carbon
     */
    public static function joinDateAndTime(string $date, string $time): Carbon
    {   
        $dateAndTime = $date . " " . $time;
        $dateAndTimeFormated = Carbon::createFromFormat('Y-m-d H:i', $dateAndTime);
        
        return $dateAndTimeFormated;
    }
    
    /**
     * getFutureEvents
     *
     * @return LengthAwarePaginator
     */
    public function getFutureEvents(): LengthAwarePaginator
    {
        $reservedPeople = $this->reservationRepo->getReservedPeople();
        $events = $this->eventRepo->getFutureEvents($reservedPeople);
        $showPerPage = 10;
        $paginatedEvents = paginateFromCollection($events, $showPerPage);

        return $paginatedEvents;
    }
    
    /**
     * getPastEvents
     *
     * @return LengthAwarePaginator
     */
    public function getPastEvents(): LengthAwarePaginator
    {
        $reservedPeople = $this->reservationRepo->getReservedPeople();
        $events = $this->eventRepo->getPastEvents($reservedPeople);
        $showPerPage = 10;
        $paginatedEvents = paginateFromCollection($events, $showPerPage);

        return $paginatedEvents;
    }
    
    /**
     * getPastEventsByUser
     *
     * @param  Collection $events
     * @return array
     */
    public function getPastEventsByUser(Collection $events): array
    {
        $reservedEvents = [];
        $sortedEvents = $events->sortByDesc('start_date');
        $today = Carbon::today()->format('Y-m-d 00:00:00');

        foreach($sortedEvents as $event){
            if(is_null($event->pivot->canceled_date) && $event->start_date < $today)
            {
                $eventInfo = [
                    'id' => $event->id,
                    'name' => $event->name,
                    'start_date' => $event->start_date,
                    'end_date' => $event->end_date,
                    'number_of_people' => $event->pivot->number_of_people,

                ];

                array_push($reservedEvents, $eventInfo);
            }
        }
        return $reservedEvents;        
    }
    
    /**
     * getEventsFromToday
     *
     * @param  Collection $events
     * @return array
     */
    public function getEventsFromToday(Collection $events): array
    {
        $reservedEvents = [];
        $sortedEvents = $events->sortBy('start_date');
        $today = Carbon::today()->format('Y-m-d 00:00:00');

        foreach($sortedEvents as $event){
            if(is_null($event->pivot->canceled_date) && $event->start_date >= $today)
            {
                $eventInfo = [
                    'id' => $event->id,
                    'name' => $event->name,
                    'start_date' => $event->start_date,
                    'end_date' => $event->end_date,
                    'number_of_people' => $event->pivot->number_of_people,

                ];

                array_push($reservedEvents, $eventInfo);
            }
        }

        return $reservedEvents;
    }

    /**
     * create
     *
     * @param  StoreEventRequest
     * @return Event
     */
    public function create(StoreEventRequest $request): Event
    {
        $startDate = EventService::joinDateAndTime(
            $request['event_date'],
            $request['start_time']
        );
        
        $endDate = EventService::joinDateAndTime(
            $request['event_date'],
            $request['end_time']
        );
        
        $requestData = [
            'name' => $request['event_name'],
            'information' => $request['information'],
            'start_date' => $startDate,
            'end_date' => $endDate,
            'max_people' => $request['max_people'],
            'is_visible' => $request['is_visible'],
        ];
        
        $event = $this->eventRepo->create($requestData);

        return $event;
    }
    
    /**
     * update
     *
     * @param  UpdateEventRequest
     * @return Event
     */
    public function update(UpdateEventRequest $request, int $id): Event
    {
        $startDate = EventService::joinDateAndTime(
            $request['event_date'],
            $request['start_time']
        );
        
        $endDate = EventService::joinDateAndTime(
            $request['event_date'],
            $request['end_time']
        );
        
        $requestData = [
            'name' => $request['event_name'],
            'information' => $request['information'],
            'start_date' => $startDate,
            'end_date' => $endDate,
            'max_people' => $request['max_people'],
            'is_visible' => $request['is_visible'],
        ];
        
        $event = $this->eventRepo->update($requestData, $id);

        return $event;
    }
}