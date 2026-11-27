<?php

namespace App\Http\Controllers;

use App\Models\Contractor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContractorTagController extends Controller
{
    // koppeling
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'contractor_id' => ['required', 'integer', 'exists:contractors,id'],
            'tag_id' => ['required', 'integer', 'exists:tags,id'],
        ]);

        $contractor = Contractor::findOrFail($data['contractor_id']);
        $contractor->tags()->syncWithoutDetaching([$data['tag_id']]);

        return response()->json($data, 201);
    }
}
