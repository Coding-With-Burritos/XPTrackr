<?php

use App\Models\User;

it('redirects guests from dashboard to home', function () {
    $response = $this->get('/dashboard');

    $response->assertRedirect('/');
});

it('allows authenticated users to access the dashboard', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertOk();
    $response->assertSee('Dashboard');
});

it('redirects authenticated users from home to dashboard', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/');

    $response->assertRedirect('/dashboard');
});
