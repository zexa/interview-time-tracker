<?php

declare(strict_types=1);

namespace App\Transformer;

use App\Entity\SummableDateInterval;
use DateInterval;
use Exception;

class SummableDateIntervalTransformer
{
    const FORMAT = 'P%yY%dDT%hH%iM%sS';

    /**
     * @param DateInterval $from
     * @return SummableDateInterval
     * @throws Exception
     */
    public function fromDateInterval(DateInterval $from): SummableDateInterval
    {
        return new SummableDateInterval($from->format(self::FORMAT));
    }

    /**
     * @param SummableDateInterval $to
     * @return DateInterval
     * @throws Exception
     */
    public function toDateInterval(SummableDateInterval $to): DateInterval
    {
        return new DateInterval($to->format(self::FORMAT));
    }
}