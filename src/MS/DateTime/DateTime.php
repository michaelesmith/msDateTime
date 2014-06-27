<?php

namespace MS\DateTime;

/**
 * An extension of the PHP native DateTime object to provide powerful convenience methods
 *
 * @version 0.3
 *
 * @author msmith
 */
class DateTime extends \DateTime
{
    /**
     * Creates a new msDateTime object
     *
     * @param  string       $time
     * @param  \DateTimeZone $object
     * @return \MS\DateTime\DateTime
     */
    public function __construct($time = null, $object = null)
    {
        if ($time && is_numeric($time) && $time == intval($time)) {
            $time = '@' . $time;
        }

        if ($object) {
            parent::__construct($time, $object);
        } elseif ($time) {
            parent::__construct($time);
        } else {
            parent::__construct();
        }
    }

    /**
     * Creates a new msDateTime object inline to preserve fluid calls
     *
     * @param  string       $time
     * @param  \DateTimeZone $object
     * @return \MS\DateTime\DateTime
     */
    public static function create($time = null, $object = null)
    {
        return new self($time, $object);
    }

    /**
     * Returns the current timestamp in "Y-m-d H:i:s" format
     *
     * @return string
     */
    public function  __toString()
    {
        return $this->format('Y-m-d H:i:s');
    }

    /**
     * Creates a copy of the current object
     *
     * @return \MS\DateTime\DateTime
     */
    public function copy()
    {
        return clone $this;
    }

    /**
     * Compares this object to $msDateTime2 by returning the difference in seconds
     *
     * @param  \MS\DateTime\DateTime $msDateTime2
     * @return int
     */
    public function compare($msDateTime2)
    {
        return $this->getTimestamp() - $msDateTime2->getTimestamp();
    }

    /**
     * Outputs the current timestamp in a general format. Should only be used for debugging.
     *
     * @return string
     */
    public function dump()
    {
        return $this->format('r');
    }

    /**
     * Sets the internal timestamp to midnight of the current day
     *
     * @return \MS\DateTime\DateTime
     */
    public function beginningOfDay()
    {
        return $this->setTime(00, 00, 00);
    }

    /**
     * Returns true if the current timestamp is the beginning of the day
     *
     * @return boolean
     */
    public function isBeginningOfDay()
    {
        return $this->getTimestamp() == $this->copy()->beginningOfDay()->getTimestamp();
    }

    /**
     * Sets the internal timestamp to one second before midnight
     *
     * @return \MS\DateTime\DateTime
     */
    public function endOfDay()
    {
        return $this->setTime(23, 59, 59);
    }

    /**
     * Returns true if the current timestamp is the end of the day
     *
     * @return boolean
     */
    public function isEndOfDay()
    {
        return $this->getTimestamp() == $this->copy()->endOfDay()->getTimestamp();
    }

    /**
     * Sets the internal timestamp to Sunday of the current week
     *
     * @return \MS\DateTime\DateTime
     */
    public function firstDayOfWeek()
    {
        return $this->modify(sprintf('-%d days', $this->format('w')));
    }

    /**
     * Returns true if the current timestamp is the first day of the week
     *
     * @return boolean
     */
    public function isFirstDayOfWeek()
    {
        return $this->getTimestamp() == $this->copy()->firstDayOfWeek()->getTimestamp();
    }

    /**
     * Sets the internal timestamp to Saturday of the current week
     *
     * @return \MS\DateTime\DateTime
     */
    public function finalDayOfWeek()
    {
        return $this->modify(sprintf('+%d days', 6 - $this->format('w')));
    }

    /**
     * Returns true if the current timestamp is the final day of the week
     *
     * @return boolean
     */
    public function isFinalDayOfWeek()
    {
        return $this->getTimestamp() == $this->copy()->finalDayOfWeek()->getTimestamp();
    }

    /**
     * Sets the internal timestamp to the first day of the curent month
     *
     * @return \MS\DateTime\DateTime
     */
    public function firstDayOfMonth()
    {
        return $this->modify(sprintf('-%d days', $this->format('d') - 1));
    }

    /**
     * Returns true if the current timestamp is the first day of the month
     *
     * @return boolean
     */
    public function isFirstDayOfMonth()
    {
        return $this->getTimestamp() == $this->copy()->firstDayOfMonth()->getTimestamp();
    }

    /**
     * Sets the internal timestamp to the last day of the current month
     *
     * @return \MS\DateTime\DateTime
     */
    public function finalDayOfMonth()
    {
        return $this->firstDayOfMonth()->modify('+1 month -1 day');
    }

    /**
     * Returns true if the current timestamp is the final day of the month
     *
     * @return boolean
     */
    public function isFinalDayOfMonth()
    {
        return $this->getTimestamp() == $this->copy()->finalDayOfMonth()->getTimestamp();
    }

    /**
     * Returns the current quarter number (1, 2, 3 or 4)
     *
     * @return integer
     */
    public function getQuarter()
    {
        return intval(($this->format('n') -1) / 3) + 1;
    }

    /**
     * Sets the internal timestamp to the first day of the current quarter (1/1, 4/1, 7/1 or 10/1)
     *
     * @return \MS\DateTime\DateTime
     */
    public function firstDayOfQuarter()
    {
        return $this->setDate($this->format('Y'), ($this->getQuarter() - 1) * 3 + 1, 1);
    }

    /**
     * Returns true if the current timestamp is the first day of the current quarter
     *
     * @return boolean
     */
    public function isFirstDayOfQuarter()
    {
        return $this->getTimestamp() == $this->copy()->firstDayOfQuarter()->getTimestamp();
    }

    /**
     * Sets the internal timestamp to the last day of the current quarter (3/31, 6/30, 9/30 or 12/31)
     *
     * @return \MS\DateTime\DateTime
     */
    public function finalDayOfQuarter()
    {
        return $this->firstDayOfQuarter()->modify('+3 months -1 day');
    }

    /**
     * Returns true if the current timestamp is the final day of the current quarter
     *
     * @return boolean
     */
    public function isFinalDayOfQuarter()
    {
        return $this->getTimestamp() == $this->copy()->finalDayOfQuarter()->getTimestamp();
    }

    /**
     * @return boolean
     */
    public function isToday()
    {
        $dt = new self('now');

        return $this->format('Y-m-d') == $dt->format('Y-m-d');
    }

    /**
     * @return boolean
     */
    public function isTomorrow()
    {
        $dt = new self('+1 day');

        return $this->format('Y-m-d') == $dt->format('Y-m-d');
    }

    /**
     * @return boolean
     */
    public function isYesterday()
    {
        $dt = new self('-1 day');

        return $this->format('Y-m-d') == $dt->format('Y-m-d');
    }

    /**
     * Uses ISO-8601 weeks Monday - Sunday
     *
     * @return boolean
     */
    public function isCurrentWeek()
    {
        $dt = new self();

        return $this->format('Y-W') == $dt->format('Y-W');
    }

    /**
     * @return boolean
     */
    public function isCurrentMonth()
    {
        $dt = new self();

        return $this->format('Y-m') == $dt->format('Y-m');
    }

    /**
     * @return boolean
     */
    public function isCurrentYear()
    {
        $dt = new self();

        return $this->format('Y') == $dt->format('Y');
    }

    /**
     * Sets the internal timestamp to beginning of the current hour
     *
     * @return \MS\DateTime\DateTime
     */
    public function beginningOfHour()
    {
        return $this->setTime((int) $this->format('G'), 00, 00);
    }

    /**
     * @return boolean
     */
    public function isBeginningOfHour()
    {
        return $this->format('i:s') == '00:00';
    }

    /**
     * Sets the internal timestamp to end of the current hour
     *
     * @return \MS\DateTime\DateTime
     */
    public function endOfHour()
    {
        return $this->setTime((int) $this->format('G'), 59, 59);
    }

    /**
     * @return boolean
     */
    public function isEndOfHour()
    {
        return $this->format('i:s') == '59:59';
    }

}
