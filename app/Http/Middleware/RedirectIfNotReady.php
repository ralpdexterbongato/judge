<?php

namespace App\Http\Middleware;

use Closure;
use App\Setup;
use App\JudgesSetup;
use App\CriteriaEvent;
use App\Rating;
use App\ContestantSetup;
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
        $SetupData= Setup::where('id', $request->route()->setupId)->get(['event_id']);
        $totalContestant = ContestantSetup::where('setup_id',$request->route()->setupId)->count();
        $eventTotalCriteria=CriteriaEvent::where('event_id',$SetupData[0]->event_id)->count();
        $totalJudges=JudgesSetup::where('setup_id', $request->route()->setupId)->count();
        $totalRating=Rating::where('setup_id', $request->route()->setupId)->count();

        $answer=$totalContestant * $eventTotalCriteria;
        $expectedTotalRating= $answer * $totalJudges;
        if ($totalRating==$expectedTotalRating)
        {
          return $next($request);
        }else
        {
          return redirect('/setup-index')->with('errorNotDone', 'Scores are not yet complete');
        }
    }
}
