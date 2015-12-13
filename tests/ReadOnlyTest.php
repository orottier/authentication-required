<?php

class ReadOnlyTest extends TestCase
{
    public function testRead()
    {
        $normal = Post::create(self::postData());
        $protected = ReadOnlyPost::find($normal->id);
        $this->assertNotNull($protected);
        $this->assertEquals($protected->id, $normal->id);
    }

    public function testCreate()
    {
        $this->setExpectedException('AuthorizationRequired\\CreatePermissionException');
        $p = ReadOnlyPost::create(self::postData());
    }

    public function testUpdateBySave()
    {
        $this->setExpectedException('AuthorizationRequired\\UpdatePermissionException');
        $normal = Post::create(self::postData());
        $protected = ReadOnlyPost::find($normal->id);
        $protected->title = 'Modified';
        $protected->save();
    }

    public function testUpdateByUpdate()
    {
        $this->setExpectedException('AuthorizationRequired\\UpdatePermissionException');
        $normal = Post::create(self::postData());
        $protected = ReadOnlyPost::find($normal->id);
        $protected->update(['title' => 'Modified']);
    }

    public function testDelete()
    {
        $this->setExpectedException('AuthorizationRequired\\DeletePermissionException');
        $normal = Post::create(self::postData());
        $protected = ReadOnlyPost::find($normal->id);
        $protected->delete();
    }
}
