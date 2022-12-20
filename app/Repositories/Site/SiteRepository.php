<?php

namespace App\Repositories\Site;

use App\Site;
use Illuminate\Database\Eloquent\Collection;

class SiteRepository implements SiteRepositoryInterface
{
    protected Site $site;

    /**
     * @param Site $site
     */
    public function __construct(Site $site)
    {
        $this->site = $site;
    }

    /**
     * @param $attributes
     *
     * @return mixed
     */
    public function create($attributes)
    {
        return $this->site->create($attributes);
    }

    /**
     * @param string $id
     * @return Site|null
     */
    public function find(string $id): ?Site
    {
        return $this->site->find($id);
    }

    /**
     * @param string $siteId
     * @return Site|null
     */
    public function findOrFail(string $siteId): ?Site
    {
        return $this->site->findOrFail($siteId);
    }

    /**
     * @param string $userId
     * @return Collection
     */
    public function getUserSites(string $userId): Collection
    {
        return $this->site->where('user_id', $userId)->get();
    }

    /**
     * @return Collection
     */
    public function getAllSites(): Collection
    {
        return $this->site->all();
    }

    /**
     * @param string $userId
     * @return array
     */
    public function getSiteInfoByUserId(string $userId): array
    {
        return \DB::select('select s.name as site_name, u.name as user_name, email, type
                            from sites s join users u on s.user_id = u.id
                            where u.id = ?', [$userId]);
    }

    /**
     * @return array
     */
    public function getAllSiteInfo(): array
    {
        return \DB::select('select s.name as site_name, u.name as user_name, email, type
                            from sites s join users u on s.user_id = u.id');
    }
}
