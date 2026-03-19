<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReadmeGeneration;
use App\Services\ReadmeGeneratorService;
use Illuminate\Http\JsonResponse;

class ReadmeController extends Controller
{
    protected $generator;

    public function __construct(ReadmeGeneratorService $generator)
    {
        $this->generator = $generator;
    }

    public function generate(Request $request): JsonResponse
    {
        $data = $request->validate([
            'description' => 'required|string',
            'project_name' => 'nullable|string',
            'template' => 'nullable|string',
        ]);

        $generation = ReadmeGeneration::create([
            'project_name' => $data['project_name'] ?? null,
            'description' => $data['description'],
            'status' => 'pending',
            'meta' => ['template' => $data['template'] ?? 'default'],
        ]);

        try {
            // For initial implementation we generate synchronously.
            $readme = $this->generator->generate($data['description'], $data['project_name'] ?? null, $data['template'] ?? 'default');

            $generation->update([
                'generated_readme' => $readme,
                'status' => 'completed',
            ]);

            return response()->json([
                'id' => $generation->id,
                'generated_readme' => $readme,
                'status' => 'completed',
            ], 201);
        } catch (\Throwable $e) {
            $generation->update([
                'status' => 'failed',
                'meta' => array_merge($generation->meta ?? [], ['error' => $e->getMessage()]),
            ]);

            return response()->json([
                'message' => 'Failed to generate README',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        $generation = ReadmeGeneration::find($id);

        if (! $generation) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json($generation);
    }
}
