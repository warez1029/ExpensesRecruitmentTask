<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
     protected $fillable = [
        'name', 'value', 'status',
    ];

    public function expense()
    {
        return $this->belongsTo('App\Expense');
    }
}
