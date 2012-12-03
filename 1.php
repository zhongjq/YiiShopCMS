<?php

echo $_SERVER['DOCUMENT_ROOT'];
echo "<br>";
echo $_SERVER['SCRIPT_FILENAME'];
echo "<br>";
echo dirname(__FILE__);
echo "<br>";


echo substr(dirname(__FILE__), strlen($_SERVER['DOCUMENT_ROOT']) );