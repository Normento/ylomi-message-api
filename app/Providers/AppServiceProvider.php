<?php

namespace App\Providers;

use Dedoc\Scramble\Scramble;
use MessageRepositoryInterface;
use ConversationRepositoryInterface;
use App\Repositories\MessageRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\ConversationRepository;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ConversationRepositoryInterface::class, ConversationRepository::class);
        $this->app->bind(MessageRepositoryInterface::class, MessageRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Scramble::extendOpenApi(function (OpenApi $openApi) {
            $openApi->secure(
                SecurityScheme::http('bearer', 'JWT')
            );
        });
    }
}
