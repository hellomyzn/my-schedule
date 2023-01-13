<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\ReserveRequest;
use App\Models\Event;
use App\Models\Reservation;
use App\Services\ReservationService;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;

class ReservationController extends Controller
{
    /**
     * eventRepo
     *
     * @var ReservationService
     */
    protected $reservationService;
    
    /**
     * reservationRepo
     *
     * @var ReservationRepositoryInterface
     */
    protected $reservationRepo;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct(
        ReservationService $reservationService,
        ReservationRepositoryInterface $reservationRepository,
        UserRepositoryInterface $userRepository,
        
    )
    {
        $this->reservationService = $reservationService;
        $this->reservationRepo = $reservationRepository;
        $this->userRepo = $userRepository;
    }
        
    /**
     * dashboard
     *
     * @return void
     */
    public function dashboard()
    {
        return view('users.reservations.dashboard');
    }
    
    /**
     * detail
     *
     * @param  mixed $id
     * @return void
     */
    public function detail($id)
    {
        $event = Event::findOrFail($id);
        $eventDate = $event->eventDate;
        $startTime = $event->startTime;
        $endTime = $event->endTime;
        $reservablePeople = $this->reservationService->getNumReservablePeople($event);
        $user = $this->userRepo->getAuthUser();
        $isReserved = $this->reservationRepo->isReserved($user->id, $event->id);
        
        return view('users.reservations.event-detail', 
            compact([
                'event', 
                'eventDate',
                'startTime',
                'endTime',
                'reservablePeople',
                'isReserved'
            ]));
    }
    
    /**
     * reserve
     *
     * @param  mixed $request
     * @return void
     */
    public function reserve(ReserveRequest $request, $id)
    {
        $event = Event::findOrFail($id);
        $numReservablePeople = $this->reservationService->getNumReservedPeople($id);
        $reservedPeopleAndRequestNumber = $numReservablePeople + $request->reservablePeople;

        if($event->max_people >= $reservedPeopleAndRequestNumber ){
            $this->reservationService->create($request);
            session()->flash('status', '予約が完了しました。');
            return to_route('dashboard');
        } else{
            session()->flash('status', 'この人数は予約できません。');
            return to_route('dashboard');
        }
    }
}
