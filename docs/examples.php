<?php

require_once dirname(__FILE__) . '/../lib/msDateTime.php';

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

###Need timestamps for the beginning and end of the current week?

####Waypoints can be used to record and quickly return to a given timestamp. The object is reset to initial timestamp when setting a waypoint.

$d = new msDateTime();
// msDateTime uses a fluid interface for chaining methods
$d->firstDayOfWeek()->beginningOfDay()->setWaypoint('start');
$d->finalDayOfWeek()->endOfDay()->setWaypoint('end');
var_dump($d->waypoint('start')->dump(), $d->waypoint('start')->getTimestamp());
///string(31) "Sun, 13 Nov 2011 00:00:00 -0600"
///int(1321164000)
var_dump($d->waypoint('end')->dump(), $d->waypoint('end')->getTimestamp());
///string(31) "Sat, 19 Nov 2011 23:59:59 -0600"
///int(1321768799)
var_dump($d->dump());
///string(31) "Mon, 14 Nov 2011 14:42:02 -0600"
