<?php

namespace App\Services\Links\Repositories;

use Illuminate\Support\Facades\DB;

class StatsRepository
{

    public function getAll(): array
    {
       return DB::select('select count(l.id) as total_views,
            count(distinct v.user_agent, v.user_ip) as unique_views
            from views as v
            inner join links as l on l.id = v.link_id
            group by v.link_id
            order by unique_views desc', []);
    }

    public function getByLinkId($id): array
    {
       return DB::select('select count(l.id) as total_views,
            count(distinct v.user_agent, v.user_ip) as unique_views,
            cast(v.created_at as date) as date
            from views as v
            inner join links as l on l.id = v.link_id and l.id = ?
            group by cast(v.created_at as date)
            order by cast(v.created_at as date) desc', [$id]);
    }

}
