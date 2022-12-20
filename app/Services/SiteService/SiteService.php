<?php

namespace App\Services\SiteService;

use App\Repositories\Site\SiteRepository;
use App\Site;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use League\Csv\CannotInsertRecord;
use League\Csv\Writer;

class SiteService implements SiteServiceInterface
{
    protected SiteRepository $siteRepository;

    /**
     * @param SiteRepository $siteRepository
     */
    public function __construct(SiteRepository $siteRepository)
    {
        $this->siteRepository = $siteRepository;
    }

    /**
     * @param string $siteId
     * @return Site|null
     * @throws \Exception
     */
    public function showUserSite(string $siteId): ?Site
    {
        $site = $this->siteRepository->findOrFail($siteId);
        $user = auth()->user();

        if (!$user->is_admin && ($site->user_id !== $user->id)) {
            throw new \Exception('User dont have permission to view this site.');
        }

        return $site;
    }

    /**
     * @return Collection
     */
    public function getLoggedInUserSites(): Collection
    {
        if (auth()->user()->is_admin) {
            return $this->siteRepository->getAllSites();
        }

        return $this->siteRepository->getUserSites(auth()->user()->id);
    }

    /**
     * @param string $siteId
     * @return string
     */
    public function generateSiteCsvData(): string
    {
        $userId = auth()->user()->id;
        if (!auth()->user()->is_admin) {
            $sites = $this->siteRepository->getSiteInfoByUserId(auth()->user()->id);
        } else {
            $sites = $this->siteRepository->getAllSiteInfo();
        }

        $time = time();
        $fileName = "site_reports/$userId/site_info_$time.csv";
        Storage::put($fileName, '');
        $fileContent = Storage::get($fileName);

        $header = ['Site name','Site type', 'User name', 'Email'];
        $records = [];
        foreach ($sites as $row) {
            $records[] = [
                $row->site_name,
                $row->type,
                $row->user_name,
                $row->email
            ];
        }

        try {
            $writer = Writer::createFromString($fileContent);
            $writer->insertOne($header);
            $writer->insertAll($records);
        } catch (CannotInsertRecord $e) {
            throw new \Exception("Exception writer");
        }
        $csvContent = $writer->__toString();
        Storage::put($fileName, $csvContent);

        return $fileName;
    }
}
