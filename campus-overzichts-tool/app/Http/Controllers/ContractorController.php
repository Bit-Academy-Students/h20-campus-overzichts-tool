<?php

namespace App\Http\Controllers;

use App\Models\Contractor;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class ContractorController extends Controller
{
    //display alle Contractors
    public function index()
    {
        return response()->json(Contractor::all());
    }

    //nieuwe contractor
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:1000'],
            'website' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:contractors,email'],
            'phone' => ['nullable', 'string', 'max:30', 'unique:contractors,phone'],
            'address' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:50']
        ]);

        $contractor = Contractor::create($data);

        return response()->json($contractor, 201);
    }

    //specifieke contractor
    public function show(Contractor $contractor): JsonResponse
    {
        return response()->json($contractor);
    }

    //edit contractor
    public function update(Request $request, Contractor $contractor)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:100'],
            'description' => ['sometimes', 'string', 'max:1000'],
            'website' => ['sometimes', 'string', 'max:100'],
            'email' => ['sometimes', 'email', 'max:100', Rule::unique('contractors', 'email')->ignore($contractor->id)],
            'phone' => ['sometimes', 'nullable', 'string', 'max:30', Rule::unique('contractors', 'phone')->ignore($contractor->id)],
            'address' => ['sometimes', 'string', 'max:100'],
            'city' => ['sometimes', 'string', 'max:50']
        ]);
        
        $contractor->update($data);

        return response()->json($contractor);
    }

    //delete contractor
    public function destroy(Contractor $contractor)
    {
        $contractor->delete();

        return response()->json(null, 204);
    }
}
