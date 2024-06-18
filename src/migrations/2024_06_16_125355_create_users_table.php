<?php

namespace Src\Migrations;

return new class {
    public function up($table) {
        $table->create('users', function($column) {
            $column->id();
            $column->string('fname');
            $column->string('lname');
            $column->string('username', 100);
            $column->string('email');
            $column->string('password');
            $column->date('bdate');
            $column->timestamps();
        });
    }

    public function down($table) {
        $table->dropIfExists('users');
    }
};
