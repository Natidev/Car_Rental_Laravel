<?php

namespace App\Http\Controllers;

use App\Enums\AgreementStatus;
use App\Models\OfficeRentAgreement;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class OfficeRentAgreementController extends Controller
{
    /**
     * Display a listing of the agreements.
     */
    public function index(Request $request): JsonResponse
    {
        $query = OfficeRentAgreement::query();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        // Expiring soon filter
        if ($request->boolean('expiring_soon')) {
            $days = $request->integer('days', 90);
            $query->expiringSoon($days);
        }

        $agreements = $query->with(['branch', 'approvedBy', 'agreementRenewals'])->get();

        return response()->json([
            'success' => true,
            'data' => $agreements,
        ]);
    }

    /**
     * Store a newly created agreement.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'agreement_id' => 'required|string|unique:office_rent_agreements,agreement_id',
            'landlord_name' => 'required|string|max:255',
            'property_address' => 'required|string',
            'monthly_rent' => 'required|numeric|min:0',
            'payment_schedule' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'scanned_contract_path' => 'nullable|string',
            'status' => ['nullable', Rule::enum(AgreementStatus::class)],
        ]);

        $validated['status'] = $validated['status'] ?? AgreementStatus::PENDING_APPROVAL;

        $agreement = OfficeRentAgreement::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Agreement created successfully',
            'data' => $agreement->load(['branch', 'approvedBy']),
        ], 201);
    }

    /**
     * Display the specified agreement.
     */
    public function show(OfficeRentAgreement $agreement): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $agreement->load(['branch', 'approvedBy', 'agreementRenewals']),
        ]);
    }

    /**
     * Update the specified agreement.
     */
    public function update(Request $request, OfficeRentAgreement $agreement): JsonResponse
    {
        $validated = $request->validate([
            'bran ch_id' => 'sometimes|exists:branches,id',
            'agreement_id' => 'sometimes|string|unique:office_rent_agreements,agreement_id,' . $agreement->id,
            'landlord_name' => 'sometimes|string|max:255',
            'property_address' => 'sometimes|string',
            'monthly_rent' => 'sometimes|numeric|min:0',
            'payment_schedule' => 'sometimes|string|max:100',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after:start_date',
            'scanned_contract_path' => 'nullable|string',
            'status' => ['sometimes', Rule::enum(AgreementStatus::class)],
        ]);

        $agreement->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Agreement updated successfully',
            'data' => $agreement->fresh(),
        ]);
    }

    /**
     * Approve the specified agreement.
     */
    public function approve(Request $request, OfficeRentAgreement $agreement): JsonResponse
    {
        $request->validate([
            'approved_by' => 'required|exists:users,id',
        ]);

        $agreement->update([
            'status' => AgreementStatus::ACTIVE,
            'approved_by' => $request->approved_by,
            'approved_at' => Carbon::now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Agreement approved successfully',
            'data' => $agreement->fresh()->load(['branch', 'approvedBy']),
        ]);
    }

    /**
     * Renew the specified agreement.
     */
    public function renew(Request $request, OfficeRentAgreement $agreement): JsonResponse
    {
        $validated = $request->validate([
            'new_monthly_rent' => 'required|numeric|min:0',
            'new_start_date' => 'required|date',
            'new_end_date' => 'required|date|after:new_start_date',
            'amendment_notes' => 'nullable|string',
        ]);
        $agreement->update(['status' => AgreementStatus::RENEWED]);

    
        $renewal = $agreement->agreementRenewals()->create([
            'new_monthly_rent' => $validated['new_monthly_rent'],
            'new_start_date' => $validated['new_start_date'],
            'new_end_date' => $validated['new_end_date'],
            'amendment_notes' => $validated['amendment_notes'] ?? null,
            'status' => AgreementStatus::PENDING_APPROVAL,
        ]);

        // Create new agreement based on renewal
        $newAgreement = OfficeRentAgreement::create([
            'branch_id' => $agreement->branch_id,
            'agreement_id' => $agreement->agreement_id . '-R' . ($agreement->agreementRenewals()->count() + 1),
            'landlord_name' => $agreement->landlord_name,
            'property_address' => $agreement->property_address,
            'monthly_rent' => $validated['new_monthly_rent'],
            'payment_schedule' => $agreement->payment_schedule,
            'start_date' => $validated['new_start_date'],
            'end_date' => $validated['new_end_date'],
            'status' => AgreementStatus::ACTIVE,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Agreement renewed successfully',
            'data' => [
                'original_agreement' => $agreement->fresh(),
                'renewal' => $renewal,
                'new_agreement' => $newAgreement->load(['branch', 'approvedBy']),
            ],
        ]);
    }

    /**
     * Remove the specified agreement.
     */
    public function destroy(OfficeRentAgreement $agreement): JsonResponse
    {
        $agreement->delete();

        return response()->json([
            'success' => true,
            'message' => 'Agreement deleted successfully',
        ]);
    }
}
