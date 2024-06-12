<?php

namespace Src\Migrations;

return new class {
    public function up($table) {
        $table->create('test', function($column) {
            $column->id();
            // Add your table columns here
            $column->timestamps();
        });
    }

    public function down($table) {
        $table->dropIfExists('test');
    }
};