<?php

namespace App\Http\Controllers;

use App\Enums\ServiceRequestStatus;
use App\Models\VehicleServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class VehicleServiceRequestController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = VehicleServiceRequest::query();

        if ($request->has('vehicle_id')) {
            $query->where('vehicle_id', $request->vehicle_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('urgency')) {
            $query->where('urgency', $request->urgency);
        }

        $serviceRequests = $query->with(['vehicle', 'requestedBy', 'vehicleMaintenanceRecords'])->get();

        return response()->json([
            'success' => true,
            'data' => $serviceRequests,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'requested_by' => 'required|exists:users,id',
            'problem_description' => 'required|string',
            'service_type' => ['required', Rule::enum(\App\Enums\ServiceType::class)],
            'urgency' => ['required', Rule::enum(\App\Enums\UrgencyLevel::class)],
            'status' => ['nullable', Rule::enum(ServiceRequestStatus::class)],
        ]);

        $validated['status'] = $validated['status'] ?? ServiceRequestStatus::PENDING;

        $serviceRequest = VehicleServiceRequest::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Service request created successfully',
            'data' => $serviceRequest->load(['vehicle', 'requestedBy']),
        ], 201);
    }

    public function show(VehicleServiceRequest $serviceRequest): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $serviceRequest->load(['vehicle', 'requestedBy', 'vehicleMaintenanceRecords']),
        ]);
    }
public function update(Request $request, VehicleServiceRequest $serviceRequest): JsonResponse
    {
        $validated = $request->validate([
            'vehicle_id' => 'sometimes|exists:vehicles,id',
            'requested_by' => 'sometimes|exists:users,id',
            'problem_description' => 'sometimes|string',
            'service_type' => ['sometimes', Rule::enum(\App\Enums\ServiceType::class)],
            'urgency' => ['sometimes', Rule::enum(\App\Enums\UrgencyLevel::class)],
            'status' => ['sometimes', Rule::enum(ServiceRequestStatus::class)],
        ]);

        $serviceRequest->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Service request updated successfully',
            'data' => $serviceRequest->fresh(),
        ]);
    }

    public function approve(VehicleServiceRequest $serviceRequest): JsonResponse
    {
        $serviceRequest->update([
            'status' => ServiceRequestStatus::APPROVED,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Service request approved',
            'data' => $serviceRequest->fresh()->load(['vehicle', 'requestedBy']),
        ]);
    }

    
    public function startWork(VehicleServiceRequest $serviceRequest): JsonResponse
    {
        $serviceRequest->update([
            'status' => ServiceRequestStatus::IN_PROGRESS,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Service request started',
            'data' => $serviceRequest->fresh()->load(['vehicle', 'requestedBy']),
        ]);
    }

    public function complete(Request $request, VehicleServiceRequest $serviceRequest): JsonResponse
    {
        $validated = $request->validate([
            'service_provider' => 'required|string|max:255',
            'service_details' => 'required|string',
            'mileage_at_service' => 'required|integer',
            'completed_date' => 'required|date',
            'cost' => 'required|numeric|min:0',
            'report_path' => 'nullable|string',
        ]);

        $maintenanceRecord = $serviceRequest->vehicleMaintenanceRecords()->create($validated);

        $vehicle = $serviceRequest->vehicle;
        $vehicle->update([
            'current_mileage' => $validated['mileage_at_service'],
            'last_service_date' => $validated['completed_date'],
        ]);
        $serviceRequest->update([
            'status' => ServiceRequestStatus::COMPLETED,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Service request completed',
            'data' => [
                'service_request' => $serviceRequest->fresh()->load(['vehicle', 'requestedBy']),
                'maintenance_record' => $maintenanceRecord,
            ],
        ]);
    }

    public function cancel(VehicleServiceRequest $serviceRequest): JsonResponse
    {
        $serviceRequest->update([
            'status' => ServiceRequestStatus::CANCELLED,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Service request cancelled',
            'data' => $serviceRequest->fresh(),
        ]);
    }

    public function destroy(VehicleServiceRequest $serviceRequest): JsonResponse
    {
        $serviceRequest->delete();

        return response()->json([
            'success' => true,
            'message' => 'Service request deleted successfully',
        ]);
    }
}
