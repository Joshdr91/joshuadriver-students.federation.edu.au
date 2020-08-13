<?php

include_once "basePage.php";
include_once "../requisiteutils.php";

class unitlookup_page extends basePage
{

    public function display_page()
    { // Body of page.

        global $p, $db, $message;

        // If the btn cancel is something else other than null\
        // Using <Script language = "Javascript"> greatly depreciated
        // If the attribute is present, its value must be a valid MIME type.
        // Source goes to: https://www.quora.com/What-is-the-difference-between-script-language-JavaScript-and-script-type-text-JavaScript-in-HTML-file?share=1
        if (isset($_POST["btnCancel"])) {
            echo "<script type="text/javascript"> this.close(); </script>";
        }
        ?>


    <script type = "text/Javascript">

      window.onerror = blockError;

      function blockError(){
        return true;
      }
    </script>

    </head>

    <body topmargin="1">

      <form name="frmunitlookup" method="post">

        <style>
          /* Font design */
          span.small {font-size: 11}
          span.boldred {color:red; font-weight:bold}
          span.red {color:red}

          /* Primary Font Style */ 
          td {font-family: Arial; font-size: 14}
        </style>


        <?php

        $unit = '';
        if ($_GET["unitid"]) {
            $unit = $_GET["unitid"];
        }
        if ($_GET["termid"]) {
            $termid = $_GET["termid"];
        }
        if ($_GET["coursetype"]) {
            $coursetype = $_GET["coursetype"];
        }
        if ($_GET["unitid"]) {

            $sql_ok = $p->db_connect() or die(basename(__FILE__, '.php') . "-01: " . mysqli_error($db));

            $sql = "select *
                    from unit
                    where unitid = '$unit'";

            $sql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-02: " . mysqli_error($db));

            $found = true;
            if (mysqli_num_rows($sql_ok) > 0) {
                $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__, '.php') . "-03: " . mysqli_error($db));
            } else {
                $found = false;
            }
            $name = stripslashes($row["name"]);
            $level = $row["level"];
            $creditpoint = $row["creditpoint"];
            $gradingbasis = $row["gradingbasis"];

            $requisitefinal = getRequisite($unit, $roundbracket = false, $csreq = false, $ignoreubsas = false, $reqeffectivetermid = '');

        }
        echo '<br><table align="center"  bgcolor="#e6e6fa" width="80%" border="1" bordercolor="#0000FF" cellpadding="3" cellspacing="0">';
        echo '<tr>';
        echo '<td align="center"><br>';

        echo '<input type="submit" name="btnCancel" value="Cancel"><br><br>';

        if ($found) {
            echo '<tr><td align="center"><br>' . $unit . ' ' . $name . '<br><br>';
            echo '</td></tr>';
            $sql = "select uod.content
                    from unitoutline as uo
                      inner join unitoutlinedetail as uod
                        on uod.unitoutlinekey = uo.unitoutlinekey
                    where uo.unitid = '$unit'
                    and concat(uo.effectivetermid,uo.stamptime) =
                        (select max(concat(uo1.effectivetermid,uo1.stamptime))
                         from unitoutline as uo1
                         where uo1.unitid = uo.unitid
                         and uo1.effectivetermid <= '$termid'
                         and uo1.`status` = 'P')
                    and uo.`status` = 'P'
                    and uod.uotype = 'summ'";

            $unitsql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-04: " . mysqli_error($db));

            if (mysqli_num_rows($unitsql_ok) > 0) {
                $unitrow = mysqli_fetch_array($unitsql_ok) or die(basename(__FILE__, '.php') . "-05: " . mysqli_error($db));

                $unitsummary = stripslashes($unitrow["content"]);
            }
            echo '<tr><td width="*" bgcolor="#C0C0C0"><b>Description</b></td>';
            echo '</td></tr>';
            echo '<tr><td><br>' . strip_tags($unitsummary) . '<br><br>';
            echo '</td></tr>';
            echo '<tr><td width="*" bgcolor="#C0C0C0"><b>[ ] = Pre-requisite, { } = Co-requisite, < > = Exclusion</b></td>';
            echo '</td></tr>';
            echo '<tr><td><br>' . $requisitefinal . '<br><br>';

            //get level for later use. try unitdetail first. if not there try unit.
            $sql = "select uod.content, uod.content_1, uod.content_2
                    from unitoutline as uo
                      inner join unitoutlinedetail as uod
                        on uod.unitoutlinekey = uo.unitoutlinekey
                    where uo.unitid = '$unit'
                    and concat(uo.effectivetermid,uo.stamptime) =
                        (select max(concat(uo1.effectivetermid,uo1.stamptime))
                         from unitoutline as uo1
                         where uo1.unitid = uo.unitid
                         and uo1.effectivetermid <= '$termid'
                         and uo1.`status` = 'P')
                    and uo.`status` = 'P'
                    and uod.uotype = 'prglvl'";

            $unitsql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-06: " . mysqli_error($db));

            if (mysqli_num_rows($unitsql_ok) > 0) {
                $unitrow = mysqli_fetch_array($unitsql_ok) or die(basename(__FILE__, '.php') . "-07: " . mysqli_error($db));

                if (!empty($unitrow["content"])) {
                    $temp = explode('|', $unitrow["content"]);
                    if (($coursetype == 'U' && $temp[2]) || ($coursetype == 'P' && $temp[4])) { //2=bachelor, 4=masters
                        $level = 'I'; //introductory
                    }
                }
                if (!empty($unitrow["content_1"])) {
                    $temp = explode('|', $unitrow["content_1"]);
                    if (($coursetype == 'U' && $temp[2]) || ($coursetype == 'P' && $temp[4])) { //2=bachelor, 4=masters
                        $level = 'M'; //intermediate
                    }
                }
                if (!empty($unitrow["content_2"])) {
                    $temp = explode('|', $unitrow["content_2"]);
                    if (($coursetype == 'U' && $temp[2]) || ($coursetype == 'P' && $temp[4])) { //2=bachelor, 4=masters
                        $level = 'A'; //advanced
                    }
                }
            }

            //If the level is null, then present as N/A
            switch ($level) {
                case 'I':
                    $leveldisplay = 'Introductory';
                    break;
                case 'M':
                    $leveldisplay = 'Intermediate';
                    break;
                case 'A':
                    $leveldisplay = 'Advanced';
                    break;
                default:
                    $leveldisplay = 'N/A'
                    break    
            }
            echo '</td></tr>';
            echo '<tr><td width="*" bgcolor="#C0C0C0"><b>Level</b></td>';
            echo '</td></tr>';
            echo '<tr><td><br>' . $leveldisplay . '<br><br>';
            echo '</td></tr>';
            echo '<tr><td width="*" bgcolor="#C0C0C0"><b>Credit Points</b></td>';
            echo '</td></tr>';
            echo '<tr><td><br>' . $creditpoint . '<br><br>';

            // Identifies any missing grades.
            switch ($gradingbasis) {
                case 'G':
                    $gradingbasisdisplay = 'Graded (HD, D, C, etc.)';
                    break;
                case 'P':
                    $gradingbasisdisplay = 'Research Pass / Not Pass (O, P, F etc.)';
                    break;
                case 'S':
                    $gradingbasisdisplay = 'Ungraded (S, UN, etc.)';
                    break;
                default:
                    $gradingbasisdisplay = 'Not Available (N/A)'
            }
            echo '</td></tr>';

            echo '<tr><td width="*" bgcolor="#C0C0C0"><b>Grading Basis</b></td>';
            echo '</td></tr>';
            echo '<tr><td><br>' . $gradingbasisdisplay . '<br><br>';

        }
            echo '</td></tr></table>';

        ?>

      </form>

    <?php

    }
    public function __construct()
    {
        basePage::basePageFunction();
    }
}
// Instantiate this page
$p = new unitlookup_page();

if (empty($_SESSION["mrkaccessallowed"])) {
    exit;
}
// Output page.
$heading = "fdlMarks --> " . $_SESSION["mrkysinstitution"] . " --> Course Lookup";
$p->display_html_header($heading);
$p->display_page();
$p->display_html_footer();

?>
