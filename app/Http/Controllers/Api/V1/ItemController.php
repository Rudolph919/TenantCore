<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Tenant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of items for the current tenant.
     */
    public function index(Request $request): JsonResponse
    {
        $tenant = $request->attributes->get('tenant');
        $items = $tenant->items()->get();

        return response()->json(['data' => $items]);
    }

    /**
     * Store a newly created item.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $tenant = $request->attributes->get('tenant');
        $item = $tenant->items()->create($request->only(['name', 'description']));

        return response()->json(['data' => $item], 201);
    }

    /**
     * Display the specified item.
     */
    public function show(Request $request, Item $item): JsonResponse
    {
        $tenant = $request->attributes->get('tenant');

        if ($item->tenant_id !== $tenant->id) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        return response()->json(['data' => $item]);
    }

    /**
     * Update the specified item.
     */
    public function update(Request $request, Item $item): JsonResponse
    {
        $tenant = $request->attributes->get('tenant');

        if ($item->tenant_id !== $tenant->id) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $item->update($request->only(['name', 'description']));

        return response()->json(['data' => $item->fresh()]);
    }

    /**
     * Remove the specified item.
     */
    public function destroy(Request $request, Item $item): JsonResponse
    {
        $tenant = $request->attributes->get('tenant');

        if ($item->tenant_id !== $tenant->id) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        $item->delete();

        return response()->json(null, 204);
    }
}
