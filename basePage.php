<?php

require_once 'host/config.php';

class basePage
{

    public function basePageFunction()
    {
        if (!isset($_SESSION['timeout'])) {
            session_start();
        }

        date_default_timezone_set("Australia/Melbourne");
        $_SESSION['timeout'] = time();

        set_time_limit(0);
        error_reporting(1);

        if (!isset($_SESSION['timeout']) && !isset($_SESSION["mrkaccessallowed"])) {
            Header("Location: index.php");
        }
    }

    public function admin_access_allowed($argAdminLevel)
    {

        if (!empty($argAdminLevel)) {
            for ($idx = 0; $idx < strlen($argAdminLevel); $idx++) {
                $argAdminLevelChar = substr($argAdminLevel, $idx, 1);
                if ($_SESSION[$_GET["trid"] . "admin"] == $argAdminLevelChar) {
                    return true;
                }
            }
        }

        return false;
    }

    public function access_denied($argAdminLevel, $argUserType, $argExtraAccess)
    { // Check that we have come from a valid page. Note each can have multiple values e.g. admin may be passed AM where admin can be either 'A' or 'M'

        if (!$_SESSION["mrkusertype"] == 'Z' && (($_SERVER["SCRIPT_NAME"] !== "/unitdescription.php" && $_SERVER["SCRIPT_NAME"] !== "/" . $_SESSION[$_GET["trid"] . "sysname"] . "/unitdescription.php") && (!isset($_SESSION[$_GET["trid"] . "accessallowed"]) || $_SESSION[$_GET["trid"] . "accessallowed"] !== 'yes'))) {
            return true;
        }

        if (is_numeric($argAdminLevel) && is_numeric($argUserType)) { //only needed for header.php, main.php, menu.php and message.php when don't know yet what user is.
            return false;
        }

        if (!empty($argAdminLevel)) {
            for ($idx = 0; $idx < strlen($argAdminLevel); $idx++) {
                $argAdminLevelChar = substr($argAdminLevel, $idx, 1);
                if ($_SESSION[$_GET["trid"] . "admin"] == $argAdminLevelChar) {
                    return false;
                }
            }
        }

        if (!empty($argUserType)) {
            for ($idx = 0; $idx < strlen($argUserType); $idx++) {
                $argUserTypeChar = substr($argUserType, $idx, 1);
                if ($_SESSION[$_GET["trid"] . "usertype"] == $argUserTypeChar) {
                    return false;
                }

                if ($_SESSION["mrkusertype"] == 'Z' && $argUserTypeChar == 'Z') { //from fdlMarks
                    return false;
                }
            }
        }

        if ($argExtraAccess) {
            return false;
        }

        return true;
    }

    public function extra_access($arguserid, $argmenuitem)
    {

        reset($_SESSION[$_GET["trid"] . "useraccess"]);
        while (list($menukey, $menuvalue) = each($_SESSION[$_GET["trid"] . "useraccess"])) {
            $menurow = $menuvalue;

            if ($menurow["userid"] == $arguserid && $menurow["menuitem"] == $argmenuitem) {
                return true;
            }

        }

        return false;
    }

    public function pdo_connect()
    {
        //Repetitive code (Reference 122)
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
        return $pdoerrorinfo[2];
    }

    public function db_connect()
    {

        global $db;
        // Could fix something about this so that we dont have to retype it again.
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

    // include css reference if css file exists for this page
    public function load_page_css()
    {

        $self = $_SERVER['PHP_SELF'];
        preg_match('/\/(\w+)\.php/', $self, $matches);

        if (isset($matches[1])) {
            $fname = '/css/' . $matches[1] . '.css';
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . $fname)) {
                echo "\r\n";
                echo '<link rel="stylesheet" href="' . $fname . '?t=' . time() . '" />';
            }
        }
    }

    // include js if it exists for this page
    public function load_page_js()
    {

        $self = $_SERVER['PHP_SELF'];
        preg_match('/\/(\w+)\.php/', $self, $matches);

        if (isset($matches[1])) {
            $fname = '/js/' . $matches[1] . '.js';
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . $fname)) {
                echo "\r\n";
                echo '<script src="' . $fname . '?t=' . time() . '"></script>';
            }
        }
    }

    public function display_html_header($argheading)
    { // Set the page up.
        ?>
  <!-- <!DOCTYPE HTML> -->
  <html>
    <head>

      <meta http-equiv="content-type" content="text/html; charset=UTF-8">

      <script src="/js/fns.js?t=<?php echo time(); ?>"></script>
      <link rel="stylesheet" href="/css/common.css" />

        <title><?= $argheading ?></title>

      <?php $this->load_page_css();?>
      <?php $this->load_page_js();?>

  <?php
}

    public function display_html_footer()
    { // Close the page off.
        ?>

    <script>
    window.open_original = window.open; // save original handler
    window.open = window_open; // redirect handler
    </script>

    </body>
  </html>

  <?php
}

    public function cs_connect()
    {

        //Obtain a connection to the database
        $csdb_username = $GLOBALS['fdlconfig']['csdb']['username'];
        $csdb_password = $GLOBALS['fdlconfig']['csdb']['password'];

        //CSPROD connection
        $csdb = $GLOBALS['fdlconfig']['csdb']['connstr'];

        $csconn = false;
        try {
            $csconn = oci_connect($csdb_username, $csdb_password, $csdb);
        } catch (Exception $e) {
            $error = $e->getMessage();
            echo 'Not connected due to ' . $error;
            exit();

        }
        if (!$csconn) {
            //If an error then display message and exit
            $e = oci_error();
            echo 'Unable to connect to CS database......exiting' . "\n";
            echo 'Error ' . htmlentities($e['message']) . "\n";
            exit();
        }
        return $csconn;
    }

    public function display_message($action)
    {

        if ($action == "wait") {
            $_SESSION[$_GET["trid"] . "message"] = 'PLEASE WAIT WHILE YOUR REQUEST IS PROCESSED';
        }

        if ($action == "clear") {
            $_SESSION[$_GET["trid"] . "message"] = '';
        }

        echo "<script language='javascript'> parent.fmeMessage.location.replace('message.php?trid=" . $_GET["trid"] . "')</script>";
    }

}

