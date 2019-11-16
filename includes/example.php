#
<?php
#
/*
#
* example.php
#
* class_db.php example usage
#
* Author: Troy Wolf (troy@troywolf.com)
#
*/
#
/*
#
Include the class. Modify path according to where you put the class file.
#
*/
#
require_once(dirname(__FILE__).'/db_class.php');
#

#
/*
#
Instantiate a new db object. If you have multiple databases to connect to
#
or need to work with multiple datasets at the same time, you can create
#
more instances. Within the class, you can define your connections. You can
#
then pass an index to the constructor to select a specific connection. You
#
can define multiple databases of the same type or different types. The default id is zero, so if you pass in nothing, the zero case will be used.
#
In this example, we use the default connection.
#
*/
#
$d = new db();
#

#
/*
#
Where do you want to store your cache files?
#
Default is current dir.
#
*/
#
$d->dir = "/home/foo/bar/";
#

#
/*
#
Execute a basic query. In this example, we've decided not to use caching.
#
*/
#
$d->ttl = 0; //Time to live in seconds.
#
$d->sql = "select * from users order by last_name";
#
$d->fetch();
#

#
/*
#
Execute a query, but this time, cache the data using the name "cars", and
#
consider the cached data good for 5 minutes.
#
*/
#
$d->cache_filename = "cars_less_100000";
#
$d->ttl = 300;
#
$d->sql = "select year, make, model, mileage from cars where mileage < 100000"
#
." order by mileage";
#
$d->fetch();
#

#
/*
#
The dump() method outputs a basic table of the data. This is useful for
#
testing and debugging. Review the dump() method for an example of how to
#
work with the dataset returned in the data array.
#
*/
#
$d->dump();
#

#
/*
#
Iterate through the rows in the data[] array created by fetch().
#
*/
#
foreach($d->data as $row) {
#
echo "<hr />Year: ".$row['year']
#
."<br />Make: ".$row['make']
#
."<br />Model: ".$row['model']
#
."<br />Mileage: ".formatnumber($row['mileage'],0);
#
}
#

#
/*
#
Access a specific column in a specific row.
#
*/
#
echo "<hr />Data in the 'model' column of the 5th row: ".$d->data[4]['model'];
#

#
/*
#
Use the static methods fmt() and fmt2() to help create your SQL statements.
#
Read the comments in the class file for more detail.
#
*/
#
$d->sql = "insert into cars (year,make,model,mileage) VALUES ("
#
.db::fmt($year,0)
#
.db::fmt2($make,0)
#
.db::fmt2($model,0)
#
.db::fmt2($mileage,1)
#
.")";
#

#
/*
#
Execute the query. You use the exec() method for INSERT, UPDATE, and DELETE
#
queries.
#
*/
#
if (!$d->exec()) {
#
/*
#
  There was a problem with the query! The class has a 'log' property that
#
  contains a log of events. This log is useful for testing and debugging.
#
  */
#
echo "<h2>Query execution failed!</h2>";
#
echo $d->log;
#
exit();
#
}
#

#
/*
#
For INSERT,UPDATE,DELETE, you can access the rows_affected() method to get a
#
count of affected rows.
#
*/
#
echo $d->rows_affected()." rows affected<br />";
#

#
/*
#
For INSERTs, if your table has an identity column or autonumber column, you can
#
use the last_id() method to return the new id.
#
*/
#
echo "New ID: ".$d->last_id()."<br />";
#

#
/*
#
The log property contains a log of the objects events. Very useful for
#
testing and debugging. If there are problems, the log will tell you what
#
is wrong. For example, if the cache dir specified does not have write privs,
#
the log will tell you it could not open the cache file. If there is an error
#
in your sql statement, the log will tell you what it is.
#
*/
#
echo "<h1>Log</h1>";
#
echo $d->log;
#
?>