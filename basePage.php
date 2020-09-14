/**Class must start with a capital letter**/
/** comments must be less than 85 characters */
/** Camel caps format */
<?php
/**
 * Php version 7.2.10
 * 
 * @category Login_Credentials
 * @package  Pagelevel_Package
 * @author   Federation University <example@email.com.au>
 * @license  MIT https://federation.edu.au/
 * @version  CVS: <cvs_id>
 * @link     http://url.com
 * 
 * Require config for user access
 */
require_once 'host/config.php';

/**
 * Main basepage file for the theme
 * 
 * @category Login_Credentials
 * @package  Pagelevel_Package
 * @author   Federation University <example@email.com.au>
 * @license  MIT https://federation.edu.au/
 * @version  Release: 2.1.2
 * @link     https://federation.edu.au/
 * 
 * Basepagefunction  class
 * Shows the Htmlpage 
 */
class BasePage
{
    /**
     * Function Basepagefunction
     *
     * @return void
     */
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

    /**
     * Undocumented function
     *
     * @param [Var] $argAdminLevel Level of admin access
     * 
     * @return void
     */
    public function adminAccessAllowed($argAdminLevel)    
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

    /**
     * Undocumented function
     *
     * @param [Char] $argAdminLevel  Level of admin access
     * @param [Char] $argUserType    User type
     * @param [Int]  $argExtraAccess Extra Access type
     * 
     * @return void
     */
    public function accessDenied($argAdminLevel, $argUserType, $argExtraAccess)
    { 
        // Check for valid page. Admin can be either 'A' or 'M'

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

                if ($_SESSION["mrkusertype"] == 'Z' && $argUserTypeChar == 'Z') { 
                    return false;
                }
            }
        }

        if ($argExtraAccess) {
            return false;
        }

        return true;
    }

    /**
     * Undocumented function
     *
     * @param [Int]     $arguserid   Identification
     * @param [varchar] $argmenuitem Menu items
     * 
     * @return void N/A
     */
    public function extraAccess($arguserid, $argmenuitem)
    {
        reset($_SESSION[$_GET["trid"] . "useraccess"]);
        while (list($menukey, $menuvalue) = ($_SESSION[$_GET["trid"] . "useraccess"])) {
            $menurow = $menuvalue;

            if ($menurow["userid"] == $arguserid && $menurow["menuitem"] == $argmenuitem) {
                return true;
            }
        }

        return false;
    }

    /**
     * Pd_oconnect function
     *
     * @return void
     */
    public function pdoConnect()
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
     * @param [Char] $pdoerrorinfo Description of error
     * 
     * @return void
     */
    public function pdoError($pdoerrorinfo)
    {
        return $pdoerrorinfo[2];
    }

    /**
     * Function db_connect
     *
     * @return void
     */
    public function db_Connect()
    {
        global $db;

        $server = $GLOBALS['fdlconfig']['mysql']['server'];
        $dbname = $GLOBALS['fdlconfig']['mysql']['dbname'];
        $username = $GLOBALS['fdlconfig']['mysql']['username'];
        $password = $GLOBALS['fdlconfig']['mysql']['password'];

        $db = mysqli_connect($server, $username, $password, $dbname);

        if (mysqli_Connect_Error()) {
            return false;
        }

        mysqli_set_charset($db, 'utf8');

        return $db;
    }

    /**
     * Undocumented function
     * Include css reference if css file exists for this page
     * 
     * @return void
     */
    public function loadPageCss()
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

    /**
     * Function loadpagejs
     * Include js if it exists for this page
     * 
     * @return void
     */
    public function loadPageJs()
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

    /**
     * Function Display_Html_Header
     *
     * @param [VarChar] $argheading Html base
     * 
     * @return void
     */
    public function displayHtmlHeader($argheading)
    { 
        // Set the page up.

        ?>
<!-- <!DOCTYPE HTML> -->
<html>

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">

    <script src="/js/fns.js?t=<?php echo time(); ?>"></script>
    <link rel="stylesheet" href="/css/common.css" />

    <title><?php $argheading ?></title>

        <?php $this->loadpagecss();
            $this->loadpagejs(); ?>

        <?php
    }
    
    /**
     * Function Displayhtmlfooter
     * 
     * @return void
     */
    
    public function displayHtmlFooter()
    { 
        // Close the page off.
        ?>

    <script>
    window.open_original = window.open; // save original handler
    window.open = window_open; // redirect handler
    </script>

    </body>
    }
</head>

</html>

        <?php
        /**
         * Function Initialisation
         *
         * @return void
         */
        
        /**
         * Function cs_connect
         *
         * @param[var] $csdb_password  Password storage
         * @param[var] $csdb_username  Username storage
         * @param[var] $csdb           Database 
         * 
         * @return mixed
         */
        function cs_connect($csdb_password, $csdb_username, $csdb)
        {
            try {
                $csconn = oci_connect($csdb_username, $csdb_password, $csdb);
            } catch (Exception $e) {
                $error = $e->getMessage();
                echo 'Not connected due to ' . $error;
                exit();
            }
            if (!$csconn) {
                $e = oci_error();
                echo 'Unable to connect to CS database' . "\n";
                echo 'Error ' . htmlentities($e['message']) . "\n";
                exit();
            }
            return $csconn;
        }

        /**
         * Function Display_message
         *
         * @param [varchar] $action Wait, clear messages
         * 
         * @return void
         */
    }
    // 304
    /**
     * Function Initialisation
     *
     * @return void
     */
    function Initialization()
    {
        //Test
        //Obtain a connection to the database
        $csdb_username = $GLOBALS['fdlconfig']['csdb']['username'];
        $csdb_password = $GLOBALS['fdlconfig']['csdb']['password'];

        //Oracle CS Production connection
        $csdb = $GLOBALS['fdlconfig']['csdb']['connstr'];
        $csconn = false;
        //Test
        return array($csdb_username, $csdb_password, $csdb, $csconn);
    }

    function display_Message($action)
    {
        if ($action == "wait") {
                $_SESSION[$_GET["trid"] . "message"] = 'PLEASE WAIT';
        } 

        if ($action == "clear") {
                $_SESSION[$_GET["trid"] . "message"] = '';
        }

            echo "<script language='javascript'> parent.fmeMessage.location.replace('message.php?trid=" . $_GET["trid"] . "')</script>";
    }
}