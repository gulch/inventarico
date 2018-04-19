<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\User;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    public function testUserCanBeCreated()
    {
        $password_hash = \app('hash')->make('test-password');
        $name = 'Test User';
        $email = 'test@inventarico.com';

        $user = \factory(User::class)->create([
            'name' => $name,
            'email' => $email,
            'password' => $password_hash
        ]);

        $latest_user = User::latest()->first();

        $this->assertEquals($user->id, $latest_user->id);
        $this->assertEquals($email, $latest_user->email);
        $this->assertEquals($name, $latest_user->name);
        $this->assertEquals($password_hash, $latest_user->password);
    }
}
