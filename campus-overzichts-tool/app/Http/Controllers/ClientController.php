<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    // lijst van alle Clients
    public function index()
    {
        return response()->json(Client::all());
    }

    // nieuwe Client aanmaken
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:1000'],
            'website' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:clients,email'],
            'phone' => ['nullable', 'string', 'max:30', 'unique:clients,phone'],
            'address' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:50']
        ]);

        $client = Client::create($data);

        return response()->json($client, 201);
    }

    // laat 1 specifieke client zien
    public function show(Client $client): JsonResponse
    {
        return response()->json($client);
    }


    // edit een client
    public function update(Request $request, Client $client)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:100'],
            'description' => ['sometimes', 'string', 'max:1000'],
            'website' => ['sometimes', 'string', 'max:100'],
            'email' => ['sometimes', 'email', 'max:100', Rule::unique('clients', 'email')->ignore($client->id)],
            'phone' => ['sometimes', 'string', 'nullable', 'max:30', Rule::unique('clients', 'phone')->ignore($client->id)],
            'address' => ['sometimes', 'string', 'max:100'],
            'city' => ['sometimes', 'string', 'max:50']
        ]);
        
        $client->update($data);

        return response()->json($client);
    }

    // delete een client
    public function destroy(Client $client): JsonResponse
    {
        $client->delete();

        return response()->json(null, 204);
    }
}
