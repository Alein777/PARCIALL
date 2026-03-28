<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Http\Requests\MarcaRequest;
use Illuminate\Http\JsonResponse;

class MarcaController extends Controller
{
    /**
     * GET /api/marcas
     * Listado de todas las marcas.
     */
    public function index(): JsonResponse
    {
        $marcas = Marca::all();

        return response()->json([
            'success' => true,
            'data'    => $marcas,
        ]);
    }

    /**
     * POST /api/marcas
     * Crear una nueva marca.
     */
    public function store(MarcaRequest $request): JsonResponse
    {
        $marca = Marca::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Marca creada correctamente.',
            'data'    => $marca,
        ], 201);
    }

    /**
     * GET /api/marcas/{id}
     * Mostrar una marca específica.
     */
    public function show(Marca $marca): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $marca,
        ]);
    }

    /**
     * PUT /api/marcas/{id}
     * Actualizar una marca existente.
     */
    public function update(MarcaRequest $request, Marca $marca): JsonResponse
    {
        $marca->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Marca actualizada correctamente.',
            'data'    => $marca,
        ]);
    }

    /**
     * DELETE /api/marcas/{id}
     * Eliminar una marca.
     */
    public function destroy(Marca $marca): JsonResponse
    {
        $marca->delete();

        return response()->json([
            'success' => true,
            'message' => 'Marca eliminada correctamente.',
        ]);
    }
}