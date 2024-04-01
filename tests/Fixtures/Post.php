<?php

namespace LakM\Comments\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use LakM\Comments\concerns\Commentable;

class Post extends Model
{
    use Commentable;

    /**
     * @var false|mixed
     */
    protected $guarded = [];

    public bool $guestMode = false;
}
