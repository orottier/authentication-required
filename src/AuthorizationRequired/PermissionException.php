<?php

namespace AuthorizationRequired;

use Illuminate\Auth\Access\AuthorizationException;

class PermissionException extends AuthorizationException
{
}
