<?php

namespace Src\Migrations;

return new class {
    public function up($table) {
        $table->create('post', function($column) {
            $column->id();
            $column->int('user_id');
            $column->string('content');
            $column->timestamps();
        });
    }

    public function down($table) {
        $table->dropIfExists('post');
    }
};
