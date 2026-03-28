<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->notNull();
            $table->text('descripcion')->nullable();
            $table->decimal('precio', 10, 2)->notNull();
            $table->unsignedBigInteger('categoria_id')->notNull();
            $table->unsignedBigInteger('marca_id')->notNull();
            $table->unsignedBigInteger('proveedor_id')->notNull();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('categoria_id')->references('id')->on('categorias');
            $table->foreign('marca_id')->references('id')->on('marcas');
            $table->foreign('proveedor_id')->references('id')->on('proveedores');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
