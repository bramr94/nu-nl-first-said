<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Article
 *
 * @author Bram Raaijmakers
 *
 * @package App\Models
 */
class Article extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['article_id', 'url', 'title', 'content', 'stored_words'];
}
