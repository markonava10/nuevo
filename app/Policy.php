<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

class Policy extends Model
{
    // Generales
	protected $table = 'policies';
	// Campos enviados por el formulario
	protected $fillable  =  [
        'company_id',
		'service_id',
        'provider_id',
        'entity',
		'policy',
		'type_value',
		'days',
		'limit_allowed',
		'value_to_count',
		'priority',
		'action_type',
		'message'
    ];

    /*+-----------------------------+
      | Relaciones entre tablas     |
      +-----------------------------+
    */

    /* Es hija de las siguientes tablas */

	// Policies -->Companies (Una política pertenece a una compañia)
	public function company()
	{
		return $this->belongsTo(Company::class);
	}

	// Policies -->Services (Una política es para un Servicio)
	public function service()
	{
		return $this->belongsTo(Service::class);
	}

    // Policies -->Providers (Una política es para un Proveedor)
    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    // Busca por: 
    // De una compañia

    public function scopeCompanyId($query,$valor)
    {
        $query->where('company_id','=',$valor);      
    }


    // De una servicio
    public function scopeServiceId($query,$valor)
    {
        if ( trim($valor) != "") {
            $query->where('service_id','=',$valor);      
        }
    }

    // De una servicio
    public function scopeProviderId($query,$valor)
    {
        if ( trim($valor) != ""){
            $query->where('provider_id','=',$valor);      
        }
    }

    // Id Reigsrtro
    public function scopePolicyId($query,$valor)
    {
        $query->where('id','=',$valor);      
    }

    // Nombre Política
    public function scopePolicy($query,$policy)
    {
        if ( trim($policy) != "") {
            $query->where('policy','LIKE',"%$policy%"); 
        }
    }    

    // Entidad: Customer o Receiver (Cliente que envía o beneficiario)
    public function scopeEntity($query,$valor)
    {
        if ( trim($valor) != "") {
            $query->where('entity','LIKE',"%$valor%"); 
        }    
    }

    // De cierto tipo (Amont-quantity)
    public function scopeType_Value($query,$valor)
    {
        if ( trim($valor) != "") {
            $query->where('type_value','=',$valor);      
        }
    }

    // De cierto valor a contar
    public function scopeValue_to_count($query,$valor)
    {
        if ( trim($valor) != "") {
            $query->where('value_to_count','=',$valor);      
        }
    }

    // De cierto tipo de acción
    public function scopeAction_type($query,$valor)
    {
        if ( trim($valor) != "") {
            $query->where('action_type','=',$valor);      
        }
    }


    // Hasta cierta prioridad
    public function scopePriority($query,$valor,$operator)
    {
        $query->where('priority',$operator,$valor);
    }


    /*+---------+
      | Listas  |
      +---------+
    */
    // Lista de Políticas (Ordenadas x Prioridad)
    public function  policies_list(){
        return $this->orderBy('priority')->pluck('policy', 'id');
    } 

    // Lista de Políticas tipo "amount" o "quantity"
    public function  policies_type_value_list($value){
        return $this->orderBy('priority')
                    ->where('type_value','=',$value)
                    ->pluck('policy', 'id');
    } 

    // Lista de Políticas de "value_to_count"
    public function  policies_value_to_count_list($value){
        return $this->orderBy('priority')
                    ->where('value_to_count','=', $value)
                    ->pluck('policy', 'id');
    } 

    // Lista de Políticas de "value_to_count"
    public function  policies_action_type_list($value){
        return $this->orderBy('priority')
                    ->where('action_type','=', $value)
                    ->pluck('policy', 'id');
    } 


    /*+-----------------------------------------------------------------+
      | Funciones de Apoyo                                              |
      +-----------------------------------------------------------------+
    */

    public function NotThisId($query,$valor)
    {
        $query->where('id','!=',$valor);      
    }
    
    
    public function updatePriorities($policy_id,$service_id)
    {
        // Localiza el registro     
        $record = Policy::findOrFail($policy_id);

        // Si la prioridad del registro es 1: Renumera todos los demás a partir del 2
        
        if ($record->priority == 1) {
            $records = $this->companyId(Auth::user()->company_id)
                            ->serviceId($service_id)
                            ->where('id','<>',$record->id)
                            ->orderby('priority')
                            ->get();
           
            $priority = 2;
            foreach($records as $record_before){
                $update = $this->updatePriority($record_before->id,$priority);
                $priority++;
            }
            return true;
        }
        
        // Si no es uno;
        // PRIMERO: renumera desde el 1 todos los que son menores o igual a la del registro
        $records = $this->priority($record->priority,'<=')
                            ->companyId(Auth::user()->company_id)
                            ->serviceId($service_id)
                            ->orderby('priority')
                            ->get();
        $priority = 1;
        foreach($records as $record_before){
            $update = $this->updatePriority($record_before->id,$priority);
            $priority++;
        }

        // SEGUNDO: Todos los que tenían mayor prioridad
        $records = $this->priority($record->priority,'>')
                            ->companyId(Auth::user()->company_id)
                            ->serviceId($service_id)
                            ->orderby('priority')
                            ->get();

        // Actualiza prioridad
        $priority = $record->priority + 1;
        foreach ($records as $record_before) {
            $update = $this->updatePriority($record_before->id,$priority);
            $priority++;
        }
        return true;
    }

    // Actualiza la prioridad de un registro
    private function updatePriority($policy_id,$priority)
    {
        $record_update = Policy::findOrFail($policy_id);
        $record_update->priority = $priority;
        $record_update->save();
    }

    // ¿En límite incluyendo transacción?
    public function in_limit($customer_id,$service_id,$days,$amount,$transction=0,$include_transaction = true)
    {
        if ($include_transaction) {
            return $this->total_amount($customer_id,$service_id,$days) + $transction >= $amount;    
        }
        return $this->total_amount($customer_id,$service_id,$days)  >= $amount;    
      
    }

    // Lee registros de un determinado rango de fechas
    public function scopeComoloquierasllamar($query,$customerId,$serviceId,$companyId,$inicial,$final)
    {
        $inicial=Carbon::parse($inicial);
        $final=Carbon::parse($final);
        $query->where('customer_id',$customerId)
              ->where('service_id',$serviceId)
              ->where('company_id',$companyId)
              ->whereBetween('created_at',[$inicial,$final])
              ->get();
    }
}