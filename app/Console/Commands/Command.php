<?php namespace App\Console\Commands;

use App\Events\DispatchesEvents;

abstract class Command extends \Illuminate\Console\Command
{

    use DispatchesEvents;

}
