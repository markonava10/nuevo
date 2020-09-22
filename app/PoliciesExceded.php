<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

class PoliciesExceded extends Model
{
    // Generales
	protected $table = 'policies_exceded';
	protected $fillable  =  [
        'policy_id',
		'customer_id',
        'receiver_id',
		'limit_allowed',
		'used'
    ];

    /*+-----------------------------+
      | Relaciones entre tablas     |
      +-----------------------------+
    */

    /* Es hija de las siguientes tablas */

	// policies_exceded -->Policies (La política excededia)
	public function policy()
	{
		return $this->belongsTo(Policy::class);
	}

    // policies_exceded -->Customers (Política excedida por el cliente)
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // policies_exceded -->Receivers (Política excedida por el recetor)
    public function receiver()
    {
        return $this->belongsTo(Receiver::class);
    }

    // Busca por: 
    // De una política

    public function scopePolicyId($query,$valor)
    {
        $query->where('policy_id','=',$valor);      
    }

    // De un cliente
    public function scopeCustomerId($query,$valor)
    {
        $query->where('customer_id','=',$valor);      
    }

    // De un receptor
    public function scopeReceiverId($query,$valor)
    {
        $query->where('receiver_id','=',$valor);      
    }
}
