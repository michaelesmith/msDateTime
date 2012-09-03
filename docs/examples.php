<?php

require_once dirname(__FILE__) . '/../vendor/autoload.php';

###Basic

$d = new msDateTime('2/5/1980 18:53:37'); //any string you could use with php's native DateTime
var_dump($d->format('l F j @ g:ia')); //any formating accepted by php's date()
///string(27) "Tuesday February 5 @ 6:53pm"
var_dump($d->dump()); //show current timestamp human readable for debugging
///string(31) "Tue, 05 Feb 1980 18:53:37 -0600"

###Convenience methods

$d = new msDateTime();
var_dump($d->modify('-1 year +3 days')->dump());
///string(31) "Wed, 17 Nov 2010 14:42:02 -0600"
var_dump($d->finalDayOfQuarter()->endOfDay()->dump());
///string(31) "Fri, 31 Dec 2010 23:59:59 -0600"
var_dump($d->reset()->dump()); //internal timestamp can be reset to initial
///string(31) "Mon, 14 Nov 2011 14:42:02 -0600"
