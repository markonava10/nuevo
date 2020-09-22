<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Keyabble extends Model
{
    public function key_movement()
    {
        return $this->belongsTo(Key_movement::class);
    }
}
