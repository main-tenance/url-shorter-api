<?php

namespace App\Http\Routes;

use App\Http\Controllers\LinkController;
use App\Http\Controllers\StatController;
use App\Models\Link;
use App\Models\Tag;
use Illuminate\Support\Facades\Route;

class ApiRoutesProvider
{

    public function registerRoutes(): void
    {
        Route::resource('links', LinkController::class)
            ->except(['create', 'edit'])
            ->parameters(['links' => 'link'])
            ->names([
                'index' => ApiRoutes::LINK_INDEX,
                'store' => ApiRoutes::LINK_STORE,
                'show' => ApiRoutes::LINK_SHOW,
                'update' => ApiRoutes::LINK_UPDATE,
                'destroy' => ApiRoutes::LINK_DESTROY,
            ]);
        Route::get('/links/title/{link:title}', [LinkController::class, 'title'])
            ->name(ApiRoutes::LINK_TITLE);
        Route::get('/links/tag/{tag}', [LinkController::class, 'tag'])
            ->name(ApiRoutes::LINK_TAG);
        Route::get('/stats', [StatController::class, 'index'])
            ->name(ApiRoutes::STAT_INDEX);
        Route::get('/stats/{id}', [StatController::class, 'show'])
            ->name(ApiRoutes::STAT_SHOW);
    }

    public static function linkIndex(): string
    {
        return route(ApiRoutes::LINK_INDEX);
    }

    public static function linkShow(Link $link): string
    {
        return route(ApiRoutes::LINK_SHOW, ['link' => $link]);
    }

    public static function linkStore(): string
    {
        return route(ApiRoutes::LINK_STORE);
    }

}
