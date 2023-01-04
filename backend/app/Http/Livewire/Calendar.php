<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Carbon\CarbonImmutable;

use App\Models\Event;
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
     * checkDay
     *
     * @var mixed
     */
    public $checkDay;
    
    /**
     * dayOfWeek
     *
     * @var mixed
     */
    public $dayOfWeek;

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
     * eventsOnCalendar
     *
     * @var mixed
     */
    public $eventsOnCalendar;


    /**
     * mount
     *
     * @return void
     */
    public function mount()
    {
        $this->currentDate = CarbonImmutable::today();
        $this->seventDaysLater = $this->currentDate->addDays(7);
        $this->currentWeek = [];
        $this->eventsOnCalendar = [];
        
        $this->events = EventService::getWeekEvents(
            $this->currentDate->format('Y-m-d'),
            $this->seventDaysLater->format('Y-m-d'),
        );

        for ($i = 0; $i < 7; $i++){
            $this->day = $this->currentDate->addDays($i)->format('m月d日');
            $this->checkDay = $this->currentDate->addDays($i)->format('Y-m-d');
            $this->dayOfWeek = $this->currentDate->addDays($i)->dayName;
            array_push($this->currentWeek, [
                'day' => $this->day, 
                'checkDay' => $this->checkDay, 
                'dayOfWeek' => $this->dayOfWeek
            ]);
            
            for ($j = 0; $j < 21; $j++){
                $dateTime =  $this->currentWeek[$i]['checkDay'] . " " . Event::EVENT_TIME[$j];
                $this->eventsOnCalendar[$i][$j] = $this->events->firstWhere('start_date', $dateTime);
            }
        }
    }

    public function getDate($date)
    {
        $this->currentDate = $date;
        $this->currentWeek = [];
        $this->seventDaysLater = CarbonImmutable::parse($this->currentDate)->addDays(7);

        $this->events = EventService::getWeekEvents(
            CarbonImmutable::parse($this->currentDate)->format('Y-m-d'),
            $this->seventDaysLater->format('Y-m-d'),
        );
        
        for ($i = 0; $i < 7; $i++){
            
            $this->day = CarbonImmutable::parse($this->currentDate)->addDays($i)->format('m月d日');
            $this->checkDay = CarbonImmutable::parse($this->currentDate)->addDays($i)->format('Y-m-d');
            $this->dayOfWeek = CarbonImmutable::parse($this->currentDate)->addDays($i)->dayName;
            array_push($this->currentWeek, [
                'day' => $this->day, 
                'checkDay' => $this->checkDay, 
                'dayOfWeek' => $this->dayOfWeek
            ]);

            for ($j = 0; $j < 21; $j++){
                $dateTime =  $this->currentWeek[$i]['checkDay'] . " " . Event::EVENT_TIME[$j];
                $this->eventsOnCalendar[$i][$j] = $this->events->firstWhere('start_date', $dateTime);
            }
        }
    }
    
    public function render()
    {
        return view('livewire.calendar');
    }
}
