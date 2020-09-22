<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Provider extends Model
{
    protected $fillable = [
		'provider',
		'short',
		'logo',
		'fee_by_latinoamerica',
		'fee_by_country',
		'active',
	];

	/*+-----------------------------+
		      | Relaciones entre tablas     |
		      +-----------------------------+
	*/

	/*+---------------------------------------+
		      | Relación uno a muchos                 |
		      +---------------------------------------+
	*/

	// Provider <--- Fees (Un proveedor puede tener muchas Cuotas)
	public function fees() {
		return $this->hasMany(Fee::class, 'provider_id', 'id');
	}

	// Provider <--- Policies (Un proveedor puede tener muchas Políticas)
	public function policies() {
		return $this->hasMany(Policy::class, 'provider_id', 'id');
	}

	/*+-------------------------------------------------------------------+
		      | Relación Muchos a muchos                                          |
		      +-------------------------------------------------------------------+
	*/

	// Un Proveedor (Provider) puede prestar muchos servicios (Serives)
	public function services() {
		return $this->belongsToMany(Service::class);
	}

	// Un Proveedor (Provider) puede ser usado por muchas empresas
	public function companies() {
		return $this->belongsToMany(Company::class);
	}

	// Un Proveedor (Provider) puede tener muchos pagadores (Payers)
	public function payers() {
		return $this->belongsToMany(Payer::class)->withPivot('exchange_rate', 'id')->orderby('exchange_rate', 'DESC');
	}

	// Un Proveedor (Provider) puede tener muchoas consultas de transacciones (transactions_by_day)
	public function transactions_by_day() {
		return $this->hasMany(Transaction_By_Day::class, 'user_id', 'id');
	}

	//
	/*+---------------------------------+
	      | Búsquedas x diferentes Criterios |
	      +----------------------------------+
*/
	// Nombre del proveedor
	public function scopeProvider($query, $valor) {
		if (trim($valor) != "") {
			$query->where('provider', 'LIKE', "%$valor%");
		}
	}
	// Nombre Corto
	public function scopeShort($query, $valor) {
		if (trim($valor) != "") {
			$query->where('short', 'LIKE', "%$valor%");
		}
	}

	// Proveedor asociado a una compañía (COMPANYPROVIDER)
	public function scopeCompanyId($query, $valor = Null) {
		if (!isset($valor)) {
			$valor = Auth::user()->company_id;
		}
		return $this->wherehas('companies', function ($query) use ($valor) {
			$query->where('company_id', '=', $valor)
				->orderBy('exchange_rate', 'DESC');
		});
	}

	// Proveedor asociado a un servicio (PROVIDERSERVICE)
	Public function scopeServiceId($query, $valor) {
		if ( trim($valor) != "") {
			return $this->wherehas('services', function ($query) use ($valor) {
				$query->where('service_id', '=', $valor)
					->orderBy('exchange_rate', 'DESC');
			});
		}
	}

	// Proveedor asociado a un pagador (PAYERPROVIDER)
	public function scopePayerId($query, $valor) {
		return $this->payers()->where('payer_id', '=', $valor);
	}

	/*+---------+
		      | De Apoyo  |
		      +---------+
	*/
	// Proveedor con pagadores de un país determinado
	public function scopePayersInCountry($query, $valor) {

		if ( trim($valor) != "") {
			return $this->wherehas('payers', function ($query) use ($valor) {
				$query->where('country_id', $valor)
					->where('exchange_rate', '>', 0);
			});
		}

	}

	/*+---------+
		  | Listas  |
		  +---------+
	*/
	// Lista de Proveedores (Nombre Proveedor)
	public function providers_list() {
		return $this->orderBy('provider')->pluck('provider', 'id');
	}

	// Lista de Proveedores (Nombre Corto)
	public function providers_short_list() {
		return $this->orderBy('provider')->pluck('provider', 'id');
	}

	// Lista de proveedores que están en la compañía y son del servicio 1
	public function ProvidersByCompanyTransfers_list() {
		return $this->wherehas('companies', function ($query) {
			$query->where('company_id', '=', Auth::user()->company_id);
		})
			->wherehas('services', function ($query) {
				$query->where('service_id', '=', 1);
			})->pluck('provider', 'id');
	}
	// Lista de proveedores de la compañía y con servicio
	public function providers_service_list($service = 1, $country_id = 136) {
		return $this->wherehas('companies', function ($query) {
			$query->where('company_id', '=', Auth::user()->company_id);
		})
			->wherehas('services', function ($query) use ($service) {
				$query->where('service_id', '=', $service);
			})
			->wherehas('payers', function ($query) use ($country_id) {
				$query->where('exchange_rate', '>', 0)
					->where('country_id', $country_id);
			})->pluck('provider', 'id');
	}
}