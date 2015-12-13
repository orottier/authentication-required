<?php

namespace AuthorizationRequired;

use Illuminate\Database\Eloquent\ScopeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AuthorizationRequiredScope implements ScopeInterface
{
    public function apply(Builder $builder, Model $model)
    {
        $model::authorizationReadScope($builder);
    }

    public function remove(Builder $builder, Model $model)
    {
        //
    }
}
