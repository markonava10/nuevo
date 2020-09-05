<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Customer as Clientes;

class Prueba extends Component
{
    public function render()
    {
        $customers = Clientes::all();
        return view('livewire.prueba')->with(array("customers" => $customers));
    }
}
