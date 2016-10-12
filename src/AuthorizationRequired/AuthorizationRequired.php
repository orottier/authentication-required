<?php

namespace AuthorizationRequired;

use Illuminate\Database\Eloquent\Builder;
use DB;
use Gate;

trait AuthorizationRequired
{
    /**
     * Boot the authorization-required trait for a model.
     *
     * @return void
     */
    public static function bootAuthorizationRequired()
    {
        static::addGlobalScope(new AuthorizationRequiredScope());

        self::creating(function ($self) {
            if (!Gate::allows('create', get_class($self))) {
                throw new CreatePermissionException('Not allowed to create a ' . class_basename($self));
            }
        });

        self::updating(function ($self) {
            if (!Gate::allows('update', $self)) {
                throw new UpdatePermissionException('Not allowed to update this ' . class_basename($self));
            }
        });

        self::deleting(function ($self) {
            if (!Gate::allows('delete', get_class($self))) {
                throw new DeletePermissionException('Not allowed to delete this ' . class_basename($self));
            }
        });
    }

    /**
     * Add the Read scope to the model
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function authorizationReadScope(Builder $query)
    {
        return $query->where(DB::raw(1), 2);
    }
}
