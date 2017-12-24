<?php

namespace App\Http\Middleware;

use Closure;
use App\Setup;
use App\JudgesSetup;
use App\CriteriaEvent;
use App\Rating;
class RedirectIfNotReady
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $SetupData= Setup::where('id', $request->route()->setupId)->get(['NumberContestant','event_id']);
        $eventTotalCriteria=CriteriaEvent::where('event_id',$SetupData[0]->event_id)->count();
        $totalJudges=JudgesSetup::where('setup_id', $request->route()->setupId)->count();
        $totalRating=Rating::where('setup_id', $request->route()->setupId)->count();

        $answer=$SetupData[0]->NumberContestant * $eventTotalCriteria;
        $expectedTotalRating= $answer * $totalJudges;
        if ($totalRating==$expectedTotalRating)
        {
          return $next($request);
        }else
        {
          return redirect('/setup-index')->with('error', 'Judges are not done yet');
        }
    }
}
