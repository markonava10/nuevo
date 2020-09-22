<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Difference extends Model
{
    protected $table = 'differences';
	protected $fillable =  [
        'register_id',
        'opening_id',
        'cashier_reports',
        'cashier_responsible',
        'authorizer_id',
        'amount',
        'type',
        'status_id'
	];

    /*+----------------------------------------------------------+
      | Uno - Uno: Un Total tiene asociado otro registro       |
      +----------------------------------------------------------+
    */

    // La diferencia es de una CAJA
    public function register()
    {
        return $this->belongsTo(register::class);
    }

    // La dierencia es de una APERTURA
    public function opening()
    {
        return $this->belongsTo(Opening::class);
    }

    // La dierencia la reorta un usuario
    public function cashier_reports()
    {
        return $this->belongsTo(User::class,'cashier_reports');
    }

    // La dierencia la generó o es de un usuario responsable
    public function cashier_responsible()
    {
        return $this->belongsTo(User::class,'cashier_responsible');
    }

    // La dierencia la autorizó un usuario
    public function authorizer()
    {
        return $this->belongsTo(User::class,'authorizer_id');
    }

    // La dierencia tiene un estado (status)
    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    /*+---------------------------------+
      | Búsquedas x diferentes Criterios |
      +----------------------------------+
    */

    // De una caja
    public function scopeRegisterId($query,$valor){
        if ( trim($valor) != "") {    
            $query->where('register_id','=',$valor); 
        }    
    }

    // De una apertura
    public function scopeOpeningId($query,$valor){
     if ( trim($valor) != "") {
            $query->where('opening_id','=',$valor);      
        }
    }
    

    // De un usuario que reportó
    public function scopeCashierreportsId($query,$valor){
     if ( trim($valor) != "") {
            $query->where('cashier_reports','=',$valor);      
        }
    }

    // De un usuario responsable
    public function scopeCashierResponsibleId($query,$valor){
     if ( trim($valor) != "") {
            $query->where('cashier_responsible','=',$valor);      
        }
    }


    // De un usuario que autorizó
    public function scopeAuthorizerId($query,$valor){
     if ( trim($valor) != "") {
            $query->where('authorizer_id','=',$valor);      
        }
    }

    // De un estado (status)
    public function scopeStatusId($query,$valor){
     if ( trim($valor) != "") {
            $query->where('status_id','=',$valor);      
        }
    }


    // De un tipo en específico
    public function scopeType($query,$valor){
     if ( trim($valor) != "") {
            $query->where('type','=',$valor);      
        }
    }

}
