<?php

use Illuminate\Database\Eloquent\Builder;

class ReadOnlyPost extends Post
{
    use AuthorizationRequired\AuthorizationRequired;

    protected $table = 'posts';

    public static function authorizationReadScope(Builder $query)
    {
        return $query->whereRaw('1=1');
    }
}
