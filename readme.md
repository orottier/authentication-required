# AuthorizationRequired

A simple and efficient authorization package for the Laravel framework

## What this package can and cannot do
This packages uses the available Eloquent hooks to impose rules for reading and writing your Models. No more, no less.

Will protect:

* Read access & creation of new models
* Updates and deletes of models, invoked on the model itself


Will not protect:

* Raw queries: `DB::table('users')->delete()`
* Mass updates and deletes: `User::where('role', 'admin')->delete()`

Please note the fundamental difference between
```
User::find(12)->delete(); // Invokes delete on the User Model
User::where('id', 12)->delete() // Invokes delete on the Eloquent Builder
DB::table('users')->where('id', 12)->delete() // Invokes delete on the Query Builder
DB::delete("DELETE FROM `users` WHERE `id` = 12") // Executes a raw query
```

This package will only protect guard deletes/updates of the first type. The latter three will pass no matter what rules you impose.

## Installation via Composer

Note: this package can only be used in combination with the **Laravel** framework.

Add AuthorizationRequired to your composer.json file to use it in your project.

```
"require" : {
    "orottier/authorization-required" : "dev-master"
}
```

And install via composer
```
composer install
```

## How it works
The Laravel models you want to protect should include the `AuthorizationRequired` trait. This puts the following methods on your model:
```PHP
public static function authorizationReadScope(\Illuminate\Database\Eloquent\Builder $query);
public function authorizationCanEdit();
public function authorizationCanCreate();
public function authorizationCanDelete();
```

These functions define the rules of *reading*, *updating*, *creating* and *deleting* the model.

### Read behaviour
Calling `Model::find` will simply yield null if the the rules prevent the object to be seen (as if it did not exist). Your application has probably been configured to return a 404 status code in these cases.

### Write behaviour (update, create, delete)
If the rules forbid writing the model, an `AuthorizationRequired\PermissionException` is thrown. Specifically: `EditPermissionException`, `CreatePermissionException` and `DeletePermissionException`. Your application can convert this into a nice 403 page using the `render` function in `App\Exception`.

## Example usage

To illustrate the usage of this package, we will put authorization rules on a simple application that allows users to post and modify blog items (referred to as `Post`).

To put authorization rules on an Eloquent model, include the `AuthorizationRequired` trait:

```PHP
<?php

// ...

use AuthorizationRequired\AuthorizationRequired;

class Post extends Model
{
	use AuthorizationRequired;

	// ...

}
```

By default, **all read and write access is denied** for posts now. Your application will look very empty. We should allow users to view posts that are visible and published. Of course a user should be able to see, edit and delete all of his own posts, even the hidden ones.

### Allow reading
Read access rules are written as a [query scope](http://laravel.com/docs/master/eloquent#query-scopes). By defining the function `authorizationReadScope` we will override the default 'deny all' behaviour:

```PHP
<?php

// ...

use AuthorizationRequired\AuthorizationRequired;
use Illuminate\Database\Eloquent\Builder;

class Post extends Model
{
	use AuthorizationRequired;

	public static function authorizationReadScope(Builder $query)
	{
		$userId = Auth::check() ? Auth::user()->id : null;
		return $query->where('published_at', '<=', date('Y-m-d H:i:s'))
			->where('hidden', false)
			->orWhere('user_id', $userId);
	}

	// ...

}
```

If you wish to impose no restrictions on read access, simply pass a trivial scope:

```PHP
public static function authorizationReadScope(Builder $query)
{
	return $query->whereRaw("1=1");
}
```

### Allow editing
Users and the superadmin should be able to modify a post. Edit/Create/Delete rules are defined as ordinary functions on the model:

```PHP
public function authorizationCanEdit()
{
	return Auth::check()
		&& ($this->user_id === Auth::user()->id || Auth::user()->isSuperAdmin());
}
```

All users can create a post:
```PHP
public function authorizationCanCreate()
{
	return Auth::check();
}
```
Note: there's no hierarchy defined in the authorization rules. It is possible to prevent users from editing the posts they just created, if you wish.

Deleting a post is governed by the same rules as editing the post:
```PHP
public function authorizationCanDelete()
{
	return $this->authorizationCanEdit();
}
```

That's it!
