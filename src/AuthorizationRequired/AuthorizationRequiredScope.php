<?php

namespace AuthorizationRequired;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class AuthorizationRequiredScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $model::authorizationReadScope($builder);
    }
}
