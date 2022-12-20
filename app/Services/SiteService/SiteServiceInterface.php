<?php

namespace App\Services\SiteService;

use App\Site;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

interface SiteServiceInterface
{
    /**
     * @param string $siteId
     * @return Site|null
     */
    public function showUserSite(string $siteId): ?Site;

    /**
     * @return Collection
     */
    public function getLoggedInUserSites(): Collection;

    /**
     * @return string
     */
    public function generateSiteCsvData(): string;
}
