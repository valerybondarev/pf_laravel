<?php

namespace App\Http\Web\Controllers\TourClubLanding;

use App\Domain\TourClub\Entities\TourClub;
use App\Http\Web\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class TourClubLanding extends Controller
{
    public function show(TourClub $tourClub): Factory|View|Application
    {
        return view('web.tourClubLanding.show', ['tourClub' => $tourClub]);
    }
}