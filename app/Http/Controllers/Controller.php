<?php namespace App\Http\Controllers;

use App\Presenters\PresenterTrait;
use App\Transformers\TransformerTrait;
use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController
{
    use DispatchesCommands,
        ValidatesRequests,
        TransformerTrait,
        PresenterTrait;
}
