<?php

namespace App\Providers;

use Closure;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class JsonApiServiceProvider extends ServiceProvider
{

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Builder::macro('allowedSortFields', function ()
        {
            /** @var Builder $this */

            $allowedSortFields = ['checkin_date', 'created_at', 'user_id'];
            if (request()->filled('sort')) {
                $sortFields = explode(',', request()->sort);
                foreach ($sortFields as $sortField) {
                    $sortOrder = 'asc';
                    $sortOrder = Str::of($sortField)->startsWith('-') ? 'desc' : 'asc';
                    $sortField = ltrim($sortField, '-');
                    abort_unless(in_array($sortField, $allowedSortFields), 400, 'Invalid sort field');
                    $this->orderBy($sortField, $sortOrder);
                }
            }
            return $this;
        });

        Builder::macro('allowedFilterFields', function () {
            /** @var Builder $this */
            $allowedFilters = ['user', 'year', 'month', 'day', 'date'];
            if (request()->filled('filter')) {
                $filters = request('filter', []);
                foreach ($filters as $filter => $value) {
                    abort_unless(in_array($filter, $allowedFilters), 400, 'Invalid filter');
                    $this->{$filter}($value);
                }
            }
            return $this;
        });

        Builder::macro('jsonPaginate', function () {
            /** @var Builder $this */

            return $this->paginate(
                $perPage = request('page.size', 15),
                $columns = ['*'],
                $pageName = 'page[number]',
                $page = request('page.number', 1)
            )->appends(request()->only('sort', 'filter', 'page.size'));
        });
    }
}
