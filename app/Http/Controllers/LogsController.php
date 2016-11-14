<?php
namespace App\Http\Controllers;

use App\OE\WarcraftLogs\Ranking;

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
}