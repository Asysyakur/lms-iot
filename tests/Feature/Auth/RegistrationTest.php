<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Fortify\Features;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->skipUnlessFortifyHas(Features::registration());
});

test('registration screen can be rendered', function () {
    $response = $this->get(route('register'));

    $response->assertOk();
});

test('new students can register', function () {
    $response = $this->post(route('register.store'), [
        'name' => 'Test Student',
        'username' => 'test.student',
        'class' => 'X TKJ-T-1',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('home'));

    $user = User::where('username', 'test.student')->firstOrFail();

    expect($user->role)->toBe('student');
    expect($user->class)->toBe('X TKJ-T-1');
});

test('registration requires a unique username', function () {
    User::factory()->create(['username' => 'taken.username']);

    $response = $this->post(route('register.store'), [
        'name' => 'Test Student',
        'username' => 'taken.username',
        'class' => 'X TKJ-T-1',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasErrors('username');
    $this->assertGuest();
});

test('registration requires a valid class', function () {
    $response = $this->post(route('register.store'), [
        'name' => 'Test Student',
        'username' => 'test.student',
        'class' => 'Not A Real Class',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasErrors('class');
    $this->assertGuest();
});
