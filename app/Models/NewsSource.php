<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsSource extends Model
{
    use HasFactory;

    protected $fillable = [ 'source_name', 'source_tag' ];

    public function newsSources()
    {
        return $this->hasMany(UserNewsSource::class);
    }
}
