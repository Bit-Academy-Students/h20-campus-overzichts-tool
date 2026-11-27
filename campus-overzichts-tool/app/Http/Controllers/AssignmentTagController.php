<?php

// Tags van een assignment (pivot assignment_tags), zie docs/endpoints.md
namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AssignmentTagController extends Controller
{
    // GET /api/assignments/{assignment}/tags - tags van een assignment
    public function index(Assignment $assignment): JsonResponse
    {
        return response()->json($assignment->tags()->orderBy('name')->get());
    }

    // POST /api/assignments/{assignment}/tags - tag(s) koppelen
    public function store(Request $request, Assignment $assignment): JsonResponse
    {
        $data = $request->validate([
            'tag_id' => ['required_without:tags', 'integer', 'exists:tags,id'],
            'tags' => ['required_without:tag_id', 'array'],
            'tags.*' => ['integer', 'exists:tags,id'],
        ]);

        // Bestaande koppelingen blijven staan, dubbele worden genegeerd
        $assignment->tags()->syncWithoutDetaching(
            $data['tags'] ?? [$data['tag_id']]
        );

        return response()->json($assignment->tags()->orderBy('name')->get(), 201);
    }

    // DELETE /api/assignments/{assignment}/tags/{tag} - tag ontkoppelen
    public function destroy(Assignment $assignment, Tag $tag): JsonResponse
    {
        if ($assignment->tags()->detach($tag->id) === 0) {
            abort(404, 'This tag is not linked to this assignment.');
        }

        return response()->json(null, 204);
    }
}
