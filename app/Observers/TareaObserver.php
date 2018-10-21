<?php

namespace App\Observers;

use App\Models\Tarea;

class TareaObserver
{
    public function deleted(Tarea $tarea): void
    {
        $tarea->deleteImage();
    }
}
