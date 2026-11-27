<?php

namespace App\Http\Controllers;

use App\Models\ClientTag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientTagController extends Controller
{
    // koppeling
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'client_id' => ['required', 'integer', 'exists:clients,id'],
            'tag_id' => ['required', 'integer', 'exists:tags,id'],
        ]);

        $client = Client::findOrFail($data['client_id']);
        $client->tags()->syncWithoutDetaching([$data['tag_id']]);

        return response()->json($data, 201);
    }
}
