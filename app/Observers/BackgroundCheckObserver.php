<?php namespace App\Observers;

use App\UserBackgroundCheck;

class BackgroundCheckObserver
{

    public function deleting(UserBackgroundCheck $background)
    {
        $image = $background->image()->first();

        if($image) {
            $image->delete();
        }
    }

}
