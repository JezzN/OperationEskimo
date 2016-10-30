<?php
/**
 * Created by PhpStorm.
 * User: Jeremy
 * Date: 23/10/2016
 * Time: 13:22
 */

namespace App\Http\Controllers;


use App\OE\Guild;
use App\OE\Loot\ItemLevelFetcher;
use App\OE\OperationEskimo;

class RosterController extends Controller
{
    public function index(OperationEskimo $operationEskimo, ItemLevelFetcher $itemLevel)
    {
        return view('roster.index')
            ->with('tanks', $operationEskimo->filterByRole('TANK', $operationEskimo->raiders()))
            ->with('healers', $operationEskimo->filterByRole('HEALING', $operationEskimo->raiders()))
            ->with('unknown', $operationEskimo->filterByRole('UNKNOWN', $operationEskimo->raiders()))
            ->with('rangedDps', $operationEskimo->ranged())
            ->with('meleeDps', $operationEskimo->melee())
            ->with('guildRoleCounts', $operationEskimo->roleCounts())
            ->with('items', $itemLevel);
    }


}