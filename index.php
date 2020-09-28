<?php

require_once "basePage.php";
require_once "utils.php";
class Login_page extends basePage
{
    public function Display_page()
    { 
        // Body of page.

        global $p, $db;

        $id = '';
        if (isset($_GET["id"])) {
            $id = $_GET["id"];
        }
        if ($id && empty($_SESSION["mrkmsg"])) {
            $link = getSystemLink("serverprod");
            $_SESSION["mrkmsg"] = "Please use https://" . $link . "/fdlMarks/";
        } ?>

<script language="javascript">
window.onerror = blockError;

function blockError() {
    return true;
}
</script>

<link rel="shortcut icon" href="https://federation.edu.au/__data/assets/file/0019/132562/favicon.ico?v=0.0.2">

</head>

        <?php
        echo "<body onload='self.focus();document.frmLogin.txtUsername.focus()'>"; ?>

<form name="frmLogin" method="post">
    <br>
    <table align="center" width="315px" border="1" cellpadding="6" cellspacing="4" bordercolor="#0000FF" bgcolor="#FFFFFF">
        <tr>
            <td>
                <?php
                        echo '<table width="100%"><tr><td>';
                        echo '<a href="' . $_SESSION["mrksysurl"] . '"><img height="25" style="border:0" title="' . $_SESSION["mrksysinstitution"] . '" src="../image\img_logo_sml.png"></a>';
                        echo '</td><td>' . str_repeat('&nbsp;', 15) . '</td>';
                        echo '<td align="right" valign="middle">';
                        echo '<span style="font-size: 18; color: gray; font-weight: bold;">fdlMarks</span>';
                        echo '</td></tr>';
                        echo '</table>';

                        $username = '';
                        $password = '';
                        if (isset($_POST["txtUsername"])) {
                            $username = htmlspecialchars($_POST["txtUsername"], ENT_QUOTES, "UTF-8");
                            $password = htmlspecialchars($_POST["txtPassword"], ENT_QUOTES, "UTF-8");
                        } ?>

            </td>

        <tr>
            <td align="center">
        <p>Student ID: <input type="TEXT" name="txtUsername" size="18" maxlength="20"
                        value="<?php echo stripslashes($username) ?>">
        </p>

                <?php
                        echo '<p>Password:&nbsp;&nbsp;<input type="PASSWORD" name="txtPassword" size="18" maxlength="100" value="' . $password . '"></p>';
                if ($link = getSystemLink("index03")) {
                    $blinker = "";
                    echo '<br>' . $blinker . '<a href="' . $link . '">Change / Forgotten Password</a><br><br>';
                } ?>
            </td>
        </tr>

        <tr>
            <td align="center">
                <input type="submit" name="btnSubmit" value="Submit">
                <?php
                if (!empty($_SESSION["mrkmsg"])) {
                        echo "<tr><td align='center' bgcolor=yellow>";
                        echo "<span class='boldred'>";
                        echo $_SESSION["mrkmsg"];
                        echo "</span>";
                } ?>
            </td>
        </tr>
    </table>
    <br><br><br><br>
    <table align="center" width="50%" cellpadding="3" style="border: 3px #000 solid; text-align: center">
        <tr>
            <td><b> It is a criminal offence to:</b>
                <br>
                <div>1. Obtain access to data without authority</div>
                <div>2. Damage, delete, alter or insert data without permission/authority</div>
                <br><b>Confidentiality Compliance:</b>
                <br>
                <div>fdlMarks is a secured information system containing official University records. As a registered
                    user, it is your responsibility to maintain the University policy of confidentiality of information.
                    Any data that you extract from fdlMarks or access with fdlMarks for example, pages, results,
                    reports, address labels must be treated as confidential and managed accordingly. Your Student ID and
                    Passwords are unique and must not be divulged to any third party. Any breach of confidentiality will
                    be taken seriously.</div>
            </td>
        </tr>
    </table>
</form>

        <?php
    }
    public function process_form()
    { // Validate fields and if ok proceed to grades form.

        global $p, $db;

        $_SESSION["mrkloginattempts"] = 0;

        $sql_ok = $p->db_connect() or die(basename(__FILE__, '.php') . "-01: " . mysqli_error($db));
        $pdo = $p->pdo_connect() or die(basename(__FILE__, '.php') . "-02: " . mysqli_error($db));
        $pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);

        // Validate if (i.e. not empty).
        if (!isset($_POST["btnSubmit"])) { // first access
            $_SESSION["mrkmsg"] = '';
            $_SESSION["mrkloginattempts"] = 0;
            $sql_ok = $p->db_connect() or die(basename(__FILE__, '.php') . "-03: " . mysqli_error($db));

            $sql = "select *
              from `system`";

            $pdostmt = $pdo->prepare($sql);
            $pdostmt->execute() or die(basename(__FILE__, '.php') . "-04: " . $p->pdo_error($pdostmt->errorinfo()));

            $row = $pdostmt->fetch(PDO::FETCH_ASSOC);

            $_SESSION["mrksysinstitution"] = $row['sysinstitution'];
            $_SESSION["mrksysurl"] = $row['sysurl'];
            switch ($row['sysacaddivlabel']) {
            case '1':
                    $_SESSION["mrksysacaddivlabel"] = 'Faculty';
                break;
            case '2':
                    $_SESSION["mrksysacaddivlabel"] = 'School';
                break;
            case '3':
                    $_SESSION["mrksysacaddivlabel"] = 'College';
                break;
            }
            //Load subdiscipline details
            $_SESSION["mrksubdiscipline"] = array();

            $sql = "select sd.*, ad.acaddivid, ad.acaddivshortname, ad.acaddivlongname, d.disciplineid, d.disciplineshortname, d.disciplinelongname
              from subdiscipline as sd
                inner join discipline as d
                  on d.disciplineid = sd.disciplineid
                inner join acaddiv as ad
                  on ad.acaddivid = d.acaddivid
              order by ad.acaddivid, d.disciplineshortname, sd.subdisciplineshortname";

            /**
             *
             * Description for function
             *
             * @param type  $sql Description
             * @param type  $pdo Description
             *
             * @return type
             */

            function db_prepare($sql, $pdo) 
            {
                $pdostmt = $pdo->prepare($sql);
                return array($pdostmt);
            }

            $pdostmt->execute() or die(basename(__FILE__, '.php') . "-05: " . $p->pdo_error($pdostmt->errorinfo()));
            $i = 0;
            while ($row = $pdostmt->fetch(PDO::FETCH_ASSOC)) {
                $_SESSION["mrksubdiscipline"][$i]["acaddivid"] = $row["acaddivid"];
                $_SESSION["mrksubdiscipline"][$i]["acaddivshortname"] = $row["acaddivshortname"];
                $_SESSION["mrksubdiscipline"][$i]["acaddivlongname"] = $row["acaddivlongname"];
                $_SESSION["mrksubdiscipline"][$i]["disciplineid"] = $row["disciplineid"];
                $_SESSION["mrksubdiscipline"][$i]["disciplineshortname"] = $row["disciplineshortname"];
                $_SESSION["mrksubdiscipline"][$i]["disciplinelongname"] = $row["disciplinelongname"];
                $_SESSION["mrksubdiscipline"][$i]["subdisciplineid"] = $row["subdisciplineid"];
                $_SESSION["mrksubdiscipline"][$i]["subdisciplineshortname"] = $row["subdisciplineshortname"];
                $_SESSION["mrksubdiscipline"][$i]["subdisciplinelongname"] = $row["subdisciplinelongname"];
                $_SESSION["mrksubdiscipline"][$i]["createstudentplan"] = $row['createstudentplan'];
                $_SESSION["mrksubdiscipline"][$i]["accessexaminers"] = $row['accessexaminers'];
                $_SESSION["mrksubdiscipline"][$i]["assessmentweeks"] = $row['assessmentweeks'];
                $_SESSION["mrksubdiscipline"][$i]["loadnoabrule"] = $row['loadnoabrule'];
                $_SESSION["mrksubdiscipline"][$i]["minexampct"] = $row['minexampct'];
                $_SESSION["mrksubdiscipline"][$i]["maxnosamplereqdpct"] = $row['maxnosamplereqdpct'];
                $_SESSION["mrksubdiscipline"][$i]["maxassessmentdueweek"] = $row['maxassessmentdueweek'];
                $_SESSION["mrksubdiscipline"][$i]["convertmarktograde"] = $row["convertmarktograde"];
                $_SESSION["mrksubdiscipline"][$i]["freeformgraduateattribute"] = $row["freeformgraduateattribute"];

                $i++;
            }
            return false;
        }
        // Close down if too many attempts have been made to logon.
        $_SESSION["mrkloginattempts"]++;
        if ($_SESSION["mrkloginattempts"] == 3) {
            die("The number of log-in attempts allowed has been exceeded");
        }
        if (empty($_POST["txtUsername"])) {
            $_SESSION["mrkmsg"] = 'Invalid Student ID or Password';
            return false;
        }
        if (empty($_POST["txtPassword"])) {
            $_SESSION["mrkmsg"] = 'Invalid Student ID or Password';
            return false;
        }

        $usr = $_POST["txtUsername"];
        $pwd = $_POST["txtPassword"];
        $loginerror = false;

        $ds = ldap_connect("ldap-uni.federation.edu.au");

        $ldap_port = 389;
        $ldap_serv = "ldap-uni.federation.edu.au";
        $ldap_conn = ldap_connect($ldap_serv, $ldap_port);

        if ($ldap_conn) {
            $ldap_adminuser = $GLOBALS['fdlconfig']['ldap']['username'];
            $ldap_adminpass = $GLOBALS['fdlconfig']['ldap']['password'];

            $ldap_bindadmin = ldap_bind($ldap_conn, $ldap_adminuser, $ldap_adminpass);

            if ($ldap_bindadmin) {
                $base = "ou=students,dc=uni,dc=federation,dc=edu,dc=au";
                $filter = "(&(objectClass=user)(samaccountname=$usr))";
                $fields = array("distinguishedname", "memberof", "useraccountcontrol");
                $srch = ldap_search($ldap_conn, $base, $filter, $fields);

                $info = ldap_get_entries($ldap_conn, $srch);
                $dn = $info[0]['distinguishedname'];

                unset($dn['count']);
                $useraccountcontrol = $info[0]['useraccountcontrol'];
                unset($useraccountcontrol['count']);

                if (isset($dn)) {
                    if ($useraccountcontrol[0] != "514") { //514=ACCOUNTDISABLE

                        if ($useraccountcontrol[0] != "528") { //528=LOCKED

                            $ldap_binduser = ldap_connect($ldap_serv, $ldap_port);

                            if ($ldap_binduser) {

                                // allow testers to log in without valid password
                                if ($GLOBALS['fdlconfig']['testing']['auto_login']) {
                                    echo '<h1>Auto login enabled</h1>';
                                } elseif (!ldap_bind($ldap_binduser, $dn[0], $pwd)) {
                                    $loginerror = true;
                                    ldap_close($ldap_binduser);
                                }
                            }
                        }
                    }
                }
            }
        } else {
            $loginerror = true;
        }



        $usr = $_POST["txtUsername"];
        $pwd = $_POST["txtPassword"];
        $sql = "select *
            from student
            where studentid = :usr";

        $pdostmt = $pdo->prepare($sql);
        $pdostmt->execute(array(':usr'=> $usr)) OR die(basename(__FILE__, '.php')."-06: ". $p->pdo_error($pdostmt->errorinfo()));
        
        $row = $pdostmt->fetchALL(PDO::FETCH_ASSOC);
        
        $locked = $row[0]['locked'];
        $debt = $row[0]['debt'];

        if ($loginerror) {
            $_SESSION["mrkmsg"];
            return false;
        }
        if (count($row) == 0) { // No record found.
            $_SESSION["mrkmsg"];
            return false;
        }

        $locked = $row[0]['locked'];
        $debt = $row[0]['debt'];

        if ($locked == 'Y') {
            $_SESSION["mrkmsg"] = "Student ID locked - contact Academic Coordinator";
            return false;
        }
        if (strtoupper($debt) == 'Y') {
            $_SESSION["mrkmsg"] = "Results withheld - contact Academic Coordinator";
            return false;
        }

        $_SESSION["mrkaccessallowed"] = 'yes';
        $_SESSION["mrkstudentid"] = $usr;
        echo "<script language='javascript'> document.location=\"main.php\"; </script>";
    }

    public function __construct()
    {
        basePage::basePageFunction();
    }
}

// Instantiate this page
$p = new login_page();

// If form has been submitted validate fields, otherwise initialise some work fields.
$_SESSION["mrkaccessallowed"] = 'no';
$p->process_form();

// Output page.
$p->display_page();


$_SESSION["mrkmsg"] = '';
?>