<?php

use Illuminate\Support\Collection;

if (!function_exists('paginateFromCollection')) {
    /**
     * paginate
     *
     * @param  mixed $results
     * @param  mixed $showPerPage
     * @return void
     */
    function paginateFromCollection(Collection $results, int $showPerPage)
    {
        return app()->make(\App\Helpers\PaginationHelper::class)->paginateFromCollection($results, $showPerPage);
    }
}
