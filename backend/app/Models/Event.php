<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

use App\Models\User;

class Event extends Model
{
    use HasFactory;
    
    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'information',
        'max_people',
        'start_date',
        'end_date',
        'is_visible'
    ];
    
    const STATUS = [
        "OPEN" => 1, 
        "CLOSE" => 0,
    ];

    public function users() 
    {
        return $this->belongsToMany(User::class, 'reservations')
            ->withPivot('id', 'number_of_people', 'canceled_date');
    }

    /**
     * eventDate
     *
     * @return Attribute
     */
    protected function eventDate(): Attribute
    {
        return new Attribute(
            get: fn() => Carbon::parse($this->start_date)->format('Y年m月d日')
        );
    }
    
    /**
     * editEventDate
     * This is for avoiding UpdateEventRequest validation.
     * 
     * @return Attribute
     */
    protected function editEventDate(): Attribute
    {
        return new Attribute(
            get: fn() => Carbon::parse($this->start_date)->format('Y-m-d')
        );
    }
    
    /**
     * startTime
     *
     * @return Attribute
     */
    protected function startTime(): Attribute
    {
        return new Attribute(
            get: fn() => Carbon::parse($this->start_date)->format('H時i分')
        );
    }
    
    /**
     * endTime
     *
     * @return Attribute
     */
    protected function endTime(): Attribute
    {
        return new Attribute(
            get: fn() => Carbon::parse($this->end_date)->format('H時i分')
        );
    }
}
