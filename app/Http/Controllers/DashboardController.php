<?php

namespace App\Http\Controllers;

use App\Models\Sell;
use App\Models\Client;
use Inertia\Inertia;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Obtener todas las ventas del usuario para cálculos y calendario
        $allSells = Sell::where('user_id', $userId)
            ->with(['client'])
            ->get();

        // Calcular ganancias totales
        $totalEarnings = $allSells->sum(function ($sell) {
            return ($sell->price * ($sell->comission ?? 0)) / 100;
        });

        // Conteo total de ventas y clientes
        $totalSellsCount = $allSells->count();
        $totalClientsCount = Client::where('user_id', $userId)->count();

        // Eventos para el calendario con próximas visitas
        $calendarEvents = $allSells->filter(function ($sell) {
            return !empty($sell->next_visit);
        })->map(function ($sell) {
            $formattedDate = $sell->next_visit instanceof \DateTimeInterface 
                ? $sell->next_visit->format('Y-m-d') 
                : substr((string)$sell->next_visit, 0, 10);

            return [
                'id' => $sell->id,
                'date' => $formattedDate,
                'title' => $sell->title,
                'client_name' => $sell->client?->name ?? 'Sin cliente',
                'price' => $sell->price,
            ];
        })->values();

        return Inertia::render('Dashboard', [
            'totalEarnings' => $totalEarnings,
            'totalSellsCount' => $totalSellsCount,
            'totalClientsCount' => $totalClientsCount,
            'calendarEvents' => $calendarEvents,
        ]);
    }
}
