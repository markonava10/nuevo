<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Opening extends Model
{
     // Generales
     protected $table = 'openings';
     protected $fillable =  [
         'register_id',
         'cashier_id',
         'amount_open',
         'amount_close',
         'open'
     ];
     /*+-----------------------------+
       | Relaciones entre tablas     |
       +-----------------------------+
     */
       
     /*+----------------------------------------------------------+
       | Muchos: Tiene varios registros dependientes (Es padre)   |
       +----------------------------------------------------------+
     */
     // Openings <--- Movements (El movimento Pertenece a una pertura)
     public function movements(){
         return $this->hasMany(Movement::class,'opening_id','id');
     }
 
     /*+----------------------------------------------------------+
       | Uno - Muchos: Pertenece a una Tabla (Es hija de)         |
       +----------------------------------------------------------+
     */
       // Opening -->Register (Una apertura/Cierre pertenece a una caja)
 
       public function register()
       {
           return $this->belongsTo(Register::class,'register_id','id');
       }
 
       // Opening -->User (Una apertura/Cierre la hizo un cajero)
     public function cashier()
     {
        return $this->belongsTo(User::class, 'cashier_id','id');
     }
 
     // Polimórfica hacia Movimiento
     public function morph_movements(){
         return $this->morphMany(Movement::class, 'transactiontable');
     }
 
 
 
     // Registros Abiertos del Usuario autenticado
     public function openAuthenticatedCashier(){
       return $this->where('open','=','1')
                   ->where('cashier_id','=',Auth::user()->id);
     }
 
     /*+---------+
       | Apoyo   |
       +---------+
     */
     // Abre/Cierra
     public function openClose($action=true)
     {
          self::update(['open'  => $action]);
     }
 
     // Activa - Desactiva
     public function activate($action=true)
     {
          self::update(['active'  => $action]);
     }
     
     // Importe con que se abre
     public function amount_close($amount)
     {
          self::update(['amount_close'  => $amount]);
     }
 
 
 
     /*+---------------------------------+
       | Búsquedas x diferentes Criterios |
       +----------------------------------+
     */
 
     // De una compañia
     public function scopeRegisterId($query,$valor)
     {
         if ( trim($valor) != "") {
             $query->where('register_id','=',$valor);      
         }
     }
     
     // De un Cajero
     public function scopeCashierId($query,$valor)
     {
         if ( trim($valor) != "") {
             $query->where('cashier_id','=',$valor);      
         }
     }
 
     // Del cajero conectado
     public function scopeThisCashier($query)
     {
       $query->where('cashier_id','=',Auth::user()->id);
  
     }
 
     
     // Operaciones abiertas
     public function scopeOpened($query)
     {
           return $query->where('open','=','1');
     }
 
     // Operaciones cerradas
     public function scopeClosed($query)
     {
         return $query->where('open','=','0');
     }
 
 
     /*+-------------------------------+
       | Funciones de almacenaimiento  |
       +-------------------------------+
     */
 
     public function store($request)
     {
         $sql = "UPDATE openings set active=0 WHERE cashier_id=" . Auth::user()->id . " AND active=1";
         \DB::update($sql);
         return self::create($request->all() + [
           'cashier_id' => Auth::user()->id,
         ]);
     }
}
