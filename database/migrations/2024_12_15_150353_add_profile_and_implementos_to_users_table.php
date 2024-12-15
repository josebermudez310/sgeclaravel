<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfileAndImplementosToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('perfil')->nullable()->after('imagenes'); // Columna para la imagen de perfil
            $table->string('implemento1')->nullable()->after('perfil'); // Primer implemento
            $table->string('implemento2')->nullable()->after('implemento1'); // Segundo implemento
            $table->string('implemento3')->nullable()->after('implemento2'); // Tercer implemento
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['perfil', 'implemento1', 'implemento2', 'implemento3']);
        });
    }
}
