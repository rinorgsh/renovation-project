<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->string('telephone');
            $table->string('personal_id')->unique(); // Numéro national
            $table->string('pays')->default('Belgique');
            $table->string('province');
            $table->string('ville');
            $table->string('code_postal');
            $table->string('adresse');
            $table->string('numero_domicile');
            $table->string('numero_tva')->nullable();
            $table->enum('valeur_tva', ['6', '21'])->default('21');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};