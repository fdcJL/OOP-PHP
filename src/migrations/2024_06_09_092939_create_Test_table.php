<?php

namespace Src\Migrations;

return new class {
    public function up($table) {
        $table->create('test', function($column) {
            $column->id();
            $column->string('fname');
            $column->string('lname');
            $column->timestamps();
        });
    }

    public function down($table) {
        $table->dropIfExists('test');
    }
};
