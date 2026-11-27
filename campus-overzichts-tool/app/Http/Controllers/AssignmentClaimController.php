<?php

// Assignment claims API, zie docs/endpoints.md
namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AssignmentClaim;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AssignmentClaimController extends Controller
{
    // Relaties die we altijd meesturen
    private const RELATIONS = ['assignment', 'user', 'contractor'];

    // GET /api/assignment-claims - lijst
    public function index(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'status' => ['sometimes', Rule::in(AssignmentClaim::STATUSES)],
            'user_id' => ['sometimes', 'integer'],
            'contractor_id' => ['sometimes', 'integer'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ]);

        $claims = AssignmentClaim::with(self::RELATIONS)
            ->where(collect($filters)->only(['status', 'user_id', 'contractor_id'])->all())
            ->orderByDesc('claimed_at')
            ->paginate($filters['per_page'] ?? 25);

        return response()->json($claims);
    }

    // POST /api/assignment-claims - claimen
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'assignment_id' => ['required', 'integer', 'exists:assignments,id', 'unique:assignment_claims,assignment_id'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'contractor_id' => ['nullable', 'integer', 'exists:contractors,id', 'required_if:status,assigned'],
            'status' => ['sometimes', Rule::in(AssignmentClaim::STATUSES)],
        ], [
            'assignment_id.unique' => 'This assignment has already been claimed.',
        ]);

        if (($data['status'] ?? 'claimed') === 'assigned') {
            $data['assigned_at'] = now();
        }

        $claim = DB::transaction(function () use ($data) {
            $claim = AssignmentClaim::create($data);
            $this->syncAssignmentStatus($claim);

            return $claim;
        });

        return response()->json($claim->load(self::RELATIONS), 201);
    }

    // GET /api/assignment-claims/{id} - één claim
    public function show(AssignmentClaim $assignmentClaim): JsonResponse
    {
        return response()->json($assignmentClaim->load(self::RELATIONS));
    }

    // PATCH /api/assignment-claims/{id} - wijzigen/toewijzen
    public function update(Request $request, AssignmentClaim $assignmentClaim): JsonResponse
    {
        $data = $request->validate([
            'user_id' => ['sometimes', 'integer', 'exists:users,id'],
            'contractor_id' => ['sometimes', 'nullable', 'integer', 'exists:contractors,id'],
            'status' => ['sometimes', Rule::in(AssignmentClaim::STATUSES)],
        ]);

        // Vul de nieuwe waarden in zonder op te slaan, zodat we kunnen controleren
        $assignmentClaim->fill($data);

        if ($assignmentClaim->status === 'assigned') {
            if ($assignmentClaim->contractor_id === null) {
                throw ValidationException::withMessages([
                    'contractor_id' => 'A contractor is required to assign this claim.',
                ]);
            }

            // assigned_at alleen bij eerste toewijzing
            $assignmentClaim->assigned_at ??= now();
        }

        DB::transaction(function () use ($assignmentClaim) {
            $assignmentClaim->save();
            $this->syncAssignmentStatus($assignmentClaim);
        });

        return response()->json($assignmentClaim->load(self::RELATIONS));
    }

    // DELETE /api/assignment-claims/{id} - intrekken
    public function destroy(AssignmentClaim $assignmentClaim): JsonResponse
    {
        DB::transaction(function () use ($assignmentClaim) {
            // Assignment terug naar 'accepted' als hij door deze claim 'assigned' was
            $assignmentClaim->assignment()->where('status', 'assigned')->update(['status' => 'accepted']);
            $assignmentClaim->delete();
        });

        return response()->json(null, 204);
    }

    // Claim assigned/completed => assignment assigned
    private function syncAssignmentStatus(AssignmentClaim $claim): void
    {
        if (in_array($claim->status, ['assigned', 'completed'], true)) {
            Assignment::whereKey($claim->assignment_id)->update(['status' => 'assigned']);
        }
    }
}
