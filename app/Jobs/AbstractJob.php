<?php

namespace App\Jobs;

use App\Events\DispatchesEvents;

abstract class AbstractJob 
{
	use DispatchesEvents;
}