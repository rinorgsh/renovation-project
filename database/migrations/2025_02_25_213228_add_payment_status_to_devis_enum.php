<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddPaymentStatusToDevisEnum extends Migration
{
    public function up()
    {
        // Vérifiez le type de connexion
        $connectionType = DB::getDriverName();

        if ($connectionType === 'mysql') {
            // Modification spécifique à MySQL
            DB::statement("ALTER TABLE devis MODIFY statut ENUM('en_attente', 'accepte', 'refuse', 'paye', 'acompte') DEFAULT 'en_attente'");
        } else {
            // Pour les autres bases de données (comme SQLite)
            Schema::table('devis', function (Blueprint $table) {
                $table->enum('statut', ['en_attente', 'accepte', 'refuse', 'paye', 'acompte'])->default('en_attente')->change();
            });
        }
    }

    public function down()
    {
        $connectionType = DB::getDriverName();

        if ($connectionType === 'mysql') {
            DB::statement("ALTER TABLE devis MODIFY statut ENUM('en_attente', 'accepte', 'refuse') DEFAULT 'en_attente'");
        } else {
            Schema::table('devis', function (Blueprint $table) {
                $table->enum('statut', ['en_attente', 'accepte', 'refuse'])->default('en_attente')->change();
            });
        }
    }
}