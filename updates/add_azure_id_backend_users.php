<?php namespace LukeTowers\AzureADSSO\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddAzureIdBackendUsers extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('backend_users', 'azure_id')) {
            Schema::table('backend_users', function ($table) {
                $table->string('azure_id')->nullable();
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('backend_users', 'azure_id')) {
            Schema::table('backend_users', function ($table) {
                $table->dropColumn('azure_id');
            });
        }
    }
}
