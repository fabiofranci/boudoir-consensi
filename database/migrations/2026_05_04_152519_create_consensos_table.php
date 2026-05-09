<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('consensi', function (Blueprint $table) {
            $table->id();
            $table->string('tipo');          // prima_seduta | completamento | preesistente | modella
            $table->json('data');            // payload completo del form
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('consensi');
    }
};