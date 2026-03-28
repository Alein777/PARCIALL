<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Http\Requests\CategoriaRequest;
use Illuminate\Http\JsonResponse;

class CategoriaController extends Controller
{
    /**
     * GET /api/categorias
     */
    public function index(): JsonResponse
    {
        $categorias = Categoria::all();

        return response()->json([
            'success' => true,
            'data'    => $categorias,
        ]);
    }

    /**
     * POST api/categoria
     */
    public function store(CategoriaRequest $request): JsonResponse
    {
        $categoria = Categoria::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Categoría creada correctamente.',
            'data'    => $categoria,
        ], 201);
    }

    /**
     * GET /api/categorias/{id}
     */
    public function show(Categoria $categoria): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $categoria,
        ]);
    }

    /**
     * PUT /api/categorias/{id}
     */
    public function update(CategoriaRequest $request, Categoria $categoria): JsonResponse
    {
        $categoria->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Categoría actualizada correctamente.',
            'data'    => $categoria,
        ]);
    }

    /**
     * DELETE /api/categorias/{id}
     */
    public function destroy(Categoria $categoria): JsonResponse
    {
        $categoria->delete();

        return response()->json([
            'success' => true,
            'message' => 'Categoría eliminada correctamente.',
        ]);
    }
}