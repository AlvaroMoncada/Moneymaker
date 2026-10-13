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
    // RECIBIR DATOS - SOLO VENTAS, CLIENTES Y COMISIONES DEL USUARIO AUTENTICADO
    public function index()
    {
        $sells = Sell::where('user_id', auth()->id()) // Solo ventas del usuario autenticado
            ->with(['client', 'comission'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    
        // Obtener TODAS las ventas sin paginación para calcular la ganancia total
        $allSells = Sell::where('user_id', auth()->id())->with('comission')->get();
    
        // Calcular el total de ganancias
        $totalEarnings = $allSells->sum(function ($sell) {
            return ($sell->price * ($sell->comission->comissionPercent ?? 0)) / 100;
        });
    
        return Inertia::render('Sells/Index', [
            'sells' => $sells,
            'clients' => Client::where('user_id', auth()->id())->orderBy('name', 'asc')->get(),
            'comissions' => Comission::where('user_id', auth()->id())->orderBy('comissionPercent', 'asc')->get(),
            'totalEarnings' => $totalEarnings, // Enviar el total de ganancias al frontend
        ]);
    }
    

    // STORE = GUARDAR - ASIGNANDO AL USUARIO AUTENTICADO
    public function store(Request $request)
    {
        $request->validate([
            'title' =>'required|max:100',
            'price' =>'required|numeric|max:9999999999999',
            'client_id' => 'required|exists:clients,id',
            'comission_id' => 'required|exists:comissions,id'
        ]);

        // Asegurar que el cliente y la comisión pertenezcan al usuario autenticado
        $client = Client::where('id', $request->client_id)->where('user_id', auth()->id())->firstOrFail();
        $comission = Comission::where('id', $request->comission_id)->where('user_id', auth()->id())->firstOrFail();

        $sell = new Sell([
            'title' => $request->title,
            'price' => $request->price,
            'client_id' => $client->id,
            'comission_id' => $comission->id,
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
            'client_id' => 'required|exists:clients,id',
            'comission_id' => 'required|exists:comissions,id'
        ]);

        // Asegurar que la venta pertenece al usuario autenticado
        $sell = Sell::where('id', $sell->id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Asegurar que el cliente y la comisión pertenezcan al usuario autenticado
        $client = Client::where('id', $request->client_id)->where('user_id', auth()->id())->firstOrFail();
        $comission = Comission::where('id', $request->comission_id)->where('user_id', auth()->id())->firstOrFail();

        $sell->update([
            'title' => $request->title,
            'price' => $request->price,
            'client_id' => $client->id,
            'comission_id' => $comission->id,
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
