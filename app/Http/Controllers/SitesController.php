<?php

namespace App\Http\Controllers;

use App\Services\SiteService\SiteServiceInterface;
use App\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SitesController extends Controller
{
    protected SiteServiceInterface $siteService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(SiteServiceInterface $siteService)
    {
        $this->middleware('auth');
        $this->siteService = $siteService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('sites.index', [
            'sites' => $this->siteService->getLoggedInUserSites(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $randomVesselNames = [
            'The Blankney',
            'Beaver',
            'Quainton',
            'Churchill',
            'Thatcham',
            'Cowper',
            'Adelaide',
            'The Kildimo',
            'Infanta',
        ];

        return view('sites.create', [
            'namePlaceholder' => '"' . $randomVesselNames[array_rand($randomVesselNames)] . '"',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $site = new Site();
        $site->name = $request->input('name');
        $site->user_id = auth()->user()->id;
        $site->type = $request->input('type');

        $site->save();

        return redirect()->route('sites.index');
    }

    /**
     * @param Request $request
     * @return void
     */
    public function show(Request $request)
    {
        $site = $this->siteService->showUserSite($request->site);

        return view('sites.show', [
            'site' => $site,
        ]);
    }

    /**
     * @return void
     */
    public function export()
    {
        $filePath = $this->siteService->generateSiteCsvData();
        $file = Storage::exists($filePath);

        if (!$file) {
            throw new \Exception('Error when export csv.');
        }

        return Storage::download($filePath);
    }
}
