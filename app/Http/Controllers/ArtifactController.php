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

        return view('artifacts.index')->with('artifacts', $hearts)
            ->with('champions', $this->getRepByName('Champions of Azeroth'))
            ->with('honorbound', $this->getRepByName('The Honorbound'))
            ->with('voldunai', $this->getRepByName('Voldunai'))
            ->with('zandalari', $this->getRepByName('Zandalari Empire'))
            ->with('talanjis', $this->getRepByName('Talanji\'s Expedition'))
            ->with('tortollan', $this->getRepByName('Tortollan Seekers'));
    }

    private function getRepByName($name) {
        return CharacterRep::orderBy('standing', 'desc')->orderBy('value', 'desc')->where('reputation_name', $name)->get();
    }

}
