<?php

namespace App\Http\Controllers;

use App\OE\OperationEskimo;
use App\OE\WoW\Artifact;
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
        return view('artifacts.index')->with('artifacts', $this->compiledArtifacts());
    }

    private function compiledArtifacts()
    {
        $artifacts = Artifact::whereHas('member', function($q) {
            return $q->whereIn('rank', $this->operationEskimo->raiderRanks());
        })->orderBy('rank', 'desc')->get();

        $counts = [];

        foreach( $artifacts as $artifact ) {

            $artifact->offspec = false;

            if( ! isset($counts[$artifact->member_id]) ) $counts[$artifact->member_id] = 0;

            $counts[$artifact->member_id]++;

            if( $counts[$artifact->member_id] > 1 ) $artifact->offspec = true;
        }

        return $artifacts;
    }
}
