<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            // Supprimer l'index unique sur email
            $table->dropUnique('clients_email_unique');
        });
    }

    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            // Recréer l'index unique pour rollback si nécessaire
            $table->unique('email');
        });
    }
};