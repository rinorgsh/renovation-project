<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('devis_produit', function (Blueprint $table) {
        $table->id();
        $table->foreignId('devis_id')->constrained()->onDelete('cascade');
        $table->foreignId('produit_id')->constrained()->onDelete('cascade');
        $table->integer('quantite');
        $table->decimal('prix_unitaire', 10, 2);
        $table->decimal('total_ligne', 10, 2);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devis_produit');
    }
};
