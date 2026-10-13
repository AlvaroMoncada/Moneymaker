<?php

namespace App\Http\Controllers;

use App\Models\Comission;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ComissionController extends Controller
{
    // RECIBIR DATOS - SOLO COMISIONES DEL USUARIO AUTENTICADO
    public function index()
    {
        return Inertia::render('Comissions/Index', [
            'comissions' => Comission::where('user_id', auth()->id()) // Solo comisiones del usuario autenticado
                ->orderBy('comissionPercent', 'asc')
                ->paginate(10)
        ]);
    }

    // STORE = GUARDAR - ASOCIANDO LA COMISIÓN AL USUARIO AUTENTICADO
    public function store(Request $request)
    {
        $request->validate([
            'comissionPercent' => 'required|numeric|between:0,100',
        ]);

        $comission = new Comission([
            'comissionPercent' => $request->comissionPercent,
            'user_id' => auth()->id(), // Asignar el usuario autenticado
        ]);

        $comission->save();

        return redirect()->route('comissions.index'); // Redirección más segura
    }

    // ACTUALIZAR - SOLO SI LA COMISIÓN PERTENECE AL USUARIO
    public function update(Request $request, Comission $comission)
    {
        $request->validate([
            'comissionPercent' => 'required|numeric|between:0,100',
        ]);

        $comission = Comission::where('id', $comission->id)
            ->where('user_id', auth()->id()) // Asegurar que la comisión pertenece al usuario
            ->firstOrFail();

        $comission->update($request->only(['comissionPercent']));

        return redirect()->route('comissions.index'); // Redirección más segura
    }

    // ELIMINAR - SOLO SI LA COMISIÓN PERTENECE AL USUARIO
    public function destroy(Comission $comission)
    {
        $comission = Comission::where('id', $comission->id)
            ->where('user_id', auth()->id()) // Asegurar que la comisión pertenece al usuario
            ->firstOrFail();

        $comission->delete();

        return redirect()->route('comissions.index');
    }
}
