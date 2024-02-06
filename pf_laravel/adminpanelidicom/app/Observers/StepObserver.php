<?php

namespace App\Observers;

use App\Models\Step;
use App\Models\StepHistory;

class StepObserver
{
    /**
     * Handle the Step "created" event.
     *
     * @param  Step  $step
     * @return void
     */
    public function created(Step $step): void
    {
        $this->createHistory($step, 'created');
    }

    /**
     * Handle the Step "updated" event.
     *
     * @param  Step  $step
     * @return void
     */
    public function updated(Step $step): void
    {
        $this->createHistory($step, 'updated');
    }

    protected function createHistory($step, $event): void
    {
        $stepHistory = new StepHistory();
        $stepHistory->step_id = $step->id;
        $stepHistory->event = $event;
        $stepHistory->source_id = $step->getOriginal('file_id');
        $stepHistory->result_id = $step->getAttribute('file_id');
        $stepHistory->from_status = $step->getOriginal('status');
        $stepHistory->to_status = $step->getAttribute('status');
        $stepHistory->save();
    }
}
