<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Total extends Model
{
    protected $table = 'totals';
	protected $fillable =  [
		'customer_id',
		'service_id_1',
		'total1',
		'service_id_2',
		'total2'
	];

    /*+----------------------------------------------------------+
      | Uno - Uno: Un Total tiene asociado otro registro       |
      +----------------------------------------------------------+
    */

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }


    // Un total pertenece a un cservicio
    public function service1()
    {
        return $this->hasOne(Service::class,'service_id_1');
    }

    public function service2()
    {
        return $this->hasOne(Service::class,'service_id_2');
    }

    /*+---------------------------------------------+
      | Funciones de apoyo                          |
      +---------------------------------------------+
     */
     
     public function has_difference(){
        if ($this->total2 > $this->total1) {
            return true;
        }
        return false;
     }
}