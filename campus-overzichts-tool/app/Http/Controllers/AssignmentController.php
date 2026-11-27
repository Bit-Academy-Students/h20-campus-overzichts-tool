<?php

// Assignments API, zie docs/endpoints.md
namespace App\Http\Controllers;

use App\Models\Assignment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class AssignmentController extends Controller
{
    // Relaties die we meesturen bij één assignment
    private const RELATIONS = ['client', 'contactPerson', 'tags'];

    // GET /api/assignments - lijst
    public function index(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'status' => ['sometimes', Rule::in(Assignment::STATUSES)],
            'priority' => ['sometimes', Rule::in(Assignment::PRIORITIES)],
            'client_id' => ['sometimes', 'integer'],
            'tag' => ['sometimes', 'integer'],
            'search' => ['sometimes', 'string', 'max:200'],
            'sort' => ['sometimes', Rule::in(['title', 'posted_at', 'priority', 'scraped_at'])],
            'direction' => ['sometimes', Rule::in(['asc', 'desc'])],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ]);

        $assignments = Assignment::with(['client', 'tags', 'claim'])
            ->where(collect($filters)->only(['status', 'priority', 'client_id'])->all())
            ->when($filters['tag'] ?? null, fn ($q, $tag) => $q->whereHas('tags', fn ($q) => $q->where('tags.id', $tag)))
            ->when($filters['search'] ?? null, fn ($q, $search) => $q->where(fn ($q) => $q
                ->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")))
            ->orderBy($filters['sort'] ?? 'scraped_at', $filters['direction'] ?? 'desc')
            ->paginate($filters['per_page'] ?? 25);

        return response()->json($assignments);
    }

    // POST /api/assignments - aanmaken
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'client_id' => ['nullable', 'integer', 'exists:clients,id'],
            'contact_person_id' => ['nullable', 'integer', 'exists:contact_persons,id'],
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'source_url' => ['nullable', 'url', 'max:500', 'unique:assignments,source_url'],
            'status' => ['sometimes', Rule::in(Assignment::STATUSES)],
            'priority' => ['sometimes', Rule::in(Assignment::PRIORITIES)],
            'posted_at' => ['nullable', 'date'],
            'tags' => ['sometimes', 'array'],
            'tags.*' => ['integer', 'exists:tags,id'],
        ]);

        $assignment = Assignment::create(Arr::except($data, 'tags'));
        $assignment->tags()->sync($data['tags'] ?? []);

        return response()->json($assignment->load(self::RELATIONS), 201);
    }

    // GET /api/assignments/{id} - één assignment
    public function show(Assignment $assignment): JsonResponse
    {
        return response()->json(
            $assignment->load([...self::RELATIONS, 'claim.user', 'claim.contractor'])
        );
    }

    // PATCH /api/assignments/{id} - wijzigen
    public function update(Request $request, Assignment $assignment): JsonResponse
    {
        $data = $request->validate([
            'client_id' => ['sometimes', 'nullable', 'integer', 'exists:clients,id'],
            'contact_person_id' => ['sometimes', 'nullable', 'integer', 'exists:contact_persons,id'],
            'title' => ['sometimes', 'string', 'max:200'],
            'description' => ['sometimes', 'nullable', 'string'],
            'source_url' => ['sometimes', 'nullable', 'url', 'max:500', Rule::unique('assignments', 'source_url')->ignore($assignment->id)],
            'status' => ['sometimes', Rule::in(Assignment::STATUSES)],
            'priority' => ['sometimes', Rule::in(Assignment::PRIORITIES)],
            'posted_at' => ['sometimes', 'nullable', 'date'],
            'tags' => ['sometimes', 'array'],
            'tags.*' => ['integer', 'exists:tags,id'],
        ]);

        $assignment->update(Arr::except($data, 'tags'));

        if (isset($data['tags'])) {
            $assignment->tags()->sync($data['tags']);
        }

        return response()->json($assignment->load(self::RELATIONS));
    }

    // DELETE /api/assignments/{id} - verwijderen
    public function destroy(Assignment $assignment): JsonResponse
    {
        $assignment->delete();

        return response()->json(null, 204);
    }
}
