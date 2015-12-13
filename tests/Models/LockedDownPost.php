<?php

class LockedDownPost extends Post
{
    use AuthorizationRequired\AuthorizationRequired;

    protected $table = 'posts';
}
