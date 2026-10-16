<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    // RECIBIR DATOS - SOLO CLIENTES DEL USUARIO AUTENTICADO
    public function index()
    {
        return Inertia::render('Clients/Index', [
            'clients' => Client::where('user_id', auth()->id()) // Solo los clientes del usuario autenticado
                ->orderBy('name', 'asc') // Ordenar alfabéticamente
                ->paginate(10)
        ]);
    }

    // STORE = GUARDAR - ASOCIANDO EL CLIENTE AL USUARIO AUTENTICADO
    public function store(Request $request)
    {
        $request->validate([
            'name' =>'required|max:100',
            'address' => 'nullable|string|max:255',
        ]);

        $client = new Client([
            'name' => $request->name,
            'address' => $request->address,
            'user_id' => auth()->id(), // Asignar el usuario autenticado
        ]);

        $client->save();

        return redirect()->route('clients.index');
    }

    // ACTUALIZAR - SOLO SI EL CLIENTE PERTENECE AL USUARIO
    public function update(Request $request, Client $client)
    {
        $request->validate([
            'name' =>'required|max:100',
            'address' => 'nullable|string|max:255',
        ]);

        $client = Client::where('id', $client->id)
            ->where('user_id', auth()->id()) // Asegurar que el cliente pertenece al usuario
            ->firstOrFail();

        $client->update($request->only(['name', 'address']));

        return redirect()->route('clients.index');
    }

    // ELIMINAR - SOLO SI EL CLIENTE PERTENECE AL USUARIO
    public function destroy(Client $client)
    {
        $client = Client::where('id', $client->id)
            ->where('user_id', auth()->id()) // Asegurar que el cliente pertenece al usuario
            ->firstOrFail();

        $client->delete();

        return redirect()->route('clients.index');
    }
}
