<?php
/**
 * Created by PhpStorm.
 * User: Keigo
 * Date: 31/01/2019
 * Time: 16:09
 */

namespace App\Services;


class DateDiffHour
{

    /**
     * Return hours difference between two dates
     * @param \DateTime $dateEnd
     * @param \DateTime $dateStart
     */
    public function getDiff(\DateTimeInterface $dateEnd, \DateTimeInterface $dateStart): int
    {
        $diff = $dateEnd->diff($dateStart);


        if ($diff->i > 0 && $diff->i < 60 && $diff->h == 0) {
            $hours = 1;
        } else {

            $hours = $diff->h;
            $hours = $hours + ($diff->days * 24);
        }

        return $hours;
    }

}