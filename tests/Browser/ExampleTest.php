<?php

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\HomePage;

test('can visit homepage and see Laravel welcome', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/')
                ->assertSee("Let's get started")
                ->assertTitle('XPTrackr');
    });
});

test('can use page objects', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit(new HomePage);
    });
});
