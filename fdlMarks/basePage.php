<?php
/**
 * 
 */
require_once '../host/config.php';

define('_ESCPERCENTON', true);
define('_ESCPERCENTOFF', false);
define('_ESCUNDERSCOREON', true);
define('_ESCUNDERSCOREOFF', false);

/**
 * Undocumented class
 */
class BasePage
{
    /**
     * Undocumented function
     *
     * @return void
     */
    public function basePageFunction()
    { 
        // Constructor.
        if (!isset($_SESSION['timeout'])) {
            session_start();
        }
        date_default_timezone_set("Australia/Melbourne");
        $_SESSION['timeout'] = time();

        set_time_limit(0);
        error_reporting(1);
    }
    /**
     * Undocumented function
     *
     * @param [type] $argAdminLevel Admin level
     * @param [type] $argUserType   User Permissions
     * 
     * @return void
     */
    public function access_denied($argAdminLevel, $argUserType)
    { 
        // Check that we have come from a valid page.

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
    /**
     * Undocumented function
     *
     * @param [type] $in            Integer
     * @param [type] $escpercent    Percentage
     * @param [type] $escunderscore Under score
     * 
     * @return void
     */
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
        // With return type void returns string but should not return anything.
        return $out;
    }
    public function pdo_Connect()
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
    /**
     * Undocumented function
     *
     * @param [type] $pdoerrorinfo error infomatics
     * 
     * @return void
     */
    public function pdo_error($pdoerrorinfo)
    {
        //$message = '';
        $temp = $pdoerrorinfo;
        return $pdoerrorinfo[2];
    }
    /**
     * db_connect function
     *
     * @return void
     */
    public function db_Connect()
    { 
        // Check that we can connect to the host and talk to the database.

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
    /**
     * Undocumented function
     *
     * @param [type] $title Title of html header
     * 
     * @return void
     */
    public function display_Html_Header($title)
    { 
        // Set the page up.
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
    /**
     * Undocumented function
     *
     * @return void
     */
    public function display_html_footer()
    { 
        // Close the page off.
        ?>

    </body>
  </html>

        <?php
    }
    /**
     * Undocumented function
     *
     * @param [type] $action Actions
     * 
     * @return void
     */
    public function display_Message($action)
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
