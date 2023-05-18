<?php

use App\Models\NewsSource;
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
        Schema::create('news_sources', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('source_name');
            $table->string('source_tag');
        });

        $default_source = [
            [
                'source_name' => 'NyTimes.com',
                'source_tag' => 'NY_TIMES',
            ],
            [
                'source_name' => 'News.org',
                'source_tag' => 'NEWS_ORG',

            ],
            [
                'source_name' => 'The Guardian',
                'source_tag' => 'THE_GUARDIAN',
            ]
        ];

        foreach($default_source as $source){

            NewsSource::firstOrCreate( $source );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_sources');
    }
};
