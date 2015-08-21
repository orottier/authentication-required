<?php

namespace AuthorizationRequired;

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
				throw new EditPermissionException();
			}
			return true;
		});

		self::creating(function($self)
		{
			if(!$self->authorizationCanCreate()) {
				throw new CreatePermissionException();
			}
			return true;
		});

		self::deleting(function($self)
		{
			if(!$self->authorizationCanDelete()) {
				throw new DeletePermissionException();
			}
			return true;
		});
	}

	/**
	 * Add the Read scope to the model
	 *
	 * @return void
	 */
	public function authorizationReadScope(Builder $query)
	{
		$query->where(DB::raw(1), 2);
	}

	public function authorizationCanEdit()
	{
		return false;
	}

	public function authorizationCanCreate()
	{
		return false;
	}

	public function authorizationCanDelete()
	{
		return false;
	}

}
