<?php

namespace App\Http\Controllers;

use App\Models\Sell;
use App\Models\Client;
use App\Models\Comission;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SellController extends Controller
{
    // RECIBIR DATOS - SOLO VENTAS Y CLIENTES DEL USUARIO AUTENTICADO
    public function index()
    {
        $sells = Sell::where('user_id', auth()->id()) // Solo ventas del usuario autenticado
            ->with(['client'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    
        // Obtener TODAS las ventas sin paginación para calcular la ganancia total
        $allSells = Sell::where('user_id', auth()->id())->get();
    
        // Calcular el total de ganancias
        $totalEarnings = $allSells->sum(function ($sell) {
            return ($sell->price * ($sell->comission ?? 0)) / 100;
        });
    
        return Inertia::render('Sells/Index', [
            'sells' => $sells,
            'clients' => Client::where('user_id', auth()->id())->orderBy('name', 'asc')->get(),
            'totalEarnings' => $totalEarnings, // Enviar el total de ganancias al frontend
        ]);
    }
    

    // STORE = GUARDAR - ASIGNANDO AL USUARIO AUTENTICADO
    public function store(Request $request)
    {
        $request->validate([
            'title' =>'required|max:100',
            'price' =>'required|numeric|max:9999999999999',
            'comission' => 'required|numeric|min:0|max:100',
            'client_id' => 'required|exists:clients,id',
        ]);

        // Asegurar que el cliente pertenezca al usuario autenticado
        $client = Client::where('id', $request->client_id)->where('user_id', auth()->id())->firstOrFail();

        $sell = new Sell([
            'title' => $request->title,
            'price' => $request->price,
            'comission' => $request->comission,
            'client_id' => $client->id,
            'user_id' => auth()->id(), // Asignar el usuario autenticado
        ]);

        $sell->save();

        return redirect()->route('sells.index');
    }

    // ACTUALIZAR - SOLO SI LA VENTA PERTENECE AL USUARIO
    public function update(Request $request, Sell $sell)
    {
        $request->validate([
            'title' =>'required|max:100',
            'price' =>'required|numeric|max:9999999999999',
            'comission' => 'required|numeric|min:0|max:100',
            'client_id' => 'required|exists:clients,id',
        ]);

        // Asegurar que la venta pertenece al usuario autenticado
        $sell = Sell::where('id', $sell->id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Asegurar que el cliente pertenezca al usuario autenticado
        $client = Client::where('id', $request->client_id)->where('user_id', auth()->id())->firstOrFail();

        $sell->update([
            'title' => $request->title,
            'price' => $request->price,
            'comission' => $request->comission,
            'client_id' => $client->id,
        ]);

        return redirect()->route('sells.index');
    }

    // ELIMINAR - SOLO SI LA VENTA PERTENECE AL USUARIO
    public function destroy(Sell $sell)
    {
        $sell = Sell::where('id', $sell->id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $sell->delete();

        return redirect()->route('sells.index');
    }
}
