<?php

namespace App\Repositories\Site;

use App\Site;
use Illuminate\Database\Eloquent\Collection;

interface SiteRepositoryInterface
{
    /**
     * @param $attributes
     * @return mixed
     */
    public function create($attributes);

    /**
     * @param string $id
     * @return Site|null
     */
    public function find(string $id): ?Site;

    /**
     * @param string $siteId
     * @return Site|null
     */
    public function findOrFail(string $siteId): ?Site;

    /**
     * @param string $userId
     * @return Collection
     */
    public function getUserSites(string $userId): Collection;

    /**
     * @return Collection
     */
    public function getAllSites(): Collection;

    /**
     * @param string $userId
     * @return array
     */
    public function getSiteInfoByUserId(string $userId): array;

    /**
     * @return array
     */
    public function getAllSiteInfo(): array;
}
