<?php

namespace App\Http\Controllers;

use App\OE\OperationEskimo;
use App\OE\WoW\Artifact;
use App\OE\WoW\CharacterRep;
use App\OE\WoW\HeartOfAzeroth;
use Illuminate\Http\Request;

class ArtifactController extends Controller
{
    /** @var OperationEskimo */
    private $operationEskimo;

    public function __construct(OperationEskimo $operationEskimo)
    {
        $this->operationEskimo = $operationEskimo;
    }

    public function index()
    {
        $hearts =  HeartOfAzeroth::orderBy('level', 'desc')->orderBy('experience', 'desc')->get();

        $championsRep = CharacterRep::orderBy('standing', 'desc')->orderBy('value', 'desc')->where('reputation_name', 'Champions of Azeroth')->get();

        $honorboundRep = CharacterRep::orderBy('standing', 'desc')->orderBy('value', 'desc')->where('reputation_name', 'The Honorbound')->get();

        return view('artifacts.index')->with('artifacts', $hearts)->with('champions', $championsRep)->with('honorbound', $honorboundRep);
    }

}
