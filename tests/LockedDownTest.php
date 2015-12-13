<?php

class LockedDownTest extends TestCase
{
    public function testRead()
    {
        $normal = Post::create(self::postData());
        $protected = LockedDownPost::find($normal->id);
        $this->assertNull($protected);
    }

    public function testCreate()
    {
        $this->setExpectedException('AuthorizationRequired\\CreatePermissionException');
        $p = LockedDownPost::create(self::postData());
    }
}
