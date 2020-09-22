<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Moneyorder extends Model
{
    // Generales
    protected $table = 'moneyorders';
    protected $fillable =  [
        'customer_id',
        'amount',
        'serial_number'
    ];

    /*+-----------------------------+
      | Relaciones entre tablas     |
      +-----------------------------+
    */

    /*+-----------------------------+
      | Polimórficas:               |
      +-----------------------------+
    */

    // El encabezado de la transacción
    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactiontable');
    }

    /*+----------------------------------------------------------+
      | Uno - Muchos: Pertenece a una Tabla (Es hija de)         |
      +----------------------------------------------------------+
    */

    // MoneyOrder -->Customer (La orden de pago la compró un cliente)
    public function customer()
      {
        return $this->belongsTo(Customer::class);
      } 


    /*+---------------------------------+
      | Búsquedas x diferentes Criterios |
      +----------------------------------+
    */

    // De una empresa que emitió el cheque
    public function scopeCustomerId($query,$valor)
    {
        if ( trim($valor) != "") {
            return $query->where('customer_id','=',$valor);      
        }
    }

    // De un banco
    public function scopeSerialNumber($query,$valor)
    {
        if ( trim($valor) != "") {
            $query->where('serial_number','LIKE',"%$valor%");     
        }
    }
}
