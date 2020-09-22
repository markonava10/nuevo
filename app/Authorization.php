<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Authorization extends Model
{
    // General
    protected $table = 'authorizations';
    protected $fillable = [
        'subsidiary_id', 
        'service_id', 
        'date',
        'reason',
        'customer_id',
        'receiver_id',
        'cashier_id',
        'authorizer_id',
        'amount_transaction',
        'amount_before',
        'authorization_code',
        'amount_commission',
        'amount_commission_before',
        'percentage_transaction',
        'percentage_before',
        'status_id',
    ];


    /*+-----------------------------+
      | Relaciones entre tablas     |
      +-----------------------------+
    */

 	/*+----------------------------------------------------------+
      | Uno - Muchos: Pertenece a una Tabla (Es hija de)         |
      +----------------------------------------------------------+
    */
    // Authorizations -->Subsidiaries (Una autorización es en una sucursal)
    public function subsidiary(){
        return $this->belongsTo(Subsidiary::class,'subsidiary_id','id');
    }

    // Authorizations -->Services (Una autorización es sobre un servicio)
    public function service(){
        return $this->belongsTo(Service::class);
    }

    // Authorizations -->Customers (Una autorización es sobre un cliente)
    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    // Authorizations -->Receivers (Una autorización es sobre un receptor)
    public function receiver(){
        return $this->belongsTo(Receiver::class);
    }

    // Authorizations -->Users (Una la solicitó un cajero -cashier_id -)
    public function cashier(){
        return $this->belongsTo(User::class,'cashier_id','id');
    }

    // Authorizations -->Users (Una la solicitó alguien la autorízó -authorizer_id -)
    public function authorizer(){
        return $this->belongsTo(User::class,'authorizer_id','id');
    }

    // Authorizations -->Statuses (Una solicitud tiene un status)
    public function status(){
        return $this->belongsTo(Status::class,'status_id','id');
    }

    /*+-------------------------------------------------------------+
      | Relación Mucho a Muchos con Role                            |
      +-------------------------------------------------------------+
    */

    /*+----------------------------------+
      | Apoyo                            |
      +----------------------------------+
    */
    public function exist_authorization_code($query,$authorization_code){
        return  $query->where('authorization_code','=',$authorization_code);      
    }

    // Marca como usado
    public function mark_used(){
        $this->status_id = 5;
        $this->save();
    }

    /*+---------------------------------+
      | Búsquedas x diferentes Criterios |
      +----------------------------------+
    */

    // Por id de la Subsidiaria
    public function scopeSubsidiaryId($query,$valor){
        if (trim($valor) != ""){
            $query->where('subsidiary_id','=',$valor);      
        }
    }

    // Por El servicio
    public function scopeServiceId($query,$valor){
        if (trim($valor) != ""){
            $query->where('service_id','=',$valor);      
        }
    }

    // Por El Cajero
    public function scopeCashierId($query,$valor){
        if (trim($valor) != ""){
            $query->where('cashier_id','=',$valor);      
        }
    }

    //  Creadas por el usuaro conectado
    public function scopeCreatedByThisUser($query){
      $query->where('authorizer_id','=',Auth::user()->id);    
    }
}