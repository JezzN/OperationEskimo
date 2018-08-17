<?php

namespace App\Http\Controllers;

use App\OE\OperationEskimo;
use App\OE\WoW\Artifact;
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
        return view('artifacts.index')->with('artifacts', HeartOfAzeroth::orderBy('level', 'desc')->orderBy('experience', 'desc')->get());
    }

}
