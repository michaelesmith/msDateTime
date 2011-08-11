<?php

/**
 * Description of msDateTime
 *
 * @todo add documentation
 *
 * @author msmith
 */
class msDateTime extends DateTime {
	public function  __toString() {
		return $this->format('Y-m-d H:i:s');
	}

	public function copy(){
		return clone $this;
	}

	public function compare($msDateTime2){
		return $this->getTimestamp() - $msDateTime2->getTimestamp();
	}

	public function dump() {
		return $this->format('r');
	}

	public function beginningOfDay() {
		return $this->setTime(00, 00, 00);
	}

	public function endOfDay() {
		return $this->setTime(23, 59, 59);
	}

	public function strtotime($str){
		return $this->setTimestamp(strtotime($str, $this->getTimestamp()));
	}

	public function firstDayOfWeek(){
		return $this->modify(sprintf('-%d days', $this->format('w')));
	}

	public function finalDayOfWeek(){
		return $this->modify(sprintf('+%d days', 6 - $this->format('w')));
	}

	public function firstDayOfMonth(){
		return $this->modify(sprintf('-%d days', $this->format('d') - 1));
	}

	public function finalDayOfMonth(){
		return $this->firstDayOfMonth()->modify('+1 month -1 day');
	}

	public function getQuarter(){
		return intval(($this->format('n') -1) / 3) + 1;
	}

	public function firstDayOfQuarter(){
		return $this->setDate($this->format('Y'), ($this->getQuarter() - 1) * 3 + 1, 1);
	}

	public function finalDayOfQuarter(){
		return $this->firstDayOfQuarter()->modify('+3 months -1 day');
	}

	public function setWaypoint($point){

	}

	public function waypoint($point){

	}
}
