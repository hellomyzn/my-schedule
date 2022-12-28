<?php

namespace App\Repositories\Events;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Database\Query\Builder;
use Carbon\Carbon;


use App\Models\Event;
use Illuminate\Database\Eloquent\Model;

use App\Repositories\Interfaces\EventRepositoryInterface;
use App\Repositories\Interfaces\ReservationRepositoryInterface;

class EventMysqlRepository implements EventRepositoryInterface
{
    
    /**
     * model
     *
     * @var Event
     */
    protected $model;

    public function __construct(Event $event)
    {
        $this->model = $event;
    }
            
    /**
     * getById
     *
     * @param  int $id
     * @return Model
     */
    public function getById(int $id): Model
    {
        try {
                $event = $this->model->findOrFail($id);

                return $event;
        } catch(Exceptions $e) {
            \Log::error(__METHOD__.'@'.$e->getLine().': '.$e->getMessage());

            return [
                'msg' => $e->getMessage(),
                'err' => false,
            ];
        }
    }

    public function getEventUsers(int $id): Collection
    {
        try {
            $event = $this->model->findOrFail($id);
            $users = $event->users;

            return $users;
        } catch(Exceptions $e) {
            \Log::error(__METHOD__.'@'.$e->getLine().': '.$e->getMessage());

            return [
                'msg' => $e->getMessage(),
                'err' => false,
            ];
        }        
    }
    
    /**
     * getFutureEvents
     *
     * @param  mixed $reservedPeople
     * @return Collection
     */
    public function getFutureEvents(Builder $reservedPeople): Collection
    {
        try {
            $today = Carbon::today();
            $events = DB::table('events')
                ->leftJoinSub($reservedPeople, 'reservedPeople', function($join){
                    $join->on('events.id', '=', 'reservedPeople.event_id');
                })
                ->whereDate('start_date', '>', $today)
                ->orderBy('start_date', 'asc')
                ->get();

            return $events;
        } catch(Exceptions $e) {
            \Log::error(__METHOD__.'@'.$e->getLine().': '.$e->getMessage());

            return [
                'msg' => $e->getMessage(),
                'err' => false,
            ];
        }
    }
        
    /**
     * getPastEvents
     *
     * @return Collection
     */
    public function getPastEvents(Builder $reservedPeople): Collection
    {
        try {
            $today = Carbon::today();
            $events = DB::table('events')
                ->leftJoinSub($reservedPeople, 'reservedPeople', function($join){
                    $join->on('events.id', '=', 'reservedPeople.event_id');
                })
                ->whereDate('start_date', '<', $today)
                ->orderBy('start_date', 'desc')
                ->get();

            return $events;
        } catch(Exceptions $e) {
            \Log::error(__METHOD__.'@'.$e->getLine().': '.$e->getMessage());

            return [
                'msg' => $e->getMessage(),
                'err' => false,
            ];
        }
    }

    /**
     * create
     *
     * @param  array $requestData
     * @return Model
     */
    public function create(array $requestData): Model
    {
        try {
            return DB::transaction(function () use ($requestData) {
                $event = $this->model->create($requestData);

                return $event;
            });
        } catch(Exceptions $e) {
            \Log::error(__METHOD__.'@'.$e->getLine().': '.$e->getMessage());

            return [
                'msg' => $e->getMessage(),
                'err' => false,
            ];
        }
    }
    
    /**
     * update
     *
     * @param  array $requestData
     * @param  int $id
     * @return Model
     */
    public function update(array $requestData, int $id): Model
    {
        try {
            return DB::transaction(function () use($requestData, $id) {
                $event = $this->getById($id);
                $event->name = $requestData['name'];
                $event->information = $requestData['information'];
                $event->start_date = $requestData['start_date'];
                $event->end_date = $requestData['end_date'];
                $event->max_people = $requestData['max_people'];
                $event->is_visible = $requestData['is_visible'];
                $event->save();

                return $event;
            });
        } catch(Exceptions $e) {
            \Log::error(__METHOD__.'@'.$e->getLine().': '.$e->getMessage());

            return [
                'msg' => $e->getMessage(),
                'err' => false,
            ];
        }
    }
}