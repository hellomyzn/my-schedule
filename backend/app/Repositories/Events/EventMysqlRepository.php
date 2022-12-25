<?php

namespace App\Repositories\Events;

use Illuminate\Support\Facades\DB;

use App\Models\Event;
use App\Repositories\Interfaces\EventRepositoryInterface;

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
     * create
     *
     * @param  array $requestData
     * @return Event
     */
    public function create(array $requestData): Event
    {
        try {
            return DB::transaction(function () use ($requestData) {
                $event = $this->model->create($requestData);

                return $event;
            });
        } catch(Exceptions $e) {
            \Log::error(__METHOD__.'@'.$e->getLine().': '.$e->getMessage());
            dd('hoge');
            return [
                'msg' => $e->getMessage(),
                'err' => false,
            ];
        }
    }
}