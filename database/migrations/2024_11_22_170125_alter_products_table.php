<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('active_in_app')->default(1)->after('type');
            $table->boolean('featured')->default(1)->after('active_in_app');
            $table->boolean('show_in_home')->default(1)->after('featured');

            // $table->unsignedBigInteger('application_category_id');
            // $table->foreign('application_category_id')->nullable()
            // ->references('id')->on('application_categories')->nullOnDelete()->after('sub_category_id');

            $table->unsignedBigInteger('application_category_id')->nullable()->after('sub_category_id');
            $table->foreign('application_category_id')
                ->references('id')
                ->on('application_categories')
                ->nullOnDelete();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['application_category_id']);
    
            // Drop the added columns
            $table->dropColumn('application_category_id');
            $table->dropColumn('active_in_app');
            $table->dropColumn('featured');
            $table->dropColumn('show_in_home');
        });
    }
}