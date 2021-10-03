<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Contracts\Auth\Factory;
use Tests\DuskTestCase;
use Laravel\Dusk\Chrome;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoginTest extends DuskTestCase
{



    /**
     * A Dusk test example.
     */
    public function testExample()
    {
        $this->browse(function ($browser) {

            $browser->ensurejQueryIsAvailable();
            $browser->visit('https://aiarticlespinner.co')
                ->pause(2000);
            // write again
            $browser->script('document.getElementById("1").checked = true;');
            $tr_value = "<option value='tr' selected='selected'>Turkish</option>";
            $browser->script('document.getElementById("select-state").innerHTML = "' . $tr_value . '";');
            $browser->value('#inp', "Firma'nın yeni üyelere özel olarak düzenlediği ilk üyelik bonusu kampanyası oldukça cazip. Bonustan üye olduktan sonra ilk para yatırma işleminde yararlanılıyor. Bu nedenle yatırım yaparken bonus alacaksanız eğer maksimum tutarda almanızı tavsiye ederiz.")
                ->pause(1000);
            $browser->click('#refase')
                ->pause(15000);
            // remove par
            $new_content = $browser->value('#out');
            $browser->script('document.getElementById("3").checked = true;');
            $browser->value('#inp', $new_content)
                ->pause(1000);
            $browser->click('#refase')
                ->pause(15000);

            dd($browser->value('#out'));
        });
    }
}
