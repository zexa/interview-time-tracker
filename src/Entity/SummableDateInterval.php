<?php

declare(strict_types=1);

namespace App\Entity;

use DateInterval;

class SummableDateInterval extends DateInterval
{
    public function add(DateInterval $interval)
    {
        foreach (str_split('ymdhis') as $prop) {
            $this->$prop += $interval->$prop;
        }
        $this->i += (int)($this->s / 60);
        $this->s = $this->s % 60;
        $this->h += (int)($this->i / 60);
        $this->i = $this->i % 60;
    }
}
