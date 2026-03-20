<?php

namespace App\Http\Controllers;

use App\Enums\BranchStatus;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class BranchController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Branch::query();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $branches = $query->with(['officeRentAgreements', 'vehicles'])->get();

        return response()->json([
            'success' => true,
            'data' => $branches,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'proposed_office' => 'required|string|max:255',
            'status' => ['nullable', Rule::enum(BranchStatus::class)],
        ]);

        $branch = Branch::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Branch created successfully',
            'data' => $branch->load(['officeRentAgreements', 'vehicles']),
        ], 201);
    }

    public function show(Branch $branch): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $branch->load(['officeRentAgreements', 'vehicles', 'branchUtilities']),
        ]);
    }


    public function update(Request $request, Branch $branch): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'proposed_office' => 'sometimes|string|max:255',
            'status' => ['sometimes', Rule::enum(BranchStatus::class)],
        ]);

        $branch->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Branch updated successfully',
            'data' => $branch->fresh(),
        ]);
    }

    public function destroy(Branch $branch): JsonResponse
    {
        $branch->delete();

        return response()->json([
            'success' => true,
            'message' => 'Branch deleted successfully',
        ]);
    }
}
