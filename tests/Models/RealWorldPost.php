<?php

use Illuminate\Database\Eloquent\Builder;

class RealWorldPost extends Post
{
    use AuthorizationRequired\AuthorizationRequired;

    protected $table = 'posts';

    public static function authorizationReadScope(Builder $query)
    {
        $userId = Auth::check() ? Auth::user()->id : null;
        $admin = Auth::check() ? Auth::user()->isSuperAdmin() : false;
        if ($admin) {
            return $query;
        }

        return $query->where('published_at', '<=', date('Y-m-d H:i:s'))
            ->where('hidden', false)
            ->orWhere('user_id', $userId);
    }

    public function authorizationCanUpdate()
    {
        return Auth::check()
            && ($this->user_id == Auth::user()->id || Auth::user()->isSuperAdmin());
    }

    public function authorizationCanCreate()
    {
        return Auth::check();
    }
    public function authorizationCanDelete()
    {
        return $this->authorizationCanUpdate();
    }
}
