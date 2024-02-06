<?php

namespace App\Services;

use App\Jobs\Filter3dmJob;
use App\Jobs\FilterCentrJob;
use App\Jobs\FilterDoneJob;
use App\Jobs\FilterFocsJob;
use App\Jobs\FilterKont3Job;
use App\Jobs\FilterKontJob;
use App\Jobs\FilterNormJob;

class StepStatusService
{
    const DEFAULT_STATUS = 'new';

    protected array $statuses = [
        'new' => [
            'status' => 'new',
            'job' => null,
            'next' => 'focs',
        ],
        'focs' => [
            'status' => 'focs',
            'job' => FilterFocsJob::class,
            'next' => 'kont3',
        ],
//        'norm' => [
//            'status' => 'norm',
//            'job' => FilterNormJob::class,
//            'next' => 'kont',
//        ],
//        'kont' => [
//            'status' => 'kont',
//            'job' => FilterKontJob::class,
//            'next' => 'centr',
//        ],
        'kont3' => [
            'status' => 'kont3',
            'job' => FilterKont3Job::class,
            'next' => 'centr',
        ],
        'centr' => [
            'status' => 'centr',
            'job' => FilterCentrJob::class,
            'next' => '3dm',
        ],
        '3dm' => [
            'status' => '3dm',
            'job' => Filter3dmJob::class,
            'next' => 'done',
        ],
        'done' => [
            'status' => 'done',
            'job' => FilterDoneJob::class,
            'next' => null,
        ],
    ];

    public function get($status = null)
    {
        return $this->statuses[$status ?? self::DEFAULT_STATUS] ?? null;
    }

    public function next($status = null)
    {
        return $this->statuses[$this->get($status ?? self::DEFAULT_STATUS)['next']];
    }

    public function prev($status = null)
    {
        $currentStatus = $status ?? self::DEFAULT_STATUS;
        foreach ($this->statuses as $status) {
            if ($status['next'] == $currentStatus) {
                return $status;
            }
        }
        return null;
    }

    public function allNextStatus($status): array
    {
        $status = $this->next($status);
        $result = [$status['status']];
        if (!empty($status['next'])) {
            $result = array_merge($result, $this->allNextStatus($status['status']));
        }
        return $result;
    }
}
