<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionsByDay extends Model
{
    protected $table = 'transactions_by_day';
  public $timestamps = false; 

  protected $fillable =  [
    'user_id',
    'subsidiary_id',
    'register_id',
    'service_id',
    'provider_id',
    'mon_qty',
    'mon_amount',
    'tue_qty',
    'tue_amount',
    'wed_qty',
    'wed_amount',
    'thu_qty',
    'thu_amount',
    'fri_qty',
    'fri_amount',
    'sat_qty',
    'sat_amount',
    'sun_qty',
    'sun_amount',
    'tot_qty',
    'tot_amount',
    'days_query'
  ];

  	/*+-----------------------------+
      | Relaciones entre tablas     |
      +-----------------------------+
    */

    /*+----------------------------------------------------------+
      | Uno - Muchos: Pertenece a una Tabla (Es hija de)         |
      +----------------------------------------------------------+
    */
  	
    // transactions_by_day -->User (La consulta la hizo un usuario)

  	public function user()
  	{
  		return $this->belongsTo(User::class);
  	}

    // transactions_by_day -->Subsidiary (La consulta es de una sucursal)

    public function subsidiary()
    {
      return $this->belongsTo(Subsidiary::class);
    }


    // transactions_by_day -->Register (La consulta es de una caja)

    public function register()
    {
      return $this->belongsTo(Register::class);
    }

    // transactions_by_day -->Subsidiary (La consulta es de un Servicio)

    public function service()
    {
      return $this->belongsTo(Service::class);
    }
  	
    // transactions_by_day -->Provider (La consulta es de un Proveedor)

    public function provider()
    {
      return $this->belongsTo(Provider::class);
    }


    /*+---------------------------------+
      | Búsquedas x diferentes Criterios |
      +----------------------------------+
    */

    // De un usuario
    public function scopeUserId($query,$valor){
        if ( trim($valor) != "") {    
            $query->where('user_id','=',$valor); 
        }    
    }

    // De una sucursal
    public function scopeSubsidiaryId($query,$valor){
        if ( trim($valor) != "") {    
            $query->where('subsidiary_id','=',$valor); 
        }    
    }

    // De una Caja
    public function scopeRegisterId($query,$valor){
        if ( trim($valor) != "") {    
            $query->where('register_id','=',$valor); 
        }    
    }

    // De un servicio
    public function scopeServiceId($query,$valor){
     if ( trim($valor) != "") {
            $query->where('service_id','=',$valor);      
        }
    }
    

    // De un proveedor
    public function scopeProviderId($query,$valor){
        if ( trim($valor) != "") {    
            $query->where('provider_id','=',$valor); 
        }    
    }

     /*+---------------------------------------------+
      | Funciones de apoyo                          |
      +---------------------------------------------+
     */
    public function DeleteUserRecords($user_id){
        return $this->where('user_id','=',$user_id)->delete();
    }
}