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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nom'); // Nom de l'utilisateur
            $table->string('prenom'); // Prénom de l'utilisateur
            $table->string('telephone')->unique(); // Téléphone unique
            $table->string('email')->unique(); // Email unique
            $table->enum('statu', ['bloquer', 'actif'])->default('actif'); // Statut de l'utilisateur
            $table->string('photo')->nullable(); // Chemin du fichier photo
            $table->string('fonction')->nullable(); // Fonction de l'utilisateur
            
            $table->enum('role', ['Admin', 'Manager', 'Vigil', 'CME']); // Utilisation de ENUM
            // Fait référence à la table roles

            // Champs existants
            $table->timestamp('email_verified_at')->nullable(); // Vérification de l'email
            $table->string('password')->nullable(); // Mot de passe
            $table->rememberToken(); // Token de "remember me"
            $table->timestamps(); // Timestamps pour created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
