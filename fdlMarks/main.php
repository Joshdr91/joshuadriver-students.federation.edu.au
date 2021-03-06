<?php

require_once "basePage.php";
require_once "utils.php";

class Marks_page extends basePage
{
    public function display_Page()
    { // Body of page.

        global $p, $db, $studentdetails; ?>

    <script language="javascript">

      window.onerror = blockError;

      function blockError(){
        return true;
      }


      function studentplancheck(studentplanid, courseplanid, studentid){
        newWindow=window.open("studentplancheck.php?studentplanid=" + studentplanid + "&courseplanid=" + courseplanid + "&studentid=" + studentid + "&openerform=''","fdlmstudentplancheck","resizable=yes, scrollbars=yes, menubar=yes, width=" + sWidth *.9  + ", height=" + sHeight *.8 + ", top=" + sTop + ", left=" + sLeft);
        newWindow.focus();
      }
      function unitdescription(locationid, termid, unitid){
        temp=window.open("../unitdescription.php?locationid=" + locationid + "&termid=" + termid + "&unitid=" + unitid + "&type=Z","_blank","location=no, menubar=yes, scrollbars=yes,  resizable=yes, width=" + sWidth *.9  + ", height=" + sHeight *.80 + ", top=" + sTop + ", left=" + sLeft + "");
      }

</script>



    <!-- The main headings !-->
    <html>
        <b class="hovermsg" align="center">&nbsp;Hover your cursor over the assessment mark to see the assessment details&nbsp;</b>
        <img src="images/FedUni_logo.svg.png" alt="Federation Logo" class="logo" align="left">
        <link rel="stylesheet" type="text/css" href="stylesheet/Design.css">
        <link rel ="Icon Page" href="images/favicon.ico">
        <table border-collapse="collapse" border="1" border="1px" class="grading-table" cellspacing="0" cellpadding="6">
    </html>
        <style>@media print {#ghostery-purple-box {display:none !important}}</style>
        

</script>

        <?php
        // Connect to the database or =! supply error
        $sql_ok = $p->db_connect() or die(basename(__FILE__, '.php') . "-01: " . mysqli_error($db));



        $studentid = $_SESSION["mrkstudentid"];
        $strandid = $_SESSION["mrkstrandid"];



        $sql = "select sp.studentplanid, cp.courseplanid
                      from studentplan as sp
                      inner join courseplan as cp
                         on cp.courseplanid = sp.courseplanid
                      where sp.studentid = '$studentid'
                      and sp.`status` in ('A','P','N','R')
                      and cp.strandid = '$strandid'";

                      
        $spsql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-02: " . mysqli_error($db));



        if (mysqli_num_rows($spsql_ok) > 0) {
            $sprow = mysqli_fetch_array($spsql_ok) or die(basename(__FILE__, '.php') . "-03: " . mysqli_error($db));
            $studentplanid = $sprow["studentplanid"];
            $courseplanid = $sprow["courseplanid"];
            echo '<b align="center">Student ID:</b>&nbsp;' . $studentid . '&nbsp;&nbsp;&nbsp;&nbsp;<b class="StudyPlan" align="center">Study Plan:</b>&nbsp;
            <a href="javascript:studentplancheck(\'' . $studentplanid . '\',\'' . $courseplanid . '\',\'' . $studentid . '\')">Click here</a>';
        } else {
            echo '<b align= "center" class="ID">Student ID:</b>&nbsp;' . $studentid . '&nbsp;&nbsp;&nbsp;&nbsp;<b>Study Plan:</b>&nbsp;Unavailable. Contact Program Coordinator.';
            echo $studentdetails;
        } ?>

        <?php
        echo $studentdetails; ?>
        </table>

    
        <?php
    }
    
    /**
     * Undocumented function
     *
     * @param [INT]     $argmark   Overall mark
     * @param [VARCHAR] $argweight Weight of the mark
     * @param [INT]     $argtype   Type of assignment **REDUNDANT**
     * 
     * @return void
     */
    public function convertToGrade($argmark, $argweight, $argtype)
    {
        
        //Grading system  - Satisfactory OR Unsatisfactory (Only Particular Subjects)
        if ($argtype == 'H') {
            if (empty($argmark)) {
                $grade = 'U';
            } else {
                $grade = 'S';
            }
            return $grade;
        }
        if (empty($argweight)) {
            return $argmark;
        }



        // Mark / The grade of work  = The Percentage of work
        $percent = $argmark / $argweight;

        //Marking grade
        $grade = '&nbsp;';
        switch (true) {
        case $percent >= 0 && $percent < 0.4:
                $grade = 'F';
            break;
        case $percent >= 0.4 && $percent < 0.5:
                $grade = 'MF';
            break;
        case $percent >= 0.5 && $percent < 0.6:
                $grade = 'P';
            break;
        case $percent >= 0.6 && $percent < 0.7:
                $grade = 'C';
            break;
        case $percent >= 0.7 && $percent < 0.8:
                $grade = 'D';
            break;
        case $percent >= 0.8:
                $grade = 'HD';
            break;
        }
        return $grade;
    }



    /**
     * Procces_form function
     *
     * @return void
     */
    public function process_Form()
    { 
        // Validate fields and if ok proceed to grades form.

        global $p, $db, $studentdetails;

        $_SESSION["mrkmsg"] = '';
        $_SESSION["mrkstrandid"] = '';
        $studentdetails = '';
        $studentid = $_SESSION["mrkstudentid"];


        // Connect to the database or =! connect then supply error
        $sql_ok = $p->db_connect() or die(basename(__FILE__, '.php') . "-04: " . mysqli_error($db));

        $sql = "select *
            from unitstudent
            where studentid = '$studentid'
            and ifnull(dropped,'') = ''
            order by termid desc, locationid, unitid";

        $sql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-05: " . mysqli_error($db));

        if (mysqli_num_rows($sql_ok) == 0) { // No record found.
            $_SESSION["mrkmsg"] = "Student units not found - please contact student administration";
            return false;
        }


        //The display feilds on table
        $studentdetails 
            = '
        <tr class="Types">
         <th rowspan="2">Term</th>
         <th rowspan="2">Course</th>
         <th rowspan="2">Type</th>
         <th colspan="12">Assessment</th>
         <th rowspan="2">Messages</th>
        </tr>
      
        <tr class="Numbers">
         <td width="5%" align="center"><b>1</b></td>
         <td width="5%" align="center"><b>2</b></td>
         <td width="5%" align="center"><b>3</b></td>
         <td width="5%" align="center"><b>4</b></td>
         <td width="5%" align="center"><b>5</b></td>
         <td width="5%" align="center"><b>6</b></td>
         <td width="5%" align="center"><b>7</b></td>
         <td width="5%" align="center"><b>8</b></td>
         <td width="5%" align="center"><b>9</b></td>
         <td width="5%" align="center"><b>10</b></td>
         <td width="5%" align="center"><b>11</b></td>
         <td width="5%" align="center"><b>12</b></td>
      </tr>'
        ;

        for ($i = 0; $i < mysqli_num_rows($sql_ok); $i++) {
            // Looks for the amount of rows in the table
            // If table is not found then display ERROR
            $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__, '.php') . "-06: " . mysqli_error($db));

            $moderationtype = '';
            $locationid = $row["locationid"];
            $termid = $row["termid"];
            $unitid = $row["unitid"];
            if (empty($_SESSION["mrkstrandid"])) {
                $_SESSION["mrkstrandid"] = $row["strandid"];
            }
            $sql = "select ul.*, u.subdisciplineid
              from unitlocation as ul
                inner join unit as u
                  on u.unitid = ul.unitid
              where ul.locationid = '$locationid'
              and ul.termid = '$termid'
              and ul.unitid = '$unitid'";

            $unitsql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-07: " . mysqli_error($db));


            if (mysqli_num_rows($unitsql_ok) > 0) {
                $unitrow = mysqli_fetch_array($unitsql_ok) or die(basename(__FILE__, '.php') . "-08: " . mysqli_error($db));
                $moderationtype = $unitrow["moderationtype"];
                $subdisciplineid = $unitrow["subdisciplineid"];
            }

            $showdidnotpass = false;
            $published = false;

            $sql = "select *
              from term
              where termid = '$termid'
              and resultcheck = '1'";

            $termsql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-09: " . mysqli_error($db));

            if (mysqli_num_rows($termsql_ok) > 0) {
                $published = true;
            }
            if (mysqli_num_rows($termsql_ok) > 0 && ($row["grade"] == 'MF' || $row["grade"] == 'F' || $row["grade"] == 'U' || $row["grade"] == 'ZN' || $row["grade"] == 'AD' || $row["grade"] == 'TD')) {
                $showdidnotpass = true;
            }
            $locationid = $row["locationid"];
            $termid = $row["termid"];
            $unitid = $row["unitid"];

            //Subdiscipline settings
            $marksaccess = '';
            $convertmarktograde = '';
            $ssql = "select s.marksaccess, s.convertmarktograde, u.name as unitname
                from unit as u
                  inner join subdiscipline as s
                    on s.subdisciplineid = u.subdisciplineid
                where u.unitid = '$unitid'";

            $ssql_ok = mysqli_query($db, $ssql) or die(basename(__FILE__, '.php') . "-10: " . mysqli_error($db));

            if (mysqli_num_rows($ssql_ok) > 0) {
                $subdisciplinerow = mysqli_fetch_array($ssql_ok) or die(basename(__FILE__, '.php') . "-11: " . mysqli_error($db));
                $marksaccess = $subdisciplinerow["marksaccess"];
                $convertmarktograde = $subdisciplinerow["convertmarktograde"];
                $unitname = $subdisciplinerow["unitname"];
            } 
            $sql = "select description
                    ,`type`
                    ,weight
                    ,due
                    ,splitweek
                    ,dueday
                    ,duetime
                    ,marks
              from unittask
              where locationid = '$locationid'
              and termid = '$termid'
              and unitid = '$unitid'
              order by taskid";

            $ssql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-12: " . mysqli_error($db));


            $mrktask = array();
            for ($sidx = 0; $sidx < mysqli_num_rows($ssql_ok); $sidx++) {
                $num = $sidx + 1;
                $srow = mysqli_fetch_array($ssql_ok) or die(basename(__FILE__, '.php') . "-13: " . mysqli_error($db));
                $mrktask[$num]["description"] = $srow['description'];
                $mrktask[$num]["weight"] = $srow['weight'];
                $mrktask[$num]["type"] = $srow['type'];

                $cleaneddue = $srow['due'];
                if (!is_numeric($srow['due'])) {
                    $cleaneddue = substr($srow["due"], 0, strpos($srow["due"], ' '));
                }
                $mrktask[$num]["due"] = $cleaneddue;

                $mrktask[$num]["splitweek"] = $srow['splitweek'];

                if (empty($srow['dueday'])) {
                    $mrktask[$num]["dueday"] = '4';
                } else {
                    $mrktask[$num]["dueday"] = $srow['dueday'];
                }
                if (empty($srow['duetime'])) {
                    $mrktask[$num]["duetime"] = '16:00';
                } else {
                    $mrktask[$num]["duetime"] = $srow['duetime'];
                }
                $mrktask[$num]["marks"] = $srow['marks'];
            }
            $duedate = getDueDate($locationid, $termid, 1, $cleaneddue, $mrktask[$num]["splitweek"], $mrktask[$num]["dueday"], $mrktask[$num]["duetime"]);
            if (!empty($row["tpp01"]) && empty($mrktask[1]["marks"])) {
                $tpp01 = "NA";
            } elseif (empty($row["tpp01"])) {
                $tpp01 = "&nbsp;";
            } elseif (empty($row["plg01"]) || $row["plg01"] == '5') {
                $lateadjusted = $row["tpp01"] - (late_Penalty($row, $mrktask, 1) * $mrktask[1]["weight"]);
                if ($lateadjusted < 0) {
                    $lateadjusted = 0;
                }
                if ($convertmarktograde) {
                    $tpp01 = $p->convertToGrade($lateadjusted, $mrktask[1]["weight"], $mrktask[1]["type"]);
                } else {
                    $tpp01 = sprintf("%.1f", $lateadjusted);
                }
                $bgtpp01 = "";
                if (late_Penalty($row, $mrktask, 1)) {
                    $bgtpp01 = 'bgcolor="#CCFF00"';
                    $titltpp01 = "LATE PENALTY\n\n";
                }
            } else {
                $tpp01 = "PLG";
            }
            $duedate = getDueDate($locationid, $termid, 2, $cleaneddue, $mrktask[$num]["splitweek"], $mrktask[$num]["dueday"], $mrktask[$num]["duetime"]);
            if (!empty($row["tpp02"]) && empty($mrktask[2]["marks"])) {
                $tpp02 = "NA";
            } elseif (empty($row["tpp02"])) {
                $tpp02 = "&nbsp;";
            } elseif (empty($row["plg02"]) || $row["plg02"] == '5') {
                $lateadjusted = $row["tpp02"] - (late_Penalty($row, $mrktask, 2) * $mrktask[2]["weight"]);

                if ($lateadjusted < 0) {
                    $lateadjusted = 0;
                }
                if ($convertmarktograde) {
                    $tpp02 = $p->convertToGrade($lateadjusted, $mrktask[2]["weight"], $mrktask[2]["type"]);
                } else {
                    $tpp02 = sprintf("%.1f", $lateadjusted);
                }
                $bgtpp02 = "";
                if (late_Penalty($row, $mrktask, 2)) {
                    $bgtpp02 = 'bgcolor="#CCFF00"';
                    $titltpp02 = "LATE PENALTY\n\n";
                }
            } else {
                $tpp02 = "PLG";
            }
            $duedate = getDueDate($locationid, $termid, 3, $cleaneddue, $mrktask[$num]["splitweek"], $mrktask[$num]["dueday"], $mrktask[$num]["duetime"]);
            if (!empty($row["tpp03"]) && empty($mrktask[3]["marks"])) {
                $tpp03 = "NA";
            } elseif (empty($row["tpp03"])) {
                $tpp03 = "&nbsp;";
            } elseif (empty($row["plg03"]) || $row["plg03"] == '5') {
                $lateadjusted = $row["tpp03"] - (late_Penalty($row, $mrktask, 3) * $mrktask[3]["weight"]);
                if ($lateadjusted < 0) {
                    $lateadjusted = 0;
                }
                if ($convertmarktograde) {
                    $tpp03 = $p->convertToGrade($lateadjusted, $mrktask[3]["weight"], $mrktask[3]["type"]);
                } else {
                    $tpp03 = sprintf("%.1f", $lateadjusted);
                }
                $bgtpp03 = "";
                if (late_Penalty($row, $mrktask, 3)) {
                    $bgtpp03 = 'bgcolor="#CCFF00"';
                    $titltpp03 = "LATE PENALTY\n\n";
                }
            } else {
                $tpp03 = "PLG";
            }
            $duedate = getDueDate($locationid, $termid, 4, $cleaneddue, $mrktask[$num]["splitweek"], $mrktask[$num]["dueday"], $mrktask[$num]["duetime"]);
            if (!empty($row["tpp04"]) && empty($mrktask[4]["marks"])) {
                $tpp04 = "NA";
            } elseif (empty($row["tpp04"])) {
                $tpp04 = "&nbsp;";
            } elseif (empty($row["plg04"]) || $row["plg04"] == '5') {
                $lateadjusted = $row["tpp04"] - (late_Penalty($row, $mrktask, 4) * $mrktask[4]["weight"]);
                if ($lateadjusted < 0) {
                    $lateadjusted = 0;
                }
                if ($convertmarktograde) {
                    $tpp04 = $p->convertToGrade($lateadjusted, $mrktask[4]["weight"], $mrktask[4]["type"]);
                } else {
                    $tpp04 = sprintf("%.1f", $lateadjusted);
                }
                $bgtpp04 = "";
                if (late_Penalty($row, $mrktask, 4)) {
                    $bgtpp04 = 'bgcolor="#CCFF00"';
                    $titltpp04 = "LATE PENALTY\n\n";
                }
            } else {
                $tpp04 = "PLG";
            }
            $duedate = getDueDate($locationid, $termid, 5, $cleaneddue, $mrktask[$num]["splitweek"], $mrktask[$num]["dueday"], $mrktask[$num]["duetime"]);
            if (!empty($row["tpp05"]) && empty($mrktask[5]["marks"])) {
                $tpp05 = "NA";
            } elseif (empty($row["tpp05"])) {
                $tpp05 = "&nbsp;";
            } elseif (empty($row["plg05"]) || $row["plg05"] == '5') {
                $lateadjusted = $row["tpp05"] - (late_Penalty($row, $mrktask, 5) * $mrktask[5]["weight"]);
                if ($lateadjusted < 0) {
                    $lateadjusted = 0;
                }
                if ($convertmarktograde) {
                    $tpp05 = $p->convertToGrade($lateadjusted, $mrktask[5]["weight"], $mrktask[5]["type"]);
                } else {
                    $tpp05 = sprintf("%.1f", $lateadjusted);
                }
                $bgtpp05 = "";
                if (late_Penalty($row, $mrktask, 5)) {
                    $bgtpp05 = 'bgcolor="#CCFF00"';
                    $titltpp05 = "LATE PENALTY\n\n";
                }
            } else {
                $tpp05 = "PLG";
            }
            $duedate = getDueDate($locationid, $termid, 6, $cleaneddue, $mrktask[$num]["splitweek"], $mrktask[$num]["dueday"], $mrktask[$num]["duetime"]);
            if (!empty($row["tpp06"]) && empty($mrktask[6]["marks"])) {
                $tpp06 = "NA";
            } elseif (empty($row["tpp06"])) {
                $tpp06 = "&nbsp;";
            } elseif (empty($row["plg06"]) || $row["plg06"] == '5') {
                $lateadjusted = $row["tpp06"] - (late_Penalty($row, $mrktask, 6) * $mrktask[6]["weight"]);
                if ($lateadjusted < 0) {
                    $lateadjusted = 0;
                }
                if ($convertmarktograde) {
                    $tpp06 = $p->convertToGrade($lateadjusted, $mrktask[6]["weight"], $mrktask[6]["type"]);
                } else {
                    $tpp06 = sprintf("%.1f", $lateadjusted);
                }
                $bgtpp06 = "";
                if (late_Penalty($row, $mrktask, 6)) {
                    $bgtpp06 = 'bgcolor="#CCFF00"';
                    $titltpp06 = "LATE PENALTY\n\n";
                }
            } else {
                $tpp06 = "PLG";
            }
            $duedate = getDueDate($locationid, $termid, 7, $cleaneddue, $mrktask[$num]["splitweek"], $mrktask[$num]["dueday"], $mrktask[$num]["duetime"]);
            if (!empty($row["tpp07"]) && empty($mrktask[7]["marks"])) {
                $tpp07 = "NA";
            } elseif (empty($row["tpp07"])) {
                $tpp07 = "&nbsp;";
            } elseif (empty($row["plg07"]) || $row["plg07"] == '5') {
                $lateadjusted = $row["tpp07"] - (late_Penalty($row, $mrktask, 7) * $mrktask[7]["weight"]);
                if ($lateadjusted < 0) {
                    $lateadjusted = 0;
                }
                if ($convertmarktograde) {
                    $tpp07 = $p->convertToGrade($lateadjusted, $mrktask[7]["weight"], $mrktask[7]["type"]);
                } else {
                    $tpp07 = sprintf("%.1f", $lateadjusted);
                }
                $bgtpp07 = "";
                if (late_Penalty($row, $mrktask, 7)) {
                    $bgtpp07 = 'bgcolor="#CCFF00"';
                    $titltpp07 = "LATE PENALTY\n\n";
                }
            } else {
                $tpp07 = "PLG";
            }
            $duedate = getDueDate($locationid, $termid, 8, $cleaneddue, $mrktask[$num]["splitweek"], $mrktask[$num]["dueday"], $mrktask[$num]["duetime"]);
            if (!empty($row["tpp08"]) && empty($mrktask[8]["marks"])) {
                $tpp08 = "NA";
            } elseif (empty($row["tpp08"])) {
                $tpp08 = "&nbsp;";
            } elseif (empty($row["plg08"]) || $row["plg08"] == '5') {
                $lateadjusted = $row["tpp08"] - (late_Penalty($row, $mrktask, 8) * $mrktask[8]["weight"]);
                if ($lateadjusted < 0) {
                    $lateadjusted = 0;
                }
                if ($convertmarktograde) {
                    $tpp08 = $p->convertToGrade($lateadjusted, $mrktask[8]["weight"], $mrktask[8]["type"]);
                } else {
                    $tpp08 = sprintf("%.1f", $lateadjusted);
                }
                $bgtpp08 = "";
                if (late_Penalty($row, $mrktask, 8)) {
                    $bgtpp08 = 'bgcolor="#CCFF00"';
                    $titltpp08 = "LATE PENALTY\n\n";
                }
            } else {
                $tpp08 = "PLG";
            }

            $duedate = getDueDate($locationid, $termid, 9, $cleaneddue, $mrktask[$num]["splitweek"], $mrktask[$num]["dueday"], $mrktask[$num]["duetime"]);
            if (!empty($row["tpp09"]) && empty($mrktask[9]["marks"])) {
                $tpp09 = "NA";
            } elseif (empty($row["tpp09"])) {
                $tpp09 = "&nbsp;";
            } elseif (empty($row["plg09"]) || $row["plg09"] == '5') {
                $lateadjusted = $row["tpp09"] - (late_Penalty($row, $mrktask, 9) * $mrktask[9]["weight"]);
                if ($lateadjusted < 0) {
                    $lateadjusted = 0;
                }
                if ($convertmarktograde) {
                    $tpp09 = $p->convertToGrade($lateadjusted, $mrktask[9]["weight"], $mrktask[9]["type"]);
                } else {
                    $tpp09 = sprintf("%.1f", $lateadjusted);
                }
                $bgtpp09 = "";
                if (late_Penalty($row, $mrktask, 9)) {
                    $bgtpp09 = 'bgcolor="#CCFF00"';
                    $titltpp09 = "LATE PENALTY\n\n";
                }
            } else {
                $tpp09 = "PLG";
            }

            $duedate = getDueDate($locationid, $termid, 10, $cleaneddue, $mrktask[$num]["splitweek"], $mrktask[$num]["dueday"], $mrktask[$num]["duetime"]);
            if (!empty($row["tpp10"]) && empty($mrktask[10]["marks"])) {
                $tpp10 = "NA";
            } elseif (empty($row["tpp10"])) {
                $tpp10 = "&nbsp;";
            } elseif (empty($row["plg10"]) || $row["plg10"] == '5') {
                $lateadjusted = $row["tpp10"] - (late_Penalty($row, $mrktask, 10) * $mrktask[10]["weight"]);
                if ($lateadjusted < 0) {
                    $lateadjusted = 0;
                }
                if ($convertmarktograde) {
                    $tpp10 = $p->convertToGrade($lateadjusted, $mrktask[10]["weight"], $mrktask[10]["type"]);
                } else {
                    $tpp10 = sprintf("%.1f", $lateadjusted);
                }
                $bgtpp10 = "";
                if (late_Penalty($row, $mrktask, 10)) {
                    $bgtpp10 = 'bgcolor="#CCFF00"';
                    $titltpp10 = "LATE PENALTY\n\n";
                }
            } else {
                $tpp10 = "PLG";
            }

            $duedate = getDueDate($locationid, $termid, 11, $cleaneddue, $mrktask[$num]["splitweek"], $mrktask[$num]["dueday"], $mrktask[$num]["duetime"]);
            if (!empty($row["tpp11"]) && empty($mrktask[11]["marks"])) {
                $tpp11 = "NA";
            } elseif (empty($row["tpp11"])) {
                $tpp11 = "&nbsp;";
            } elseif (empty($row["plg11"]) || $row["plg11"] == '5') {
                $lateadjusted = $row["tpp11"] - (late_Penalty($row, $mrktask, 11) * $mrktask[11]["weight"]);
                if ($lateadjusted < 0) {
                    $lateadjusted = 0;
                }
                if ($convertmarktograde) {
                    $tpp11 = $p->convertToGrade($lateadjusted, $mrktask[11]["weight"], $mrktask[11]["type"]);
                } else {
                    $tpp11 = sprintf("%.1f", $lateadjusted);
                }
                $bgas11 = "";
                if (late_Penalty($row, $mrktask, 11)) {
                    $bgtpp11 = 'bgcolor="#CCFF00"';
                    $titltpp11 = "LATE PENALTY\n\n";
                }
            } else {
                $tpp11 = "PLG";
            }

            $duedate = getDueDate($locationid, $termid, 12, $cleaneddue, $mrktask[$num]["splitweek"], $mrktask[$num]["dueday"], $mrktask[$num]["duetime"]);
            if (!empty($row["tpp12"]) && empty($mrktask[12]["marks"])) {
                $tpp01 = "NA";
            } elseif (empty($row["tpp12"])) {
                $tpp12 = "&nbsp;";
            } elseif (empty($row["plg12"]) || $row["plg12"] == '5') {
                $lateadjusted = $row["tpp12"] - (late_Penalty($row, $mrktask, 12) * $mrktask[12]["weight"]);
                if ($lateadjusted < 0) {
                    $lateadjusted = 0;
                }
                if ($convertmarktograde) {
                    $tpp12 = $p->convertToGrade($lateadjusted, $mrktask[12]["weight"], $mrktask[12]["type"]);
                } else {
                    $tpp12 = sprintf("%.1f", $lateadjusted);
                }
                $bgtpp12 = "";
                if (late_Penalty($row, $mrktask, 12)) {
                    $bgtpp12 = 'bgcolor="#CCFF00"';
                    $titltpp12 = "LATE PENALTY\n\n";
                }
            } else {
                $tpp12 = "PLG";
            }
            //Moderated
            $duedate = getDueDate($locationid, $termid, 1, $cleaneddue, $mrktask[$num]["splitweek"], $mrktask[$num]["dueday"], $mrktask[$num]["duetime"]);
            if (!empty($row["mod01"]) && empty($mrktask[1]["marks"])) {
                $mod01 = "NA";
            } elseif (empty($row["mod01"])) {
                $mod01 = "&nbsp;";
            } elseif (empty($row["plg01"]) || $row["plg01"] == '5') {
                $lateadjusted = $row["mod01"] - (late_Penalty($row, $mrktask, 1) * $mrktask[1]["weight"]);
                if ($lateadjusted < 0) {
                    $lateadjusted = 0;
                }
                if ($convertmarktograde) {
                    $mod01 = $p->convertToGrade($lateadjusted, $mrktask[1]["weight"], $mrktask[1]["type"]);
                } else {
                    $mod01 = sprintf("%.1f", $lateadjusted);
                }
                $bgmod01 = "";
                if (late_Penalty($row, $mrktask, 1)) {
                    $bgmod01 = 'bgcolor="#CCFF00"';
                    $titlmod01 = "LATE PENALTY\n\n";
                }
            } else {
                $mod01 = "PLG";
            }
            $duedate = getDueDate($locationid, $termid, 2, $cleaneddue, $mrktask[$num]["splitweek"], $mrktask[$num]["dueday"], $mrktask[$num]["duetime"]);
            if (!empty($row["mod02"]) && empty($mrktask[2]["marks"])) {
                $mod02 = "NA";
            } elseif (empty($row["mod02"])) {
                $mod02 = "&nbsp;";
            } elseif (empty($row["plg02"]) || $row["plg02"] == '5') {
                $lateadjusted = $row["mod02"] - (late_Penalty($row, $mrktask, 2) * $mrktask[2]["weight"]);
                if ($lateadjusted < 0) {
                    $lateadjusted = 0;
                }
                if ($convertmarktograde) {
                    $mod02 = $p->convertToGrade($lateadjusted, $mrktask[2]["weight"], $mrktask[2]["type"]);
                } else {
                    $mod02 = sprintf("%.1f", $lateadjusted);
                }
                $bgmod02 = "";
                if (late_Penalty($row, $mrktask, 2)) {
                    $bgmod02 = 'bgcolor="#CCFF00"';
                    $titlmod02 = "LATE PENALTY\n\n";
                }
            } else {
                $mod02 = "PLG";
            }
            $duedate = getDueDate($locationid, $termid, 3, $cleaneddue, $mrktask[$num]["splitweek"], $mrktask[$num]["dueday"], $mrktask[$num]["duetime"]);
            if (!empty($row["mod03"]) && empty($mrktask[3]["marks"])) {
                $mod03 = "NA";
            } elseif (empty($row["mod03"])) {
                $mod03 = "&nbsp;";
            } elseif (empty($row["plg03"]) || $row["plg03"] == '5') {
                $lateadjusted = $row["mod03"] - (late_Penalty($row, $mrktask, 3) * $mrktask[3]["weight"]);
                if ($lateadjusted < 0) {
                    $lateadjusted = 0;
                }
                if ($convertmarktograde) {
                    $mod03 = $p->convertToGrade($lateadjusted, $mrktask[3]["weight"], $mrktask[3]["type"]);
                } else {
                    $mod03 = sprintf("%.1f", $lateadjusted);
                }
                $bgmod03 = "";
                if (late_Penalty($row, $mrktask, 3)) {
                    $bgmod03 = 'bgcolor="#CCFF00"';
                    $titlmod03 = "LATE PENALTY\n\n";
                }
            } else {
                $mod03 = "PLG";
            }
            $bgmod04 = "";
            $duedate = getDueDate($locationid, $termid, 4, $cleaneddue, $mrktask[$num]["splitweek"], $mrktask[$num]["dueday"], $mrktask[$num]["duetime"]);
            if (!empty($row["mod04"]) && empty($mrktask[4]["marks"])) {
                $mod04 = "NA";
            } elseif (empty($row["mod04"])) {
                $mod04 = "&nbsp;";
            } elseif (empty($row["plg04"]) || $row["plg04"] == '5') {
                $lateadjusted = $row["mod04"] - (late_Penalty($row, $mrktask, 4) * $mrktask[4]["weight"]);
                if ($lateadjusted < 0) {
                    $lateadjusted = 0;
                }
                if ($convertmarktograde) {
                    $mod04 = $p->convertToGrade($lateadjusted, $mrktask[4]["weight"], $mrktask[4]["type"]);
                } else {
                    $mod04 = sprintf("%.1f", $lateadjusted);
                }
                $bgmod04 = "";
                if (late_Penalty($row, $mrktask, 4)) {
                    $bgmod04 = 'bgcolor="#CCFF00"';
                    $titlmod04 = "LATE PENALTY\n\n";
                }
            } else {
                $mod04 = "PLG";
            }
            $duedate = getDueDate($locationid, $termid, 5, $cleaneddue, $mrktask[$num]["splitweek"], $mrktask[$num]["dueday"], $mrktask[$num]["duetime"]);
            if (!empty($row["mod05"]) && empty($mrktask[5]["marks"])) {
                $mod05 = "NA";
            } elseif (empty($row["mod05"])) {
                $mod05 = "&nbsp;";
            } elseif (empty($row["plg05"]) || $row["plg05"] == '5') {
                $lateadjusted = $row["mod05"] - (late_Penalty($row, $mrktask, 5) * $mrktask[5]["weight"]);
                if ($lateadjusted < 0) {
                    $lateadjusted = 0;
                }
                if ($convertmarktograde) {
                    $mod05 = $p->convertToGrade($lateadjusted, $mrktask[5]["weight"], $mrktask[5]["type"]);
                } else {
                    $mod05 = sprintf("%.1f", $lateadjusted);
                }
                $bgmod05 = "";
                if (late_Penalty($row, $mrktask, 5)) {
                    $bgmod05 = 'bgcolor="#CCFF00"';
                    $titlmod05 = "LATE PENALTY\n\n";
                }
            } else {
                $mod05 = "PLG";
            }
            $duedate = getDueDate($locationid, $termid, 6, $cleaneddue, $mrktask[$num]["splitweek"], $mrktask[$num]["dueday"], $mrktask[$num]["duetime"]);
            if (!empty($row["mod06"]) && empty($mrktask[6]["marks"])) {
                $mod06 = "NA";
            } elseif (empty($row["mod06"])) {
                $mod06 = "&nbsp;";
            } elseif (empty($row["plg06"]) || $row["plg06"] == '5') {
                $lateadjusted = $row["mod06"] - (late_Penalty($row, $mrktask, 6) * $mrktask[6]["weight"]);
                if ($lateadjusted < 0) {
                    $lateadjusted = 0;
                }
                if ($convertmarktograde) {
                    $mod06 = $p->convertToGrade($lateadjusted, $mrktask[6]["weight"], $mrktask[6]["type"]);
                } else {
                    $mod06 = sprintf("%.1f", $lateadjusted);
                }
                $bgmod06 = "";
                if (late_Penalty($row, $mrktask, 6)) {
                    $bgmod06 = 'bgcolor="#CCFF00"';
                    $titlmod06 = "LATE PENALTY\n\n";
                }
            } else {
                $mod06 = "PLG";
            }
            $duedate = getDueDate($locationid, $termid, 7, $cleaneddue, $mrktask[$num]["splitweek"], $mrktask[$num]["dueday"], $mrktask[$num]["duetime"]);
            if (!empty($row["mod07"]) && empty($mrktask[7]["marks"])) {
                $mod07 = "NA";
            } elseif (empty($row["mod07"])) {
                $mod07 = "&nbsp;";
            } elseif (empty($row["plg07"]) || $row["plg07"] == '5') {
                $lateadjusted = $row["mod07"] - (late_Penalty($row, $mrktask, 7) * $mrktask[7]["weight"]);
                if ($lateadjusted < 0) {
                    $lateadjusted = 0;
                }
                if ($convertmarktograde) {
                    $mod07 = $p->convertToGrade($lateadjusted, $mrktask[7]["weight"], $mrktask[7]["type"]);
                } else {
                    $mod07 = sprintf("%.1f", $lateadjusted);
                }
                if (late_Penalty($row, $mrktask, 7)) {
                    $bgmod07 = 'bgcolor="#CCFF00"';
                    $titlmod07 = "LATE PENALTY\n\n";
                }
            } else {
                $mod07 = "PLG";
            }
            $duedate = getDueDate($locationid, $termid, 8, $cleaneddue, $mrktask[$num]["splitweek"], $mrktask[$num]["dueday"], $mrktask[$num]["duetime"]);
            if (!empty($row["mod08"]) && empty($mrktask[8]["marks"])) {
                $mod08 = "NA";
            } elseif (empty($row["mod08"])) {
                $mod08 = "&nbsp;";
            } elseif (empty($row["plg08"]) || $row["plg08"] == '5') {
                $lateadjusted = $row["mod08"] - (late_Penalty($row, $mrktask, 8) * $mrktask[8]["weight"]);
                if ($lateadjusted < 0) {
                    $lateadjusted = 0;
                }
                if ($convertmarktograde) {
                    $mod08 = $p->convertToGrade($lateadjusted, $mrktask[8]["weight"], $mrktask[8]["type"]);
                } else {
                    $mod08 = sprintf("%.1f", $lateadjusted);
                }
                $bgmod08 = "";
                if (late_Penalty($row, $mrktask, 8)) {
                    $bgmod08 = 'bgcolor="#CCFF00"';
                    $titlmod08 = "LATE PENALTY\n\n";
                }
            } else {
                $mod08 = "PLG";
            }
            $duedate = getDueDate($locationid, $termid, 9, $cleaneddue, $mrktask[$num]["splitweek"], $mrktask[$num]["dueday"], $mrktask[$num]["duetime"]);
            if (!empty($row["mod09"]) && empty($mrktask[9]["marks"])) {
                $mod09 = "NA";
            } elseif (empty($row["mod09"])) {
                $mod09 = "&nbsp;";
            } elseif (empty($row["plg09"]) || $row["plg09"] == '5') {
                $lateadjusted = $row["mod09"] - (late_Penalty($row, $mrktask, 9) * $mrktask[9]["weight"]);
                if ($lateadjusted < 0) {
                    $lateadjusted = 0;
                }
                if ($convertmarktograde) {
                    $mod09 = $p->convertToGrade($lateadjusted, $mrktask[9]["weight"], $mrktask[9]["type"]);
                } else {
                    $mod09 = sprintf("%.1f", $lateadjusted);
                }
                $bgmod09 = "";
                if (late_Penalty($row, $mrktask, 9)) {
                    $bgmod09 = 'bgcolor="#CCFF00"';
                    $titlmod09 = "LATE PENALTY\n\n";
                }
            } else {
                $mod09 = "PLG";
            }
            $duedate = getDueDate($locationid, $termid, 10, $cleaneddue, $mrktask[$num]["splitweek"], $mrktask[$num]["dueday"], $mrktask[$num]["duetime"]);
            if (!empty($row["mod10"]) && empty($mrktask[10]["marks"])) {
                $mod10 = "NA";
            } elseif (empty($row["mod10"])) {
                $mod10 = "&nbsp;";
            } elseif (empty($row["plg10"]) || $row["plg10"] == '5') {
                $lateadjusted = $row["mod10"] - (late_Penalty($row, $mrktask, 10) * $mrktask[10]["weight"]);
                if ($lateadjusted < 0) {
                    $lateadjusted = 0;
                }
                if ($convertmarktograde) {
                    $mod10 = $p->convertToGrade($lateadjusted, $mrktask[10]["weight"], $mrktask[10]["type"]);
                } else {
                    $mod10 = sprintf("%.1f", $lateadjusted);
                }
                $bgmod10 = "";
                if (late_Penalty($row, $mrktask, 10)) {
                    $bgmod10 = 'bgcolor="#CCFF00"';
                    $titlmod10 = "LATE PENALTY\n\n";
                }
            } else {
                $mod10 = "PLG";
            }
            $duedate = getDueDate($locationid, $termid, 11, $cleaneddue, $mrktask[$num]["splitweek"], $mrktask[$num]["dueday"], $mrktask[$num]["duetime"]);
            if (!empty($row["mod11"]) && empty($mrktask[11]["marks"])) {
                $mod11 = "NA";
            } elseif (empty($row["mod11"])) {
                $mod11 = "&nbsp;";
            } elseif (empty($row["plg11"]) || $row["plg11"] == '5') {
                $lateadjusted = $row["mod11"] - (late_Penalty($row, $mrktask, 11) * $mrktask[11]["weight"]);
                if ($lateadjusted < 0) {
                    $lateadjusted = 0;
                }
                if ($convertmarktograde) {
                    $mod11 = $p->convertToGrade($lateadjusted, $mrktask[11]["weight"], $mrktask[11]["type"]);
                } else {
                    $mod11 = sprintf("%.1f", $lateadjusted);
                }
                $bgmod11 = "";
                if (late_Penalty($row, $mrktask, 11)) {
                    $bgmod11 = 'bgcolor="#CCFF00"';
                    $titlmod11 = "LATE PENALTY\n\n";
                }
            } else {
                $mod11 = "PLG";
            } //enelse

            $duedate = getDueDate($locationid, $termid, 12, $cleaneddue, $mrktask[$num]["splitweek"], $mrktask[$num]["dueday"], $mrktask[$num]["duetime"]);
            if (!empty($row["mod12"]) && empty($mrktask[12]["marks"])) {
                $mod12 = "NA";
            } elseif (empty($row["mod12"])) {
                $mod12 = "&nbsp;";
            } elseif (empty($row["plg12"]) || $row["plg12"] == '5') {
                $lateadjusted = $row["mod12"] - (late_Penalty($row, $mrktask, 12) * $mrktask[12]["weight"]);
                if ($lateadjusted < 0) {
                    $lateadjusted = 0;
                }
                if ($convertmarktograde) {
                    $mod12 = $p->convertToGrade($lateadjusted, $mrktask[12]["weight"], $mrktask[12]["type"]);
                } else {
                    $mod12 = sprintf("%.1f", $lateadjusted);
                }
                $bgmod12 = "";
                if (late_Penalty($row, $mrktask, 12)) {
                    $bgmod12 = 'bgcolor="#CCFF00"';
                    $titlmod12 = "LATE PENALTY\n\n";
                }
            } else {
                $mod12 = "PLG";
            }
            //End here.


            $message = " ";

            if (!empty($row["message"])) {
                $message = "<span class='boldred'>" . stripslashes($row["message"]) . "</span>";
            }
            if (!empty($row["didnotpass"]) && $showdidnotpass) {
                if (!empty($message)) {
                    $message = $message . '<br><br>';
                }
                $message = $message . "<span class='boldbigred'>" . stripslashes($row["didnotpass"]) . "<br></span>";
            }
            $bgmessage = "";
            if (!empty($row["message"]) || (!empty($row["didnotpass"]) && $showdidnotpass)) {
                $bgmessage = "bgcolor=#FFFF00";
            }

            //Check for unit description
            $udsql = "select udd.*
                from unitdescriptiondetail as udd
                  inner join unitlocation as ul
                    on  ul.locationid = udd.locationid
                    and ul.termid = udd.termid
                    and ul.unitid = udd.unitid
                where udd.locationid = '$locationid'
                and udd.termid = '$termid'
                and udd.unitid = '$unitid'
                and udd.udtype in ('orggen','seq1','seq2','seq3','seq4','staff','time')
                and ul.udstatus = 'P'";

            $udsql_ok = mysqli_query($db, $udsql) or die(basename(__FILE__, '.php') . "-14: " . mysqli_error($db));

            if (mysqli_num_rows($udsql_ok) == 0) {
                $unitiddisplay = '<span title="' . $unitname . '">' . $row["unitid"] . '</span>';
            } else {
                $unitiddisplay = '<span title="Course description for ' . $unitname . '">' . '<a href="javascript:unitdescription(\'' . $locationid . '\',\'' . $termid . '\',\'' . $unitid . '\')">' . $unitid . '</a>' . '</span>';
            }
            if ($marksaccess == 'M' || $moderationtype == 'U') { //Moderated marks
                if ($moderationtype == 'U') {
                    $typedisplay = '<td align="center">Not applicable</td>';
                } else {
                    $typedisplay = '<td align="center">Moderated</td>';
                }
                $studentdetailstemp = '
            <tr>
            <td  width="*" align="center">'
                    . $row["termid"] .
                    '</td>
            <td width="*" align="center">'
                    . $unitiddisplay .
                    '</td>'
                    . $typedisplay .
                    '<td width="*" title="' . $titlmod01 . $mrktask[1]["description"] . ' - Weight: ' . $mrktask[1]["weight"] . '%" align="center" ' . $bgmod01 . '>'
                    . $mod01 .
                    '</td>
            <td width="*" title="' . $titlmod02 . $mrktask[2]["description"] . ' - Weight: ' . $mrktask[2]["weight"] . '%" align="center" ' . $bgmod02 . '>'
                    . $mod02 .
                    '</td>
            <td width="*" title="' . $titlmod03 . $mrktask[3]["description"] . ' - Weight: ' . $mrktask[3]["weight"] . '%" align="center" ' . $bgmod03 . '>'
                    . $mod03 .
                    '</td>
            <td width="*" title="' . $titlmod04 . $mrktask[4]["description"] . ' - Weight: ' . $mrktask[4]["weight"] . '%" align="center" ' . $bgmod04 . '>'
                    . $mod04 .
                    '</td>
            <td width="*" title="' . $titlmod05 . $mrktask[5]["description"] . ' - Weight: ' . $mrktask[5]["weight"] . '%" align="center" ' . $bgmod05 . '>'
                    . $mod05 .
                    '</td>
            <td width="*" title="' . $titlmod06 . $mrktask[6]["description"] . ' - Weight: ' . $mrktask[6]["weight"] . '%" align="center" ' . $bgmod06 . '>'
                    . $mod06 .
                    '</td>
            <td width="*" title="' . $titlmod07 . $mrktask[7]["description"] . ' - Weight: ' . $mrktask[7]["weight"] . '%" align="center" ' . $bgmod07 . '>'
                    . $mod07 .
                    '</td>
            <td width="*" title="' . $titlmod08 . $mrktask[8]["description"] . ' - Weight: ' . $mrktask[8]["weight"] . '%" align="center" ' . $bgmod08 . '>'
                    . $mod08 .
                    '</td>
            <td width="*" title="' . $titlmod09 . $mrktask[9]["description"] . ' - Weight: ' . $mrktask[9]["weight"] . '%" align="center" ' . $bgmod09 . '>'
                    . $mod09 .
                    '</td>
            <td width="*" title="' . $titlmod10 . $titlmod01 . $mrktask[10]["description"] . ' - Weight: ' . $mrktask[10]["weight"] . '%" align="center" ' . $bgmod10 . '>'
                    . $mod10 .
                    '</td>
            <td width="*" title="' . $titlmod11 . $mrktask[11]["description"] . ' - Weight: ' . $mrktask[11]["weight"] . '%" align="center" ' . $bgmod11 . '>'
                    . $mod11 .
                    '</td>
            <td width="*" title="' . $titlmod12 . $mrktask[12]["description"] . ' - Weight: ' . $mrktask[12]["weight"] . '%" align="center" ' . $bgmod12 . '>'
                    . $mod12 .
                    '</td>
            <td width="*" align="left" ' . $bgmessage . '>'
                    . $message .
                    '</td>
          </tr>
               ';
            } elseif ($marksaccess == '' || $marksaccess == 'R') {
                $studentdetailstemp = '
          <tr>

            <td class="Types" width="*" align="center">'
                    . $row["termid"] .
                    '</td>

            <td width="*" align="center">'
                    . $unitiddisplay .
                    '</td>

            <td width="*" align="center">Un-moderated</td>
            <td width="*" title="' . $titltpp01 . $mrktask[1]["description"] . ' - Weight: ' . $mrktask[1]["weight"] . '%" align="center" ' . $bgtpp01 . '>'
                    . $tpp01 .
                    '</td>
            <td width="*" title="' . $titltpp02 . $mrktask[2]["description"] . ' - Weight: ' . $mrktask[2]["weight"] . '%" align="center" ' . $bgtpp02 . '>'
                    . $tpp02 .
                    '</td>
            <td width="*" title="' . $titltpp03 . $mrktask[3]["description"] . ' - Weight: ' . $mrktask[3]["weight"] . '%" align="center" ' . $bgtpp03 . '>'
                    . $tpp03 .
                    '</td>
            <td width="*" title="' . $titltpp04 . $mrktask[4]["description"] . ' - Weight: ' . $mrktask[4]["weight"] . '%" align="center" ' . $bgtpp04 . '>'
                    . $tpp04 .
                    '</td>
            <td width="*" title="' . $titltpp05 . $mrktask[5]["description"] . ' - Weight: ' . $mrktask[5]["weight"] . '%" align="center" ' . $bgtpp05 . '>'
                    . $tpp05 .
                    '</td>
            <td width="*" title="' . $titltpp06 . $mrktask[6]["description"] . ' - Weight: ' . $mrktask[6]["weight"] . '%" align="center" ' . $bgtpp06 . '>'
                    . $tpp06 .
                    '</td>
            <td width="*" title="' . $titltpp07 . $mrktask[7]["description"] . ' - Weight: ' . $mrktask[7]["weight"] . '%" align="center" ' . $bgtpp07 . '>'
                    . $tpp07 .
                    '</td>
            <td width="*" title="' . $titltpp08 . $mrktask[8]["description"] . ' - Weight: ' . $mrktask[8]["weight"] . '%" align="center" ' . $bgtpp08 . '>'
                    . $tpp08 .
                    '</td>
            <td width="*" title="' . $titltpp09 . $mrktask[9]["description"] . ' - Weight: ' . $mrktask[9]["weight"] . '%" align="center" ' . $bgtpp09 . '>'
                    . $tpp09 .
                    '</td>
            <td width="*" title="' . $titltpp10 . $mrktask[10]["description"] . ' - Weight: ' . $mrktask[10]["weight"] . '%" align="center" ' . $bgtpp10 . '>'
                    . $tpp10 .
                    '</td>
            <td width="*" title="' . $titltpp11 . $mrktask[11]["description"] . ' - Weight: ' . $mrktask[11]["weight"] . '%" align="center" ' . $bgtpp11 . '>'
                    . $tpp11 .
                    '</td>
            <td width="*" title="' . $titltpp12 . $mrktask[12]["description"] . ' - Weight: ' . $mrktask[12]["weight"] . '%" align="center" ' . $bgtpp12 . '>'
                    . $tpp12 .
                    '</td>
            <td width="*" align="left" ' . $bgmessage . '>'
                    . $message .
                    '</td>


          </tr>
               ';
            } else { //Both
                $studentdetailstemp = '
        <tr>
          <td rowspan="2 width="*" align="center">'
                    . $row["termid"] .
                    '</td>
          <td rowspan="2 width="*" align="center">'
                    . $unitiddisplay .
                    '</td>
          <td width="*" align="center">Un-moderated</td>
          <td title="' . $titltpp01 . $mrktask[1]["description"] . ' - Weight: ' . $mrktask[1]["weight"] . '%"align="right" ' . $bgtpp01 . '>'
                    . $tpp01 .
                    '</td>
          <td title="' . $titltpp02 . $mrktask[2]["description"] . ' - Weight: ' . $mrktask[2]["weight"] . '%" align="right" ' . $bgtpp02 . '>'
                    . $tpp02 .
                    '</td>
          <td title="' . $titltpp03 . $mrktask[3]["description"] . ' - Weight: ' . $mrktask[3]["weight"] . '%" align="right" ' . $bgtpp03 . '>'
                    . $tpp03 .
                    '</td>
          <td title="' . $titltpp04 . $mrktask[4]["description"] . ' - Weight: ' . $mrktask[4]["weight"] . '%" align="right" ' . $bgtpp04 . '>'
                    . $tpp04 .
                    '</td>
          <td title="' . $titltpp05 . $mrktask[5]["description"] . ' - Weight: ' . $mrktask[5]["weight"] . '%" align="right" ' . $bgtpp05 . '>'
                    . $tpp05 .
                    '</td>
          <td title="' . $titltpp06 . $mrktask[6]["description"] . ' - Weight: ' . $mrktask[6]["weight"] . '%" align="right" ' . $bgtpp06 . '>'
                    . $tpp06 .
                    '</td>
          <td title="' . $titltpp07 . $mrktask[7]["description"] . ' - Weight: ' . $mrktask[7]["weight"] . '%" align="right" ' . $bgtpp07 . '>'
                    . $tpp07 .
                    '</td>
          <td title="' . $titltpp08 . $mrktask[8]["description"] . ' - Weight: ' . $mrktask[8]["weight"] . '%" align="right" ' . $bgtpp08 . '>'
                    . $tpp08 .
                    '</td>
          <td title="' . $titltpp09 . $mrktask[9]["description"] . ' - Weight: ' . $mrktask[9]["weight"] . '%" align="right" ' . $bgtpp09 . '>'
                    . $tpp09 .
                    '</td>
          <td title="' . $titltpp10 . $mrktask[10]["description"] . ' - Weight: ' . $mrktask[10]["weight"] . '%" align="right" ' . $bgtpp10 . '>'
                    . $tpp10 .
                    '</td>
          <td title="' . $titltpp11 . $mrktask[11]["description"] . ' - Weight: ' . $mrktask[11]["weight"] . '%" align="right" ' . $bgtpp11 . '>'
                    . $tpp11 .
                    '</td>
          <td title="' . $titltpp12 . $mrktask[12]["description"] . ' - Weight: ' . $mrktask[12]["weight"] . '%" align="right" ' . $bgtpp12 . '>'
                    . $tpp12 .
                    '</td>
          <td rowspan="2" width="*" align="left" ' . $bgmessage . '>'
                    . $message .
                    '</td>
        </tr>



        <tr>
          <td width="*" align="center">Moderated</td>
          <td align="right" ' . $bgmod01 . '>'
                    . $mod01 .
                    '</td>
          <td align="right" ' . $bgmod02 . '>'
                    . $mod02 .
                    '</td>
          <td align="right" ' . $bgmod03 . '>'
                    . $mod03 .
                    '</td>
          <td align="right" ' . $bgmod04 . '>'
                    . $mod04 .
                    '</td>
          <td align="right" ' . $bgmod05 . '>'
                    . $mod05 .
                    '</td>
          <td align="right" ' . $bgmod06 . '>'
                    . $mod06 .
                    '</td>
          <td align="right" ' . $bgmod07 . '>'
                    . $mod07 .
                    '</td>
          <td align="right" ' . $bgmod08 . '>'
                    . $mod08 .
                    '</td>
          <td align="right" ' . $bgmod09 . '>'
                    . $mod09 .
                    '</td>
          <td align="right" ' . $bgmod10 . '>'
                    . $mod10 .
                    '</td>
          <td align="right" ' . $bgmod11 . '>'
                    . $mod11 .
                    '</td>
          <td align="right" ' . $bgmod12 . '>'
                    . $mod12 .
                    '</td>
        </tr>
        ';
            }
            $studentdetails = $studentdetails . $studentdetailstemp;
        }
    }

    /**
     * Funtion __construct. 
     */
    public function __construct()
    {
        basePage::basePageFunction();
    }
}

$p = new marks_page();

if (empty($_SESSION["mrkaccessallowed"])) {
    exit;
}

// If form has been submitted fields then move on, or initialise some work fields.
$p->process_form();

// Output page.
$p->display_page();

// Initialise for next time around.
$_SESSION['mrkmsg'] = '';
?>