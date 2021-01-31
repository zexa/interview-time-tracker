<?php

declare(strict_types=1);

namespace App\Calculator;

use App\Entity\PublicTask;
use App\Entity\SummableDateInterval;
use App\Transformer\SummableDateIntervalTransformer;
use DateInterval;
use Exception;

class DateIntervalCalculator
{
    private SummableDateIntervalTransformer $summableDateIntervalTransformer;

    public function __construct(
        SummableDateIntervalTransformer $summableDateIntervalTransformer
    ) {
        $this->summableDateIntervalTransformer = $summableDateIntervalTransformer;
    }

    /**
     * @param DateInterval[] $dateIntervals
     * @return DateInterval
     * @throws Exception
     */
    public function sumDateIntervals(array $dateIntervals): DateInterval
    {
        $sum = new SummableDateInterval('PT0S');

        foreach ($dateIntervals as $dateInterval) {
            $sum->add($dateInterval);
        }

        return $this->summableDateIntervalTransformer->toDateInterval($sum);
    }

    /**
     * @param PublicTask[] $publicTasks
     * @return DateInterval
     * @throws Exception
     */
    public function sumFromPublicTasks(array $publicTasks): DateInterval
    {
        foreach ($publicTasks as $publicTask) {
            $dateIntervals[] = $publicTask->getDuration();
        }

        return $this->sumDateIntervals($dateIntervals ?? []);
    }
}
