<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'name', 
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function payments()
    {
        return $this->hasMany('App\Payment');
    }
}
