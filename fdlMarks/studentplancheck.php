<?php

include_once "basePage.php";
include_once "utils.php";
include_once "../requisiteutils.php";
include_once "../sort.php";

class studentplancheck_page extends basePage
{

    public function testRange($nbr, $min, $max)
    {
        if ($nbr >= $min && $nbr <= $max) {
            return true;
        } else {
            return false;
        }
    }
    public function display_page()
    { // Body of page.

        global $p, $db, $message;

     //Install of Javascript (Allows data from student extract into table)
    echo '<script language="javascript">

    window.onerror = blockError;

    function blockError()
    {
      return true;
    }

    sWidth = screen.width;
    sHeight = screen.height;
    sLeft = (sWidth - (sWidth *.9)) /2;
    sTop = (sHeight - (sHeight *.9)) /2;

    function courseplanelectivelookup(argcourseplanid, argusedunitid, argfieldname, argformname, argstudentplanid, argmethodnbr, argpreapproved){
        newWindow=window.open("courseplanelectivelookup.php?courseplanid=" + argcourseplanid + "&usedunitid=" + argusedunitid + "&fieldname=" + argfieldname + "&formname=" + argformname + "&studentplanid=" + argstudentplanid + "&methodnbr=" + argmethodnbr + "&preapproved=" + argpreapproved,"fdlgcourseplanelectivelookup","resizable=yes, scrollbars=yes, menubar=yes, width=" + sWidth *.9  + ", height=" + sHeight *.8 + ", top=" + sTop + ", left=" + sLeft);
        newWindow.focus();
      }
    function unitlookup(argunitid, argtermid, argcoursetype){

      newWindow2=window.open("unitlookup.php?unitid=" + argunitid + "&termid=" + argtermid + "&coursetype=" + argcoursetype,"fdlgunitlookup","location=no, menubar=yes, scrollbars=yes,  resizable=yes, width=" + sWidth *.9  + ", height=" + sHeight *.48 + ", top=" + sTop * 6 + ", left=" + sLeft + "");
      newWindow2.focus();

    }
  	</script>

    </head>';

        echo '<body topmargin="1">';

        echo '<form name="frmstudentplancheck" method="post">

    <style type="text/css" media="screen">
      span.big {font-size: 16}
      span.small {font-size: 12}
      span.tiny {font-size: 10}
      span.tinyred { font-size: 10; color:red}
      span.boldred {color:red; font-weight:bold}
      span.red {color:red}
      span.green {color:green}
      span.purple {color:purple}
      span.maroon {color:maroon}
      td {font-family: Arial; font-size: 14}
      DIV.printonly {display: none;}
    </style>

    <style  type="text/css" media="print">
      span.big {font-size: 16}
      span.small {font-size: 12}
      span.tiny {font-size: 10}
      span.tinyred { font-size: 10; color:red}
      span.boldred {color:red; font-weight:bold}
      span.red {color:red}
      span.green {color:green}
      span.purple {color:purple}
      span.maroon {color:maroon}
      td {font-family: Arial; font-size: 14}
      DIV.printonly {font-weight: bold;}
    </style>';

        $locationid = $_SESSION["studentplan"]["locationid"];
        $strandid = $_SESSION["studentplan"]["strandid"];

        // If the subject is either Approved - Failed else if none of them, then its still pending. 
        $status = $_SESSION["studentplan"]["status"];
        switch ($_SESSION["studentplan"]["status"]) {
            case 'A':
                $status = 'Approved';
                break;
            case 'C':
                $status = 'Completed';
                break;
            case 'D':
                $status = 'Deleted';
                break;
            case 'U':
                $status = 'Discontinued';
                break;
            case 'G':
                $status = 'Eligible to Graduate';
                break;
            case 'Y':
                $status = 'Early Completion';
                break;
            case 'Y':
                $status = 'Early Exit';
                break;
            case 'E':
                $status = 'Excluded';
                break;
            case 'F':
                $status = 'Failed to enrol';
                break;
            case 'L':
                $status = 'Leave';
                break;
            case 'S':
                $status = 'Suspended';
                break;
            case 'T':
                $status = 'Transferred';
                break;
            case 'W':
                $status = 'Withdrawn';
                break;
            default:
                $status = 'Pending';
            break;
        }
        echo '<div class="printonly"><table align="center" width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td align="center" style="font-weight: bold; font-size: 40;">This is an unofficial study plan</td></tr></table></div>';


        // Save delete or cancel any kinds of electives chosen by the user
        echo '<table align="center" bgcolor="#e6e6fa" width="100%" border="1" bordercolor="#0000FF" cellpadding="4" cellspacing="0">';
        echo '<tr>';
        echo '<td align="center" colspan="2"><br>';
        echo '&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="btnCheck" value="Check">';
        echo '&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="btnSave" value="Save">';
        echo '&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="btnCancel" value="Cancel">';
        echo '<br><br><table align="center" width="51%" border="0" cellpadding="4" cellspacing="0"><tr><td style="text-align: justify;"><span class="maroon">Press the Check button to ensure your study plan is still correct and has no requisite issues caused by the Study Courses you have selected. If you do have a requisite issue you may be able to resolve it by changing the Study Term. A Study Terms legend, Colours legend, a link to Course Outlines, and your programs Academic Progress Rules can be found at the bottom of the page. The changes you make here will not affect your official study plan.</span></td></tr></table>';
        echo '<input type="hidden" name="hidFirstTime">';
        echo '<br></td></tr>';

        //If the message 
        if ($message) {
            echo "<tr><td align='center' colspan='2' bgcolor=yellow>";
            echo "<span class='boldred'>";
            echo $message;
            echo "</span>";
            echo '</td></tr>';
        }


        echo '<tr>';
        $lastname = strtoupper($_SESSION["studentplan"]["lastname"]);
        $othernames = $_SESSION["studentplan"]["othernames"];
        //Present lastname and first name student details
        echo '<td width="10%"><b>Student:</b></td><td>' . $_SESSION["studentplan"]["studentid"] . ' - ' . $othernames . ' ' . $lastname '</td></tr>';
        $locationid = $_SESSION["studentplan"]["locationid"];
        echo '</td></tr><tr><td><b>Location: </b></td>' ;
        echo '<td>' . $locationid '</td>';
        echo "</tr>";


        $planlocationid = $_SESSION["studentplan"]["planlocationid"];

        echo '</td></tr><tr><td><b>Program Plan: </b></td>';
        echo '<td><span title="' . $_SESSION["studentplan"]["strandid"] . '">' . $_SESSION["studentplan"]["strandname"];
        echo '</td></tr></td>';
        echo '<tr><td><b>Status:</b>';
        echo '<td>' . $status'</td></tr>';

        echo '</table>';

        // Process unit items
        echo '<table align="center" width="100%" border="1" bordercolor="#0000FF" cellpadding="4" cellspacing="0">';

        //Connect session then apply to Term
        $_SESSION["studysequencearray"] = array();
        if (empty($_POST["btnSequence"]) || $_POST["btnSequence"] == 'Change to Plan Term Sequence') {
            $buttonplanstudysequence = '<input type="submit" name="btnSequence" value="Change to Study Term Sequence">';
            $planstudysequence = 'Plan Term Sequence';
        } else {
            $buttonplanstudysequence = '<input type="submit" name="btnSequence" value="Change to Plan Term Sequence">';
            $planstudysequence = 'Study Term Sequence';
        }
        echo '<tr>';
        echo '<td colspan="4" bgcolor="#C0C0C0">' . $buttonplanstudysequence . '</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td align="center" width="10%" bgcolor="#C0C0C0"><b>' . $planstudysequence . '</b></td>';
        if ($planstudysequence == 'Plan Term Sequence') {
            echo '<td align="center" width="*" bgcolor="#C0C0C0"><b>Plan Course</b><br><span class="small">[ ] = Pre-requisite, { } = Co-requisite, < > = Exclusion</span></td>';
            echo '<td align="center" width="12%" bgcolor="#C0C0C0"><b>Study Course</b></td>';
            echo '<td align="center" width="11%" bgcolor="#C0C0C0"><b>Study Term</b></td>';
        } else {
            echo '<td align="center" width="*" bgcolor="#C0C0C0"><b>Course</b></td>';
            echo '<td align="center" width="12%" bgcolor="#C0C0C0"><b>Study Term</b></td>';
            echo '<td align="center" width="11%" bgcolor="#C0C0C0"><b>Credit Point</b></td>';
            echo '</tr>';
        }

        $previousterm = '';
        $bgcolor = '';
        $bgwaivecolor = '';
        $bgunitcolor = '';
        $tabindex1 = 1000;
        $tabindex2 = 2000;
        $tabindex3 = 3000;
        $tabindex4 = 4000;

        $legendtermarray = array();

        //build complete list of used unitids
        reset($_SESSION["studentplanunit"]);
        $spidx = 0;
        $unitidlist = '';
        foreach ($_SESSION["studentplanunit"] as $key => $value) {

            $spurow = $value;

            $pos = strpos($spurow["unit"], 'SPECIALISATION') !== false;
            $pos1 = strpos($spurow["unit"], 'SPECIALIZATION') !== false;
            $pos2 = strpos($spurow["unit"], 'APPROVED LIST') !== false;
            if (($pos != false || $pos1 != false || $pos2 != false) && $spurow["unitid"] != 'CREDIT') {
                $unitidlist = $unitidlist . $spurow["unitid"] . '|';
            } else {
                if ($spurow["unitid"] != 'CREDIT' && $spurow["unitid"] != 'ELECTIVE' && $spurow["unitid"] != 'UNKNOWN' && $spurow["unitid"] != 'MERGED') {
                    $unitidlist = $unitidlist . $spurow["unitid"] . '!|';
                }
            }
        }

        $unitarray = array();
        $sql = "select *
                from unit
                where ifnull(hide,'') = ''";

        $unitsql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-01: " . mysqli_error($db));

        for ($unti = 0; $unti < mysqli_num_rows($unitsql_ok); $unti++) {
            $unitrow = mysqli_fetch_array($unitsql_ok) or die(basename(__FILE__, '.php') . "-02: " . mysqli_error($db));

            $unitarray[$unti]['unitid'] = $unitrow['unitid'];
            $unitarray[$unti]['name'] = stripslashes($unitrow['name']);

        }

        $termarray = array();
        $sql = "select *
            from term
            where ifnull(resultcheck,'') = ''
            order by termid desc";

        $termsql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-03: " . mysqli_error($db));
        for ($trmi = 0; $trmi < mysqli_num_rows($termsql_ok); $trmi++) {
            $termrow = mysqli_fetch_array($termsql_ok) or die(basename(__FILE__, '.php') . "-04: " . mysqli_error($db));
            $termarray[$trmi]['termid'] = $termrow['termid'];
        }

        reset($_SESSION["studentplanunit"]);
        while (list($key, $value) = each($_SESSION["studentplanunit"])) {
            $row = $value;


            // If there is a study plan add a new Table row.
            if ($planstudysequence == 'Plan Term Sequence') {
                echo '<tr>';
            }
            $lineid = $row["lineid"];
            $term = $row["term"];
            $unit = $row["unit"];

            $sql = "select *
                    from unit
                    where unitid = '$unit'";

            $unitsql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-05: " . mysqli_error($db));

            $requisitetemp = '';


            // Find the courses requisite for student. 
            if (mysqli_num_rows($unitsql_ok)) {
                $unitrow = mysqli_fetch_array($unitsql_ok) or die(basename(__FILE__, '.php') . "-06: " . mysqli_error($db));
                $unitname = $unit . ' ' . $unitrow["name"];
                $noncourserequisite = $unitrow["requisite"];
            } else {
                $pos = strpos($unit, 'SPECIALISATION') !== false;
                $pos1 = strpos($unit, 'SPECIALIZATION') !== false;
                $pos2 = strpos($unit, 'APPROVED LIST') !== false;

                $courseplanid = $_SESSION["studentplan"]["courseplanid"];
                $sql = "select *
                from courseplanelective
                where courseplanid = '$courseplanid'";

                $tmpsql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-07: " . mysqli_error($db));

                if (mysqli_num_rows($tmpsql_ok) > 0 && ($pos !== false || $pos1 !== false || $pos2 !== false || $row["electivelookup"]) && empty($row["grade"])) {
                    if ($pos || $pos1) {
                        $temp = explode('ATION ', strtoupper($row["unit"]));
                        $nbr = substr($temp[1], 0, 1);
                    }
                    if ($pos2) 
                    {
                        $temparray = explode('APPROVED LIST', $unit);
                        $temparray[1] = trim($temparray[1]);
                        $preapproved = substr($temparray[1], 0, 1);
                        $unitname = '<a href="javascript:courseplanelectivelookup(\'' . $_SESSION["studentplan"]["courseplanid"] . '\',\'' . $unitidlist . '\',\'optUnitid' . $key . '\',\'frmstudentplancheck\',\'' . $_SESSION["studentplan"]["studentplanid"] . '\',\'' . $nbr . '\',\'' . $preapproved . '\')">' . $unit . '</a>';
                    } 
                    else {
                        if ($row["electivelookup"]) {
                            $unitname = '<a href="javascript:courseplanelectivelookup(\'' . $_SESSION["studentplan"]["courseplanid"] . '\',\'' . $unitidlist . '\',\'optUnitid' . $key . '\',\'frmstudentplancheck\',\'' . $_SESSION["studentplan"]["studentplanid"] . '\',\'' . $nbr . '\',\'L\')">' . $unit . '</a>';
                        } 
                        else {
                            $unitname = '<a href="javascript:courseplanelectivelookup(\'' . $_SESSION["studentplan"]["courseplanid"] . '\',\'' . $unitidlist . '\',\'optUnitid' . $key . '\',\'frmstudentplancheck\',\'' . $_SESSION["studentplan"]["studentplanid"] . '\',\'' . $nbr . '\',\'\')">' . $unit . '</a>';
                        }
                    }
                } 

                else {
                    $unitname = $unit;
                }

                if ($row["unit"]) {
                    $unit = $row["unitid"];
                    $sql = "select *
                  from unit
                  where unitid = '$unit'";

                    $unitsql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-08: " . mysqli_error($db));

                    if (mysqli_num_rows($unitsql_ok)) {
                        $unitrow = mysqli_fetch_array($unitsql_ok) or die(basename(__FILE__, '.php') . "-09: " . mysqli_error($db));
                        $noncourserequisite = $unitrow["requisite"];

                        $requisitetemp = $unit . ' ' . $unitrow["name"] . '&nbsp;&nbsp;&nbsp;';
                    }
                }
            }
            $requisite = $requisitetemp . getrequisite($unit, $roundbracket = false, $csreq = false, $ignoreubsas = false, $reqeffectivetermid = '');

            $tabindex1++;
            $tabindex2++;
            $tabindex3++;
            $tabindex4++;

            $hidden = '<input type="hidden" name="txtHidden' . $key . '">';

            $unitid = '';
            $unitidname = '';
            $temptermid = $row["termid"];

            $unitid = $row["unitid"];
            if ((!empty($row["grade"]) && !empty($row["resultcheck"])) || $row["unitid"] == 'CREDIT') {
                $unitidname = $row["unitid"];
            } else {
                if (empty($row["unitid"]) && strlen($row["unit"]) < 11 && $row["unit"] != 'ELECTIVE' && $row["unit"] != 'UNKNOWN') {
                    $unitid = $row["unit"];
                } else {
                    $unitid = $row["unitid"];
                }
                $tempname = "optUnitid" . $key;
                $unitOptions = '<select name="' . $tempname . '" onchange="javascript:document.forms[0].submit()">';
                $unitOptions = $unitOptions . "<option value=''></option>";

                reset($unitarray);
                while (list($key, $value) = each($unitarray)) {
                    $unitrow = $value;

                    $temp = $unitrow['unitid'];
                    $temp1 = $unitrow['name'];
                    if ($temp == $unitid) {
                        $unitOptions = $unitOptions . "\n<option selected title='$temp1' value='$temp'>$temp</option>";
                    } else {
                        $unitOptions = $unitOptions . "\n<option title='$temp1' value='$temp'>$temp</option>";
                    }
                }
                $unitOptions = $unitOptions . '</select>';

                $coursetype = $_SESSION["studentplan"]["coursetype"];

                $unitidname = $unitOptions . '&nbsp;&nbsp;<a title="Lookup course details" href="javascript:unitlookup(\'' . $unitid . '\',\'' . $temptermid . '\',\'' . $coursetype . '\')" ><span class="big">?</span></a>';
            }
            $termid = '';
            if (isset($row["unitid"]) && $row["unitid"] == 'CREDIT') {
                $termidname = '&nbsp;';
            } else {
                $termidname = '&nbsp;';
                if (!empty($row["grade"]) && !empty($row["resultcheck"])) {
                    $termidname = $row["termid"];
                } else {
                    $termid = '';
                    if (isset($row["termid"])) {
                        $termid = $row["termid"];
                    }
                    $tempname = "optTermid" . $key;
                    $termOptions = '<select name="' . $tempname . '">';
                    $termOptions = $termOptions . "<option value=''></option>";
                    reset($termarray);
                    while (list($key, $value) = each($termarray)) {
                        $termrow = $value;

                        $temp = $termrow['termid'];
                        if ($temp == $termid) {
                            $termOptions = $termOptions . "\n<option selected value='$temp'>$temp</option>";
                        } else {
                            $termOptions = $termOptions . "\n<option value='$temp'>$temp</option>";
                        }
                    }
                    $termOptions = $termOptions . '</select>';
                    $termidname = $termOptions;
                }
            }

            // check if in term legend array If it doesn't load.
            if (!empty($row["termid"])) {

                reset($legendtermarray);
                $termi = 0;
                $termfound = false;
                while (list($termkey, $termvalue) = each($legendtermarray)) {
                    $termrow = $termvalue;

                    if ($termrow["termid"] == $row["termid"]) {
                        $termfound = true;
                        $legendtermarray[$termkey]["count"]++;
                    }
                    $termi++;
                }
                if (!$termfound) {
                    $termi++;
                    $legendtermarray[$termi]["termid"] = $row["termid"];
                    $legendtermarray[$termi]["count"] = 1;
                }
            }
            if ($term != $previousterm) {
                $previousterm = $term;
                if ($bgcolor == ' bgcolor="#FFFFFF"') {
                    $bgcolor = 'bgcolor="#ffff99"';
                    $bgunitcolor = 'bgcolor="#ffff99"';
                    $bgtermcolor = 'bgcolor="#ffff99"';
                } else {
                    $bgcolor = 'bgcolor="#FFFFFF"';
                    $bgunitcolor = 'bgcolor="#FFFFFF"';
                    $bgtermcolor = 'bgcolor="#FFFFFF"';
                }
            }
            //Check if unit running at partner. if not highlight.
            $uufound = false;
            $unitfound = true;
            $temptermid = $row["termid"];

            $sql = "select count(*) as usercount
              from unituser
              where locationid = '$locationid'
              and termid = '$temptermid'";

            $uusql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-10: " . mysqli_error($db));

            if (mysqli_num_rows($uusql_ok) > 0) {
                $uurow = mysqli_fetch_array($uusql_ok) or die(basename(__FILE__, '.php') . "-11: " . mysqli_error($db));

                if ($uurow["usercount"] > 0) {
                    $uufound = true;
                }
            }
            
            if ($uufound) { 

                //if found one (anyone) means term has been loaded at location so now look for unit
                $tempunitid = $row["unitid"];

                $sql = "select count(*) as unitcount
                from unituser
                where locationid = '$locationid'
                and termid = '$temptermid'
                and unitid = '$tempunitid'
                and `type` in ('C','U','O')";

                $uusql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-12: " . mysqli_error($db));

                if (mysqli_num_rows($uusql_ok) > 0) {
                    $uurow = mysqli_fetch_array($uusql_ok) or die(basename(__FILE__, '.php') . "-13: " . mysqli_error($db));

                    if ($uurow["unitcount"] == 0) {
                        $unitfound = false;
                    }
                }
                if ($row["unitid"] && $row["termid"] && !$unitfound && empty($row["bgunitcolor"])) {
                    $bgunitcolor = ' bgcolor="#808000"';
                }
            }
            //Check to see if we need to highlight failing units and if unitid incomplete
            $enrolcheck = false;
            $enrolchecktermid = '';
            if ($row["termid"]) {

                if (!empty($row["enrolcheck"])) {
                    $enrolcheck = true;
                    $enrolchecktermid = $row["termid"];
                }
                if ($row["enrolcheck"] && empty($row["unitid"])) {
                    $bgunitcolor = ' bgcolor="#FF0000"';
                }
                if ($row["resultcheck"] && empty($row["grade"])) {
                    $bgtermcolor = ' bgcolor="#FF0000"';
                } else {
                    $bgtermcolor = $bgcolor;
                }
            }
            if ($row["unitid"] == 'CREDIT') {
                $bgunitcolor = ' bgcolor="#00FF00"';
            }
            if ($enrolcheck && (strlen($row["unit"]) < 11 && $row["unit"] != 'ELECTIVE' && $row["unit"] != 'UNKNOWN' && $row["unitid"] != 'CREDIT' && !empty($row["unitid"]) && $row["unitid"] != $row["unit"])) {

                $tempunitid = $row["unit"];

                if ($tempunitid) {

                    $sql = "select *
                  from csunit
                  where unitid = '$tempunitid'";

                    $csunitsql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-14: " . mysqli_error($db));

                    if (mysqli_num_rows($csunitsql_ok) > 0) {
                        $csunitrow = mysqli_fetch_array($csunitsql_ok) or die(basename(__FILE__, '.php') . "-15: " . mysqli_error($db));
                        if ($row["unitid"] != $csunitrow["csunitid"] && empty($row["bgunitcolor"])) {
                            $bgunitcolor = ' bgcolor="#ff00ff"';
                        }
                    } elseif (empty($row["bgunitcolor"])) {
                        $bgunitcolor = ' bgcolor="#ff00ff"';
                    }
                }
            }
            //Check if right level
            if (strlen($row["unitid"]) > 5) {
                $subject = substr($row["unitid"], 0, 5);
                $catalogue = substr($row["unitid"], 5, 4);
            } else {
                $subject = substr($row["unitid"], 0, 2);
                $catalogue = substr($row["unitid"], 2, 3);
            }
            if ($row["unitid"] && !$mixedlevel && $row["termid"] > '2008/9') {
                if (empty($row["bgunitcolor"]) && (($_SESSION["studentplan"]["coursetype"] == 'P' && $catalogue < 5000) || (($_SESSION["studentplan"]["coursetype"] == 'T' || $_SESSION["studentplan"]["coursetype"] == 'U') && $catalogue > 3999))) {
                    $bgunitcolor = ' bgcolor="#ffff00"';
                }
            }
            //Check if enough units per term
            $tempstudentplanunit = $_SESSION["studentplanunit"];
            reset($tempstudentplanunit);

            $count = 0;

            while (list($akey, $avalue) = each($tempstudentplanunit)) {
                $tempstudentplanunitrow = $avalue;
                // Add count for every term
                if ($tempstudentplanunitrow["termid"] == $row["termid"]) {
                    $count++;
                }
            }
            $tempstudentplanunit = '';

            //If we have less than term load units per term (except for CREDIT or previous terms) highlight
            $termload = $_SESSION["studentplan"]["termload"];
            if ($termload > 0 && $count != $termload && $row["termid"] && $row["unitid"] != 'CREDIT' && $bgtermcolor == $bgcolor) {
                $bgtermcolor = ' bgcolor="#00FFFF"';
            } elseif ($row["unitid"] == 'CREDIT' || !$row["termid"]) {
                $bgtermcolor = $bgcolor;
            }
            if (substr($message, 0, 21) == 'Invalid Study Course - ' && $row["unitid"] == substr($message, 21, 5)) {
                $bgunitcolor = ' bgcolor="#FF0000"';
            }
            if ($planstudysequence == 'Study Term Sequence') {

                $_SESSION["studysequencearray"][$key]["creditpoint"] = $row["creditpoint"];
                $_SESSION["studysequencearray"][$key]["gray"] = false;

                if ($unitid == 'CREDIT') {
                    $_SESSION["studysequencearray"][$key]["sequence"] = '01-' . $unitname;
                    $_SESSION["studysequencearray"][$key]["unitid"] = 'CREDIT&nbsp;&nbsp;(' . $row["unit"] . ')';
                    $notapprovedcreditstatus = array('', 'A', 'S', 'W', 'R');
                    if (in_array($_SESSION["studentplan"]["creditstatus"], $notapprovedcreditstatus)) {
                        $_SESSION["studysequencearray"][$key]["unitid"] = 'PROVISIONAL CREDIT&nbsp;&nbsp;(' . $row["unit"] . ')';
                    }
                    $_SESSION["studysequencearray"][$key]["termid"] = '&nbsp;';
                    $_SESSION["studysequencearray"][$key]["gray"] = true;
                } elseif (strpos($termidname, 'optTermid') == false) {
                    $temptermid = $termidname;

                    if (empty($unitid) || $unitid == '&nbsp;') {
                        $_SESSION["studysequencearray"][$key]["unitid"] = $row["unit"];
                    } else {
                        $_SESSION["studysequencearray"][$key]["unitid"] = $unitid;
                    }
                    $_SESSION["studysequencearray"][$key]["sequence"] = '02-' . $temptermid . $_SESSION["studysequencearray"][$key]["unitid"];
                    $_SESSION["studysequencearray"][$key]["termid"] = $temptermid;
                    $_SESSION["studysequencearray"][$key]["gray"] = true;
                } else {
                    $temptermid = $row["termid"];

                    if (empty($unitid) || $unitid == '&nbsp;') {
                        $_SESSION["studysequencearray"][$key]["unitid"] = $row["unit"];
                    } else {
                        $_SESSION["studysequencearray"][$key]["unitid"] = $unitid;
                    }
                    $_SESSION["studysequencearray"][$key]["sequence"] = '03-' . $temptermid . $_SESSION["studysequencearray"][$key]["unitid"];
                    $_SESSION["studysequencearray"][$key]["termid"] = $temptermid;
                    $_SESSION["studysequencearray"][$key]["bgtermcolor"] = $bgtermcolor;
                }
            } else {
                echo '<tr>';
                echo '<td ' . $bgcolor . ' align="center">' . $term . '</td>';
                echo '<td ' . $bgcolor . ' align="left">' . $unitname . '<br><span class="small">' . $requisite . '</span></td>';
                if ($bgtermcolor == ' bgcolor="#FF0000"') {
                    echo '<td ' . $bgunitcolor . ' align="center">&nbsp;</span>' . $unitidname . '</td>';
                } else {
                    echo '<td ' . $bgunitcolor . ' align="center">' . $unitidname . '</td>';
                }
                echo '<td ' . $bgtermcolor . ' width="10%" align="center">' . $termidname . '</td>';
                echo '</tr>';
            }
            $bgunitcolor = $bgcolor;

        }
        $htmlbgstudytermcolor = '';
        $htmlpreviousterm = '';
        if ($planstudysequence == 'Study Term Sequence') {
            usort($_SESSION["studysequencearray"], 'sortSequence');

            while (list($seqkey, $seqvalue) = each($_SESSION["studysequencearray"])) {
                
                $seqrow = $seqvalue;
                $tempkey = $seqkey + 1;

                if ($seqrow["termid"] != $htmlpreviousterm) {
                    if ($htmlbgstudytermcolor == ' bgcolor="#FFFFFF" ') {
                        $htmlbgstudytermcolor = ' bgcolor="#ffff99" ';
                        $bgtermcolor = ' bgcolor="#ffff99"';
                    } else {
                        $htmlbgstudytermcolor = ' bgcolor="#FFFFFF" ';
                        $bgtermcolor = ' bgcolor="#FFFFFF"';
                    }
                }
                if ($seqrow["bgtermcolor"] == ' bgcolor="#FF0000"') {
                    $bgtermcolor = ' bgcolor="#FF0000"';
                }
                $unitname = '';
                if ($seqrow["unitid"] == 'UNALLOCATED') {
                    $unitid = 'UNALLOCATED';
                } else {
                    $unitid = $seqrow["unitid"];

                    if ($unitid != 'CREDIT') {
                        $sql = "select *
                    from unit
                    where unitid = '$unitid'";

                        $unitsql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-16: " . mysqli_error($db));

                        if (mysqli_num_rows($unitsql_ok)) {
                            $unitrow = mysqli_fetch_array($unitsql_ok) or die(basename(__FILE__, '.php') . "-17: " . mysqli_error($db));
                            $unitname = stripslashes($unitrow["name"]);
                        }
                    }
                }


                $_SESSION["studysequencearray"][$seqkey]["unitname"] = $unitname;

                $gray = '';
                if ($seqrow["gray"]) {
                    $gray = ' style="color: gray;" ';
                }
                if (empty($seqrow["termid"])) {
                    $termid = '&nbsp;';
                } else {
                    $termid = $seqrow["termid"];
                }
                echo '<td ' . $htmlbgstudytermcolor . $gray . ' align="center">' . $tempkey . '</td>';

                echo '<td ' . $htmlbgstudytermcolor . $gray . '>';
                if ($unitid != '&nbsp;') {
                    echo $unitid . '&nbsp;';
                }
                if ($unitname) {
                    echo $unitname;
                }
                echo '</td>';

                echo '<td ' . $bgtermcolor . $gray . ' align="center">' . $termid . '</td>';
                echo '<td ' . $htmlbgstudytermcolor . $gray . ' align="center">' . $seqrow["creditpoint"] . '</td>';

                echo '</tr>';
                $htmlpreviousterm = $seqrow["termid"];
            }
        }
        echo '</table>';

        usort($legendtermarray, 'sortTermid');

        echo '<br><table width="100%" border="0" cellpadding="4" cellspacing="0"><tr><td>';
        //Feild
        //</Table> at 746
        echo '<table border="1" cellpadding="4" cellspacing="0">';
        echo '<tr>';
        echo '<td align="center" bgcolor="#C0C0C0"><b>Study Term</b></td>';
        echo '<td align="center" bgcolor="#C0C0C0"><b>Description</b></td>';
        echo '<td align="center" bgcolor="#C0C0C0"><b>Courses</b></td>';
        echo '</tr>';

        foreach ($legendtermarray as $key => $value) {

            $termrow = $value;

            echo '<tr>';
            echo '<td align="center">' . $termrow["termid"] . '</td>';

            $termid = $termrow["termid"];

            $sql = "select description
                    from term
                    where termid = '$termid'";

            $termsql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-18: " . mysqli_error($db));

            if (mysqli_num_rows($termsql_ok)) {
                $temprow = mysqli_fetch_array($termsql_ok) or die(basename(__FILE__, '.php') . "-19: " . mysqli_error($db));

                $termdescription = $temprow["description"];
            }
            $tempyear = explode('/', $termrow["termid"]);
            $year = $tempyear[0];

            echo '<td>' . $termdescription . ' ' . $year . '</td>';
            echo '<td align="center">' . $termrow["count"] . '</td>';
            echo '</tr>';
        }
        echo '</table>';

        echo '</td><td><table border="1" cellpadding="4" cellspacing="0">';
        echo '<tr>';
        echo '<td align="center" bgcolor="#C0C0C0"><b>Colour</b></td>';
        echo '<td align="center" bgcolor="#C0C0C0"><b>Description</b></td>';
        echo '</tr><tr>';
        echo '<td align="center" bgcolor="#00FFFF">Cyan</td>';
        echo '<td>Number of Study Courses per term differs from that expected for this program (Warning only)</td>';
        echo '</tr><tr>';
        echo '<td align="center" bgcolor="#00FF00">Lime</td>';
        echo '<td>Credit (Information only)</td>';
        echo '</tr><tr>';
        echo '<td align="center" bgcolor="#FF00FF">Magenta</td>';
        echo '<td>Study Course is different to Plan Course (Warning only)</td>';
        echo '</tr><tr>';
        echo '<td align="center" bgcolor="#808000">Olive</td>';
        echo '<td>Check if Study Course is running in the Study Term selected (Warning only)</td>';
        echo '</tr><tr>';
        echo '<td align="center" bgcolor="#FF0000">Red</td>';
        echo '<td>Invalid entry (Error)</td>';
        echo '</tr><tr>';
        echo '<td align="center" bgcolor="#FFFF00">Yellow</td>';
        echo '<td>Level of Study Course differs from that expected for this program (Warning only)</td>';
        echo '</tr>';

        // Extra table? Make it one?
        echo '</table>';
        echo '<td><table border="0" cellpadding="4" cellspacing="0">';
        echo '<tr>';
        echo '<td align="center" width="200px"><a target="_blank" href="../outlines/outlinefaculty.php">Course Outlines</a></td>';
        echo '</tr>';
        echo '</table></td>';


        echo '<tr><td colspan="3">';
        echo '<br><br><span style="font-size: 22; font-weight: bold;">Academic Progress Rules';
        echo '</td></tr>';

        $courseplanid = $_SESSION["studentplan"]["courseplanid"];
        $unitrulegeneral = $_SESSION["studentplan"]["unitrulegeneral"];

        //Course rules
        $sql = "select content, cr.courseregulationid
            from courseplan as cp
              inner join courseregulation as cr
                on cr.courseregulationkey = cp.unitrule
            where courseplanid = '$courseplanid'";

        $sql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-20: " . mysqli_error($db));

        $temp = '';
        if (mysqli_num_rows($sql_ok) > 0) {
            $cprow = mysqli_fetch_array($sql_ok) or die(basename(__FILE__, '.php') . "-21: " . mysqli_error($db));
            $temp = stripslashes($cprow["content"]);
            $_SESSION["studentplan"]["unitrule"] = stripslashes($cprow["courseregulationid"]);
        }
        if (!empty($temp)) {
            $unitruletemp = $unitrulegeneral . "<br><br>" . $temp;
        }
        $sql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-22: " . mysqli_error($db));

        if (mysqli_num_rows($sql_ok) > 0) {
            $cprow = mysqli_fetch_array($sql_ok) or die(basename(__FILE__, '.php') . "-23: " . mysqli_error($db));

            echo '<tr style="text-align: justify;"><td colspan="3">';
            echo '<br><b>Course Rules:</b>';
            echo '<br><br>' . $unitruletemp;
            echo '</td></tr>';
        }
        //Unsatisfactory
        $sql = "select content
            from courseplan as cp
              inner join courseregulation as cr
                on cr.courseregulationkey = cp.unsatisfactory
            where courseplanid = '$courseplanid'";

        $sql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-24: " . mysqli_error($db));

        if (mysqli_num_rows($sql_ok) > 0) {
            $cprow = mysqli_fetch_array($sql_ok) or die(basename(__FILE__, '.php') . "-25: " . mysqli_error($db));
            echo '<tr style="text-align: justify;"><td colspan="3">';
            echo '<br><b>Unsatisfactory Progress:</b>';
            echo '<br><br>'.stripslashes($cprow["content"]);
            echo '</td></tr>';
        }
        //Exclusion
        $sql = "select content
            from courseplan as cp
              inner join courseregulation as cr
                on cr.courseregulationkey = cp.exclusion
            where courseplanid = '$courseplanid' ";

        $sql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-26: " . mysqli_error($db));

        if (mysqli_num_rows($sql_ok) > 0) {
            $cprow = mysqli_fetch_array($sql_ok) or die(basename(__FILE__, '.php') . "-27: " . mysqli_error($db));

            echo '<tr style="text-align: justify;"><td colspan="3">';
            echo '<br><b>Exclusion / Suspension:</b>';
            echo '<br><br>' . stripslashes($cprow["content"]);
            echo '</td></tr>';
        }
        //FUSA
        $sql = "select content
            from courseplan as cp
              inner join courseregulation as cr
                on cr.courseregulationkey = cp.fusa
            where courseplanid = '$courseplanid'";

        $sql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-28: " . mysqli_error($db));

        if (mysqli_num_rows($sql_ok) > 0) {
            $cprow = mysqli_fetch_array($sql_ok) or die(basename(__FILE__, '.php') . "-29: " . mysqli_error($db));

            echo '<tr style="text-align: justify;"><td colspan="3">';
            echo '<br><b>Final Course Supplementary Assessment:</b>';
            echo '<br><br>' . stripslashes($cprow["content"]);
            echo '</td></tr>';
        }
        //Counselling
        $sql = "select content
            from courseplan as cp
              inner join courseregulation as cr
                on cr.courseregulationkey = cp.counselling
            where courseplanid = '$courseplanid'";

        $sql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-30: " . mysqli_error($db));

        if (mysqli_num_rows($sql_ok) > 0) {
            $cprow = mysqli_fetch_array($sql_ok) or die(basename(__FILE__, '.php') . "-31: " . mysqli_error($db));

            echo '<tr style="text-align: justify;"><td colspan="3">';
            echo '<br><b>Intervention Counselling:</b>';
            echo '<br><br>' . stripslashes($cprow["content"]);
            echo '</td></tr>';
        }
        //Commendation
        $sql =  "select content
                from courseplan as cp
                inner join courseregulation as cr
                on cr.courseregulationkey = cp.commendation
                where courseplanid = '$courseplanid'";

        $sql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-32: " . mysqli_error($db));

        if (mysqli_num_rows($sql_ok) > 0) {
            $cprow = mysqli_fetch_array($sql_ok) or die(basename(__FILE__, '.php') . "-33: " . mysqli_error($db));

            echo '<tr style="text-align: justify;"><td colspan="3">';
            echo '<br><b>Term Commendation:</b>';
            echo '<br><br>' . stripslashes($cprow["content"]);
            echo '</td></tr>';
        }
        //Distinction avg Degree
        $sql = "select content
            from courseplan as cp
              inner join courseregulation as cr
                on cr.courseregulationkey = cp.distinction
            where courseplanid = '$courseplanid'";

        $sql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-34: " . mysqli_error($db));

        if (mysqli_num_rows($sql_ok) > 0) {
            $cprow = mysqli_fetch_array($sql_ok) or die(basename(__FILE__, '.php') . "-35: " . mysqli_error($db));

            echo '<tr style="text-align: justify;"><td colspan="3">';
            echo '<br><b>Degree with Distinction:</b>';
            echo '<br><br>' . stripslashes($cprow["content"]);
            echo '</td></tr>';
        }
        echo '</table>';

        echo '</table>';
        echo '</form>';

        if (!isset($_POST["hidFirstTime"])) { //Enforce validation 
            echo "<script language='javascript'> document.forms[0].btnCheck.click(); </script>";
        }
    }
    
    public function process_page()
    {

        global $p, $db, $message;

        if (isset($_POST["btnCancel"])) {
            $_SESSION["studentplan"] = null;
            $_SESSION["studentplanunit"] = null;
            unset($_SESSION["studentplan"], $_SESSION["studentplanunit"]);
            echo "<script language='javascript'> this.close(); </script>";
        }
        $sql_ok = $p->db_connect() or die(basename(__FILE__, '.php') . "-36: " . mysqli_error($db));

        if (!isset($_POST["hidFirstTime"])) { //First time in
            $_SESSION["studentplan"] = array();
            $_SESSION["studentplanunit"] = array();
            $_SESSION["oldcreditunit"] = array();

            $_SESSION["studentplan"]["studentid"] = $_GET["studentid"];
            $studentid = $_GET["studentid"];

            $_SESSION["studentplan"]["studentplanid"] = '';
            if ($_GET["studentplanid"]) {
                $_SESSION["studentplan"]["studentplanid"] = $_GET["studentplanid"];
            }
            $_SESSION["studentplan"]["courseplanid"] = '';
            if ($_GET["courseplanid"]) {
                $_SESSION["studentplan"]["courseplanid"] = $_GET["courseplanid"];
            }
            //Name details (Student ID)
            $sql = "select *
              from student
              where studentid = '$studentid'";

            $sql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-37: " . mysqli_error($db));

            if (mysqli_num_rows($sql_ok)) {
                $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__, '.php') . "-38: " . mysqli_error($db));
                if ($row["lastname"]) {
                    $_SESSION["studentplan"]["lastname"] = stripslashes($row["lastname"]);
                }
                $_SESSION["studentplan"]["othernames"] = stripslashes($row["othernames"]);
                $_SESSION["studentplan"]["hidephoto"] = $row["hidephoto"];
            } else {
                $_SESSION["studentplan"]["lastname"] = '';
                $_SESSION["studentplan"]["othernames"] = '';
            }
            if ($_SESSION["studentplan"]["studentplanid"]) {
                $studentplanid = $_SESSION["studentplan"]["studentplanid"];

                //Load studentplan
                $sql = "select *
                from studentplan
                where studentplanid = '$studentplanid'";

                $sql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-39: " . mysqli_error($db));

                $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__, '.php') . "-40: " . mysqli_error($db));

                $_SESSION["studentplan"]["status"] = $row["status"];
                $_SESSION["studentplan"]["locationid"] = $row["locationid"];
                $_SESSION["studentplan"]["unitrulewaivenote"] = stripslashes($row["unitrulewaivenote"]);
                $_SESSION["studentplan"]["archived"] = $row["archived"];

            } else { //Else student plan N/A
                $_SESSION["studentplan"]["status"] = '';
                $_SESSION["studentplan"]["locationid"] = '';
                $_SESSION["studentplan"]["unitrulewaivenote"] = '';
                $_SESSION["studentplan"]["archived"] = '';
            }
        }



        //Load course plan information
        $courseplanid = $_SESSION["studentplan"]["courseplanid"];

        $sql = "select cp.termid, cp.strandid, cp.unitrule, cp.unitrulegeneral, cp.creditrule, c.name, c.`type`, cp.termload, cp.mixedlevel, c.subdisciplineid, cp.planlocationid
            from courseplan as cp
              inner join course as c
                on cp.strandid = c.strandid
            where courseplanid = '$courseplanid'";

        $sql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-41: " . mysqli_error($db));
        $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__, '.php') . "-42: " . mysqli_error($db));

        //Create session for...
        $_SESSION["studentplan"]["strandid"] = $row["strandid"];
        $_SESSION["strandid"] = $row["strandid"];
        $_SESSION["studentplan"]["strandname"] = $row["name"];
        $_SESSION["studentplan"]["termid"] = $row["termid"];
        $_SESSION["studentplan"]["unitrulegeneral"] = stripslashes($row["unitrulegeneral"]);
        $_SESSION["studentplan"]["creditrule"] = $row["creditrule"];
        $_SESSION["studentplan"]["coursetype"] = $row["type"];
        $_SESSION["studentplan"]["termload"] = $row["termload"];
        $_SESSION["studentplan"]["mixedlevel"] = $row["mixedlevel"];
        $_SESSION["studentplan"]["subdisciplineid"] = $row["subdisciplineid"];
        $_SESSION["studentplan"]["planlocationid"] = $row["planlocationid"];

        // Process unit items
        $courseplanid = $_SESSION["studentplan"]["courseplanid"];

        $sql = "select *
            from courseplanunit
            where courseplanid = '$courseplanid'
            order by lineid";

        $sql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-43: " . mysqli_error($db));

        $coursecount = 0;
        for ($i = 0; $i < mysqli_num_rows($sql_ok); $i++) {
            $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__, '.php') . "-44: " . mysqli_error($db));

            $coursecount++;

            $_SESSION["studentplanunit"][$i]["lineid"] = $row["lineid"];
            $_SESSION["studentplanunit"][$i]["term"] = $row["term"];
            $_SESSION["studentplanunit"][$i]["unit"] = $row["unit"];

            if (!empty($row["minimumlevel"])) {
                $_SESSION["studentplanunit"][$i]["unit"] = $_SESSION["studentplanunit"][$i]["unit"] . getlevel($row["minimumlevel"], $row["maximumlevel"]);
            }
            $_SESSION["studentplanunit"][$i]["creditpoint"] = $row["creditpoint"];
            $_SESSION["studentplanunit"][$i]["grade"] = '';
            $creditpoint = '';
        }
        if ($_SESSION["studentplan"]["studentplanid"]) {

            // check student saved info first. if none found use plan info.
            $studentplanid = $_SESSION["studentplan"]["studentplanid"];

            $sql = "select *
              from studentplancheckunit
              where studentplanid = '$studentplanid'
              order by lineid";

            $sql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-45: " . mysqli_error($db));

            if (mysqli_num_rows($sql_ok) == 0) {
                $sql = "select *
              from studentplanunit
              where studentplanid = '$studentplanid'
              order by lineid";

                $sql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-46: " . mysqli_error($db));
            }
            for ($i = 0; $i < mysqli_num_rows($sql_ok); $i++) {
                $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__, '.php') . "-47: " . mysqli_error($db));

                if (empty($_SESSION["studentplanunit"][$i]["unitid"])) {
                    $_SESSION["studentplanunit"][$i]["unitid"] = $row["unitid"];

                    //Convert to new unit codes
                    if ($row["termid"] > '2008/9') {
                        $tempunitid = $_SESSION["studentplanunit"][$i]["unitid"];

                        if ($tempunitid) {

                            $sql = "select *
                        from csunit
                        where unitid = '$tempunitid'";

                            $csunitsql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-48: " . mysqli_error($db));

                            if (mysqli_num_rows($csunitsql_ok) > 0) {
                                $csunitrow = mysqli_fetch_array($csunitsql_ok) or die(basename(__FILE__, '.php') . "-49: " . mysqli_error($db));
                                $_SESSION["studentplanunit"][$i]["unitid"] = $csunitrow["csunitid"];
                            }
                        }
                    }
                }
                if (empty($_SESSION["studentplanunit"][$i]["termid"])) {
                    $_SESSION["studentplanunit"][$i]["termid"] = $row["termid"];
                }
                if (empty($_SESSION["studentplanunit"][$i]["creditunit"])) {
                    $_SESSION["studentplanunit"][$i]["creditunit"] = stripslashes($row["creditunit"]);
                    $_SESSION["oldcreditunit"][$i]["lineid"] = $_SESSION["studentplanunit"][$i]["lineid"];
                    $_SESSION["oldcreditunit"][$i]["creditunit"] = stripslashes($row["creditunit"]);
                }
                if ($_SESSION["studentplanunit"][$i]["unitid"] == 'CREDIT') {
                    $_SESSION["studentplanunit"][$i]["termid"] = '';
                }
                $_SESSION["studentplanunit"][$i]["grade"] = '';
                $creditpoint = '';
                $_SESSION["studentplanunit"][$i]["electivelookup"] = $row["electivelookup"];
                $_SESSION["studentplanunit"][$i]["waive"] = $row["waive"];

            }
            for ($i = mysqli_num_rows($sql_ok); $i < $coursecount; $i++) {

                if (strlen($_SESSION["studentplanunit"][$i]["unit"]) < 11 && $_SESSION["studentplanunit"][$i]["unit"] != 'ELECTIVE' && $_SESSION["studentplanunit"][$i]["unit"] != 'UNKNOWN' && empty($_SESSION["studentplanunit"][$i]["unitid"])) {
                    $_SESSION["studentplanunit"][$i]["unitid"] = $_SESSION["studentplanunit"][$i]["unit"];

                    //Convert to new unit codes
                    if ($_SESSION["studentplanunit"][$i]["termid"] > '2008/9') {
                        $tempunitid = $_SESSION["studentplanunit"][$i]["unitid"];
                        if ($tempunitid) {

                            $sql = "select *
                      from csunit
                      where unitid = '$tempunitid'";

                            $csunitsql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-50: " . mysqli_error($db));

                            if (mysqli_num_rows($csunitsql_ok) > 0) {
                                $csunitrow = mysqli_fetch_array($csunitsql_ok) or die(basename(__FILE__, '.php') . "-51: " . mysqli_error($db));
                                $_SESSION["studentplanunit"][$i]["unitid"] = $csunitrow["csunitid"];
                            }
                        }
                    }
                } else {
                    if (empty($_SESSION["studentplanunit"][$i]["unitid"])) {
                        $_SESSION["studentplanunit"][$i]["unitid"] = '';
                    }
                }
                if (empty($_SESSION["studentplanunit"][$i]["termid"])) {
                    $_SESSION["studentplanunit"][$i]["termid"] = '';
                }
            }
        }
        //Process any studentplanunit posts
        reset($_POST);
        while (list($key, $arrayvalue) = each($_POST)) {

            if (substr($key, 0, 9) == 'optUnitid') {
                $idx = substr($key, 9);
                $oldunitid = $_SESSION["studentplanunit"][$idx]["unitid"];
                if (!empty($_POST[$key])) {
                    $_SESSION["studentplanunit"][$idx]["unitid"] = strtoupper(trim($_POST[$key]));
                }
                $temp = $_SESSION["studentplanunit"][$idx]["unitid"];
                $creditstatus = $_SESSION["studentplan"]["creditstatus"];
                if (($temp == 'X' || $temp == 'EX' || $temp == 'EXEMPT' || $temp == 'CR' || $temp == 'CRED' || $temp == 'CREDIT')) {

                    $_SESSION["studentplanunit"][$idx]["unitid"] = $oldunitid;

                } else {
                    $_SESSION["studentplanunit"][$idx]["creditunit"] = '';
                }
            }
            if (substr($key, 0, 9) == 'optTermid') {
                $idx = substr($key, 9);
                if (!empty($_POST[$key])) {
                    $_SESSION["studentplanunit"][$idx]["termid"] = trim($_POST[$key]);
                }
                if (substr($_SESSION["studentplanunit"][$idx]["termid"], -2) == '/5') {
                    $_SESSION["studentplanunit"][$idx]["termid"] = substr($_SESSION["studentplanunit"][$idx]["termid"], 0, 4) . '/05';
                }
                //Convert any old unit codes to course codes.
                if ($_POST[$key] > '2008/9' && strlen($_SESSION["studentplanunit"][$idx]["unitid"]) < 6) {
                    $tempunitid = $_SESSION["studentplanunit"][$idx]["unitid"];

                    if ($tempunitid) {

                        $sql = "select *
                    from csunit
                    where unitid = '$tempunitid'";

                        $csunitsql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-52: " . mysqli_error($db));

                        if (mysqli_num_rows($csunitsql_ok) > 0) {
                            $csunitrow = mysqli_fetch_array($csunitsql_ok) or die(basename(__FILE__, '.php') . "-53: " . mysqli_error($db));
                            $_SESSION["studentplanunit"][$idx]["unitid"] = $csunitrow["csunitid"];
                        }
                    }
                }
                //get enrolcheck resultcheck info
                $temptermid = $_SESSION["studentplanunit"][$idx]["termid"];
                $sql = "select *
                from term
                where termid = '$temptermid'";

                $tempsql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-54: " . mysqli_error($db));

                $_SESSION["studentplanunit"][$idx]["enrolcheck"] = '';
                $_SESSION["studentplanunit"][$idx]["resultcheck"] = '';
                if (mysqli_num_rows($tempsql_ok) > 0) {
                    $temprow = mysqli_fetch_array($tempsql_ok) or die(basename(__FILE__, '.php') . "-55: " . mysqli_error($db));

                    $_SESSION["studentplanunit"][$idx]["enrolcheck"] = $temprow["enrolcheck"];
                    $_SESSION["studentplanunit"][$idx]["resultcheck"] = $temprow["resultcheck"];
                }
            }
        }
        //Process studied units.
        if (isset($_SESSION["studentplan"]["strandid"])) 
            //load cs enrolments

            $studentid = $_SESSION["studentplan"]["studentid"];

            $passgrades = array('HD', 'D', 'C', 'P', 'S', 'AD', 'TD', 'ZN', 'O');

            $sql = "select studentid, us.unitid, us.termid, grade, creditpoint, name, enrolcheck, resultcheck
                    from unitstudent as us
                    left join unit as u
                    on u.unitid = us.unitid
                    inner join term as t
                    on t.termid = us.termid
                    where studentid = '$studentid'
                    and strandid like '$programid%'
                    and grade IN ('HD','D','C','P','S','AD','TD','ZN','O','MN', 'MF', 'NN', 'F')
                    and ifnull(dropped,'') = ''
                    ";

            $unitstudentsql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-56: " . mysqli_error($db));

            for ($i = 0; $i < mysqli_num_rows($unitstudentsql_ok); $i++) {
                $unitstudentrow = mysqli_fetch_array($unitstudentsql_ok) or die(basename(__FILE__, '.php') . "-57: " . mysqli_error($db));

                $unitfound = false;
                reset($_SESSION["studentplanunit"]);

                while (list($key, $value) = each($_SESSION["studentplanunit"])) {
                    $studentplanunitrow = $value;

                    if ($unitstudentrow["unitid"] == $studentplanunitrow["unitid"]) {

                        if (in_array($unitstudentrow["grade"], $passgrades)) {
                            $_SESSION["studentplanunit"][$key]["termid"] = $unitstudentrow["termid"];
                            $_SESSION["studentplanunit"][$key]["grade"] = $unitstudentrow["grade"];
                            $_SESSION["studentplanunit"][$key]["enrolcheck"] = $unitstudentrow["enrolcheck"];
                            $_SESSION["studentplanunit"][$key]["resultcheck"] = $unitstudentrow["resultcheck"];
                        }
                        if (empty($unitstudentrow["creditpoint"])) {
                            $creditpoint = '15';
                        } else {
                            $creditpoint = $unitstudentrow["creditpoint"];
                        }
                        $unitfound = true;

                    } else { //lookup csunit to see if old code
                        if (in_array($unitstudentrow["grade"], $passgrades)) {
                            $tempunitid = $unitstudentrow["unitid"];
                            $sql = "select *
                                    from csunit
                                    where unitid = '$tempunitid'
                                    ";

                            $csunitsql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-58: " . mysqli_error($db));

                            if (mysqli_num_rows($csunitsql_ok) > 0) {
                                $csunitrow = mysqli_fetch_array($csunitsql_ok) or die(basename(__FILE__, '.php') . "-59: " . mysqli_error($db));

                                if ($csunitrow["csunitid"] == $studentplanunitrow["unitid"]) {

                                    if (in_array($unitstudentrow["grade"], $passgrades)) {
                                        $_SESSION["studentplanunit"][$key]["unitid"] = $csunitrow["unitid"];
                                        $_SESSION["studentplanunit"][$key]["termid"] = $unitstudentrow["termid"];
                                        $_SESSION["studentplanunit"][$key]["grade"] = $unitstudentrow["grade"];
                                        $_SESSION["studentplanunit"][$key]["enrolcheck"] = $unitstudentrow["enrolcheck"];
                                        $_SESSION["studentplanunit"][$key]["resultcheck"] = $unitstudentrow["resultcheck"];
                                    }
                                    $unitfound = true;
                                }
                            }
                        }
                    }
                }
            }
        
        //initialise bg colors
        reset($_SESSION["studentplanunit"]);
        while (list($key, $value) = each($_SESSION["studentplanunit"])) {
            $studentplanunitrow = $value;

            $_SESSION["studentplanunit"][$key]["bgunitcolor"] = '';
            $_SESSION["studentplanunit"][$key]["bgtermcolor"] = '';
        }
        if (isset($_POST["btnCheck"]) || isset($_POST["btnSave"])) {

            $studentplanid = $_SESSION["studentplan"]["studentplanid"];
            $studentid = $_SESSION["studentplan"]["studentid"];
            $courseplanid = $_SESSION["studentplan"]["courseplanid"];
            $note = addslashes($_SESSION["studentplan"]["note"]);
            $intervention = addslashes($_SESSION["studentplan"]["intervention"]);
            $locationid = $_SESSION["studentplan"]["locationid"];

            //load unit prefixes
            if (empty($unitprefixarray)) {
                $unitprefixarray = array();
                $sql = "SELECT distinct substr(unitid,1,5) as prefix, subdisciplineid
                FROM unit
                where length(unitid) > 5
                union all
                SELECT distinct substr(unitid,1,2) as prefix, subdisciplineid
                FROM unit
                where length(unitid) < 6";

                $presql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-60: " . mysqli_error($db));

                for ($prei = 0; $prei < mysqli_num_rows($presql_ok); $prei++) {
                    $prerow = mysqli_fetch_array($presql_ok) or die(basename(__FILE__, '.php') . "-61: " . mysqli_error($db));
                    $unitprefixarray[$prei]["prefix"] = $prerow['prefix'];
                    $unitprefixarray[$prei]["subdisciplineid"] = $prerow['subdisciplineid'];
                }
            }
            //Validate unitid
            $incomplete = false;
            $tempgrade = array('AD', 'TD', 'O', 'ZN');
            reset($_SESSION["studentplanunit"]);
            while (list($key, $value) = each($_SESSION["studentplanunit"])) {
                $studentplanunitrow = $value;

                //determine if incomplete
                if ($studentplanunitrow["unitid"] != 'CREDIT') {
                    if (empty($studentplanunitrow["grade"]) || in_array($studentplanunitrow["grade"], $tempgrade)) {
                        $incomplete = true;
                    }
                }
                reset($unitprefixarray);
                while (list($prefixkey, $prefixvalue) = each($unitprefixarray)) {
                    $prefixrow = $prefixvalue;

                    if (strlen($studentplanunitrow["unitid"]) > 5) {
                        $unitidprefix = substr($studentplanunitrow["unitid"], 0, 5);
                    } else {
                        $unitidprefix = substr($studentplanunitrow["unitid"], 0, 2);
                    }
                    $unitprefix = '';
                    if (strlen($studentplanunitrow["unit"]) < 11 && $studentplanunitrow["unit"] != 'ELECTIVE' && $studentplanunitrow["unit"] != 'UNKNOWN') {

                        if (strlen($studentplanunitrow["unit"]) > 5) {
                            $unitprefix = substr($studentplanunitrow["unit"], 0, 5);
                        } else {
                            $unitprefix = substr($studentplanunitrow["unit"], 0, 2);
                        }
                    }
                    //Validate termid, we also need to do it here because we want enrolcheck value.
                    if ($studentplanunitrow["termid"]) {

                        if ($studentplanunitrow["enrolcheck"] && empty($studentplanunitrow["unitid"]) && $parttime != '2') {
                            $message = 'Incomplete Study Course';
                            return;
                        }
                        //Check if need to change termid (for a failed unit)
                        if (isset($_POST["btnCheck"]) && $studentplanunitrow["resultcheck"] && empty($studentplanunitrow["grade"])) {
                            $message = 'Study Term must be updated for a Study Course that has not been successfully completed';
                            return;
                        }
                    }
                }
                //Validate for duplicates
                $tempstudentplanunit = $_SESSION["studentplanunit"];
                reset($tempstudentplanunit);
                $count = 0;
                while (list($akey, $avalue) = each($tempstudentplanunit)) {
                    $tempstudentplanunitrow = $avalue;
                    if ($studentplanunitrow["unitid"] && $studentplanunitrow["unitid"] != 'CREDIT' && $studentplanunitrow["unitid"] !== 'ELECTIVE' && $studentplanunitrow["unitid"] !== 'UNKNOWN' && ($tempstudentplanunitrow["unitid"] == $studentplanunitrow["unitid"])) {
                        $count++;
                    }
                }
                $tempstudentplanunit = '';
                if ($count > 1) {
                    $message = 'Duplicate course found - ' . $studentplanunitrow["unitid"];
                    return;
                }
            }
            //Validate termid
            $termidcount = 0;
            reset($_SESSION["studentplanunit"]);
            while (list($key, $value) = each($_SESSION["studentplanunit"])) {
                $studentplanunitrow = $value;

                if (!empty($studentplanunitrow["termid"]) || !empty($studentplanunitrow["grade"]) || $studentplanunitrow["unitid"] == 'CREDIT') {
                    $termidcount++;
                }
            }
            //validate no restricted to
            $norestrictedto = $_SESSION["studentplan"]["norestrictedto"];

            if (isset($_POST["txtnorestrictedto"])) {
                $temp = $_SESSION["studentplanunit"];
                reset($_SESSION["studentplanunit"]);
                while (list($key, $value) = each($_SESSION["studentplanunit"])) {
                    $studentplanunitrow = $value;

                    if (!empty($studentplanunitrow["termid"]) && empty($studentplanunitrow["grade"])) {
                        $temptermid = $studentplanunitrow["termid"];
                        $termsql = "select *
                        from term
                        where termid = '$temptermid'
                        and enrolcheck = '1'";

                        $termsql_ok = mysqli_query($db, $termsql) or die(basename(__FILE__, '.php') . "-62: " . mysqli_error($db));

                        reset($temp);
                        $termidcount = 0;
                        while (list($tempkey, $tempvalue) = each($temp)) {
                            $tempstudentplanunitrow = $tempvalue;

                            if ($tempstudentplanunitrow["termid"] == $studentplanunitrow["termid"]) {
                                $termidcount++;
                                $term = $tempstudentplanunitrow["termid"];
                            }
                        }
                        if ($termidcount > $norestrictedto && mysqli_num_rows($termsql_ok)) {
                            $message = 'Term allocation for ' . $term . ' exceeds Restricted maximum number of courses';
                            return;
                        }
                    }
                }
            }
            if ($_SESSION["studentplan"]["archived"] == '1') {
                $archived = '1';
            }
            //validate level rules - error
            if (in_array($_SESSION["studentplan"]["status"], array('A', 'P', 'N', 'R'))) {
                include_once "../validatelevels.php";
            }
            //validate requisite rules - error (note: studentplanunit used in common code includes that follow is built elsewhere.)
            if (in_array($_SESSION["studentplan"]["status"], array('A', 'P', 'N', 'R'))) {
                include_once "../validaterequisites.php";
            }
            //validate course rules - error
            if (empty($_SESSION["studentplan"]["unitrulewaivenote"]) && in_array($_SESSION["studentplan"]["status"], array('A', 'P', 'N', 'R'))) {

                $coursetype = $_SESSION["studentplan"]["coursetype"];
                $unitrule = $_SESSION["studentplan"]["unitrule"];

                include_once "../validatecourserules.php";

            }
            //If message == Empty then return value
            if (!empty($message)) {
                return;
            }
            //Exit before saves begin if refreshing only
            if (isset($_POST["btnCheck"])) {
                return;
            }
            //process any entered fields
            reset($_POST);
            while (list($key, $arrayvalue) = each($_POST)) {

                if (substr($key, 0, 9) == 'txtUnitid') {
                    $idx = substr($key, 9);

                    $_SESSION["studentplanunit"][$idx]["unitid"] = strtoupper(trim($_POST[$key]));

                }
                if (substr($key, 0, 9) == 'txtTermid') {
                    $idx = substr($key, 9);

                    $_SESSION["studentplanunit"][$idx]["termid"] = trim($_POST[$key]);

                }
                //get enrolcheck resultcheck info
                $temptermid = $_SESSION["studentplanunit"][$idx]["termid"];
                $sql = "select *
                from term
                where termid = '$temptermid'";

                $tempsql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-63: " . mysqli_error($db));

                $_SESSION["studentplanunit"][$idx]["enrolcheck"] = '';
                $_SESSION["studentplanunit"][$idx]["resultcheck"] = '';
                if (mysqli_num_rows($tempsql_ok) > 0) {
                    $temprow = mysqli_fetch_array($tempsql_ok) or die(basename(__FILE__, '.php') . "-64: " . mysqli_error($db));

                    $_SESSION["studentplanunit"][$idx]["enrolcheck"] = $temprow["enrolcheck"];
                    $_SESSION["studentplanunit"][$idx]["resultcheck"] = $temprow["resultcheck"];
                }
            }
            //save student changes
            $sql = "delete
              from studentplancheckunit
              where studentplanid = '$studentplanid'";

            $sql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-65: " . mysqli_error($db));

            $idx = 0;

            reset($_SESSION["studentplanunit"]);
            while (list($key, $value) = each($_SESSION["studentplanunit"])) {
                $row = $value;

                $studentid = $_SESSION["studentplan"]["studentid"];
                $lineid = $key;
                $unitid = $row["unitid"];
                $termid = $row["termid"];
                $waive = $row["waive"];
                $waivechecked = $row["waivechecked"];
                $waivenote = $row["waivenote"];
                $creditunit = addslashes($row["creditunit"]);

                $sql = "INSERT INTO studentplancheckunit VALUES ('$studentplanid', '$idx', '$unitid', '$termid', '$waive','$waivechecked', '$creditunit', '$waivenote')";

                $sql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-66: " . mysqli_error($db));

                $idx++;
            }
            $_SESSION["studentplan"] = null;
            $_SESSION["studentplanunit"] = null;
            unset($_SESSION["studentplan"], $_SESSION["studentplanunit"]);
            echo "<script language='javascript'> this.close(); </script>";

        }
    }
    public function __construct()
    {
        basePage::basePageFunction();
    }
}
// Instantiate this page
$p = new studentplancheck_page();

if (empty($_SESSION["mrkaccessallowed"])) {
    exit;
}
$p->process_page();

// Output page.
$heading = "fdlMarks --> " . $_SESSION["mrksysinstitution"] . " --> Study Plan Check";
$p->display_html_header($heading);
$p->display_page();
$p->display_html_footer();
