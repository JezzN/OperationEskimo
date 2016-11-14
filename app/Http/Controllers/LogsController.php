<?php
namespace App\Http\Controllers;

use App\OE\OperationEskimo;
use App\OE\WarcraftLogs\Ranking;
use Carbon\Carbon;

class LogsController extends Controller
{
    public function index()
    {
        $rankings = Ranking::whereIn('difficulty', ['heroic', 'mythic'])->orderBy('fight_start_time', 'desc')->paginate(20);

        return view('logs.index')->with('rankings', $rankings);
    }

    public function character($character)
    {
        $rankings = Ranking::where('character_name', $character)->whereIn('difficulty', ['heroic', 'mythic'])->orderBy('fight_start_time', 'desc')->paginate(20);

        return view('logs.character')->with('rankings', $rankings)->with('character', $character);
    }

    public function averages(OperationEskimo $oe)
    {
        $healers = $oe->healers()->pluck('character_name')->toArray();
        $dps = $oe->dps()->pluck('character_name')->toArray();

        $averages = Ranking::whereIn('difficulty', ['mythic'])
            ->where('fight_start_time', '>=', Carbon::now()->subWeek(1))
            ->selectRaw('character_name, AVG(percentile) as average, metric, count(*) log_count')
            ->groupBy('character_name')
            ->groupBy('metric')
            ->orderByRaw('AVG(percentile) DESC')
            ->get();

        return view('logs.averages')->with('averages', $averages)->with('dps', $dps)->with('healers', $healers);
    }
}