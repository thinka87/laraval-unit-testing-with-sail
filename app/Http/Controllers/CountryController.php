<?php

namespace App\Http\Controllers;

use App\Models\Country;

class CountryController extends Controller
{
    public function index()
    {
        // TODO 17: test_countries_with_team_size
        // load the relationship average of team size
        $countries = Country::with('avgTeamize')->get();

        foreach($countries as &$country){  
            $country->teams_avg_size=round($country->avgTeamize->first()->aggregate,2);
            
        }
        return view('countries.index', compact('countries'));
    }
}
