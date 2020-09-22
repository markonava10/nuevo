<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    // Generales
    protected $table = 'transfers';
    protected $fillable =  [
                        'receiver_id',
                        'payer_id',
                        'exchange_rate',
                        'notes',
                    ];


    /*+-----------------------------+
      | Relaciones entre tablas     |
      +-----------------------------+
    */

    /*+----------------------------+
      | UNO-UNO                    |
      +----------------------------+
    */



    /*+----------------------------------------------------------+
      | Polimórficas: 									                         |
      +----------------------------------------------------------+
    */

    // El encabezado de la transacción
	 public function transactions(){
        return $this->morphMany(Transaction::class, 'transactiontable');
    }

    /*+----------------------------------------------------------+
      | Uno - Muchos: Pertenece a una Tabla (Es hija de)         |
      +----------------------------------------------------------+
    */


    // Transfers -->Receivres (El envío es para un receptor)
    public function receiver()
      {
        return $this->belongsTo(Receiver::class);
      } 


	// Transfers -->payers (El envío lo hará un PAGADOR)
    public function payer()
    {
      return $this->belongsTo(Payer::class);
    }


    /*+---------------------------------+
      | Búsquedas x diferentes Criterios |
      +----------------------------------+
    */

    // De un receptor
    public function scopereceiverId($query,$valor)
    {
        $query->where('receiver_id','=',$valor);      

    }

    // De un pagador
    public function scopePayerId($query,$valor)
    {
        $query->where('payer_id','=',$valor);      
    }

    // De un rango de fechas 
    public function scopeFrom_to_Dates($query,$from_date,$to_date){
      $query->whereBetween('created_at',[$from_date,$to_date]); 
    }
    /*+---------------------+
      | Funciones de Apyo   |
      +---------------------+
    */

    // Se crea colección de Id's de las transacciones de un receptor
    public function create_ids_transactions($query,$receiver_id){
        $query->select('id')
              ->receiverId($receiver_id);
    }
}