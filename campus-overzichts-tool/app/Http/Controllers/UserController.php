<?php

// Users API, zie docs/endpoints.md
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // GET /api/users - lijst van alle users.
    public function index(): JsonResponse
    {
        return response()->json(User::all());
    }

    // POST /api/users - nieuwe user aanmaken.
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::create($data + ['role' => 'pending']);

        return response()->json($user, 201);
    }

    // GET /api/users/{user} - één user.
    public function show(User $user): JsonResponse
    {
        return response()->json($user);
    }

    // PUT/PATCH /api/users/{user} - user wijzigen.
    public function update(Request $request, User $user): JsonResponse
    {
        $data = $request->validate([
            'first_name' => ['sometimes', 'string', 'max:100'],
            'last_name' => ['sometimes', 'string', 'max:100'],
            'email' => ['sometimes', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['sometimes', 'string', 'min:8'],
            'role' => ['sometimes', Rule::in(User::ROLES)],
        ]);

        $user->update($data);

        return response()->json($user);
    }

    // DELETE /api/users/{user} - user verwijderen.
    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json(null, 204);
    }
}
