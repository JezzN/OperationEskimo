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
use App\OE\WoW\OeGuildApiResponse;

class RosterController extends Controller
{
    public function index(OperationEskimo $operationEskimo, ItemLevelFetcher $itemLevel)
    {
        return view('roster.index')
            ->with('tanks', $operationEskimo->tanks())
            ->with('healers', $operationEskimo->healers())
            ->with('unknown', $operationEskimo->unknown())
            ->with('rangedDps', $operationEskimo->ranged())
            ->with('meleeDps', $operationEskimo->melee())
            ->with('guildRoleCounts', $operationEskimo->roleCounts())
            ->with('items', $itemLevel);
    }


}