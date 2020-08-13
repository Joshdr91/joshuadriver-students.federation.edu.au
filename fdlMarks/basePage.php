<?php

require_once '../host/config.php';

define('_escpercentON', true);
define('_escpercentOFF', false);
define('_escunderscoreON', true);
define('_escunderscoreOFF', false);

class basePage
{

    public function basePageFunction()
    { // Constructor.
        if (!isset($_SESSION['timeout'])) {
            session_start();
        }
        date_default_timezone_set("Australia/Melbourne");
        $_SESSION['timeout'] = time();

        set_time_limit(0);
        error_reporting(1);
    }
    public function access_denied($argAdminLevel, $argUserType)
    { // Check that we have come from a valid page.

        if (!isset($_SESSION["mrkaccessallowed"]) || $_SESSION["mrkaccessallowed"] != 'yes') {
            return true;
        }
        if (empty($argAdminLevel) && empty($argUserType)) {
            return false;
        }
        if (!empty($argAdminLevel)) {
            for ($idx = 0; $idx < strlen($argAdminLevel); $idx++) {
                $argAdminLevelChar = substr($argAdminLevel, $idx, 1);
                if ($_SESSION["mrkadmin"] == $argAdminLevelChar) {
                    return false;
                }
            }
        }
        if (!empty($argUserType)) {
            for ($idx = 0; $idx < strlen($argUserType); $idx++) {
                $argUserTypeChar = substr($argUserType, $idx, 1);
                if ($_SESSION["mrkusertype"] == $argUserTypeChar) {
                    return false;
                }
            }
        }
        return true;

    }
    public function escapeinput($in, $escpercent, $escunderscore)
    {

        $out = trim($in);
        $banned = array(
            ';',
            '=',
            'DELETE ',
            'DROP ',
            'FROM ',
            'INSERT ',
            'SELECT ',
            'TABLE ',
            'UPDATE ',
            'WHERE ',
        );
        $out = str_ireplace($banned, '', $out);
        $out = str_ireplace($banned, '', $out);
        if (stripos($out, '"') != false) {
            $out = str_replace('"', "''", $out);
        }
        if (stripos($out, "'") != false) {
            $out = stripslashes($out);
        }
        $out = addslashes($out);
        if ($escpercent && stripos($out, '%') != false) {
            $out = str_replace('%', "\%", $out);
        }
        if ($escunderscore && stripos($out, '_') != false) {
            $out = str_replace('_', "\_", $out);
        }
        return $out;

    }
    public function pdo_connect()
    {

        $server = $GLOBALS['fdlconfig']['mysql']['server'];
        $dbname = $GLOBALS['fdlconfig']['mysql']['dbname'];
        $username = $GLOBALS['fdlconfig']['mysql']['username'];
        $password = $GLOBALS['fdlconfig']['mysql']['password'];

        $conn = new PDO("mysql:host=$server;dbname=$dbname", $username, $password);

        if (!$conn) {
            return false;
        }
        return $conn;

    }
    public function pdo_error($pdoerrorinfo)
    {

        $message = '';

        $temp = $pdoerrorinfo;

        return $pdoerrorinfo[2];

    }
    public function db_connect()
    { // Check that we can connect to the host and talk to the database.

        global $db;

        $server = $GLOBALS['fdlconfig']['mysql']['server'];
        $dbname = $GLOBALS['fdlconfig']['mysql']['dbname'];
        $username = $GLOBALS['fdlconfig']['mysql']['username'];
        $password = $GLOBALS['fdlconfig']['mysql']['password'];

        $db = mysqli_connect($server, $username, $password, $dbname);

        if (mysqli_connect_error()) {
            return false;
        }
        mysqli_set_charset($db, 'utf8');

        return $db;

    }
    public function display_html_header($title)
    { // Set the page up.
        ?>

  <html>
    <head>

      <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />

      <?php
if (!empty($title)) { // Only output title if one provided.
            echo "<title>$title</title>";
        }
        ?>

  <?php
}
    public function display_html_footer()
    { // Close the page off.
        ?>

    </body>
  </html>

  <?php
}
    public function display_message($action)
    {

        if ($action == "wait") {
            $_SESSION["mrkmessage"] = 'PLEASE WAIT WHILE YOUR REQUEST IS PROCESSED';
        }
        if ($action == "clear") {
            $_SESSION["mrkmessage"] = '';
        }
        echo "<script language='javascript'> parent.fmeMessage.location.replace('message.php')</script>";

    }
}
?>
