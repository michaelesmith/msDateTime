<?php
/**
 * Description of msDateTimeTest
 *
 * @todo add documentation
 *
 * @author msmith
 */
require_once dirname(__FILE__) . '/../src/msDateTime.php';

class msDateTimeTest extends PHPUnit_Framework_TestCase{
    public function testConstruct(){
		 $d = new msDateTime();
		 $this->assertEquals(time(), $d->getTimestamp());

		 $d = new msDateTime($str = '2/5/1980 06:53:37');
		 $this->assertEquals(strtotime($str), $d->getTimestamp());

		 $d = new msDateTime($str = 'yesterday');
		 $this->assertEquals(strtotime($str), $d->getTimestamp());

		 $d = new msDateTime($str = '+1 month +2 day +1 hour');
		 $this->assertEquals(strtotime($str), $d->getTimestamp());

		 $d = new msDateTime($str = '13:05:19');
		 $this->assertEquals(strtotime($str), $d->getTimestamp());

		 $d = new msDateTime($str = 'August 2011');
		 $this->assertEquals(strtotime('8/1/2011'), $d->getTimestamp());
	 }

    public function testDump(){
		 $d = new msDateTime($str = '2/5/1980 06:53:37');
		 $this->assertEquals(date('r', strtotime($str)), $d->dump());
	 }

	 public function testCopy(){
		 $d1 = new msDateTime($str = '2/5/1980 06:53:37');
		 $d2 = $d1->copy();
		 $this->assertEquals($d2, $d1);
		 $this->assertNotSame($d2, $d1);
	 }

    public function testToString(){
		 $d = new msDateTime($str = '2/5/1980 06:53:37');
		 $this->assertEquals(date('Y-m-d H:i:s', strtotime($str)), (string) $d);
	 }

    public function testStrToTime(){
		 $d = new msDateTime($str = '2/5/1980 06:53:37');
		 $this->assertEquals(date('r', strtotime('2/5/1980 00:00:00')), $d->strtotime('00:00:00')->dump());
	 }

    public function testBeginningOfDay(){
		 $d = new msDateTime($str = '2/5/1980 06:53:37');
		 $this->assertEquals(date('r', strtotime('2/5/1980 00:00:00')), $d->beginningOfDay()->dump());
		 $this->assertEquals(date('Y-m-d', strtotime($str . '-1 day')), $d->modify('-1 second')->format('Y-m-d'));
	 }

    public function testEndOfDay(){
		 $d = new msDateTime($str = '2/5/1980 06:53:37');
		 $this->assertEquals(date('r', strtotime('2/5/1980 23:59:59')), $d->endOfDay()->dump());
		 $this->assertEquals(date('Y-m-d', strtotime($str . '+1 day')), $d->modify('+1 second')->format('Y-m-d'));
	 }

    public function testFirstDayOfWeek(){
		 $d = new msDateTime($str = '8/10/2011');
		 $this->assertEquals('2011-08-07', $d->firstDayOfWeek()->format('Y-m-d'));
	 }

    public function testFinalDayOfWeek(){
		 $d = new msDateTime($str = '8/10/2011');
		 $this->assertEquals('2011-08-13', $d->finalDayOfWeek()->format('Y-m-d'));
	 }

    public function testFirstDayOfMonth(){
		 $d = new msDateTime($str = '8/10/2011');
		 $this->assertEquals('2011-08-01', $d->firstDayOfMonth()->format('Y-m-d'));
	 }

    public function testFinalDayOfMonth(){
		 $d = new msDateTime($str = '8/10/2011');
		 $this->assertEquals('2011-08-31', $d->finalDayOfMonth()->format('Y-m-d'));

		 $d = new msDateTime($str = '2/5/2011');
		 $this->assertEquals('2011-02-28', $d->finalDayOfMonth()->format('Y-m-d'));

		 $d = new msDateTime($str = '2/5/2008');
		 $this->assertEquals('2008-02-29', $d->finalDayOfMonth()->format('Y-m-d'));
	 }

    public function testGetQuarter(){
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

    public function testFirstDayOfQuarter(){
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

    public function testFinalDayOfQuarter(){
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

    public function testCompare(){
		 $d1 = new msDateTime($str = '2/5/1980 06:53:37');
		 $d2 = new msDateTime($str = '2/5/1980 06:53:38');
		 $d3 = new msDateTime($str);
		 
		 $this->assertEquals(-1, $d1->compare($d2));
		 $this->assertEquals(1,  $d2->compare($d1));
		 $this->assertEquals(0,  $d2->compare($d3));
	 }
}
