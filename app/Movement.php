<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    // Generales
    protected $table = 'movements';
    protected $fillable =  [
        'opening_id',
        'register_id',
        'cashier_id',
        'key_movement_id',
        'amount',
        'reference',
    ];

  	/*+-----------------------------+
      | Relaciones entre tablas     |
      +-----------------------------+
    */


    /*+----------------------------------------------------------+
      | Uno - Muchos: Pertenece a una Tabla (Es hija de)         |
      +----------------------------------------------------------+
    */
  	// Movement -->Register (Un Movimiento pertenece a una caja)

  	public function register()
  	{
  		return $this->belongsTo(Register::class);
  	}

  	// Movement -->User (Un movimiento lo hizo un cajero)
    public function cashier()
    {
       return $this->belongsTo(User::class, 'cashier_id','id');
    }

    // Movement -->Opening (Un movimiento se hizo sobre una Apertura de caja)
    public function Opening()
    {
       return $this->belongsTo(Opening::class, 'opening_id','id');
    }

  	// Movement -->Key_Movment (Un movimiento es de una clave de movimiento)
    public function key_movement()
    {
       return $this->belongsTo(Key_movement::class, 'key_movement_id','id');
    }

    /*+----------------------------------------------------------+
      | Polimórficas                                             |
      +----------------------------------------------------------+
    */

     // Para diferentes Modelos que generarán movimientos de cajeros
    public function transactiontable()
    {
        return $this->morphTo();
    }

    /*+---------------------------------+
      | Búsquedas x diferentes Criterios |
      +----------------------------------+
    */

    // De la apertura activa
    public function active_opening()
    {
      return $this->this_cashier()
                  ->register_with_active_opening();
    }

    // De una caja en particular
    public function scopeRegisterId($query,$valor)
    {
        if ( trim($valor) != "") {
            $query->where('register_id','=',$valor);      
        }

    }
    
    // De una sucursal en particular
    public function scopeSubsidiaryId($query,$valor){
        if ( trim($valor) != "") {
            $query->register->where('subsidiary_id','=',$valor);      
        }
    }

    // De un Cajero
    public function scopeCashierId($query,$valor)
    {
        if ( trim($valor) != "") {
            $query->where('cashier_id','=',$valor);      
        }
    }

    // Del cajaero conectado
    public function scopeThisCashier($query)
    {
      $query->where('cashier_id','=',Auth::user()->id);    
    }

    // De una clave de movimiento
    public function scopeKeyMovmentId($query,$valor)
    {
        if ( trim($valor) != "") {
            $query->where('key_movement_id','=',$valor);      
        }
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

    // Por un modelo asociado
    public function scopeTransactionTable_Type($query,$model){
        return $query->where('transactiontable_type',$model);
    }
    
    // Total de una fecha
    public function concentrated_between_dates($register_id,$cashier_id,$date_from,$date_to){
      $year       = substr($date_from,6,4);
      $month      = substr($date_from,0,2);
      $day        = substr($date_from,3,2);
      $formated_date_from = $year . "-" . $month . "-" . $day;
      $year       = substr($date_to,6,4);
      $month      = substr($date_to,0,2);
      $day        = substr($date_to,3,2);
      $formated_date_to = $year . "-" . $month . "-" . $day;
      $sql= " SELECT k.id,k.spanish,k.english,k.type_movement,count(*) as total,sum(m.amount) as importe";
      $sql.= " FROM key_movements k,movements m WHERE k.id = m.key_movement_id";
      $sql.= " AND m.cashier_id = "   . $cashier_id;
      $sql.= " AND m.register_id = "  . $register_id;
      $sql.= " AND m.created_at BETWEEN '" . $date_from . "'  AND '" . $date_to . "'";
      $sql.= " GROUP BY k.id,k.spanish,k.english,k.type_movement";
      return  \DB::select($sql); 
     
    }
}