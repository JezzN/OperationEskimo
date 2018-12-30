<?php

namespace App\Providers;

use App\OE\Discord\Bot\Commander;
use App\OE\Forum\Link;
use App\OE\Forum\Setting;
use Discord\Discord;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use JBBCode\DefaultCodeDefinitionSet;
use JBBCode\Parser;
use Pwnraid\Bnet\Region;
use Pwnraid\Bnet\Warcraft\Characters\CharacterRequest;
use Pwnraid\Bnet\Warcraft\Client;
use Pwnraid\Bnet\Warcraft\Client as WarcraftClient;
use Pwnraid\Bnet\Warcraft\Guilds\GuildEntity;
use Pwnraid\Bnet\Warcraft\Guilds\GuildRequest;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::share('links', Link::orderBy('position')->get());
        View::share('logo', Setting::where('key', 'logo_path')->first()->value);
        View::share('favicon', Setting::where('key', 'favicon_path')->first()->value);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Client::class, function() {
            return new WarcraftClient(config('services.battle-net.api-key'), new Region(config('services.battle-net.region')));
        });

        $this->app->singleton(GuildEntity::class, function() {
            return app(GuildRequest::class)->find(config('services.battle-net.guild-name'), ['members']);
        });

        $this->app->singleton(GuildRequest::class, function() {
            return app(WarcraftClient::class)->guilds()->on(config('services.battle-net.realm'));
        });

        $this->app->singleton(CharacterRequest::class, function() {
            return app(WarcraftClient::class)->characters()->on(config('services.battle-net.realm'));
        });

        $this->app->singleton(Discord::class, function() {
            return new Discord(['token' => config('operation-eskimo.discord-bot-token')]);
        });

        $this->app->singleton(Commander::class, Commander::class);
    }

}
