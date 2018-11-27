#
<?php
#
/*
#
* Filename.......: db_class.php
#
* Author.........: Troy Wolf [troy@troywolf.com]
#
* Last Modified..: Date: 2005/06/19 16:42:00
#
* Description....: A database class that provides methods to work with mysql,
#
  postgres, and mssql databases. The class provides a common
#
  interface to the various database types. A powerful
#
  feature of the class is the ability to cache datasets to disk
#
  using a Time-To-Live parameter. This can eliminate a lot of
#
  unneccessary hits to your database! Also, a database
#
  connection is not created unless and until needed.
#
*/
#
class db {
#
var $cnn_id;
#
var $db_type;
#
var $dir;
#
var $name;
#
var $filename;
#
var $fso;
#
var $data;
#
var $sql;
#
var $cnn;
#
var $db;
#
var $res;
#
var $ttl;
#
var $data_ts;
#
var $server;
#
var $log;
#

#
/*
#
  The class constructor. You can set some defaults here if desired.
#
  */
#
function db($cnn_id=0) {
#
$this->log = "initialize db() called<br />";
#
$this->cnn_id = $cnn_id;
#
$this->dir = realpath("./")."/"; //Default to current dir.
#
$this->ttl = 0;
#
$this->data_ts = 0;
#
}
#

#
/*
#
  connect() method makes the actual server connection and selects a database
#
  only if needed. This saves database connections. Multiple database types are
#
  supported. Enter your connection credentials in the switch statement below.
#

#
  This is a private function, but it is at the top of the class because you need
#
  to enter your connections.
#
  */
#
function connect() {
#
$this->log .= "connect() called<br />";
#
switch($this->cnn_id) {
#
/*
#
  You can define all the database connections you need in this
#
  switch statement.
#
  */
#
case 0:
#
$this->db_type = "mysql";
#
$this->server = "";
#
$user = "";
#
$pwd = "";
#
$this->db = "";
#
break;
#
case 1:
#
$this->db_type = "mysql";
#
$this->server = "";
#
$user = "";
#
$pwd = "";
#
$this->db = "";
#
break;
#
case 2:
#
$this->db_type = "postgres";
#
$this->server = "";
#
$user = "";
#
$pwd = "";
#
$this->db = "";
#
break;
#
case 3:
#
$this->db_type = "mssql";
#
$this->server = "";
#
$user = "";
#
$pwd = "";
#
$this->db = "";
#
break;
#
}
#
switch($this->db_type) {
#
case "mysql":
#
if (!$this->cnn = mysql_connect($this->server,$user,$pwd )) {
#
$this->log .= "mysql_connect() failed<br />";
#
$this->log .= mysql_error()."<br />";
#
return false;
#
}
#
if (!mysql_select_db($this->db,$this->cnn)) {
#
$this->log .= "Could not select database named ".$this->db."<br />";
#
$this->log .= mysql_error()."<br />";
#
return false;
#
}
#
break;
#
case "postgres":
#
if (!$this->cnn = pg_connect("host=$this->server dbname=$this->dbuser=$user password=$pwd")) {
#
$this->log .= "pg_connect() failed<br />";
#
$this->log .= pg_last_error()."<br />";
#
return false;
#
}
#
break;
#
case "mssql":
#
if (!$this->cnn = mssql_connect($this->server,$user,$pwd )) {
#
$this->log .= "mssql_connect() failed<br />";
#
$this->log .= mssql_error()."<br />";
#
return false;
#
}
#
if (!mssql_select_db($this->db,$this->cnn)) {
#
$this->log .= "Could not select database named ".$this->db."<br />";
#
$this->log .= mssql_error()."<br />";
#
return false;
#
}
#
break;
#
}
#
return true;
#
}
#

#
/*
#
  fetch() is used to retrieve a dataaset. fetch() determines whether to use the
#
  cache or not, and queries either the database or the cache file accordingly.
#
  */
#
function fetch() {
#
$this->log .= "---------------------------------<br />fetch() called<br />";
#
$this->log .= "SQL: ".$this->sql."<br />";
#
$this->data = "";
#
if ($this->ttl == "0") {
#
return $this->getFromDB();
#
} else {
#
$this->filename = $this->dir."db_".$this->name;
#
$this->getFile_ts();
#
if ($this->ttl == "daily") {
#
if (date('Y-m-d',$this->data_ts) != date('Y-m-d',time())) {
#
$this->log .= "cache has expired<br />";
#
if ($this->getFromDB()) { return $this->saveToCache(); }
#
} else {
#
return $this->getFromCache();
#
}
#
} else {
#
if ((time() - $this->data_ts) >= $this->ttl) {
#
$this->log .= "cache has expired<br />";
#
if ($this->getFromDB()) { return $this->saveToCache(); }
#
} else {
#
return $this->getFromCache();
#
}
#
}
#
}
#
}
#

#
/*
#
  Use exec() to execute INSERT, UPDATE, DELETE statements.
#
  */
#
function exec() {
#
$this->log .= "exec() called<br />";
#
$this->log .= "SQL: ".$this->sql."<br />";
#
if (!$this->cnn) { if (!$this->connect()) { return false; } }
#
switch($this->db_type) {
#
case "mysql":
#
if (!$res = @mysql_query($this->sql, $this->cnn)) {
#
$this->log .= "Query execution failed.<br />";
#
$this->log .= mysql_error()."<br />";
#
return false;
#
}
#
break;
#
case "postgres":
#
if (!$this->res = @pg_query($this->cnn, $this->sql)) {
#
$this->log .= "Query execution failed.<br />";
#
$this->log .= pg_last_error()."<br />";
#
return false;
#
}
#
break;
#
case "mssql":
#
if (!$res = @mssql_query($this->sql, $this->cnn)) {
#
$this->log .= "Query execution failed.<br />";
#
$this->log .= mssql_error()."<br />";
#
return false;
#
}
#
break;
#
}
#
return true;
#
}
#

#
/*
#
  rows_affected() returns number of rows affected by INSERT, UPDATE, DELETE.
#
  $rows_affected = $objName->rows_affected();
#
  */
#
function rows_affected() {
#
$this->log .= "rows_affected() called<br />";
#
if (!$this->cnn) {
#
$this->log .= "rows_affected(): database connection does not exist.<br />";
#
return false;
#
}
#
switch($this->db_type) {
#
case "mysql":
#
return mysql_affected_rows($this->cnn);
#
case "postgres":
#
return pg_affected_rows($this->res);
#
case "mssql":
#
return mssql_rows_affected($this->cnn);
#
}
#
return false;
#
}
#

#
/*
#
  last_id() returns newly inserted identity or autonumber from last INSERT.
#
  Of course, this is only applicable if your table has an autonumber column.
#
  $last_id = $objName->last_id();
#
  */
#
function last_id() {
#
$this->log .= "last_id() called<br />";
#
if (!$this->cnn) {
#
$this->log .= "last_id(): database connection does not exist.<br />";
#
return false;
#
}
#
switch($this->db_type) {
#
case "mysql":
#
return mysql_insert_id();
#
break;
#
case "postgres":
#
return pg_last_oid($this->res);
#
break;
#
case "mssql":
#
if (!$res = @mysql_query("select SCOPE_IDENTITY()")) {
#
$this->log .= "Failed to retrieve identity value.<br />";
#
$this->log .= mssql_error()."<br />";
#
return false;
#
}
#
if (!$identity = @mssql_result($res,0,0)) {
#
$this->log .= "Failed to retrieve identity value.<br />";
#
$this->log .= mssql_error()."<br />";
#
return false;
#
}
#
return $identity;
#
break;
#
}
#
return false;
#
}
#

#
/*
#
  fmt() is a helper function for formatting SQL statement strings.
#
  For strings values, it will escape embedded single ticks, replace emptry
#
  strings with 'NULL', and properly wrap the value in quotes. For numeric types,
#
  it will replace empty values with zero.
#
  val : value to format
#
  dtype : 0 = string, 1 = numeric
#
  */
#
function fmt($val,$dtype) {
#
switch($dtype) {
#
case 0:
#
if(! $val && $val != "0") {
#
$tmp = "null";
#
} else {
#
$tmp = "'".str_replace("'","''",$val)."'";
#
}
#
break;
#
case 1:
#
if(! $val) {
#
$tmp = "0";
#
} else {
#
$tmp = $val;
#
}
#
break;
#
}
#
return $tmp;
#
}
#

#
/*
#
  fmt2() is the same as fmt() except it inserts a comma at the beginning
#
  of the return value and a space at the end. Useful in building SQL statements
#
  with multiple values.
#
  */
#
function fmt2($val,$dtype) {
#
switch($dtype) {
#
case 0:
#
if(! $val && $val != "0") {
#
$tmp = ",null ";
#
} else {
#
$tmp = ",'".str_replace("'","''",$val)."' ";
#
}
#
break;
#
case 1:
#
if(! $val) {
#
$tmp = ",0";
#
} else {
#
$tmp = ",".$val." ";
#
}
#
break;
#
}
#
return $tmp;
#
}
#

#
/*
#
  dump() produces an HTML table of the data. It is useful for debugging.
#
  This is also a good example of how to work with the data array.
#
  */
#
function dump() {
#
$this->log .= "dump() called<br />";
#
if (!$this->data) {
#
$this->log .= "dump(): no rows exist<br />";
#
return false;
#
}
#
echo "<style>table.dump { font-family:Arial; font-size:8pt; }</style>";
#
echo "<table class=\"dump\" border=\"1\" cellpadding=\"1\" cellspacing=\"0\">\n";
#
echo "<tr>";
#
echo "<th>#</th>";
#
foreach($this->data[0] as $key=>$val) {
#
echo "<th><b>";
#
echo $key;
#
echo "</b></th>";
#
}
#
echo "</tr>\n";
#
$row_cnt = 0;
#
foreach($this->data as $row) {
#
$row_cnt++;
#
echo "<tr align='center'>";
#
echo "<td>".$row_cnt."</td>";
#
foreach($row as $val) {
#
echo "<td>";
#
echo $val;
#
echo "</td>";
#
}
#
echo"</tr>\n";
#
}
#
echo "</table>\n";
#
}
#

#
/*
#
  PRIVATE FUNCTIONS BELOW THIS POINT
#
  ------------------------------------------------------------------------------
#
  */
#

#
function getFromDB() {
#
$this->log .= "getFromDB() called<br />";
#
if (!$this->cnn) {
#
if (!$this->connect()) {
#
$this->log .= "Database connection failed.<br />";
#
return false;
#
}
#
}
#
switch($this->db_type) {
#
case "mysql":
#
if (!$res = @mysql_query($this->sql, $this->cnn)) {
#
$this->log .= "Query execution failed.<br />";
#
$this->log .= mysql_error()."<br />";
#
return false;
#
}
#
while ($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
#
$this->data[] = $row;
#
}
#
break;
#
case "postgres":
#
if (!$res = @pg_query($this->cnn, $this->sql)) {
#
$this->log .= "Query execution failed.<br />";
#
$this->log .= pg_last_error()."<br />";
#
return false;
#
}
#
if (!$this->data = @pg_fetch_all($res)) {
#
$this->log .= "getFromDB() failed<br />";
#
return false;
#
}
#
break;
#
case "mssql":
#
if (!$res = @mssql_query($this->sql, $this->cnn)) {
#
$this->log .= "Query execution failed.<br />";
#
$this->log .= mssql_error()."<br />";
#
return false;
#
}
#
while ($row = mssql_fetch_array($res)) {
#
$this->data[] = $row;
#
}
#
break;
#
}
#
return true;
#
}
#

#
function getFromCache() {
#
$this->log .= "getFromCache() called<br />";
#
if (!$x = @file_get_contents($this->filename)) {
#
$this->log .= "Could not read ".$this->filename."<br />";
#
return false;
#
}
#
if (!$this->data = unserialize($x)) {
#
$this->log .= "getFromCache() failed<br />";
#
return false;
#
}
#
return true;
#
}
#

#
function saveToCache() {
#
$this->log .= "saveToCache() called<br />";
#

#
//create file pointer
#
if (!$fp=@fopen($this->filename,"w")) {
#
$this->log .= "Could not open ".$this->filename."<br />";
#
return false;
#
}
#
//write to file
#
if (!@fwrite($fp,serialize($this->data))) {
#
$this->log .= "Could not write to ".$this->filename."<br />";
#
fclose($fp);
#
return false;
#
}
#
//close file pointer
#
fclose($fp);
#
return true;
#
}
#

#
function getFile_ts() {
#
$this->log .= "getFile_ts() called<br />";
#
if (!file_exists($this->filename)) {
#
$this->data_ts = 0;
#
$this->log .= $this->filename." does not exist<br />";
#
return false;
#
}
#
$this->data_ts = filemtime($this->filename);
#
return true;
#
}
#

#
}
#

#
?>