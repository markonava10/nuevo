<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cancellation extends Model
{
    // Generales
    protected $table = 'cancellations';
    protected $fillable =  [
        'reason_id',
        'transaction_id',
        'user_cancel_id',
        'voucher',
    ];

    /*+-----------------------------+
      | Relaciones entre tablas     |
      +-----------------------------+
    */

    /*+----------------------------------------------------------+
      | Muchos - Uno: Tiene a muchos en otra Tabla (Es Pader de)  |
      +----------------------------------------------------------+
    */


    /*+----------------------------------------------------------+
      | Uno - Muchos: Pertenece a una Tabla (Es hija de)         |
      +----------------------------------------------------------+
    */
    // Cancellation -->Reasons (Una cancelación pertenece a una razón)
    public function reason(){
        return $this->belongsTo(Reason::class);
    }

    // Cancellation -->Users (Una cancelación la hizo un usuario)
    public function user(){
      return $this->belongsTo(User::class);
    }

    /*+----------------------------------------------------------+
      | Uno - Uno: Tiene un registro asociado en otra tabla      |
      +----------------------------------------------------------+
    */

	// Cancellation -->Transaction (Una cancelación pertenece a una Transacción)
    public function transaction(){
        return $this->belongsTo(Transaction::class)->withDefault();
    }

      /*+---------------------------------+
      | Búsquedas x diferentes Criterios |
      +----------------------------------+
    */
    // De una razon
    public function scopeReasonId($query,$valor){
        if (trim($valor) != "") {
            $query->where('reason_id','=',$valor);      
        }
    }

    // De una razon
    public function scopeTransactionId($query,$valor){
        if (trim($valor) != "") {
            $query->where('transaction_id','=',$valor);      
        }
    }

    // Del usuario que la creó
    public function scopeUserId($query,$valor){
        if (trim($valor) != "") {
            $query->where('user_id','=',$valor);      
        }
    }
    // Del usuario que está conectado fue quien creó la cancelación
    public function scopeAuthenticatedUser($query){
     	return $this->where('user_id','=',Auth::user()->id);
    }

    // De un rango de fechas 
    public function scopeFrom_to_Dates($query,$from_date,$to_date){
      $query->whereBetween('created_at',[$from_date,$to_date]); 
    }


    // Creados el día de hoy
    public function scopeToday($query){
      $from_date = Carbon::today();
      $to_date   = Carbon::today()->endOfDay();
      return $query->whereBetween('created_at',$from_date,$to_date);
    }

    /*+---------+
      | Listas  |
      +---------+
    */
    // Lista de sucursales (Subsidiarias)
    public function  subsidiaries_list(){
        return $this->orderBy('subsidiary')->pluck('subsidiary', 'id');
    } 
}