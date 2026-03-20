<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class VehicleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Vehicle::query();

        if ($request->has('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        $vehicles = $query->with(['branch', 'vehicleLicenses', 'vehicleServiceRequests', 'vehicleMaintenanceRecords'])->get();

        return response()->json([
            'success' => true,
            'data' => $vehicles,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'plate_number' => 'required|string|unique:vehicles,plate_number',
            'registration_number' => 'required|string|unique:vehicles,registration_number',
            'make_model' => 'required|string|max:255',
            'current_mileage' => 'required|integer|min:0',
            'last_service_date' => 'nullable|date',
        ]);

        $vehicle = Vehicle::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Vehicle created successfully',
            'data' => $vehicle->load(['branch']),
        ], 201);
    }

 
    public function show(Vehicle $vehicle): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $vehicle->load(['branch', 'vehicleLicenses', 'vehicleServiceRequests', 'vehicleMaintenanceRecords']),
        ]);
    }

    public function update(Request $request, Vehicle $vehicle): JsonResponse
    {
        $validated = $request->validate([
            'branch_id' => 'sometimes|exists:branches,id',
            'plate_number' => 'sometimes|string|unique:vehicles,plate_number,' . $vehicle->id,
            'registration_number' => 'sometimes|string|unique:vehicles,registration_number,' . $vehicle->id,
            'make_model' => 'sometimes|string|max:255',
            'current_mileage' => 'sometimes|integer|min:0',
            'last_service_date' => 'nullable|date',
        ]);

        $vehicle->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Vehicle updated successfully',
            'data' => $vehicle->fresh(),
        ]);
    }

    public function destroy(Vehicle $vehicle): JsonResponse
    {
        $vehicle->delete();

        return response()->json([
            'success' => true,
            'message' => 'Vehicle deleted successfully',
        ]);
    }


    public function checkServiceNeed(Vehicle $vehicle): JsonResponse
    {
        $needsService = false;
        $reasons = [];

        // Check mileage (threshold: 5000 km)
        $mileageSinceLastService = $vehicle->current_mileage - ($vehicle->last_service_date ? 
            $vehicle->vehicleMaintenanceRecords()->max('mileage_at_service') ?? 0 : 0);
        
        if ($mileageSinceLastService >= 5000) {
            $needsService = true;
            $reasons[] = 'Mileage threshold reached (5000 km)';
        }

        // Check last service date (threshold: 6 months)
        if ($vehicle->last_service_date) {
            $monthsSinceService = now()->diffInMonths($vehicle->last_service_date);
            if ($monthsSinceService >= 6) {
                $needsService = true;
                $reasons[] = 'Service due (6 months since last service)';
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'vehicle_id' => $vehicle->id,
                'needs_service' => $needsService,
                'reasons' => $reasons,
                'current_mileage' => $vehicle->current_mileage,
                'last_service_date' => $vehicle->last_service_date,
            ],
        ]);
    }
}
