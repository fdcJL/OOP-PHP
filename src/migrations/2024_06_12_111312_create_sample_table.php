<?php

namespace Src\Migrations;

return new class {
    public function up($table) {
        $table->create('sample', function($column) {
            $column->id();
            $column->string('idacct');
            $column->string('fname');
            $column->timestamps();
        });
    }

    public function down($table) {
        $table->dropIfExists('sample');
    }
};
