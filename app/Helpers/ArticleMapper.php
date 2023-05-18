<?php

namespace App\Helpers;

use Carbon\Carbon;

class ArticleMapper
{
    private array $articles_formatted;

    public function __construct(){

        $this->articles_formatted = [ ];
    }

    public function formatArticleGeneral($articles, $tag): void
    {

        foreach ( $articles as $article ){

            switch( $tag ){

                case 'NEWS_ORG':
                    $title = $article['title'] ?? 'No Title Found';
                    $description = $article['description'] ?? 'No Description Found';
                    $web_url = $article['url'] ?? 'No Url Provided';
                    $image_preview = $article['urlToImage'] ?? env('ARTICLE_IMAGE_DEFAULT');
                    $date = $article['publishedAt'];
                    $author = $article['author'] ?? 'Unidentified Author';
                    $section = $article['sectionName'] ?? 'Unidentified Section';
                    break;

                case 'NY_TIMES':
                    $title = $article['headline']['main'] ?? 'No Title Found';
                    $description = $article['abstract'] ?? 'No Description Found';
                    $web_url =  'https://www.nytimes.com/'.$article['web_url'] ?? 'No Url Provided';
                    $image_preview = $article['multimedia']['url'] ?? env('ARTICLE_IMAGE_DEFAULT');
                    $date = $article['pub_date'];
                    $author = $article['author'] ?? 'Unidentified Author';
                    $section = $article['subsection_name'] ?? 'Unidentified Section';
                    break;

                case 'THE_GUARDIAN':
                default:
                    $title = $article['webTitle'] ?? 'No Title Found';
                    $description = $article['webTitle'] ?? 'No Description Found';
                    $web_url = $article['url'] ?? 'No Url Provided';
                    $image_preview = env('ARTICLE_IMAGE_DEFAULT');
                    $date = $article['webPublicationDate'];
                    $author = $article['author'] ?? 'Unidentified Author';
                    $section = $article['sectionName'] ?? 'Unidentified Section';
                    break;
            }

            $this->articles_formatted[] = [
                'title' => $title,
                'description' => $description,
                'web_url' => $web_url,
                'image_preview' => $image_preview,
                'date' => $date,
                'author' => $author,
                'section' => $section,
                'tag' => $tag
            ];
        }
    }

    public function getFormattedArticles(): array
    {

        return $this->articles_formatted;
    }
}
