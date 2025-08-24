<?php

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\HomePage;

test('can visit homepage and see welcome message', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/')
                ->assertSee('Welcome to XPTrackr')
                ->assertSee('Track your habits and level up!')
                ->assertTitle('Welcome - XPTrackr');
    });
});

test('can use page objects', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit(new HomePage);
    });
});
