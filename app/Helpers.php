<?php

namespace App;

use App\Models\NewsSource;
use App\Models\UserNewsSource;

class Helpers
{
    public static function getNewsSourcesForUser( $userId ): array
    {
        $userNewsSources = UserNewsSource::where('user_id', $userId)->get();
        $newsSources = [];

        foreach($userNewsSources as $news_source){

            $newsSources [] = NewsSource::where('id', $news_source->news_source_id)->first();
        }

        return $newsSources;
    }
}
