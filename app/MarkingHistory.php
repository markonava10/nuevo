<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MarkingHistory extends Model
{
     // Generales
     protected $table = 'marking_history';
     protected $fillable =  [ 
       'customer_id',
       'mark_id',
       'user_id',
       'active',
       'unmarked_mark_id',
       'unmarked_user_id',
       'unmarked_user_at'
     ];
 
     /*+-----------------------------+
       | Relaciones entre tablas     |
       +-----------------------------+
     */
 
     /*+----------------------------------------------------------+
       | Mucho - Uno: Tiene a muchos en otra Tabla (Es Pader de)  |
       +----------------------------------------------------------+
     */
 
     /*+----------------------------------------------------------+
       | Uno - Muchos: Pertenece a una Tabla (Es hija de)         |
       +----------------------------------------------------------+
     */
 
     // Marking_Histories --> Customers (Una marca pertenece a un cliente)
     public function customer()
     {
       return $this->belongsTo(Customer::class);
     }
 
     // Marking_Histories --> Marks (Una marca pertenece a una razón o tipo de marca)
     public function mark()
     {
       return $this->belongsTo(Mark::class);
     }
 
     // Marking_Histories --> Marks (Por la causa que desmarcó)
     public function unmark()
     {
       return $this->belongsTo(Mark::class,'unmarked_mark_id');
     }
 
     // Marking_Histories --> Users (Una marca pertenece a un usuario que la generó)
     public function user()
     {
       return $this->belongsTo(User::class);
     }
 
     // Marking_Histories --> Users (Una marca pertenece a un usuario que la desmarcó)
     public function unmarked_by()
     {
       return $this->belongsTo(User::class,'unmarked_user_id');
     }
 
     // ¿La marca bloquea al cliente?
     public function mark_lock_customer()
     {
       return $this->belongsTo(Mark::class)
                     ->where('lock_customer','=',1);
     }
 
 
     /*+---------------------------------+
       | Búsquedas x diferentes Criterios |
       +----------------------------------+
     */
 
     // De un cliente
     public function scopeCustomerId($query,$valor)
     {
         if ( trim($valor) != "") {
             $query->where('customer_id','=',$valor);      
         }
     }
 
     // Marcada por alguna causa
     public function scopeMarkId($query,$valor)
     {
         if ( trim($valor) != "") {
             $query->where('mark_id','=',$valor);      
         }
     }
 
     // Desmarcada por la causa
     public function scopeUnMarkId($query,$valor)
     {
         if ( trim($valor) != "") {
             $query->where('unmarked_mark_id','=',$valor);      
         }
     }
 
     // Marcada por algun usuario
     public function scopeUserId($query,$valor)
     {
         if ( trim($valor) != "") {
             $query->where('user_id','=',$valor);      
         }
     }  
 
     // DeMarcada por algun usuario
     public function scopeUnmarkedBy($query,$valor)
     {
         if ( trim($valor) != "") {
             $query->where('unmarked_user_id','=',$valor);      
         }
     } 
 
     // Del usuario conectado
     public function scopAuthUser($query){
         return $this->where('user_id','=',Auth::user()->id);
     }
 
     // Marcas activas
     public function scopeActive($query,$active=True){
         $query->where('active','=',$active);  
     }
}