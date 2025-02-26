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
    Schema::create('devis', function (Blueprint $table) {
        $table->id();
        $table->foreignId('client_id')->constrained()->onDelete('cascade');
        $table->string('numero_devis')->unique();
        $table->decimal('total_ht', 10, 2);
        $table->decimal('total_tva', 10, 2);
        $table->decimal('total_ttc', 10, 2);
        $table->enum('statut', ['en_attente', 'accepte', 'refuse'])->default('en_attente');
        $table->date('date_validite');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devis');
    }
};
