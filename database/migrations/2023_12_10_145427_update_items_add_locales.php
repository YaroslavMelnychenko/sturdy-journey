<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('place_ru')->nullable()->after('place')->index();
            $table->text('short_description_ru')->nullable()->after('short_description');
            $table->json('description_ru')->nullable()->after('description');
            $table->json('features_ru')->nullable()->after('features');
            $table->json('seo_ru')->nullable()->after('seo');

            $table->string('place_uk')->nullable()->after('place_ru')->index();
            $table->text('short_description_uk')->nullable()->after('short_description_ru');
            $table->json('description_uk')->nullable()->after('description_ru');
            $table->json('features_uk')->nullable()->after('features_ru');
            $table->json('seo_uk')->nullable()->after('seo_ru');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn([
                'place_ru',
                'short_description_ru',
                'description_ru',
                'features_ru',
                'seo_ru',
                'place_uk',
                'short_description_uk',
                'description_uk',
                'features_uk',
                'seo_uk',
            ]);
        });
    }
};
