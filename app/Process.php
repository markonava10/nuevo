<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Process extends Model
{
      // Generales
	protected $table = 'processes';
    public $timestamps = false;
	protected $fillable =  [
        'company_id',
        'spanish',
        'english',
        'use_register',
        'route',
        'detail_denominations'
    ];

    /*+-----------------------------+
      | Relaciones entre tablas     |
      +-----------------------------+
    */

   // processes -->Companies (Un proceso pertenece a una compañía
    public function company()
    {
        return $this->belongsTo(Company::class);
    }


    // Polimórfica muchos a muchos: 
    public function keysmovements(){
        return $this->morphToMany(Key_movement::class, 'keyabbles');
    }

    
    /*+---------------------------------+
      | Búsquedas x diferentes Criterios |
      +----------------------------------+
    */

    // De una compañia
    public function scopeCompanyId($query,$valor)
    {
        if ( trim($valor) != "") {
            $query->where('company_id','=',$valor);      
        }
    }

    // Procesos de compañía del usuario conectado
    public function scopeCompanyAuthUser($query)
    {
        $query->where('company_id','=',Auth::user()->company_id);   
    }

    // Nombre del proceso en español
    public function scopeSpanish($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('spanish','LIKE',"%$valor%");   
        }
    }

    // Nombre del proceso en Inglés
    public function scopeEnglish($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('english','LIKE',"%$valor%");   
        }
    }

    // Usa caja registradora?
	public function scopeUseRegister($query)
	{
		return $this->use_register;
	}

	// Detalla la denominaciones?
	public function scopeDetail_Denominations($query)
	{
		return $this->detail_denominations;
	}

    /*+---------+
      | Listas  |
      +---------+
    */
    // Lista de claves de movimiento de la compañia
    public function  processes_spanish_list(){
        return $this->where('company_id','=',Auth::user()->company_id)
                    ->orderBy('spanish')->pluck('spanish', 'id');
    } 

  	public function  processes_english_list(){
        return $this->where('company_id','=',Auth::user()->company_id)
                    ->orderBy('english')->pluck('english', 'id');
    }
}