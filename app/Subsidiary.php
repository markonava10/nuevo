<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
class Subsidiary extends Model
{
     // Generales
     protected $table = 'subsidiaries';
     protected $fillable =  [
         'subsidiary',
         'short',
         'address',
         'zipcode',
         'phone',
         'registers_allowed',
         'folio_transfers',
         'folio_safes',
         'folio_petty',
         'amount_safe',
         'company_id',
         'active'
     ];
 
     /*+-----------------------------+
       | Relaciones entre tablas     |
       +-----------------------------+
     */
 
     /*+----------------------------------------------------------+
       | Muchos - Uno: Tiene a muchos en otra Tabla (Es Pader de)  |
       +----------------------------------------------------------+
     */
     // Subsidiary <--- Transactions (Una subsidiaria tiene muchas transacciones)
     public function transactions(){
         return $this->hasMany(Transaction::class,'subsidiary_id','id');
     }
 
     // Subsidiary <--- Autorhizations (Una subsidiaris tiene varias autorizaciones)
     public function authoriztions(){
         return $this->hasMany(Authorization::class,'subsidiary_id','id');
     }
 
     // Subsidiary <--- Registers (Una subsidiaris tiene varias cajas)
     public function registers(){
         return $this->hasMany(Register::class,'subsidiary_id','id');
     }
 
 
     // Una sucursal  puede haber muchas consultas de transacciones x dia
     public function transactions_by_day(){
       return $this->hasMany(Transaction_By_Day::class,'user_id','id');
     }
 
     /*+----------------------------------------------------------+
       | Uno - Muchos: Pertenece a una Tabla (Es hija de)         |
       +----------------------------------------------------------+
     */
     // Subsidiary -->Zipcodes (Una sucursal pertenece a un zipcode)
     public function zipcode()
     {
         return $this->belongsTo(Zipcode::class,'zipcode','zipcode');
     }
 
 
     // Subsidiary -->Companies (Una Sucursal pertenece a una compañia)
     public function company()
     {
         return $this->belongsTo(Company::class);
     }
 
     public function are_avaiable_registers()
     {
         return $this->registers()->where('open','=',0)->count();
     }
 
     /*+----------------------------------------------------------+
       | Muchos A través de:Usa tabla intermedia para una tercera |
       +----------------------------------------------------------+
     */
 
     // Movimientos a través de las cajas registradoras
     public function movements(){
         return $this->hasManyThrough(Movement::class,Register::class,'subsidiary_id','register_id');
     }
 
     /*+----------------------------------+
       | Funciones de Apoy                |
       +----------------------------------+
     */
 
     public function there_are_avaiable_registers(){
         return $this->where('subsidiary_id','=',session()->get('subsidiary')->id)->where('open','=',0)->count();
     }
 
     /*+---------------------------------+
       | Búsquedas x diferentes Criterios |
       +----------------------------------+
     */
     // Nombre de la sucursal(Subsidiaria)
     public function scopeSubsidiary($query,$valor)
     {
        if ( trim($valor) != "") {
            $query->where('subsidiary','LIKE',"%$valor%");   
         }
     }
     // Nombre corto
     public function scopeShort($query,$valor)
     {
        if ( trim($valor) != "") {
            $query->where('short','LIKE',"%$valor%");   
         }
     }
 
 
     // De una compañia
     public function scopeCompanyId($query,$valor)
     {
        if (trim($valor) != "") {
             $query->where('company_id','=',$valor);      
         }
     }
 
     // Cajas Fuerte
     public function scopeSafeRegister($query)
     {
         return $this->registers->where('type','=','S');
     }
 
     /*+---------+
       | Listas  |
       +---------+
     */
     // Lista de sucursales (Subsidiarias) de la compañía del usuario conectado
     public function  subsidiaries_list(){
         return $this->where('company_id','=',Auth::user()->company_id)
                     ->orderBy('subsidiary')->pluck('subsidiary', 'id');
     } 
 
     // Sucursales de la compañía del usuario conectado que tenga cajas registradoras 
     public function subsidiaries_has_registers_list(){
         return $this->where('company_id','=',Auth::user()->company_id)
                     ->wherehas('registers')
                     ->orderBy('subsidiary')
                     ->pluck('subsidiary', 'id');
     }
     /*+-----------------------------+
       | Funciones Independientes    |
       +-----------------------------+
     */
     // ¿Está Activo?
     public function isActive(){
       return $this->active;
     }
}