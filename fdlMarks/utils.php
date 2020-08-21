<?php

//Had to place these two functions in the same directory as calling script (i.e. fdlMarks directory) because call to fdlGrades version ofthese functions returned incorrect value in spite of the fact that the arguments were exactly the same. Bizarre.

function getDueDate($arglocationid, $argtermid, $argtaskno, $argdueweek, $argsplitweek, $argdueday, $argduetime)
{
    global $p, $db;

    if ($argdueweek == 'Other') {
        return strtotime($argduetime);
    }
    if (empty($argdueday)) {
        $argdueday = '4';
    }
    if (!is_numeric($argdueday)) {
        $argdueday = '4';
    }
    if (empty($argduetime)) {
        $argduetime = '16:00';
    }
    if (!is_numeric($argdueweek)) { // We can't do anything if the due week was not an actual week number (e.g. they may have a range)
        return 0;
    }
    $splitweeksql = '';
    if (!empty($argsplitweek)) {
        $splitweeksql = " and lti.locationtermitemid = $argsplitweek ";
    }
    $sql = "select lti.termdate
            from locationterm as lt
              inner join locationtermitem as lti
                on lti.locationtermid = lt.locationtermid
            where lt.locationid = '$arglocationid'
            and lt.termid = '$argtermid'
            and upper(lti.description) = 'WEEK " . $argdueweek . "'"
        . $splitweeksql;

    $sql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-01: " . mysqli_error($db));

    if (mysqli_num_rows($sql_ok) !== 0) { // Record found.

        $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__, '.php') . "-02: " . mysqli_error($db));

        if (empty($row['termdate'])) {
            return 0; // Can't determine the date when the task was due.
        } else {
            $temp = $row['termdate'];
        }
    } else {
        return 0; // Can't determine the date when the task was due.
    }
    $termdate = strtotime($temp);

    if (empty($termdate)) {
        return 0; // Can't determine the date when the task was due.
    }
    if (!checkdate(date('m', $termdate), date('d', $termdate), date('Y', $termdate))) {
        return 0; // Can't determine the date when the task was due.
    }
    $hour = substr($argduetime, 0, strpos($argduetime, ':'));
    $minute = substr($argduetime, strpos($argduetime, ':') + 1, 2);

    $duedate = mktime($hour, $minute, 0, date('m', $termdate), date('d', $termdate) + $argdueday - 1, date('Y', $termdate));

    return $duedate;
}
function late_penalty($argrow, $argtaskarray, $argtaskno)
{
    $duedate = '';
    if (empty($argtaskarray[$argtaskno]["dueday"])) {
        $argtaskarray[$argtaskno]["dueday"] = '4';
    }
    if (is_numeric($argtaskarray[$argtaskno]["dueday"])) {
        $duedate = getDueDate($argrow["locationid"], $argrow["termid"], $argtaskno, $argtaskarray[$argtaskno]["due"], $argtaskarray[$argtaskno]["splitweek"], $argtaskarray[$argtaskno]["dueday"], $argtaskarray[$argtaskno]["duetime"]);
    }
    if (!$duedate || !is_numeric($argtaskarray[$argtaskno]["due"])) {
        return 0;
    }
    switch ($argtaskno) {
        case 1:
            $submitteddate = $argrow["sub01"];
            $extension = $argrow["ext01"];
            break;
        case 2:
            $submitteddate = $argrow["sub02"];
            $extension = $argrow["ext02"];
            break;
        case 3:
            $submitteddate = $argrow["sub03"];
            $extension = $argrow["ext03"];
            break;
        case 4:
            $submitteddate = $argrow["sub04"];
            $extension = $argrow["ext04"];
            break;
        case 5:
            $submitteddate = $argrow["sub05"];
            $extension = $argrow["ext05"];
            break;
        case 6:
            $submitteddate = $argrow["sub06"];
            $extension = $argrow["ext06"];
            break;
        case 7:
            $submitteddate = $argrow["sub07"];
            $extension = $argrow["ext07"];
            break;
        case 8:
            $submitteddate = $argrow["sub08"];
            $extension = $argrow["ext08"];
            break;
        case 9:
            $submitteddate = $argrow["sub09"];
            $extension = $argrow["ext09"];
            break;
        case 10:
            $submitteddate = $argrow["sub10"];
            $extension = $argrow["ext10"];
            break;
        case 11:
            $submitteddate = $argrow["sub11"];
            $extension = $argrow["ext11"];
            break;
        case 12:
            $submitteddate = $argrow["sub12"];
            $extension = $argrow["ext12"];
            break;
    }
    if (!$submitteddate) {
        return 0;
    }
    if (empty($argtaskarray[$argtaskno]["duetime"])) {
        $duetime = '16:00';
    }
    $hour = substr($argduetime, 0, strpos($duetime, ':'));
    $second = substr($duetime, strpos($duetime, ':') + 1, 2);

    $adjustedsubmitteddate = $submitteddate;
    if ($extension) {
        $adjustedsubmitteddate = mktime($hour, $second, 0, date('m', $submitteddate), date('d', $submitteddate) - $extension, date('Y', $submitteddate));
    }
    $difference = $adjustedsubmitteddate - $duedate;

    if ($difference <= 0) {
        return 0;
    }
    $daysdifference = $difference / 86400;

    if ($daysdifference > 0 && $daysdifference <= 1) {
        return .1;
    }
    if ($daysdifference > 1 && $daysdifference <= 2) {
        return .2;
    }
    if ($daysdifference > 2 && $daysdifference <= 3) {
        return .3;
    }
    if ($daysdifference > 3 && $daysdifference <= 4) {
        return .4;
    }
    if ($daysdifference > 4 && $daysdifference <= 5) {
        return .5;
    }
    if ($daysdifference > 5 && $daysdifference <= 6) {
        return .6;
    }
    if ($daysdifference > 6 && $daysdifference <= 7) {
        return .7;
    }
    if ($daysdifference > 7 && $daysdifference <= 8) {
        return .8;
    }
    if ($daysdifference > 8 && $daysdifference <= 9) {
        return .9;
    }
    if ($daysdifference > 9) {
        return 1;
    }
    return 0;
}
function getSystemLink($systemlinkid)
{
    global $p, $db;

    $sql = "select *
            from systemlink
            where systemlinkid = '$systemlinkid'
            and ifnull(hide,'') = ''";

    $sql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-03: " . mysqli_error($db));

    if (mysqli_num_rows($sql_ok) > 0) {
        $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__, '.php') . "-04: " . mysqli_error($db));
        return stripslashes($row['link']);
    } else {
        return false;
    }
}
function getUnit($unitid, &$unitname, &$unitlevel, &$unitcreditpoint, &$unitasced, &$unitsubdisciplineid, &$unitdisciplineid, &$unitacaddivid, &$unitgradingbasis, &$unitprofessionalengagement, &$weboutline, &$nosupplementary)
{
    global $p, $db;

    $sql = "select u.*, d.disciplineid, ad.acaddivid
            from unit as u
              inner join subdiscipline as sd
                on sd.subdisciplineid = u.subdisciplineid
              inner join discipline as d
                on d.disciplineid = sd.disciplineid
              inner join acaddiv as ad
                on ad.acaddivid = d.acaddivid
            where u.unitid = '$unitid'";

    $sql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . "-05: " . mysqli_error($db));

    if (mysqli_num_rows($sql_ok) == 0) { // No record found.
        $unitname = '';
        $unitlevel = '';
        $unitcreditpoint = '';
        $unitasced = '';
        $unitsubdisciplineid = '';
        $unitdisciplineid = '';
        $unitacaddivid = '';
        $unitgradingbasis = '';
        $unitprofessionalengagement = '';
        $weboutline = '';
        $nosupplementary = '';
        return false;
    } else {
        $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__, '.php') . "-06: " . mysqli_error($db));
        $unitname = stripslashes($row["name"]);
        $unitlevel = $row["level"];
        $unitcreditpoint = $row["creditpoint"];
        $unitasced = $row["asced"];
        $unitsubdisciplineid = $row["subdisciplineid"];
        $unitdisciplineid = $row["disciplineid"];
        $unitacaddivid = $row["acaddivid"];
        $unitgradingbasis = $row["gradingbasis"];
        $unitprofessionalengagement = $row["professionalengagement"];
        $weboutline = $row["weboutline"];
        $nosupplementary = $row["nosupplementary"];
        return true;
    }
}
function getLevel($minimumlevel, $maximumlevel)
{
    if (empty($minimumlevel) || empty($maximumlevel)) {
        return '';
    }
    if ($_SESSION[$_GET["trid"] . "sysunitdigits"] == '4') {
        switch ($minimumlevel) {
            case '1':
                $level = ' at ' . $minimumlevel . '000-';
                break;
            case '2':
                $level = ' at ' . $minimumlevel . '000-';
                break;
            case '3':
                $level = ' at ' . $minimumlevel . '000-';
                break;
            case '4':
                $level = ' at ' . $minimumlevel . '000-';
                break;
            case '5':
                $level = ' at ' . $minimumlevel . '000-';
                break;
            case '6':
                $level = ' at ' . $minimumlevel . '000-';
                break;
            case '7':
                $level = ' at ' . $minimumlevel . '000-';
                break;
            case '8':
                $level = ' at ' . $minimumlevel . '000-';
                break;
            case '9':
                $level = ' at ' . $minimumlevel . '000-';
                break;
        }
        $level = $level . $maximumlevel . '999 level';
    } elseif ($_SESSION[$_GET["trid"] . "sysunitdigits"] == '3') {
        switch ($minimumlevel) {
            case '1':
                $level = ' at ' . $minimumlevel . '00-';
                break;
            case '2':
                $level = ' at ' . $minimumlevel . '00-';
                break;
            case '3':
                $level = ' at ' . $minimumlevel . '00-';
                break;
            case '4':
                $level = ' at ' . $minimumlevel . '00-';
                break;
            case '5':
                $level = ' at ' . $minimumlevel . '00-';
                break;
            case '6':
                $level = ' at ' . $minimumlevel . '00-';
                break;
            case '7':
                $level = ' at ' . $minimumlevel . '00-';
                break;
            case '8':
                $level = ' at ' . $minimumlevel . '00-';
                break;
            case '9':
                $level = ' at ' . $minimumlevel . '00-';
                break;
        }
        $level = $level . $maximumlevel . '99 level';
    }
    return $level;
}
