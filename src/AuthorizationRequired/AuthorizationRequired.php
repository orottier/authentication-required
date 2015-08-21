<?php

namespace AuthorizationRequired;

use Illuminate\Database\Eloquent\Builder;
use DB;

trait AuthorizationRequired
{
	/**
	 * Boot the authorization-required trait for a model.
	 *
	 * @return void
	 */
	public static function bootAuthorizationRequired()
	{
		static::addGlobalScope(new AuthorizationRequiredScope);

		self::updating(function($self)
		{
			if(!$self->authorizationCanEdit()) {
				throw new EditPermissionException("Not allowed to edit this " . class_basename($self));
			}
			return true;
		});

		self::creating(function($self)
		{
			if(!$self->authorizationCanCreate()) {
				throw new CreatePermissionException("Not allowed to create a " . class_basename($self));
			}
			return true;
		});

		self::deleting(function($self)
		{
			if(!$self->authorizationCanDelete()) {
				throw new DeletePermissionException("Not allowed to delete this " . class_basename($self));
			}
			return true;
		});
	}

	/**
	 * Add the Read scope to the model
	 *
	 * @return void
	 */
	public static function authorizationReadScope(Builder $query)
	{
		$query->where(DB::raw(1), 2);
	}

	/**
	 * Rules for editing the model
	 *
	 * @return bool
	 */
	public function authorizationCanEdit()
	{
		return false;
	}

	/**
	 * Rules for creating the model
	 *
	 * @return bool
	 */
	public function authorizationCanCreate()
	{
		return false;
	}

	/**
	 * Rules for deleting the model
	 *
	 * @return bool
	 */
	public function authorizationCanDelete()
	{
		return false;
	}

}
