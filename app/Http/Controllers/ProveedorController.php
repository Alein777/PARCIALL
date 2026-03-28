<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use App\Http\Requests\ProveedorRequest;
use Illuminate\Http\JsonResponse;

class ProveedorController extends Controller
{
    /**
     * GET /api/proveedores
     */
    public function index(): JsonResponse
    {
        $proveedores = Proveedor::all();

        return response()->json([
            'success' => true,
            'data'    => $proveedores,
        ]);
    }

    /**
     * POST /api/proveedores
     */
    public function store(ProveedorRequest $request): JsonResponse
    {
        $proveedor = Proveedor::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Proveedor creado correctamente.',
            'data'    => $proveedor,
        ], 201);
    }

    /**
     * GET /api/proveedores/{id}
     */
    public function show(Proveedor $proveedor): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $proveedor,
        ]);
    }

    /**
     * PUT /api/proveedores/{id}
     */
    public function update(ProveedorRequest $request, Proveedor $proveedor): JsonResponse
    {
        $proveedor->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Proveedor actualizado correctamente.',
            'data'    => $proveedor,
        ]);
    }

    /**
     * DELETE /api/proveedores/{id}
     */
    public function destroy(Proveedor $proveedor): JsonResponse
    {
        $proveedor->delete();

        return response()->json([
            'success' => true,
            'message' => 'Proveedor eliminado correctamente.',
        ]);
    }
}