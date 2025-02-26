<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('devis', function (Blueprint $table) {
            $table->text('signature_data')->nullable(); // Stockera les données de la signature en base64
            $table->dateTime('signature_date')->nullable();
        });
    }

    public function down()
    {
        Schema::table('devis', function (Blueprint $table) {
            $table->dropColumn(['signature_data', 'signature_date']);
        });
    }
};