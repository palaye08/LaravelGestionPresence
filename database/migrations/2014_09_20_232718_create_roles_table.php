<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id(); // Identifiant unique du rôle
            $table->string('libelle')->unique(); // Libellé du rôle (ex : Admin, Manager, Vigil, CME)
            $table->timestamps(); // Timestamps pour created_at et updated_at
        });

        // Insérer les rôles par défaut
        DB::table('roles')->insert([
            ['libelle' => 'Admin'],
            ['libelle' => 'Manager'],
            ['libelle' => 'Vigil'],
            ['libelle' => 'CME'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
    