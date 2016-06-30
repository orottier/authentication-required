<?php

class RealWorldTest extends TestCase
{
    protected static function makeUser($fill= [])
    {
        $user = User::create(array_merge([
            'email' => uniqid() . '@example.com',
            'name' => 'Otto',
            'password' => uniqid(),
        ], $fill));
        return $user;
    }

    public function testCreate()
    {
        $user = self::makeUser();
        Auth::login($user);
        RealWorldPost::create(self::postData([
            'user_id' => $user->id,
        ]));
    }

    public function testReadOwnHidden()
    {
        $user = self::makeUser();
        Auth::login($user);
        $normal = Post::create(self::postData([
            'user_id' => $user->id,
            'hidden' => true,
        ]));
        $protected = RealWorldPost::find($normal->id);
        $this->assertNotNull($protected);
        $this->assertEquals($protected->id, $normal->id);
    }

    public function testReadOtherHidden()
    {
        $user = self::makeUser();
        $other = self::makeUser();
        Auth::login($user);
        $normal = Post::create(self::postData([
            'user_id' => $other->id,
            'hidden' => true,
        ]));
        $protected = RealWorldPost::find($normal->id);
        $this->assertNull($protected);
    }

    public function testReadOtherHiddenByAdmin()
    {
        $user = self::makeUser(['admin' => true]);
        $this->assertTrue($user->isSuperAdmin());

        $other = self::makeUser();
        Auth::login($user);

        $normal = Post::create(self::postData([
            'user_id' => $other->id,
            'hidden' => true,
        ]));
        $protected = RealWorldPost::find($normal->id);
        $this->assertNotNull($protected);
        $this->assertEquals($protected->id, $normal->id);
    }

    public function testUpdateOwn()
    {
        $user = self::makeUser();
        Auth::login($user);
        $post = RealWorldPost::create(self::postData([
            'user_id' => $user->id,
            'hidden' => true,
        ]));
        $post->title = 'Modified';
        $post->save();
    }
}
