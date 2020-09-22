<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    // Generales
    protected $table = 'marks';
    protected $fillable =  [
        'company_id',
        'mark',
        'action',
        'Lock Customer',
    ];

    /*+----------------------------------------------------------+
      | Muchos - Uno: Tiene a muchos en otra Tabla (Es Pader de)  |
      +----------------------------------------------------------+
    */

    // Una marca está en muchos registros del historial
    public function marking_history(){
      return $this->hasMany(MarkHistory::class,'mark_id','id');
    }

    /*+----------------------------------------------------------+
      | Uno - Muchos: Pertenece a una Tabla (Es hija de)         |
      +----------------------------------------------------------+
    */
      
    // Company -->Reason (Una razon pertenece a una compañia)
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    /*+---------------------------------+
      | Búsquedas x diferentes Criterios |
      +----------------------------------+
    */

    // De una compañia determinada
    public function scopeCompanyId($query,$valor)
    {
      $query->where('company_id','=',$valor);  
    }

    // De una compañia del usuario conectado
    public function scopeCompanyAuth($query)
    {
      $query->where('company_id','=',Auth::user()->company_id);  
    }

    // Nombre de la marca (Motivo)
    public function scopeMark($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('mark','LIKE',"%$valor%");   
        }
    }
   
   // Action: M=Mark o U=Unmark 
    public function scopeAction($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('action','=',$valor);   
        }
    }
   
  	/*+---------+
      | Listas  |
      +---------+
    */
    // Lista de razones (Motivos) para marcar o desmarcar cliente
    // Param: $action (M, o U)

    public function marks_list($action='M')
    {
        return $this->where('company_id','=',Auth::user()->company_id)
              		->where('action','=',$action)
                    ->orderBy('mark')
          	        ->pluck('mark', 'id');
    }
}