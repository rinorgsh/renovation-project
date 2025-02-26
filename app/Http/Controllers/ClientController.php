<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::all();
        
        return Inertia::render('Client/Index', [
            'clients' => $clients
        ]);
    }

    public function show(Client $client)
    {
        return $client;
    }
}