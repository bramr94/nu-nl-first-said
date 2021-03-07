<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UniqueWord extends Model
{
    use SoftDeletes;

    protected $fillable = ['value', 'occurrences', 'should_be_tweeted', 'tweeted'];
}
