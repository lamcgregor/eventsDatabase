<?php

$link = mysql_connect('localhost', 'evdbuser', 'testpass');

if (!$link)
{
	errorReport('Functional error', 'Unable to connect to the database server.');
}

if (!mysql_set_charset('utf8'))
{
	errorReport('Functional error', 'Unable to set database connection encoding.');
}

if (!mysql_select_db('eventsdb'))
{
	errorReport('Functional error', 'Unable to locate the events database.');
}
?>