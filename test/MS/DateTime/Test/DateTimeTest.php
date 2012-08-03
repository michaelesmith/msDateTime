<?php

/**
 * @version 0.2
 *
 * @author msmith
 */

namespace MS\DateTime\Test;

use MS\DateTime\DateTime as msDateTime;
use \DateTimeZone;

class DateTimeTest extends TestCase
{
    public function testConstruct()
    {
        $d = new msDateTime();
        $this->assertEquals(time(), $d->getTimestamp(), 'no parameters uses current time');

        $d = new msDateTime($str = '2/5/1980 06:53:37');
        $this->assertEquals(strtotime($str), $d->getTimestamp(), 'passing a str sets tiemstamp to that time using current timezone');

        $d = new msDateTime($time = strtotime('2/5/1980 06:53:37'));
        $this->assertEquals($time, $d->getTimestamp(), 'given a timestamp it is used');

        $d = new msDateTime($str = 'yesterday');
        $this->assertEquals(strtotime($str), $d->getTimestamp(), 'giving a term ie "yesterday" is valid');

        $d = new msDateTime($str = '+1 month +2 day +1 hour');
        $this->assertEquals(strtotime($str), $d->getTimestamp(), 'giving a string offset like "+1 day" is valid');

        $d = new msDateTime($str = '13:05:19');
        $this->assertEquals(strtotime($str), $d->getTimestamp(), 'giving just a time use today a specificed time');

        $d = new msDateTime($str = 'August 2011');
        $this->assertEquals(strtotime('8/1/2011'), $d->getTimestamp(), 'giving a text month and year is valid');

        $d = new msDateTime('now', new DateTimeZone('America/Chicago'));
        $this->assertEquals(time(), $d->getTimestamp(), 'preserves compatibility given a DateTimeZone as second parameter');
    }

    /**
     * @depends testConstruct
     */
    public function testCreate()
    {
        $d = msDateTime::create($str = '2/5/1980 06:53:37');
        $this->assertInstanceOf('MS\\DateTime\\DateTime', $d);
        $this->assertEquals(strtotime($str), $d->getTimestamp());
    }

    /**
     * @depends testConstruct
     */
    public function testDump()
    {
        $d = new msDateTime($str = '2/5/1980 06:53:37');
        $this->assertEquals(date('r', strtotime($str)), $d->dump());
    }

    /**
     * @depends testConstruct
     */
    public function testCopy()
    {
        $d1 = new msDateTime($str = '2/5/1980 06:53:37');
        $d2 = $d1->copy();
        $this->assertEquals($d2, $d1);
        $this->assertNotSame($d2, $d1);
    }

    /**
     * @depends testConstruct
     */
    public function testToString()
    {
        $d = new msDateTime($str = '2/5/1980 06:53:37');
        $this->assertEquals(date('Y-m-d H:i:s', strtotime($str)), (string) $d);
    }

    /**
     * @depends testConstruct
     * @depends testDump
     */
    public function testBeginningOfDay()
    {
        $d = new msDateTime($str = '2/5/1980 06:53:37');
        $this->assertEquals(date('r', strtotime('2/5/1980 00:00:00')), $d->beginningOfDay()->dump());
        $this->assertEquals(date('Y-m-d', strtotime($str . '-1 day')), $d->modify('-1 second')->format('Y-m-d'));

        $d = new msDateTime($str = '3/13/2011 06:53:37');
        $this->assertEquals(date('r', strtotime('3/13/2011 00:00:00')), $d->beginningOfDay()->dump(), 'spans daylight savings time');
        $this->assertEquals(date('Y-m-d', strtotime($str . '-1 day')), $d->modify('-1 second')->format('Y-m-d'));
    }

    /**
     * @depends testConstruct
     * @depends testBeginningOfDay
     */
    public function testIsBeginningOfDay()
    {
        $d = new msDateTime($str = '2/5/1980 06:53:37');
        $this->assertFalse($d->isBeginningOfDay());

        $d = new msDateTime($str = '2/5/1980 00:00:00');
        $this->assertTrue($d->isBeginningOfDay());

        $d = new msDateTime($str = '2/5/1980 23:59:59');
        $this->assertFalse($d->isBeginningOfDay());
    }

    /**
     * @depends testConstruct
     * @depends testDump
     */
    public function testEndOfDay()
    {
        $d = new msDateTime($str = '2/5/1980 06:53:37');
        $this->assertEquals(date('r', strtotime('2/5/1980 23:59:59')), $d->endOfDay()->dump());
        $this->assertEquals(date('Y-m-d', strtotime($str . '+1 day')), $d->modify('+1 second')->format('Y-m-d'));

        $d = new msDateTime($str = '11/6/2011 00:53:37');
        $this->assertEquals(date('r', strtotime('11/6/2011 23:59:59')), $d->endOfDay()->dump());
        $this->assertEquals(date('Y-m-d', strtotime($str . '+1 day')), $d->modify('+1 second')->format('Y-m-d'), 'spans daylight savings time');
    }

    /**
     * @depends testConstruct
     * @depends testEndOfDay
     */
    public function testIsEndOfDay()
    {
        $d = new msDateTime($str = '2/5/1980 06:53:37');
        $this->assertFalse($d->isEndOfDay());

        $d = new msDateTime($str = '2/5/1980 00:00:00');
        $this->assertFalse($d->isEndOfDay());

        $d = new msDateTime($str = '2/5/1980 23:59:59');
        $this->assertTrue($d->isEndOfDay());
    }

    /**
     * @depends testConstruct
     */
    public function testFirstDayOfWeek()
    {
        $d = new msDateTime($str = '8/10/2011');
        $this->assertEquals('2011-08-07', $d->firstDayOfWeek()->format('Y-m-d'));
    }

    /**
     * @depends testConstruct
     * @depends testFirstDayOfWeek
     */
    public function testIsFirstDayOfWeek()
    {
        $d = new msDateTime($str = '2/2/2011 06:53:37');
        $this->assertFalse($d->isFirstDayOfWeek());

        $d = new msDateTime($str = '1/30/2011 00:00:00');
        $this->assertTrue($d->isFirstDayOfWeek());

        $d = new msDateTime($str = '2/5/2011 23:59:59');
        $this->assertFalse($d->isFirstDayOfWeek());
    }

    /**
     * @depends testConstruct
     */
    public function testFinalDayOfWeek()
    {
        $d = new msDateTime($str = '8/10/2011');
        $this->assertEquals('2011-08-13', $d->finalDayOfWeek()->format('Y-m-d'));
    }

    /**
     * @depends testConstruct
     * @depends testFinalDayOfWeek
     */
    public function testIsFinalDayOfWeek()
    {
        $d = new msDateTime($str = '2/2/2011 06:53:37');
        $this->assertFalse($d->isFinalDayOfWeek());

        $d = new msDateTime($str = '1/30/2011 00:00:00');
        $this->assertFalse($d->isFinalDayOfWeek());

        $d = new msDateTime($str = '2/5/2011 23:59:59');
        $this->assertTrue($d->isFinalDayOfWeek());
    }

    /**
     * @depends testConstruct
     */
    public function testFirstDayOfMonth()
    {
        $d = new msDateTime($str = '8/10/2011');
        $this->assertEquals('2011-08-01', $d->firstDayOfMonth()->format('Y-m-d'));
    }

    /**
     * @depends testConstruct
     * @depends testFirstDayOfMonth
     */
    public function testIsFirstDayOfMonth()
    {
        $d = new msDateTime($str = '2/5/2011 06:53:37');
        $this->assertFalse($d->isFirstDayOfMonth());

        $d = new msDateTime($str = '2/1/2011 00:00:00');
        $this->assertTrue($d->isFirstDayOfMonth());

        $d = new msDateTime($str = '2/28/2011 23:59:59');
        $this->assertFalse($d->isFirstDayOfMonth());
    }

    /**
     * @depends testConstruct
     */
    public function testFinalDayOfMonth()
    {
        $d = new msDateTime($str = '8/10/2011');
        $this->assertEquals('2011-08-31', $d->finalDayOfMonth()->format('Y-m-d'));

        $d = new msDateTime($str = '2/5/2011');
        $this->assertEquals('2011-02-28', $d->finalDayOfMonth()->format('Y-m-d'));

        $d = new msDateTime($str = '2/5/2008');
        $this->assertEquals('2008-02-29', $d->finalDayOfMonth()->format('Y-m-d'));
    }

    /**
     * @depends testConstruct
     * @depends testFinalDayOfMonth
     */
    public function testIsFinalDayOfMonth()
    {
        $d = new msDateTime($str = '2/5/2011 06:53:37');
        $this->assertFalse($d->isFinalDayOfMonth());

        $d = new msDateTime($str = '2/1/2011 00:00:00');
        $this->assertFalse($d->isFinalDayOfMonth());

        $d = new msDateTime($str = '2/28/2008 23:59:59');
        $this->assertFalse($d->isFinalDayOfMonth());

        $d = new msDateTime($str = '2/29/2008 23:59:59');
        $this->assertTrue($d->isFinalDayOfMonth());

        $d = new msDateTime($str = '2/28/2011 23:59:59');
        $this->assertTrue($d->isFinalDayOfMonth());
    }

    /**
     * @depends testConstruct
     */
    public function testGetQuarter()
    {
        $d = new msDateTime($str = '1/1/2011');
        $this->assertEquals(1, $d->getQuarter());

        $d = new msDateTime($str = '2/5/2011');
        $this->assertEquals(1, $d->getQuarter());

        $d = new msDateTime($str = '4/5/2011');
        $this->assertEquals(2, $d->getQuarter());

        $d = new msDateTime($str = '9/5/2011');
        $this->assertEquals(3, $d->getQuarter());

        $d = new msDateTime($str = '11/5/2011');
        $this->assertEquals(4, $d->getQuarter());

        $d = new msDateTime($str = '12/31/2011');
        $this->assertEquals(4, $d->getQuarter());
    }

    /**
     * @depends testConstruct
     * @depends testDump
     */
    public function testFirstDayOfQuarter()
    {
        $d = new msDateTime($str = '1/1/2011');
        $this->assertEquals(date('r', strtotime('1/1/2011')), $d->firstDayOfQuarter()->dump());

        $d = new msDateTime($str = '2/5/2011');
        $this->assertEquals(date('r', strtotime('1/1/2011')), $d->firstDayOfQuarter()->dump());

        $d = new msDateTime($str = '4/5/2011');
        $this->assertEquals(date('r', strtotime('4/1/2011')), $d->firstDayOfQuarter()->dump());

        $d = new msDateTime($str = '9/5/2011');
        $this->assertEquals(date('r', strtotime('7/1/2011')), $d->firstDayOfQuarter()->dump());

        $d = new msDateTime($str = '11/5/2011');
        $this->assertEquals(date('r', strtotime('10/1/2011')), $d->firstDayOfQuarter()->dump());

        $d = new msDateTime($str = '12/31/2011');
        $this->assertEquals(date('r', strtotime('10/1/2011')), $d->firstDayOfQuarter()->dump());
    }

    /**
     * @depends testConstruct
     * @depends testFirstDayOfQuarter
     */
    public function testIsFirstDayOfQuarter()
    {
        $d = new msDateTime($str = '2/5/2011 06:53:37');
        $this->assertFalse($d->isFirstDayOfQuarter());

        $d = new msDateTime($str = '1/1/2011 00:00:00');
        $this->assertTrue($d->isFirstDayOfQuarter());

        $d = new msDateTime($str = '3/31/2011 23:59:59');
        $this->assertFalse($d->isFirstDayOfQuarter());
    }

    /**
     * @depends testConstruct
     * @depends testDump
     */
    public function testFinalDayOfQuarter()
    {
        $d = new msDateTime($str = '1/1/2011');
        $this->assertEquals(date('r', strtotime('3/31/2011')), $d->finalDayOfQuarter()->dump());

        $d = new msDateTime($str = '2/5/2011');
        $this->assertEquals(date('r', strtotime('3/31/2011')), $d->finalDayOfQuarter()->dump());

        $d = new msDateTime($str = '4/5/2011');
        $this->assertEquals(date('r', strtotime('6/30/2011')), $d->finalDayOfQuarter()->dump());

        $d = new msDateTime($str = '9/5/2011');
        $this->assertEquals(date('r', strtotime('9/30/2011')), $d->finalDayOfQuarter()->dump());

        $d = new msDateTime($str = '11/5/2011');
        $this->assertEquals(date('r', strtotime('12/31/2011')), $d->finalDayOfQuarter()->dump());

        $d = new msDateTime($str = '12/31/2011');
        $this->assertEquals(date('r', strtotime('12/31/2011')), $d->finalDayOfQuarter()->dump());
    }

    /**
     * @depends testConstruct
     * @depends testFinalDayOfQuarter
     */
    public function testIsFinalDayOfQuarter()
    {
        $d = new msDateTime($str = '2/5/2011 06:53:37');
        $this->assertFalse($d->isFinalDayOfQuarter());

        $d = new msDateTime($str = '1/1/2011 00:00:00');
        $this->assertFalse($d->isFinalDayOfQuarter());

        $d = new msDateTime($str = '3/31/2011 23:59:59');
        $this->assertTrue($d->isFinalDayOfQuarter());
    }

    /**
     * @depends testConstruct
     */
    public function testCompare()
    {
        $d1 = new msDateTime($str = '2/5/1980 06:53:37');
        $d2 = new msDateTime($str = '2/5/1980 06:53:38');
        $d3 = new msDateTime($str);

        $this->assertEquals(-1, $d1->compare($d2));
        $this->assertEquals(1, $d2->compare($d1));
        $this->assertEquals(0, $d2->compare($d3));
    }

}
