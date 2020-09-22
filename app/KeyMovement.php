<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KeyMovement extends Model
{
    // Generales
	protected $table = 'key_movements';
	protected $fillable =  [
        'company_id',
        'key_mov',
        'spanish',
        'short_spanish',
        'english',
        'short_english',
        'type_movement',
        'type_amount'
    ];

    /*+-----------------------------+
      | Relaciones entre tablas     |
      +-----------------------------+
    */

    // Key_movements <--- keyabbles (Una clave está para muchos modelos)
    public function keyabbles(){
        return $this->hasMany(keyabbles::class,'key_movement_id','id');
    }


   // Key_mov -->Companies (Una Clave de Movimiento pertenece a una compañia)
    public function company()
    {
        return $this->belongsTo(Company::class);
    }


    // Todos los servicios asociados a esta clave de movimiento
    public function services()
    {
        return $this->morphedByMany(Service::class, 'keyable');
    }

    // Todos los procesos asociados a esta clave de movimiento
    public function processes()
    {
        return $this->morphedByMany(Process::class, 'keyable');
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

    // Nombre en Español
    public function scopeSpanish($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('spanish','LIKE',"%$valor%");   
        }
    }

    // Nombre en Inglés
    public function scopeEnglish($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('english','LIKE',"%$valor%");   
        }
    }


    // Clave de movimiento
    public function scopeKeyMov($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('key_mov','LIKE',"%$valor%");   
        }
    }

    // Tipo (Entrada o Salids)
    public function scopeTypeMovement($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('type_movement','LIKE',"%$valor%");   
        }
    }

    /*+---------+
      | Listas  |
      +---------+
    */
    // Lista de claves de movimiento de la compañia
    public function  key_movements_list(){
      if (App::isLocale('en')) {
        return $this->where('company_id','=',Auth::user()->company_id)
                    ->orderBy('english')->pluck('english', 'id');
      }

      return $this->where('company_id','=',Auth::user()->company_id)
                    ->orderBy('spanish')->pluck('spanish', 'id');
    }
}
