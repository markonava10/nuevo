<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
    // Generales
    protected $table = 'exchanges';
    protected $fillable =  [
        'issue_id',
        'bank_id',
        'chek_number',
        'chek_date'
    ];

    /*+-----------------------------+
      | Relaciones entre tablas     |
      +-----------------------------+
    */

    /*+----------------------------------------------------------+
      | Polimórficas:                            |
      +----------------------------------------------------------+
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

    // Exchanges -->Issues (El cambio fue de un cheque de una empresa)
    public function issue()
      {
        return $this->belongsTo(Issue::class);
      } 

	// Exchanges -->Banks (El cheque cambiado es de un banco)
    public function bank()
    {
      return $this->belongsTo(Bank::class);
    }



    /*+---------------------------------+
      | Búsquedas x diferentes Criterios |
      +----------------------------------+
    */

    // De una empresa que emitió el cheque
    public function scopeIssueId($query,$valor)
    {
        if ( trim($valor) != "") {
            return $query->where('issue_id','=',$valor);      
        }
    }

    // De un banco
    public function scopeBankId($query,$valor)
    {
        if ( trim($valor) != "") {
            $query->where('bank_id','=',$valor);      
        }
    }
}