<?php

namespace App\Http\Controllers;
use App\Models\Visit;
use Illuminate\Http\Request;
use App\Models\Landingpage;
use App\Http\Requests\UpdateLandingpageRequest;


class VisitorController extends Controller
{
    public function landingPage(Request $request)
    {
        $visit = new Visit();
        $visit->ip_address = $request->ip();
        $visit->user_agent = $request->header('User-Agent');
        $visit->visited_at = now();
        $lastVisit = Visit::where('ip_address', $request->ip())->latest('visited_at')->first();
        if ($lastVisit) {
            $visit->duration = $lastVisit->duration;
        }

        $visit->save();
        $totalVisits = Visit::count();
        $averageDurationInSeconds = Visit::avg('duration');
        $averageDurationInMinutes = floor($averageDurationInSeconds / 60);
        $averageDuration = sprintf('%02d:%02d', $averageDurationInMinutes / 60, $averageDurationInMinutes % 60);


        $data = Landingpage::all();
        return view('index', compact('totalVisits', 'averageDuration', 'data'));
    }

    public function update(UpdateLandingpageRequest $request, $id)
    {
        $validator = $request->validated();
        $data = Landingpage::find($id);
        $data->update($validator);
        return redirect()->back()->with('success','');
    }

    public function tentang()
    {
        $data = Landingpage::all();
        return view('tentang', compact('data'));
    }
}
