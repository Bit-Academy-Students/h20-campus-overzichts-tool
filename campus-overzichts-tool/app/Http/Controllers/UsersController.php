<?php

// user controller api 
// docs in endpoints find je details over alle endpoints en hoe je ze moet gebruiken
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UsersController extends Controller
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
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::create($data + ['role' => 'pending']);

        return response()->json(['redirect' => route('login')], 201);
    }

    // POST /login - inloggen met email + password (wachtwoord wordt server-side vergeleken met de hash).
    // Geeft 200 + redirect-url bij succes, 422 bij foute gegevens.
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return response()->json(['message' => 'Email or password is incorrect.'], 422);
        }

        $request->session()->regenerate();

        return response()->json(['redirect' => route('dashboard')]);
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
            'role' => ['sometimes', Rule::in(['pending', 'municipality', 'admin'])],
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
