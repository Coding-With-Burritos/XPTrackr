<?php

use App\Models\User;

test('user can be created with username and email', function () {
    $user = User::factory()->create([
        'username' => 'testuser',
        'email' => 'test@example.com',
    ]);

    expect($user->username)->toBe('testuser');
    expect($user->email)->toBe('test@example.com');
    expect($user)->toHaveKey('id');
    expect($user->created_at)->not->toBeNull();
});

test('user fillable attributes are correct for magic links', function () {
    $user = new User();
    
    expect($user->getFillable())->toEqual(['username', 'email']);
});

test('user factory creates valid user', function () {
    $user = User::factory()->create();
    
    expect($user->username)->not->toBeNull();
    expect($user->email)->not->toBeNull();
    expect($user->email)->toContain('@');
});
