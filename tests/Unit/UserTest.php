<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;

class UserTest extends TestCase
{
    public function testUserCanBeCreated()
    {
        $password_hash = bcrypt('test-password');
        $name = 'Test User';
        $email = 'test@inventarico.com';

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password_hash
        ]);

        $latest_user = User::latest()->first();

        $this->assertEquals($user->id, $latest_user->id);
        $this->assertEquals($email, $latest_user->email);
        $this->assertEquals($name, $latest_user->name);
        $this->assertEquals($password_hash, $latest_user->password);

        $this->seeInDatabase('User', [
            'name' => $name,
            'email' => $email,
            'password' => $password_hash
        ]);
    }
}
