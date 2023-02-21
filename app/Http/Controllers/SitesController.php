<?php

namespace App\Http\Controllers;

use App\Exports\SitesExport;
use App\Site;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SitesController extends Controller
{
    /**
     * @var Collection
     */
    private $sites;
    /**
     * @var Authenticatable|null
     */
    private $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            if ($this->user->hasRole('admin')) {
                $this->sites = Site::all();
            } else {
                $this->sites = $this->user->sites()->get();
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('sites.index', [
            'sites' => $this->sites,
        ]);
    }

    /**
     * Display a detail of the resource.
     * @param Site $site
     * @return View
     * @throws AuthorizationException
     */
    public function show(Site $site): View
    {
        $this->authorize('view', $site);
        return view('sites.show', compact('site'));
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
            'namePlaceholder' => '"'.$randomVesselNames[array_rand($randomVesselNames)].'"',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
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
     * @return BinaryFileResponse
     */
    public function export(): BinaryFileResponse
    {
        return Excel::download(new SitesExport($this->sites), 'sites.csv', \Maatwebsite\Excel\Excel::CSV);
    }
}
