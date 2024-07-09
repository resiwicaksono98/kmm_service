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
        Schema::create('package_tasks', function (Blueprint $table) {
            $table->uuid('package_id');
            $table->uuid('task_id');
            $table->primary(['package_id', 'task_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('package_task');
    }

};
