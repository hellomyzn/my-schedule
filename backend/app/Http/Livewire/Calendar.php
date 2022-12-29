<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Carbon\Carbon;

use App\Services\EventService;

class Calendar extends Component
{   
    /**
     * currentDate
     *
     * @var mixed
     */
    public $currentDate;    
    
    /**
     * currentWeek
     *
     * @var mixed
     */
    public $currentWeek;
    
    /**
     * day
     *
     * @var mixed
     */
    public $day;
    
    /**
     * day
     *
     * @var mixed
     */
    public $seventDaysLater;

    /**
     * day
     *
     * @var mixed
     */
    public $events;

    /**
     * mount
     *
     * @return void
     */
    public function mount()
    {
        $this->currentDate = Carbon::today();
        $this->seventDaysLater = $this->currentDate->addDays(7);
        $this->currentWeek = [];
        
        $this->events = EventService::getWeekEvents(
            $this->currentDate->format('Y-m-d'),
            $this->seventDaysLater->format('Y-m-d'),
        );

        for ($i = 0; $i < 7; $i++){
            $this->day = Carbon::today()->addDays($i)->format('m月d日');
            array_push($this->currentWeek, $this->day);
        }
    }

    public function getDate($date)
    {
        $this->currentDate = $date;
        $this->currentWeek = [];
        $this->seventDaysLater = Carbon::parse($this->currentDate)->addDays(7);

        $this->events = EventService::getWeekEvents(
            Carbon::parse($this->currentDate)->format('Y-m-d'),
            $this->seventDaysLater->format('Y-m-d'),
        );
        
        for ($i = 0; $i < 7; $i++){
            $this->day = Carbon::parse($this->currentDate)->addDays($i)->format('m月d日');
            array_push($this->currentWeek, $this->day);
        }
    }
    
    public function render()
    {
        return view('livewire.calendar');
    }
}
