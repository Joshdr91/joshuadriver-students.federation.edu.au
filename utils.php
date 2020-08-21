<?php

include_once("vendor/autoload.php");
require_once 'host/config.php';

// static TRID
define('_TRID', '5EF');

  //subdisciplineid, locationid, and termid constants
  define('_allOFF', false);
  define('_allON', true);
  define('_checkarchiveOFF', false);
  define('_checkarchiveON', true);
  define('_coursetypeOFF', false);
  define('_coursetypeON', true);
  define('_disabledOFF', false);
  define('_disabledON', true);
  define('_disableacaddivOFF', false);
  define('_disableacaddivON', true);
  define('_displaynameOFF', false);
  define('_displaynameON', true);
  define('_enrollingonlyON', 2);
  define('_enrollingpublishedOFF', false);
  define('_enrollingpublishedON', 3);
  define('_formnameOFF', false);
  define('_ignorelidOFF', false);
  define('_ignorelidON', true);
  define('_ignoresidOFF', false);
  define('_ignoresidON', true);
  define('_ignoreunitlocationOFF', false);
  define('_ignoreunitlocationON', true);
  define('_includehiddenOFF', false);
  define('_includehiddenON', true);
  define('_includetrustedOFF', false);
  define('_includetrustedON', true);
  define('_ignorehiddenOFF', false);
  define('_ignorehiddenON', true);
  define('_INsubdisciplineidOFF', false);
  define('_INsubdisciplineidON', true);
  define('_labelOFF', false);
  define('_labelON', true);
  define('_multipleOFF', false);
  define('_multipleON', true);
  define('_partneruniversityOFF', false);
  define('_partneruniversityON', true);
  define('_placementOFF', false);
  define('_placementON', true);
  define('_pleasewaitOFF', false);
  define('_pleasewaitON', true);
  define('_publishedonlyON', 1);
  define('_sevenstermOFF', false);
  define('_sevenstermON', true);
  define('_sortbynameOFF', false);
  define('_sortbynameON', true);
  define('_userlocationOFF', false);
  define('_userlocationON', true);

  //for convert_for_html
  define('_stripalllinefeedOFF', false);
  define('_stripalllinefeedON', true);
  define('_stripAtagOFF', false);
  define('_stripAtagON', true);
  define('_striplinebreakOFF', false);
  define('_striplinebreakON', true);
  define('_tablesettingOFF', false);
  define('_tablesettingON', true);

  //for letters
  define('_alreadyreportedOFF', false);
  define('_alreadyreportedON', true);
  define('_dearOFF', false);
  define('_dearON', true);
  define('_foldguideOFF', false);
  define('_foldguideON', true);
  define('_headerheadingOFF', false);
  define('_headerheadingON', true);
  define('_letteridOFF', false);
  define('_letteridON', true);
  define('_previewOFF', false);
  define('_previewON', true);
  define('_towhomOFF', false);
  define('_towhomON', true);
  define('_termidOFF', false);
  define('_termidON', true);
  define('_semesterOFF', false);
  define('_semesterON', true);
  define('_yearOFF', false);
  define('_yearON', true);

  //for getcreditunit
  define('_equivalentseparateOFF', false);
  define('_equivalentseparateON', true);
  define('_includecreditidOFF', false);
  define('_includecreditidON', true);

  //for getStudent
  define('_uppperlastnameOFF', false);
  define('_uppperlastnameON', true);

  //for escapeinput
  define('_escpercentOFF', false);
  define('_escpercentON', true);
  define('_escunderscoreOFF', false);
  define('_escunderscoreON', true);
  
  //for student photos
  define('_randomhashkey', $GLOBALS['fdlconfig']['photos']['hashkey']);
  
  //for planstatus
  define('_APonlyOFF', false);
  define('_APonlyON', true);
  define('_checkforblankstatusOFF', false);
  define('_checkforblankstatusON', true);
  define('_cscompleteOFF', false);
  define('_nameOFF', false);
  define('_processingpleasewaitOFF', false);
  define('_processingpleasewaitON', true);
  define('_restrictaccessOFF', false);
  define('_restrictaccessON', true);
  define('_statusOFF', false);
  
  function escapeinput($in, $escpercent, $escunderscore)
  {
      $out = trim($in);
      $banned = array(
      ';',
      '=',
      '..',
      'DELETE ',
      'DROP ',
      'FROM ',
      'INSERT ',
      'SELECT ',
      'TABLE ',
      'UPDATE ',
      'WHERE '
    );
      $out = str_ireplace($banned, '', $out);
      $out = str_ireplace($banned, '', $out);
      if (stripos($out, '"') !== false) {
          $out = str_replace('"', "''", $out);
      }//endif
      if (stripos($out, "'") !== false) {
          $out = stripslashes($out);
      }//endif
      $out = addslashes($out);
      if ($escpercent && stripos($out, '%') !== false) {
          $out = str_replace('%', "\%", $out);
      }//endif
      if ($escunderscore && stripos($out, '_') !== false) {
          $out = str_replace('_', "\_", $out);
      }//endif

      return $out;
  }//endfunction

  function getDueDate($arglocationid, $argtermid, $argtaskno, $argdueweek, $argsplitweek, $argdueday, $argduetime)
  {
      global $p, $db;
    
      if ($argdueweek == 'Other') {
          return strtotime($argduetime);
      }//endif

      if (empty($argdueday)) {
          $argdueday = '4';
      }//endif
      if (!is_numeric($argdueday)) {
          $argdueday = '4';
      }//endif

      if (empty($argduetime)) {
          $argduetime = '16:00';
      }//endif

      if (!is_numeric($argdueweek)) { // We can't do anything if the due week was not an actual week number (e.g. they may have a range)
          return 0;
      }//endif

      $splitweeksql = '';
      if (!empty($argsplitweek)) {
          $splitweeksql = " and lti.locationtermitemid = $argsplitweek ";
      }//endif

      $sql = "select lti.termdate
            from locationterm as lt
              inner join locationtermitem as lti
                on lti.locationtermid = lt.locationtermid
            where lt.locationid = '$arglocationid'
            and lt.termid = '$argtermid'
            and upper(lti.description) = 'WEEK " . $argdueweek . "'"
            . $splitweeksql;

      $sql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php')."-001: ".mysqli_error($db));

      if (mysqli_num_rows($sql_ok)!==0) { // Record found.

          $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__, '.php')."-002: ".mysqli_error($db));

          if (empty($row['termdate'])) {
              return 0; // Can't determine the date when the task was due.
          }//endif
      else {
          $temp = $row['termdate'];
      }//endelse
      }//endif
      else {
          return 0; // Can't determine the date when the task was due.
      }//endif

    $termdate = strtotime($temp);

      if (empty($termdate)) {
          return 0; // Can't determine the date when the task was due.
      }//endif

    if (!checkdate(date('m', $termdate), date('d', $termdate), date('Y', $termdate))) {
        return 0; // Can't determine the date when the task was due.
    }//endif

    $hour = substr($argduetime, 0, strpos($argduetime, ':'));
      $minute = substr($argduetime, strpos($argduetime, ':') + 1, 2);

      $duedate = mktime($hour, $minute, 0, date('m', $termdate), date('d', $termdate) + $argdueday - 1, date('Y', $termdate));

      return $duedate;
  }//endfunction

  function late_penalty($argrow, $argtaskarray, $argtaskno)
  {
      $duedate = '';
      if (empty($argtaskarray[$argtaskno]["dueday"])) {
          $argtaskarray[$argtaskno]["dueday"] = '4';
      }//endif
    
      if (empty($argtaskarray[$argtaskno]["duetime"])) {
          $argtaskarray[$argtaskno]["duetime"]="16:00";
      }
      if (is_numeric($argtaskarray[$argtaskno]["dueday"])) {
          $duedate = getDueDate($argrow["locationid"], $argrow["termid"], $argtaskno, $argtaskarray[$argtaskno]["due"], $argtaskarray[$argtaskno]["splitweek"], $argtaskarray[$argtaskno]["dueday"], $argtaskarray[$argtaskno]["duetime"]);
      }//endif

      if (!$duedate || !is_numeric($argtaskarray[$argtaskno]["due"])) {
          return 0;
      }//endif

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
    }//endcase

      if (!$submitteddate) {
          return 0;
      }//endif

      //Obtain the due date/time and adjust for any extensions
      if (empty($argtaskarray[$argtaskno]["duetime"])) {
          $duetime = '16:00';
      } else {
          $duetime =  $argtaskarray[$argtaskno]["duetime"];
      }//endif
    

      $hour = substr($duetime, 0, strpos($duetime, ':'));
      $second = substr($duetime, strpos($duetime, ':') + 1, 2);
    

      $adjustedsubmitteddate = $submitteddate;
      if ($extension) {
          $adjustedsubmitteddate = mktime($hour, $second, 0, date('m', $submitteddate), date('d', $submitteddate) - $extension, date('Y', $submitteddate));
      }//endif

      $difference = $adjustedsubmitteddate - $duedate;

      if ($difference <= 0) {
          return 0;
      }//endif

      $daysdifference = $difference / 86400;

      if ($daysdifference > 0 && $daysdifference <= 1) {
          return .1;
      }//endif

      if ($daysdifference > 1 && $daysdifference <= 2) {
          return .2;
      }//endif

      if ($daysdifference > 2 && $daysdifference <= 3) {
          return .3;
      }//endif

      if ($daysdifference > 3 && $daysdifference <= 4) {
          return .4;
      }//endif

      if ($daysdifference > 4 && $daysdifference <= 5) {
          return .5;
      }//endif

      if ($daysdifference > 5 && $daysdifference <= 6) {
          return .6;
      }//endif

      if ($daysdifference > 6 && $daysdifference <= 7) {
          return .7;
      }//endif
    
      if ($daysdifference > 7 && $daysdifference <= 8) {
          return .8;
      }//endif
    
      if ($daysdifference > 8 && $daysdifference <= 9) {
          return .9;
      }//endif

      if ($daysdifference > 9) {
          return 1;
      }//endif

      return 0;
  }//endfunction

  function getSubdiscipline($argsubdisciplineid)
  {
      global $p, $db;
    
      $subdiscipline = array();
    
      if ($_SESSION["mrkssubdiscipline"]) {
          $tempsubdisciplinearray = $_SESSION["mrkssubdiscipline"];
      }//endif
      else {
          $tempsubdisciplinearray = $_SESSION[$_GET["trid"] . "subdiscipline"];
      }//endelse

      $subdiscipline = array();

      foreach ($tempsubdisciplinearray as $temp) {
          if ($temp["subdisciplineid"] == $argsubdisciplineid) {
              $subdiscipline["acaddivid"] = $temp["acaddivid"];
              $subdiscipline["acaddivshortname"] = $temp["acaddivshortname"];
              $subdiscipline["acaddivlongname"] = $temp["acaddivlongname"];
              $subdiscipline["disciplineid"] = $temp["disciplineid"];
              $subdiscipline["disciplineshortname"] = $temp["disciplineshortname"];
              $subdiscipline["disciplinelongname"] = $temp["disciplinelongname"];
              $subdiscipline["subdisciplineid"] = $temp["subdisciplineid"];
              $subdiscipline["subdisciplineshortname"] = $temp["subdisciplineshortname"];
              $subdiscipline["subdisciplinelongname"] = $temp["subdisciplinelongname"];
              $subdiscipline["createstudentplan"]= $temp['createstudentplan'];
              $subdiscipline["accessexaminers"]= $temp['accessexaminers'];
              $subdiscipline["assessmentweeks"]= $temp['assessmentweeks'];
              $subdiscipline["loadnoabrule"] = $temp['loadnoabrule'];
              $subdiscipline["minexampct"] = $temp['minexampct'];
              $subdiscipline["maxnosamplereqdpct"] = $temp['maxnosamplereqdpct'];
              $subdiscipline["maxassessmentdueweek"] = $temp['maxassessmentdueweek'];
              $subdiscipline["convertmarktograde"] = $temp["convertmarktograde"];
              $subdiscipline["freeformgraduateattribute"] = $temp["freeformgraduateattribute"];
        
              return $subdiscipline;
          }//endif
      }//endfor
    
      return $subdiscipline;
  }//endfunction
  
  function getStrand($strandid, & $strandname, & $subdisciplineid)
  {
      global $p, $db;
    
      $sql = "select c.*, d.disciplineid, ad.acaddivid
            from course as c 
              inner join subdiscipline as sd
                on sd.subdisciplineid = c.subdisciplineid
              inner join discipline as d
                on d.disciplineid = sd.disciplineid
              inner join acaddiv as ad
                on ad.acaddivid = d.acaddivid
            where c.strandid = '$strandid'";

      $sql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php')."-003: ".mysqli_error($db));

      if (mysqli_num_rows($sql_ok)==0) {// No record found.
          $strandname='';
          $subdisciplineid='';
          return false;
      }//endif
      else {
          $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__, '.php')."-004: ".mysqli_error($db));
          $strandname = stripslashes($row["name"]);
          $subdisciplineid = $row["subdisciplineid"];
          return true;
      }//endelse
  }//endfunction
  
  function getHelp($helpcontentid)
  {
      global $p, $db;
    
      $sql = "select *
            from helpcontent
            where helpcontentid = '$helpcontentid'";

      $sql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php')."-005: ".mysqli_error($db));

      $helpcontent = '';
      if (mysqli_num_rows($sql_ok) > 0) {
          $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__, '.php')."-006: ".mysqli_error($db));
      
          $helpcontent = stripslashes($row["helpcontent"]);
      
          if (substr_count($helpcontent, '{{') > 0) {
              while (stripos($helpcontent, '{{') !== false) {
                  preg_match('/{{(.*?)}}/', $helpcontent, $match);
          
                  $searchstr = '{{' . $match[1] . '}}';
          
                  switch ($match[1]) {
            case 'sysabbreviation':
              $helpcontent = str_replace($searchstr, $_SESSION[$_GET["trid"] . "sysabbreviation"], $helpcontent);
              break;
            case 'sysname':
              $helpcontent = str_replace($searchstr, $_SESSION[$_GET["trid"] . "sysname"], $helpcontent);
              break;
            case 'COURSElabel':
              $helpcontent = str_replace($searchstr, strtoupper($_SESSION[$_GET["trid"] . "syscourselabel"]), $helpcontent);
              break;
            case 'Courselabel':
              $helpcontent = str_replace($searchstr, $_SESSION[$_GET["trid"] . "syscourselabel"], $helpcontent);
              break;
            case 'courselabel':
              $helpcontent = str_replace($searchstr, strtolower($_SESSION[$_GET["trid"] . "syscourselabel"]), $helpcontent);
              break;
            case 'UNITlabel':
              $helpcontent = str_replace($searchstr, strtoupper($_SESSION[$_GET["trid"] . "sysunitlabel"]), $helpcontent);
              break;
            case 'Unitlabel':
              $helpcontent = str_replace($searchstr, $_SESSION[$_GET["trid"] . "sysunitlabel"], $helpcontent);
              break;
            case 'unitlabel':
              $helpcontent = str_replace($searchstr, strtolower($_SESSION[$_GET["trid"] . "sysunitlabel"]), $helpcontent);
              break;
            default:
              $helpcontent = str_replace($searchstr, $match[1], $helpcontent);
              break;
          }//endswitch
              }//endwhile
          }//endif
      }//endif
    
      return $helpcontent;
  }//endfunction
  
  function getLocation($locationid, & $locationname, & $loadmoderationtype, & $partneruniversity, & $examshecup)
  {
      global $p, $db;
    
      $sql = "select *
            from location               
            where locationid = '$locationid'";

      $sql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php')."-007: ".mysqli_error($db));

      if (mysqli_num_rows($sql_ok)==0) {// No record found.
          $locationname='';
          $loadmoderationtype='';
          $partneruniversity='';
          $examshecup='';
          return false;
      }//endif
      else {
          $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__, '.php')."-008: ".mysqli_error($db));
          $locationname = stripslashes($row["locname"]);
          $loadmoderationtype = $row["loadmoderationtype"];
          $partneruniversity = $row["partneruniversity"];
          $examshecup = $row["examshecup"];
          return true;
      }//endelse
  }//endfunction

  function getUnit($unitid, & $unitname, & $unitlevel, & $unitcreditpoint, & $unitasced, & $unitsubdisciplineid, & $unitdisciplineid, & $unitacaddivid, & $unitgradingbasis, & $unitprofessionalengagement, & $weboutline, & $nosupplementary)
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

      $sql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php')."-009: ".mysqli_error($db));

      if (mysqli_num_rows($sql_ok)==0) {// No record found.
          $unitname='';
          $unitlevel='';
          $unitcreditpoint='';
          $unitasced='';
          $unitsubdisciplineid='';
          $unitdisciplineid='';
          $unitacaddivid='';
          $unitgradingbasis='';
          $unitprofessionalengagement='';
          $weboutline='';
          $nosupplementary='';
          return false;
      }//endif
      else {
          $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__, '.php')."-010: ".mysqli_error($db));
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
      }//endelse
  }//endfunction

  function getUnitName($unitid)
  { // Get unit name

      global $p, $db;

      $sql = "select `name`
            from unit
            where unitid = '$unitid'";

      $sql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php')."-011: ".mysqli_error($db));

      if (mysqli_num_rows($sql_ok)==0) { // No record found.
          return "";
      }//endif
      else {
          $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__, '.php')."-012: ".mysqli_error($db));
          return stripslashes($row['name']);
      }//endelse
  }//endfunction

  function locationOptions($argformname, $arguserlocation, $argincludetrusted, & $locationid, $multiple, $ignorelid, $all, $pleasewait, $label, $includehidden, $INsubdisciplineid, $ignoreunitlocation, $partneruniversity)
  {
      global $p, $db;

      $changecancelbutton = '';
      if ($pleasewait) {
          $changecancelbutton = 'document.' . $argformname . '.btnCancel.style.color=\'#FF0000\';document.' . $argformname . '.btnCancel.value=\'Processing ... please wait\';';
      }//endif

      if ($argformname) {
          $locationOptions = '<select name="optlocationid" onchange="'.$changecancelbutton.'document.' . $argformname . '.submit();">';
      }//endif
      else {
          $locationOptions = '<select name="optlocationid">';
      }//endelse

      if ($label) {
          $locationOptions = $locationOptions . '<option value="">--Location--</option>';
      }//endif
      else {
          $locationOptions = $locationOptions . '<option value=""></option>';
      }//endelse

      if ($all) {
          if ($_POST["optlocationid"]=='ALL') {
              $locationOptions = $locationOptions . '<option selected value="ALL">ALL</option>';
              $locationOptions = $locationOptions . '<option value=""></option>';
              $locationid = 'ALL';
          }//endif
          else {
              $locationOptions = $locationOptions . '<option value="ALL">ALL</option>';
              $locationOptions = $locationOptions . '<option value=""></option>';
          }//endelse
      }//endif

    if ($multiple) {
        if ($_POST["optlocationid"]=='MULTIPLE') {
            $locationOptions = $locationOptions . '<option selected value="MULTIPLE">MULTIPLE</option>';
            $locationid = 'MULTIPLE';
        }//endif
        else {
            $locationOptions = $locationOptions . '<option value="MULTIPLE">MULTIPLE</option>';
        }//endelse
    }//endif

    $locationsql = '';
      if ($arguserlocation) {
          $userlocations = '';
          if (is_array($arguserlocation)) {
              foreach ($arguserlocation as $key=>$userlocationid) {
                  if ($userlocations) {
                      $userlocations = $userlocations . ',';
                  }//endif
                  $userlocations = $userlocations . "'" . $userlocationid . "'";
              }//endfor
          }//endif
      else {
          $userlocations = "'" . $arguserlocation . "'";
      }//endelse

          if (empty($locationid) && empty($_POST["optlocationid"])) {
              if (is_array($arguserlocation)) {
                  $_POST["optlocationid"] = $arguserlocation[0];
              }//endif
              else {
                  $_POST["optlocationid"] = $arguserlocation;
              }//endelse
          }//endif

      $locationsql = ' and locationid in (' . $userlocations .')';
      }//endif

      $includehiddensql = " and ifnull(hide,'') = '' ";
      if ($includehidden) {
          $includehiddensql = '';
      }//endif

      $limittofilterssql = '';
      if ($INsubdisciplineid && $INsubdisciplineid !== "('ALL')" && !$ignoreunitlocation) {
          $limittofilterssql = " and exists(select *
                                        from unitlocation as ul
                                          inner join unit as u
                                            on u.unitid = ul.unitid
                                        where ul.locationid = l.locationid
                                        and u.subdisciplineid in $INsubdisciplineid) ";
      }//endif

      $includetrustedsql = "  and loadmoderationtype <> 'T' ";
      if ($argincludetrusted) {
          $includetrustedsql = '';
      }//endif
    
      $partneruniversitysql = '';
      if ($partneruniversity) {
          $partneruniversitysql = " and l.partneruniversity = '$partneruniversity' ";
      }//endif

      $sql = "select locationid, campus, locname
            from location as l
            where true
            $includehiddensql
            $locationsql
            $limittofilterssql
            $includetrustedsql
            $partneruniversitysql
            order by locationid";

      $locationsql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php')."-013: ".mysqli_error($db));

      for ($locationi=0; $locationi < mysqli_num_rows($locationsql_ok); $locationi++) {
          $locationrow = mysqli_fetch_array($locationsql_ok) or die(basename(__FILE__, '.php')."-014: ".mysqli_error($db));
          $temp = $locationrow['locationid'];
          $campus = $locationrow['campus'];
          $locname = $locationrow['locname'];
          $campuslocname = '';
          if ($campus) {
              $campuslocname = $campus;
          }//endif
          if ($locname) {
              $campuslocname = $locname;
          }//endif
          if ($campus && $locname) {
              $campuslocname = $campus . ' - ' . $locname;
          }//endif

          if (!$ignorelid) {
              $tempoptlocationid = $_SESSION[$_GET["trid"] . "lid"];
          }//endif
          if (!empty($locationid)) {
              $tempoptlocationid = $locationid;
          }//endif
          if (!empty($_POST["optlocationid"])) {
              $tempoptlocationid = $_POST["optlocationid"];
          }//endif

          $locationid = $tempoptlocationid;
          if ($temp == $tempoptlocationid) {
              $locationOptions = $locationOptions . "\n<option title='$campuslocname' selected value='$temp'>$temp</option>";
          }//endif
          else {
              $locationOptions = $locationOptions . "\n<option title='$campuslocname' value='$temp'>$temp</option>";
          }//endelse
      }//endfor

    $locationOptions = $locationOptions . '</select>';

      return $locationOptions;
  }//endfunction

  function pad($field, $length, $direction)
  { // pad left or right

      if (strlen($field)>$length) {
          return substr($field, 0, $length);
      }//endif

      $temp = trim($field);

      for ($i = 1; $i <= $length - strlen($field); $i++) {
          if ($direction=="padleft") {
              $temp = '&nbsp;'. $temp;
          }//endif
          else {
              $temp = $temp . '&nbsp;';
          }//endelse
      }//endfor
    return $temp;
  }//endfunction

  function explodeINsubdisciplineid($argINsubdisciplineid)
  {
      $temp = str_replace("(", "", $argINsubdisciplineid);
      $temp = str_replace(")", "", $temp);
      $temp = str_replace(" ", "", $temp);
      $temp = explode(",", $temp);
      return $temp;
  }//endfunction

  function subdisciplineOptions($argformname, $label, & $INsubdisciplineid, $all, $ignoresid, $pleasewait, $disableacaddiv, $ignorehidden)
  {
      $changecancelbutton = '';
      if ($pleasewait) {
          $changecancelbutton = 'document.' . $argformname . '.btnCancel.style.color=\'#FF0000\';document.' . $argformname . '.btnCancel.value=\'Processing ... please wait\';';
      }//endif

      $INsubdisciplineid = " (";

      if ($argformname) {
          $subdisciplineOptions = '<select name="optsubdisciplineid" onchange="'.$changecancelbutton.'document.' . $argformname . '.submit();">';
      }//endif
      else {
          $subdisciplineOptions = '<select name="optsubdisciplineid">';
      }//endelse

      if ($label) {
          $subdisciplineOptions = $subdisciplineOptions . '<option value="">--'.$_SESSION[$_GET["trid"] . "sysacaddivlabel"].' / Discipline--</option>';
      }//endif
      if ($all) {
          if ($_POST["optsubdisciplineid"]=='ALL') {
              $subdisciplineOptions = $subdisciplineOptions . '<option selected value="ALL">ALL</option>';
              $subdisciplineOptions = $subdisciplineOptions . '<option value=""></option>';
              $subdisciplineid = 'ALL';
          }//endif
          else {
              $subdisciplineOptions = $subdisciplineOptions . '<option value="ALL">ALL</option>';
              $subdisciplineOptions = $subdisciplineOptions . '<option value=""></option>';
          }//endelse
      }//endif

    foreach ($_SESSION[$_GET["trid"] . "usersubdiscipline"] as $subdiscipline) {
        $tempsubdisciplineid = $subdiscipline["subdisciplineid"];
        $tempsubdisciplineshortname = $subdiscipline["subdisciplineshortname"];
        $tempsubdisciplinelongname = $subdiscipline["subdisciplinelongname"];
        $disciplineid = $subdiscipline["disciplineid"];
        $disciplineshortname = $subdiscipline["disciplineshortname"];
        $disciplinelongname = $subdiscipline["disciplinelongname"];
        $acaddivid = $subdiscipline["acaddivid"];
        $acaddivlongname = $subdiscipline["acaddivlongname"];
        $acaddivhide = $subdiscipline["acaddivhide"];
        $disciplinehide = $subdiscipline["disciplinehide"];
        $subdisciplinehide = $subdiscipline["subdisciplinehide"];
      
        if ($ignorehidden && ($acaddivhide || $disciplinehide || $subdisciplinehide)) {
            continue;
        }//endif

        $temp = '';
        $temp1 = '';

        if (empty($tempsubdisciplineshortname) && empty($disciplineshortname)) {//acaddiv/school
            $temp = '0'. $acaddivid;
            $temp1 = '<span >' . $acaddivid . '</span>';
            $title = ' title="'.$acaddivlongname.'" ';
            $style = '';
            if ($acaddivhide) {
                $style = ' style="color:magenta;" ';
            }//endif
        }//endif
      if (empty($tempsubdisciplineshortname) && !empty($disciplineshortname)) {//discipline
        $temp = '1'. $disciplineid;
          $temp1 = str_repeat('&nbsp;', 4) . $disciplineshortname;
          $title = ' title="'.$disciplinelongname.'" ';
          $style = '';
          if ($disciplinehide) {
              $style = ' style="color:magenta;" ';
          }//endif
      }//endif
      if (!empty($tempsubdisciplineshortname) && ($tempsubdisciplineshortname !== $disciplineshortname)) {//subdiscipline
        $temp = '2'. $tempsubdisciplineid;
          $temp1 = str_repeat('&nbsp;', 8) . $tempsubdisciplineshortname;
          $title = ' title="'.$tempsubdisciplinelongname.'" ';
          $style = '';
          if ($subdisciplinehide) {
              $style = ' style="color:magenta;" ';
          }//endif
      }//endif

      if (!empty($temp)) {
          if ($_GET["getsubdisciplineid"]) {
              $tempsubdiscipline = $_GET["getsubdisciplineid"];
          }//endif
          if (isset($_POST["optsubdisciplineid"])) {
              $tempsubdiscipline = $_POST["optsubdisciplineid"];
          }//endif

          if (!$ignoresid && !$disableacaddiv && !isset($_POST["optsubdisciplineid"])) {
              $sesssid = getSubdiscipline($_SESSION[$_GET["trid"] . "sid"]);
              $tempsubdiscipline = '0' . $sesssid["acaddivid"];
          }//endif

          //check if need to disable higher levels
          $disabled = '';
          $level = substr($temp, 0, 1);
          if ($disableacaddiv && $level == '0') {
              $disabled = ' disabled ';
          }//endif

          if (($temp == $tempsubdiscipline || count($_SESSION[$_GET["trid"] . "usersubdiscipline"]) == 3) && ($_POST["optsubdisciplineid"] !== 'ALL')) {
              $subdisciplineOptions = $subdisciplineOptions . "\n<option $title $style selected $disabled value='$temp'>$temp1</option>";
              $selectedlevel = substr($temp, 0, 1);
              $selectedacaddivid = $acaddivid;
              $selecteddisciplineid = $disciplineid;
              $selectedsubdisciplineid = substr($temp, 1);
          }//endif
          else {
              $subdisciplineOptions = $subdisciplineOptions . "\n<option $title $style $disabled value='$temp'>$temp1</option>";
          }//endelse
      }//endif
    }//endfor

      $subdisciplineOptions = $subdisciplineOptions . '</select>';

      if ($subdisciplineid == 'ALL') {
          $INsubdisciplineid = "('ALL')";
          return $subdisciplineOptions;
      }//endif

      reset($_SESSION[$_GET["trid"] . "usersubdiscipline"]);
      foreach ($_SESSION[$_GET["trid"] . "usersubdiscipline"] as $subdiscipline) {
          $subdisciplineid = $subdiscipline["subdisciplineid"];
          $subdisciplineshortname = $subdiscipline["subdisciplineshortname"];
          $disciplineid = $subdiscipline["disciplineid"];
          $disciplineshortname = $subdiscipline["disciplineshortname"];
          $acaddivid = $subdiscipline["acaddivid"];

          //determine current level
          if (empty($disciplineid) && empty($subdisciplineid)) {
              $currentlevel = '0';//acaddiv/school
          }//endif
      if (!empty($disciplineid) && empty($subdisciplineid)) {
          $currentlevel = '1';//discipline
      }//endif
      if (!empty($disciplineid) && !empty($subdisciplineid)) {
          $currentlevel = '2';//subdiscipline
      }//endif

      if ($currentlevel == '2' && (($selectedlevel == '0' && $acaddivid == $selectedacaddivid) || ($selectedlevel == '1' && $disciplineid == $selecteddisciplineid) || ($selectedlevel == '2' && $subdisciplineid == $selectedsubdisciplineid))) {
          if ($INsubdisciplineid <> " (") {
              $INsubdisciplineid = $INsubdisciplineid  .  ", ";
          }//endif

          $INsubdisciplineid = $INsubdisciplineid  . $subdisciplineid;
      }//endif
      }//endfor

      $INsubdisciplineid = $INsubdisciplineid . ") ";

      if ($INsubdisciplineid == " () ") {// if empty set to something it wont find
          $INsubdisciplineid = "(9999999999)";
      }//endif

      return $subdisciplineOptions;
  }//endfunction

  function subdisciplineupdateOptions($argsubdisciplineid, $argINsubdisciplineid, $arglabel, $argignorehidden)
  {
      $subdisciplineupdateOptions = '<select name="optsubdisciplineid" onchange="document.forms[0].submit()">';

      if ($arglabel) {
          $subdisciplineupdateOptions = $subdisciplineupdateOptions . "\n<option value=''>Discipline / ".$_SESSION[$_GET["trid"] . "sysacaddivlabel"]."</option>";
      }//endif
      else {
          $subdisciplineupdateOptions = $subdisciplineupdateOptions . "\n<option value=''></option>";
      }//endelse

      $tempINsubdisciplineid = explodeINsubdisciplineid($argINsubdisciplineid);//check if only loading what was chosen in browse screen otherwise load all.

      foreach ($_SESSION[$_GET["trid"] . "usersubdiscipline"] as $tempacaddiv) {
          $acaddivhide = $tempacaddiv["acaddivhide"];
          $disciplinehide = $tempacaddiv["disciplinehide"];
          $subdisciplinehide = $tempacaddiv["subdisciplinehide"];
      
          if ($argignorehidden && ($acaddivhide || $disciplinehide || $subdisciplinehide)) {
              continue;
          }//endif

          if (empty($argINsubdisciplineid)) {
              $acaddivyes = true;
          }//endif
          else {
              $acaddivyes = false;
              foreach ($tempINsubdisciplineid as $tempINsubdiscipline) {
                  if ($tempINsubdiscipline == $tempacaddiv["subdisciplineid"]) {
                      $acaddivyes = true;
                  }//endif
              }//endfor
          }//endelse

      if ($acaddivyes) {
          $temp = $tempacaddiv["subdisciplineid"];
          $subdiscipline = getSubdiscipline($temp);
          $temp1 = $subdiscipline["subdisciplineshortname"] . ' (' . $subdiscipline["acaddivid"] . '-' . $subdiscipline["disciplineshortname"] .")";
          if ($subdiscipline["subdisciplineshortname"] == $subdiscipline["disciplineshortname"]) {
              $temp1 = $subdiscipline["subdisciplineshortname"] . ' (' . $subdiscipline["acaddivid"] .")";
          }//endif
          $displaysubdiscipline = $subdiscipline["subdisciplinelongname"]. ' ('. $subdiscipline["acaddivlongname"] . '-' .$subdiscipline["disciplinelongname"] . ')';
          if ($subdiscipline["subdisciplineshortname"] == $subdiscipline["disciplineshortname"]) {
              $displaysubdiscipline = $subdiscipline["subdisciplinelongname"]. ' (' .$subdiscipline["acaddivlongname"] .")";
          }//endif

          if ($temp) {
              if ($temp == $argsubdisciplineid || (count($tempINsubdisciplineid) == 1 && !empty($tempINsubdisciplineid[0]))) {
                  $subdisciplineupdateOptions = $subdisciplineupdateOptions . "\n<option title='$displaysubdiscipline' selected value='$temp'>" . $temp1 ."</option>";
              }//endif
              else {
                  $subdisciplineupdateOptions = $subdisciplineupdateOptions . "\n<option title='$displaysubdiscipline' value='$temp'>" . $temp1 . "</option>";
              }//endif
          }//endif
      }//endif
      }//endfor
      $subdisciplineupdateOptions = $subdisciplineupdateOptions . '</select>';

      return $subdisciplineupdateOptions;
  }//endfunction

  function termOptions($argfieldname, $argformname, & $termid, $enrollingpublished, $pleasewait, $checkarchive, $label, $includetrusted, $includehidden, $INsubdisciplineid, $ignoreunitlocation, $sevensterm)
  {
      global $p, $db;

      $changecancelbutton = '';
      if ($pleasewait) {
          $changecancelbutton = 'document.' . $argformname . '.btnCancel.style.color=\'#FF0000\';document.' . $argformname . '.btnCancel.value=\'Processing ... please wait\';';
      }//endif

      if ($argformname) {
          $termOptions = '<select name="'.$argfieldname.'" onchange="'.$changecancelbutton.'document.' . $argformname . '.submit();">';
      }//endif
      else {
          $termOptions = '<select name="'.$argfieldname.'">';
      }//endelse

      $enrollingpublishedsql = '';
      if ($enrollingpublished == _enrollingpublishedON) {
          $enrollingpublishedsql = " and (enrolcheck = '1' or resultcheck = '1') ";
      }//endif
      if ($enrollingpublished == _publishedonlyON) {
          $enrollingpublishedsql = " and resultcheck = '1' ";
      }//endif
      if ($enrollingpublished == _enrollingonlyON) {
          $enrollingpublishedsql = " and enrolcheck = '1' ";
      }//endif

      $includetrustedsql = '';
      if (!empty($includetrusted)) {
          $includetrustedsql = " and exists (select *
                                         from unitlocation as ul
                                         where ul.termid = t.termid
                                         and ul.moderationtype = 'T') ";
      }//endif

      $includehiddensql = " and ifnull(hide,'') = '' ";
      if ($includehidden) {
          $includehiddensql = '';
      }//endif

      $limittofilterssql = '';
      if ($INsubdisciplineid && !empty($_POST["optlocationid"]) && !in_array($_POST["optlocationid"], array('ALL','MULTIPLE')) && $INsubdisciplineid !== "('ALL')" && !$ignoreunitlocation) {
          $limittofilterssql = " and exists (select *
                                         from unitlocation as ul
                                           inner join unit as u
                                             on u.unitid = ul.unitid
                                         where ul.locationid = '".$_POST["optlocationid"]
                                     ."' and ul.termid = t.termid
                                         and u.subdisciplineid in $INsubdisciplineid) ";
      }//endif
      elseif ($INsubdisciplineid && !empty($_POST["optlocationid"]) && !in_array($_POST["optlocationid"], array('ALL','MULTIPLE')) && !$ignoreunitlocation) {
          $limittofilterssql = " and exists (select *
                                         from unitlocation as ul
                                         where ul.locationid = '".$_POST["optlocationid"]
                                     ."' and ul.termid = t.termid) ";
      }//endelseif
      elseif ($INsubdisciplineid && $INsubdisciplineid !== "('ALL')" && !$ignoreunitlocation) {
          $limittofilterssql = " and exists (select *
                                         from unitlocation as ul
                                           inner join unit as u
                                             on u.unitid = ul.unitid
                                         where ul.termid = t.termid
                                         and u.subdisciplineid in $INsubdisciplineid) ";
      }//endelse

      if ($label) {
          if ($label === true) {
              $termOptions = $termOptions . '<option value="">--Term--</option>';
          }//endif
          else {
              $termOptions = $termOptions . '<option value="">--'.$label.'--</option>';
          }//endelse
      }//endif
    else {
        $termOptions = $termOptions . '<option value=""></option>';
    }//endelse
    
      $sevenstermsql = '';
      if ($sevensterm === true) {
          $sevenstermsql =" and substr(termid,6,2) in ('03','07','17','23','27') ";
      }//endif

      $sql = "select termid, description
            from term as t
            where true
            $includehiddensql
            $enrollingpublishedsql
            $includetrustedsql
            $limittofilterssql
            $sevenstermsql
            order by length(termid) DESC, termid DESC";

      $termsql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php')."-015: ".mysqli_error($db));

      $selectedtermid = '';
      for ($termi=0; $termi < mysqli_num_rows($termsql_ok); $termi++) {
          $termrow = mysqli_fetch_array($termsql_ok) or die(basename(__FILE__, '.php')."-016: ".mysqli_error($db));
          $temp = $termrow['termid'];

          if (!empty($termid)) {
              $tempopttermid = $termid;
          }//endif
          if (isset($_POST[$argfieldname])) {
              $tempopttermid = $_POST[$argfieldname];
          }//endif

          $description = stripslashes($termrow['description']);

          $termid = $tempopttermid;
          if ($temp == $tempopttermid) {
              $termOptions = $termOptions . "\n<option title='$description' selected value='$temp'>$temp</option>";
              $selectedtermid = $tempopttermid;
          }//endif
          else {
              $termOptions = $termOptions . "\n<option title='$description' value='$temp'>$temp</option>";
          }//endelse
      }//endfor

    if ($checkarchive && $selectedtermid) {
        $sql = "select *
              from unitstudentarchive
              where termid = '$selectedtermid'";

        $tempql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php')."-017: ".mysqli_error($db));

        if (mysqli_num_rows($tempql_ok) && ($argformname !== 'frmresults' || ($argformname == 'frmresults' && isset($_POST["btnGo"])))) {
            echo '<script language=\'javascript\'>
              sWidth = screen.width;
              sHeight = screen.height;
              sLeft = (sWidth - (sWidth *.45)) / 2;
              sTop = (sHeight - (sHeight *.4)) / 2;
              newWindow=window.open("archivemessage.php?trid='.$_GET["trid"].'","fdlgarchivemessage","resizable=no, scrollbars=no, menubar=no, width=" + sWidth *.45  + ", height=" + sHeight *.3 + ", top=" + sTop + ", left=" + sLeft + "");</script>';
        }//endif
    }//endif

      $termOptions = $termOptions . '</select>';

      return $termOptions;
  }//endfunction

  function unitOptions($argfieldname, $argformname, & $unitid, $INsubdisciplineid, $pleasewait, $label, $includehidden, $placement, $ignoreunitlocation)
  {
      global $p, $db;

      $changecancelbutton = '';
      if ($pleasewait) {
          $changecancelbutton = 'document.' . $argformname . '.btnCancel.style.color=\'#FF0000\';document.' . $argformname . '.btnCancel.value=\'Processing ... please wait\';';
      }//endif

      if ($argformname) {
          $unitOptions = '<select name="'.$argfieldname.'" onchange="'.$changecancelbutton.'document.' . $argformname . '.submit();">';
      }//endif
      else {
          $unitOptions = '<select name="'.$argfieldname.'">';
      }//endelse

      $includehiddensql = " and ifnull(hide,'') = '' ";
      if ($includehidden) {
          $includehiddensql = '';
      }//endif

      $limittofilterssql = '';
      if (!empty($_POST["optlocationid"]) && !in_array($_POST["optlocationid"], array('ALL','MULTIPLE')) && !empty($_POST["opttermid"]) && !$ignoreunitlocation) {
          $limittofilterssql = " and exists (select *
                                         from unitlocation as ul
                                         where ul.locationid = '".$_POST["optlocationid"]
                                    . "' and ul.termid = '".$_POST["opttermid"]
                                    . "' and ul.unitid = u.unitid) ";
      }//endif
      elseif (!empty($_POST["optlocationid"]) && !in_array($_POST["optlocationid"], array('ALL','MULTIPLE')) && !$ignoreunitlocation) {
          $limittofilterssql = " and exists (select *
                                         from unitlocation as ul
                                         where ul.locationid = '".$_POST["optlocationid"]
                                     ."' and ul.unitid = u.unitid) ";
      }//endelseif
      elseif (!empty($_POST["opttermid"]) && !$ignoreunitlocation) {
          $limittofilterssql = " and exists (select *
                                         from unitlocation as ul
                                         where ul.termid = '".$_POST["opttermid"]
                                     ."' and ul.unitid = u.unitid) ";
      }//endelse

      $INsubdisciplineidsql = "";
      if ($INsubdisciplineid) {
          $INsubdisciplineidsql = " and subdisciplineid in $INsubdisciplineid ";
      }//endif

      $placementsql = '';
      if ($placement) {
          $placementsql = " and placementcomponent = '1' ";
      }//endif

      if ($label) {
          if ($label === true) {
              $unitOptions = $unitOptions . '<option value="">--'.$_SESSION[$_GET["trid"] . "sysunitlabel"].'--</option>';
          }//endif
          else {
              $unitOptions = $unitOptions . '<option value="">--'.$label.'--</option>';
          }//endelse
      }//endif
    else {
        $unitOptions = $unitOptions . '<option value=""></option>';
    }//endelse

      $sql = "select unitid, `name`
            from unit as u
            where true
            $includehiddensql
            $placementsql
            $limittofilterssql
            $INsubdisciplineidsql
            order by length(unitid) desc, unitid";

      $unitsql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php')."-018: ".mysqli_error($db));

      for ($uniti=0; $uniti < mysqli_num_rows($unitsql_ok); $uniti++) {
          $unitrow = mysqli_fetch_array($unitsql_ok) or die(basename(__FILE__, '.php')."-019: ".mysqli_error($db));
          $temp = $unitrow['unitid'];

          if (!empty($unitid)) {
              $tempoptunitid = $unitid;
          }//endif
          if (isset($_POST[$argfieldname])) {
              $tempoptunitid = $_POST[$argfieldname];
          }//endif

          $name = stripslashes($unitrow['name']);

          $unitid = $tempoptunitid;
          if ($temp == $tempoptunitid) {
              $unitOptions = $unitOptions . "\n<option title='$name' selected value='$temp'>$temp</option>";
          }//endif
          else {
              $unitOptions = $unitOptions . "\n<option title='$name' value='$temp'>$temp</option>";
          }//endelse
      }//endfor

    $unitOptions = $unitOptions . '</select>';

      return $unitOptions;
  }//endfunction

  function strandOptions($argfieldname, $argformname, & $strandid, $INsubdisciplineid, $pleasewait, $label, $includehidden, $sortbyname, $displayname, $disabled, $coursetype)
  {
      global $p, $db;

      $changecancelbutton = '';
      if ($pleasewait) {
          $changecancelbutton = 'document.' . $argformname . '.btnCancel.style.color=\'#FF0000\';document.' . $argformname . '.btnCancel.value=\'Processing ... please wait\';';
      }//endif

      $disabledon = '';
      if ($disabled) {
          $disabledon = ' DISABLED ';
      }//endif

      if ($argformname) {
          $strandOptions = '<select name="'.$argfieldname.'" '.$disabledon.' onchange="'.$changecancelbutton.'document.' . $argformname . '.submit();">';
      }//endif
      else {
          $strandOptions = '<select '.$disabledon.' name="'.$argfieldname.'">';
      }//endelse

      $includehiddensql = " and ifnull(hide,'') = '' ";
      if ($includehidden) {
          $includehiddensql = '';
      }//endif

      $coursetypesql = '';
      if ($coursetype) {
          $coursetypesql = " and `type` = '$coursetype' ";
      }//endif

      $INsubdisciplineidsql = "";
      if ($INsubdisciplineid) {
          $INsubdisciplineidsql = " and subdisciplineid in $INsubdisciplineid ";
      }//endif

      if ($label) {
          if ($label === true) {
              $strandOptions = $strandOptions . '<option value="">--'.$_SESSION[$_GET["trid"] . "syscourselabel"].'--</option>';
          }//endif
          else {
              $strandOptions = $strandOptions . '<option value="">--'.$label.'--</option>';
          }//endelse
      }//endif
    else {
        $strandOptions = $strandOptions . '<option value=""></option>';
    }//endelse

      $sortby = ' order by strandid ';
      if ($sortbyname) {
          $sortby = ' order by `name` ';
      }//endif

      $sql = "select strandid, `name`
            from course as c
            where true
            $includehiddensql
            $coursetypesql
            $INsubdisciplineidsql
            $sortby";

      $strandsql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php')."-020: ".mysqli_error($db));

      $selectedstrandid = '';
      for ($strandi=0; $strandi < mysqli_num_rows($strandsql_ok); $strandi++) {
          $strandrow = mysqli_fetch_array($strandsql_ok) or die(basename(__FILE__, '.php')."-021: ".mysqli_error($db));
          $temp = $strandrow['strandid'];

          if (!empty($strandid)) {
              $tempoptstrandid = $strandid;
          }//endif
          if (isset($_POST[$argfieldname])) {
              $tempoptstrandid = $_POST[$argfieldname];
          }//endif

          $name = stripslashes($strandrow['name']);

          $displaytemp = $temp;
          $displaytemp1 = $name;
          if ($displayname) {
              $displaytemp = $name;
              $displaytemp1 = $temp;
          }//endif

          $strandid = $tempoptstrandid;
          if ($temp == $tempoptstrandid) {
              $strandOptions = $strandOptions . "\n<option title='$displaytemp1' selected value='$temp'>$displaytemp</option>";
          }//endif
          else {
              $strandOptions = $strandOptions . "\n<option title='$displaytemp1' value='$temp'>$displaytemp</option>";
          }//endelse
      }//endfor

    $strandOptions = $strandOptions . '</select>';

      return $strandOptions;
  }//endfunction

  function format_datetime($datetime_in, $format_in, $format_out, $validate)
  {
      $datetime_in = trim($datetime_in);

      if (empty($datetime_in) && $format_in !== 'sysdate') {
          return false;
      }//endif

      $hh = '';
      $ii = '';
      $ss = '';

      $months = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');

      switch ($format_in) {
      case "sysdatetime":
        $yyyy = date("Y");
        $mm = date("m");
        $dd = date("d");
        $hh = date("H");
        $ii = date("i");
        $ss = date("s");
        break;
      case "Ymd":
        $yyyy = substr($datetime_in, 0, 4);
        $mm = substr($datetime_in, 4, 2);
        $dd = substr($datetime_in, 6, 2);
        break;
      case "m/d/Y":
        list($mm, $dd, $yyyy) = explode('/', $datetime_in);
        break;
      case "d/m/Y":
        list($dd, $mm, $yyyy) = explode('/', $datetime_in);
        break;
      case "d/m/Y H:i":
        $temp = $datetime_in;
        $datetime_in = str_replace('/', ' ', $datetime_in);
        $datetime_in = str_replace(':', ' ', $datetime_in);
        $datetime_in = str_replace('  ', ' ', $datetime_in);
        list($dd, $mm, $yyyy, $hh, $ii) = explode(' ', $datetime_in);
        $datetime_in = $temp;
        break;
      case "Y-m-d H:i:s":
        $datetimeparts = explode('-', $datetime_in);
        $yyyy = $datetimeparts[0];
        $mm = $datetimeparts[1];

        $datetime_in1 = $datetimeparts[2];
        $datetime_in2 = explode(' ', $datetime_in1);

        $dd = $datetime_in2[0];

        $timeparts = explode(':', $datetime_in2[1]);
        $hh = $timeparts[0];
        $ii = $timeparts[1];
        $ss = $timeparts[2];
        break;
      case "Y-m-d":
          $datetimeparts = explode('-', $datetime_in);
          $yyyy = $datetimeparts[0];
          $mm = $datetimeparts[1];
  
          $datetime_in1 = $datetimeparts[2];
          $datetime_in2 = explode(' ', $datetime_in1);
  
          $dd = $datetime_in2[0];
          break;
      case "M j, Y":
        $temp = $datetime_in;
        $datetime_in = str_replace(',', ' ', $datetime_in);
        $datetime_in = str_replace('  ', ' ', $datetime_in);
        $datetimeparts = explode(' ', $datetime_in);
        $datetime_in = $temp;
        $yyyy = $datetimeparts[2];
        $mm = array_search($datetimeparts[0], $months) + 1;
        $dd = $datetimeparts[1];
        break;
      case "M j, Y H:i":
        $temp = $datetime_in;
        $datetime_in = str_replace(',', ' ', $datetime_in);
        $datetime_in = str_replace('  ', ' ', $datetime_in);
        $datetime_in = str_replace(':', ' ', $datetime_in);
        $datetimeparts = explode(' ', $datetime_in);
        $datetime_in = $temp;
        $yyyy = $datetimeparts[2];
        $mm = array_search($datetimeparts[0], $months) + 1;
        $MMM = $datetimeparts[0];
        $dd = $datetimeparts[1];
        $hh = $datetimeparts[3];
        $ii = $datetimeparts[4];
        break;
      case "D, j m Y, H:i A":
        $temp = $datetime_in;
        $datetime_in = str_replace(',', ' ', $datetime_in);
        $datetime_in = str_replace('  ', ' ', $datetime_in);
        $datetime_in = str_replace(':', ' ', $datetime_in);
        list($day, $dd, $mm, $yyyy, $hh, $ii, $ampm) = explode(' ', $datetime_in);
        $datetime_in = $temp;
        $temp = substr(strtoupper($mm), 0, 3);
        $months = array('ZZZ','JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEV');
        $key = array_search($temp, $months);
        $mm = $key;
        break;
    }//endswitch
    
      //validation only if requested
      if (!empty($validate)) {
          if (!is_numeric($dd) || !is_numeric($mm) || !is_numeric($yyyy)) {
              return false;
          }//endif
      
          if (!empty($MMM)) {
              $temp = strtolower($MMM);
              $temp = ucwords($temp);
              if (!in_array($temp, $months)) {
                  return false;
              }//endif
          }//endif

      if (checkdate($mm, $dd, $yyyy)===false) {
          return false;
      }//endif

          if (!empty($hh) && ($hh < '0' || $hh > '24')) {
              return false;
          }//endif

          if (!empty($ii) && ($ii < '0' || $ii > '59')) {
              return false;
          }//endif

          if (!empty($ss) && ($ss < '0' || $ss > '59')) {
              return false;
          }//endif
      }//endif

      if (empty($hh)) {
          $hh = "00";
      }//endif
      if (empty($ii)) {
          $ii = "00";
      }//endif
      if (empty($ss)) {
          $ss = "00";
      }//endif
           
      switch ($format_out) {
      case "unixdatetime":
        $datetime = mktime($hh, $ii, $ss, $mm, $dd, $yyyy);
        break;
      case "d/m/Y":
        $datetime = sprintf('%02d', $dd)  . '/' . sprintf('%02d', $mm) . '/' . $yyyy;
        break;
      case "M j, Y":
        $datetime = $months[$mm - 1] . ' ' . ltrim($dd, '0') . ', ' . $yyyy;
        break;
      case "M j, Y H:i":
        $datetime = $months[$mm - 1] . ' ' . ltrim($dd, '0') . ', ' . $yyyy . ' ' . $hh . ':' . $ii;
        break;
      case "Y-m-d":
        $datetime = $yyyy . '-' . sprintf('%02d', $mm) . '-' . sprintf('%02d', $dd);
        break;
      case "Ymd":
        $datetime = $yyyy . sprintf('%02d', $mm) . sprintf('%02d', $dd);
        break;
      case "Y-m-d H:i:s":
        $datetime = $yyyy . '-' . sprintf('%02d', $mm) . '-' . sprintf('%02d', $dd);
        $datetime = $datetime . " " .sprintf('%02d', $hh) . ":" . sprintf('%02d', $ii) . ":" . sprintf('%02d', $ss);
        break;
    }//endswitch

      return $datetime;
  }//endfunction

  function nameaddress($pdf, $tmppdf, $dear, $towhom, $headerheading, $foldguide, $studentdata)
  {
      $studentid = $studentdata[0];
      $othernames = $studentdata[1];
      $lastname = $studentdata[2];
      $address1 = trim($studentdata[3]);
      $address2 = trim($studentdata[4]);
      $address3 = trim($studentdata[5]);
      $address4 = trim($studentdata[6]);
      $citystatecountrypostal = $studentdata[7];
      
      if ($dear) {
          $dearname = trim($othernames . ' ' . $lastname);
      }//endif
      
      $html = '
        <table style="margin-left:15mm;" width="100%" border="0" cellspacing="0" cellpadding="0"><tr valign="center"><td height="100"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        ';
      
      $html = $html . '<tr><td>&nbsp;</td><td align="right" width="35%">'. date('jS F Y') .'<br></td></tr>
      ';
      $html = $html . '<tr><td>&nbsp;</td><td align="right" width="35%">'. $studentid .'</td></tr>
      ';

      $html = $html . '
        <tr><td>'.trim($othernames . ' ' . $lastname).'</td><td align="right" width="35%">&nbsp;</td></tr>
        ';
      
      if ($address1) {
          $html = $html . '
        <tr><td colspan="2">'.$address1.'</td></tr>
        ';
      }//endif
      if ($address2) {
          $html = $html . '
        <tr><td colspan="2">'.$address2.'</td></tr>
        ';
      }//endif
      if ($address3) {
          $html = $html . '
        <tr><td colspan="2">'.$address3.'</td></tr>
        ';
      }//endif
      if ($address4) {
          $html = $html . '
        <tr><td colspan="2">'.$address4.'</td></tr>
        ';
      }//endif
      $html = $html . '
        <tr><td>'.$citystatecountrypostal.'</td><td>&nbsp;</td></tr>
        ';
      $html = $html . '
        </table></td></tr></table>
        ';
      
      if ($pdf) {
          $pdf->SetFont('', '', 10);
          
          if ($foldguide) {
              $pdf->Ln(3);
          }//endif
          else {
              $pdf->SetY(10);
          }//endelse
          
          $pdf->writeHTML($html);
          
          $html = '
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
        ';
          $html = $html . '
          <tr><td align="center"><br><br>'.$headerheading.'<br><br></td></tr>
          ';
          $html = $html . '</table>';
          
          if ($headerheading) {
              $pdf->writeHTML($html);
          }//endif
          else {
              if ($foldguide) {
                  $pdf->SetY(81);
              }//endif
              else {
                  $pdf->Ln(8);
              }//endelse
          }//endelse
          
          if ($dear) {
              $pdf->SetX(25);
              $html  = '<span style="font-weight:normal;">Dear ' . $dearname .'</span>,<br>';
              $pdf->writeHTML($html);
          }//endif
          
          if ($towhom) {
              $pdf->SetX(25);
              $html  = '<span style="font-weight:normal;">To Whom It May Concern,</span><br>';
              $pdf->writeHTML($html);
          }//endif
          
          if ($foldguide) {
              $pdf->SetY(86);
              $pdf->SetX(5);
              $pdf->Cell('', 6, '-', 0, 0, 'L', 0);
              $pdf->SetRightMargin(5);
              $pdf->Cell('', 6, '-', 0, 1, 'R', 0);
              $pdf->SetRightMargin(15);
          }//endif
          
          $pdf->SetY(85);
      }//endif

      $html = '
        <table style="margin-left:15mm;" width="100%" border="0" cellspacing="0" cellpadding="0"><tr valign="center"><td height="100"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        ';
      
      $html = $html . '<tr><td>&nbsp;</td><td align="right" width="35%">'. date('jS F Y') .'<br></td></tr>
      ';
      $html = $html . '<tr><td>&nbsp;</td><td align="right" width="35%">'. $studentid .'</td></tr>
      ';
      
      $html = $html . '
        <tr><td>'.trim($othernames . ' ' . $lastname).'</td><td align="right" width="35%">&nbsp;</td></tr>
        ';
      
      if ($address1) {
          $html = $html . '
        <tr><td colspan="2">'.$address1.'</td></tr>
        ';
      }//endif
      if ($address2) {
          $html = $html . '
        <tr><td colspan="2">'.$address2.'</td></tr>
        ';
      }//endif
      if ($address3) {
          $html = $html . '
        <tr><td colspan="2">'.$address3.'</td></tr>
        ';
      }//endif
      if ($address4) {
          $html = $html . '
        <tr><td colspan="2">'.$address4.'</td></tr>
        ';
      }//endif
      $html = $html . '
        <tr><td>'.$citystatecountrypostal.'</td><td>&nbsp;</td></tr>
        ';
      $html = $html . '
        </table></td></tr></table>
        ';
      
      if ($tmppdf) {
          $tmppdf->SetFont('', '', 10);
          
          if ($foldguide) {
              $tmppdf->Ln(3);
          }//endif
          else {
              $tmppdf->SetY(15);
          }//endelse
          
          $tmppdf->writeHTML($html);
          
          $html = '
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
          ';
          $html = $html . '
          <tr><td align="center"><br><br>'.$headerheading.'<br><br></td></tr>
          ';
          $html = $html . '</table>';
          
          if ($headerheading) {
              $tmppdf->writeHTML($html);
          }//endif
          else {
              if ($foldguide) {
                  $tmppdf->SetY(81);
              }//endif
              else {
                  $tmppdf->Ln(8);
              }//endelse
          }//endelse
          
          if ($dear) {
              $tmppdf->SetX(25);
              $html  = '<span style="font-weight:normal;">Dear ' . $dearname .'</span>,<br>';
              $tmppdf->writeHTML($html);
          }//endif
          
          if ($towhom) {
              $tmppdf->SetX(25);
              $html  = '<span style="font-weight:normal;">To Whom It May Concern,</span><br>';
              $tmppdf->writeHTML($html);
          }//endif
          
          if ($foldguide) {
              $tmppdf->SetY(86);
              $tmppdf->SetX(5);
              $tmppdf->Cell('', 6, '-', 0, 0, 'L', 0);
              $tmppdf->SetRightMargin(5);
              $tmppdf->Cell('', 6, '-', 0, 1, 'R', 0);
              $tmppdf->SetRightMargin(15);
          }//endif
          
          $tmppdf->SetY(85);
      }//endif
  }//endfunction
  

  function convert_for_html($text, $tablesetting, $striplinebreak, $stripAtag, $stripalllinefeed)
  {
      if ($tablesetting) {
          $text = str_replace('border="0"', 'border="1"', $text);
          $text = str_replace('cellpadding="1"', 'cellpadding="3"', $text);
          $text = str_replace('cellspacing="1"', 'cellspacing="0"', $text);
      }//endif

      if ($striplinebreak) {
          $text = str_replace("<br />", "", $text);
      }//endif
    
      if ($stripAtag) {
          while (stripos($text, '<a href="') !== false) {
              list($pre, $mid) = explode('<a href="', $text, 2);
              list($mid, $post) = explode('">', $mid, 2);
              $text = $pre.$post;
          }//endwhile
          $text = str_replace('</a>', '', $text);
      }//endif
    
      if ($stripalllinefeed) {
          $text = str_replace('<p>', "", $text);
          $text = str_replace('</p>', "", $text);
          $text = str_replace("\r\n", "", $text);
          $text = str_replace("\n", "", $text);
          $text = str_replace("<br>", "", $text);
          $text = str_replace("<br />", "", $text);
      }//endif

      $text = str_replace("&nbsp;", " ", $text);
    
      return $text;
  }//endfunction

  function find_similar($search, $list)
  {
      if (empty($search) || empty($list)) {
          return false;
      }//endif

      //similarity: 0=perfect match, usually set at 2 or 3 for mispellings
      $similarity = 3;

      $close = array();
      $closei = 0;

      $temp = strtoupper($search);
      $temp = str_replace('-', ' ', $temp);
      $temp = str_replace('(', ' ', $temp);
      $temp = str_replace(')', ' ', $temp);
      $temp = str_replace('[', ' ', $temp);
      $temp = str_replace(']', ' ', $temp);
      $searchwords = explode(' ', $temp);

      foreach ($list as $lst) {
          foreach ($searchwords as $searchword) {

        //fro smaller words want tighter match
              if (strlen($searchword) < 6) {
                  $similarity = 1;
              }//endif

              if (strlen($searchword) > 2) {
                  $temp1 = strtoupper($lst["field2"]);
                  $temp1 = str_replace('-', ' ', $temp1);
                  $temp1 = str_replace('(', ' ', $temp1);
                  $temp1 = str_replace(')', ' ', $temp1);
                  $temp1 = str_replace('[', ' ', $temp1);
                  $temp1 = str_replace(']', ' ', $temp1);
                  $listwords = explode(' ', $temp1);

                  foreach ($listwords as $listword) {
                      $lev = levenshtein($searchword, $listword);

                      if ($listword && $lev <= $similarity) {
                          $closei++;
                          $close[$closei]["field1"] = $lst["field1"];
                          $close[$closei]["field2"] = $lst["field2"];
                          if ($searchword == $search) {
                              $close[$closei]["field2"] = $lst["field2"];
                          }//endif
                      }//endif
                  }//endfor
              }//endif
          }//endfor
      }//endfor

      $temp = multiarray_remove_duplicates($close, "field2");

      return $temp;
  }//endfunction

  function multiarray_remove_duplicates($array, $key)
  {
      $temp_array = array();

      foreach ($array as &$v) {
          if (!isset($temp_array[$v[$key]])) {
              $temp_array[$v[$key]] =& $v;
          }//endif
      }//endfor

      $array = array_values($temp_array);

      return $array;
  }//endfunction

  function highlight($term, $target, & $wordsfound)
  {
      $temp = str_replace('-', ' ', $term);
      $temp = str_replace('(', ' ', $temp);
      $temp = str_replace(')', ' ', $temp);
      $temp = str_replace('[', ' ', $temp);
      $temp = str_replace(']', ' ', $temp);
      $searchwords = explode(' ', $temp);

      $found=0;
      foreach ($searchwords as $word) {
          if (strtoupper($word) !== 'A' && strtoupper($word) !== 'AND' && strtoupper($word) !== 'OF' && strtoupper($word) !== 'IN' && strtoupper($word) !== 'THE' && strtoupper($word) !== 'SPAN' && strtoupper($word) !== 'CLASS') {
              $target = str_ireplace($word, '<span class="textfound">' . strtoupper($word) . '</span>', $target);
              if (strpos($target, '<span class="textfound">' . strtoupper($word) . '</span>') !== false) {
                  $found++;
              }//endif
          }//endif
      }//endfor

      $wordsfound = $found;

      return $target;
  }//endfunction

  function compressprismslocationname($locationname)
  {
      $temp = str_replace('-', ' ', $locationname);
      $temp = str_replace('Melbourne Institute', 'Melb Inst', $temp);
      $temp = str_replace('of', '', $temp);
      $temp = str_replace(' and ', '', $temp);
      $temp = str_replace(' of ', '', $temp);
      $temp = str_replace('(', '', $temp);
      $temp = str_replace(')', '', $temp);
      $temp = str_replace('Pty', '', $temp);
      $temp = str_replace('Ltd', '', $temp);
      $temp = str_replace('MIT', '', $temp);
      $temp = str_replace('IIBIT', '', $temp);
      $temp = str_replace('Bourke St', '', $temp);
      $temp = str_replace('Federation University', 'FedUni', $temp);

      $dontcompress = array('Adelaide','Geelong','Melbourne','Sydney', 'FedUni','Ballarat','Gippsland','Berwick','Brisbane');

      $words = explode(' ', $temp);
      $acronym = "";

      foreach ($words as $w) {
          if (in_array($w, $dontcompress)) {
              $acronym = $acronym . ' ' . $w;
          }//endif
          else {
              $acronym = $acronym . $w[0];
          }//endelse
      }//endfor

    return strtoupper($acronym);
  }//endfunction

  function upwords($in)
  {
      $out = strtolower($in);
      $out = ucwords($out);
      $out = implode('-', array_map('ucfirst', explode('-', $out)));
      $out = implode("'", array_map('ucfirst', explode("'", $out)));

      return $out;
  }//endfunction

  function gpaexcludedunits(& $unitexclude, $studentid, $programid)
  {
      global $p, $db;
      //Obtain list of units to exclude due to Final Grade Appeal outcome 4 - exclude from GPA CV19
      $sql = "select studentid, us.unitid, us.termid, grade, creditpoint, `name`, resultcheck
              from unitstudent as us
                left join unit as u
                  on u.unitid = us.unitid
              inner join term as t
                on t.termid = us.termid
              where studentid = '$studentid'
              and strandid like '$programid%'
              and grade in ('HD','D','C','P','S','AD','TD','ZN','O','MN', 'MF', 'NN', 'F')
              and ifnull(dropped,'') = '' and us.unitid in (
                  select unitid
                  from letter
                  where studentid = '$studentid'
                  and strandid like '$programid%'
                  and type=25
                  and outcome=4)";
      $unitexcludesql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php')."-222: ".mysqli_error($db));

      $idx=0;
      while ($unitexcluderow = mysqli_fetch_array($unitexcludesql_ok)) {
          $unitexclude[$idx]=$unitexcluderow["unitid"];
          $idx++;
      }
  }

  function accumulategpa(& $gpagross, & $gpacreditpointsattempted, $grade, $creditpoints)
  {
      $gpagrades = array('HD', 'D', 'C', 'P', 'MN', 'MF', 'NN', 'F', 'LW');

      if (in_array($grade, $gpagrades)) {
          $gpacreditpointsattempted = $gpacreditpointsattempted + $creditpoints;
      }//endif

      switch ($grade) {
    case 'HD':
      $gpagross = $gpagross + (7 * $creditpoints);
      break;
    case 'D':
      $gpagross = $gpagross + (6 * $creditpoints);
      break;
    case 'C':
      $gpagross = $gpagross + (5 * $creditpoints);
      break;
    case 'P':
      $gpagross = $gpagross + (4 * $creditpoints);
      break;
    case 'MN':
    case 'MF':
      $gpagross = $gpagross + (3 * $creditpoints);
      break;
    case 'NN':
    case 'F':
      $gpagross = $gpagross + (1.5 * $creditpoints);
      break;
    case 'LW':
      $gpagross = $gpagross + (1.5 * $creditpoints);
      break;
    }//endcase
  }//endfunction

  function accumulatetermgpa(& $termgpagross, & $termgpacreditpointsattempted, $grade, $creditpoints)
  {
      $gpagrades = array('HD', 'D', 'C', 'P', 'MN', 'MF', 'NN', 'F', 'LW');

      if (in_array($grade, $gpagrades)) {
          $termgpacreditpointsattempted = $termgpacreditpointsattempted + $creditpoints;
      }//endif

      switch ($grade) {
    case 'HD':
      $termgpagross = $termgpagross + (7 * $creditpoints);
      break;
    case 'D':
      $termgpagross = $termgpagross + (6 * $creditpoints);
      break;
    case 'C':
      $termgpagross = $termgpagross + (5 * $creditpoints);
      break;
    case 'P':
      $termgpagross = $termgpagross + (4 * $creditpoints);
      break;
    case 'MN':
    case 'MF':
      $termgpagross = $termgpagross + (3 * $creditpoints);
      break;
    case 'NN':
    case 'F':
      $termgpagross = $termgpagross + (1.5 * $creditpoints);
      break;
    case 'LW':
      $termgpagross = $termgpagross + (1.5 * $creditpoints);
      break;
    }//endcase
  }//endfunction

  function getSystemLink($systemlinkid)
  {
      global $p, $db;

      $sql = "select *
            from systemlink
            where systemlinkid = '$systemlinkid'
            and ifnull(hide,'') = ''";

      $sql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php')."-022: ".mysqli_error($db));

      if (mysqli_num_rows($sql_ok) > 0) {
          $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__, '.php')."-023: ".mysqli_error($db));
          return stripslashes($row['link']);
      }//endif
      else {
          return false;
      }//endelse
  }//endfunction
  
  function getCoordinator($utltype, $utlsubdisciplineid, $utlstrandid, $utllocationid, & $utlcoordinatorid, & $utltitlename, & $utlposition, & $utladdress, & $utlroom, & $utltelephone, & $utlemail, & $utlcoordinatoruserid)
  {
      global $p, $db;

      $locationsql = '';
      $locationordersql = '';
      if (!empty($utllocationid)) {
          $locationsql = " and crd.locationid = '$utllocationid' ";
      }//endif

      $subdisciplinesql = '';
      if (!empty($utlsubdisciplineid)) {
          $subdisciplinesql = " and crd.subdisciplineid = '$utlsubdisciplineid' ";
      }//endif

      if ($utltype=='P') {//program coordinator
          $sql = "select coordinator
              from coordinatorlocation
              where strandid = '$utlstrandid'
              and locationid = '$utllocationid'";

          $sql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php')."-024: ".mysqli_error($db));

          if (mysqli_num_rows($sql_ok) > 0) {
              $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__, '.php')."-025: ".mysqli_error($db));

              $coordinatorid = $row["coordinator"];
          }//endif
          else {
              $sql = "select coordinator
                from course
                where strandid = '$utlstrandid'";

              $sql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php')."-026: ".mysqli_error($db));

              if (mysqli_num_rows($sql_ok) > 0) {
                  $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__, '.php')."-027: ".mysqli_error($db));

                  $coordinatorid = $row["coordinator"];
              }//endif
          }//endelse

      $sql = "select *
              from coordinator as crd
                inner join user as usr
                  on usr.userid = crd.userid
                left join title as t
                  on t.titleid = usr.titleid
              where crd.coordinatorid = '$coordinatorid'";

          $sql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php')."-028: ".mysqli_error($db));
      }//endif
      else {
          $sql = "select *
              from coordinator as crd
                inner join user as usr
                  on usr.userid = crd.userid
                left join title as t
                  on t.titleid = usr.titleid
              where `type` = '$utltype'
              $subdisciplinesql
              $locationsql
              and ifnull(crd.hide,'') = ''
              $locationordersql";

          $sql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php')."-029: ".mysqli_error($db));
      }//endelse

      if (mysqli_num_rows($sql_ok) > 0) {
          $utlemail='';
          for ($i=0; $i < mysqli_num_rows($sql_ok); $i++) {
              $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__, '.php')."-030: ".mysqli_error($db));

              if ($i == 0) {//get details for first found but acculate emails for all for given type.
                  $userid = $row["userid"];
                  $utlcoordinatoruserid = $row["userid"];
                  $utlcoordinatorid = $row["coordinatorid"];
                  if (!empty($row["shorttitle"]) && $row["shorttitle"] !== 'N/A') {
                      $utltitlename = trim($row["title"] . ' ' . stripslashes($row["fullname"]));
                  }//endif
                  else {
                      $utltitlename = trim(stripslashes($row["fullname"]));
                  }//endelse

                  $utladdress = stripslashes($row["address"]);
                  if (!empty($row["coordaddress"])) {
                      $utladdress = stripslashes($row["coordaddress"]);
                  }//endif
                  $utlposition = stripslashes($row["position"]);
                  $utlroom = $row["room"];//from user
                  $utltelephone = $row["telephone"];
                  if (!empty($row["coordtelephone"])) {
                      $utltelephone = $row["coordtelephone"];
                  }//endif
              }//endif

        if (!empty($row["email"])) {
            if (stripos($utlemail, stripslashes($row["email"])) === false) {
                $utlemail = $utlemail . stripslashes($row["email"]) . ',';
            }//endif
        }//endif
        
        if (!empty($row["coordemail"])) {
            if (stripos($utlemail, stripslashes($row["coordemail"])) === false) {
                $utlemail = $utlemail . stripslashes($row["coordemail"]) . ',';
            }//endif
        }//endif
          }//endfor

          $utlemail = rtrim($utlemail, ',');
          $utlemail = trim($utlemail);
      
          if (empty($utltitlename)) {
              $utltitlename = 'Student HQ';//feduni student hq
          }//endif
      if (empty($utlemail)) {
          $utlemail = 'studenthq@federation.edu.au';//feduni student hq
      }//endif
      if (empty($utltelephone)) {
          $utltelephone = '1800 333 864';//feduni student hq
      }//endif

      return true;
      }//endif
      else {
          $utlcoordinatorid='';
          $utlcoordinatoruserid='';
          $utltitlename='Student HQ';//feduni student hq
          $utladdress='';
          $utlposition='';
          $utlroom='';
          $utltelephone = '1800 333 864';//feduni student hq
      $utlemail = 'studenthq@federation.edu.au';//feduni student hq
      return false;
      }//endelse
  }//endfunction

  function getUser($utluserid, & $utluserfullname, & $utlusertelephone, & $utluseremail)
  {
      global $p, $db;

      $sql = "select *
            from user
            where userid = '$utluserid'";

      $sql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php')."-031: ".mysqli_error($db));

      $utluserfullname = '';
      $utlusertelephone = '';
      $utluseremail = '';

      if (mysqli_num_rows($sql_ok) > 0) {
          $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__, '.php')."-032: ".mysqli_error($db));

          $utluserfullname = stripslashes($row["fullname"]);
          $utlusertelephone = $row["telephone"];
          $utluseremail = stripslashes($row["email"]);
          $utluseremail = trim($utluseremail);
      }//endif
  }//endfunction

  function getLevel($minimumlevel, $maximumlevel)
  {
      if (empty($minimumlevel) || empty($maximumlevel)) {
          return '';
      }//endif

      if ($_SESSION[$_GET["trid"] . "sysunitdigits"]=='4') {
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
      }//endswitch
  
          $level = $level . $maximumlevel . '999 level';
      }//endif
      elseif ($_SESSION[$_GET["trid"] . "sysunitdigits"]=='3') {
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
      }//endswitch
  
          $level = $level . $maximumlevel . '99 level';
      }//endelseif
    
      return $level;
  }//endfunction

  function getCreditUnit($studentplanid, $lineid, $includecreditid, $equivalentseparate)
  {
      global $p, $db;

      $sql = "select spuc.*,spu.equivalentlevel,spu.equivalentunit
            from studentplanunit as spu
              left join studentplanunitcredit as spuc
                on  spuc.studentplanid = spu.studentplanid
                and spuc.lineid = spu.lineid
            where spu.studentplanid = '$studentplanid'
            and spu.lineid = '$lineid'
            order by spuc.modulecode, spuc.modulename,spu.equivalentlevel,spu.equivalentunit,spuc.itmnbr";

      $spucsql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php')."-033: ".mysqli_error($db));

      $creditunit = '';
      $equivalentlevel = '';
      $equivalentunit = '';
      for ($spuci=0; $spuci < mysqli_num_rows($spucsql_ok); $spuci++) {
          $spucrow = mysqli_fetch_array($spucsql_ok) or die(basename(__FILE__, '.php')."-034: ".mysqli_error($db));

          if ($creditunit) {
              $creditunit = $creditunit . ', ';
          }//endif

          $creditunit = $creditunit . $spucrow['modulecode'];

          if ($spucrow['modulename']) {
              $creditunit = $creditunit . ' '. stripslashes($spucrow['modulename']);
          }//endif

          if ($includecreditid && !empty($spucrow['creditid'])) {
              $creditunit = $creditunit . ' <sup>(' . $spucrow['creditid'] .')</sup>';
          }//endif

          $equivalentlevel = $spucrow['equivalentlevel'];
          $equivalentunit = stripslashes($spucrow['equivalentunit']);
      }//endfor

      if ($equivalentseparate && (!empty($equivalentunit) || !empty($equivalentlevel))) {
          $creditunit = $creditunit . '|';
      }//endif

      if (!empty($equivalentunit)) {
          $creditunit = $creditunit . ' FOR ' . $equivalentunit;
      }//endif

      if (!empty($equivalentlevel)) {
          $creditunit = $creditunit . ' AT LEVEL ' . $equivalentlevel . '000-' . $equivalentlevel . '999' ;
      }//endif

      $creditunit = trim($creditunit);

      return $creditunit;
  }//endfunction

  function getStudent($utlstudentid, & $utllastname, & $utlothernames, & $utlfullname, & $utladdress1, & $utladdress2, & $utladdress3, & $utladdress4, & $utlcity, & $utlstate, & $utlcountry, & $utlpostal, & $utlemail, & $utlaltemail, & $utlphone, & $utlaltphone, & $utlstopcorrespondence, & $utlstopenrol, $uppperlastname)
  {
      global $p, $db;

      $sql = "select lastname, othernames, address1, address2, address3, address4, city, state, country, postal, email, altemail, phone, altphone, stopcorrespondence, stopenrol
            from student
            where studentid = '$utlstudentid'";

      $sql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php')."-035: ".mysqli_error($db));

      if (mysqli_num_rows($sql_ok) > 0) {
          $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__, '.php')."-036: ".mysqli_error($db));

          $utllastname = stripslashes($row["lastname"]);
          $utlothernames = stripslashes($row["othernames"]);
          if ($uppperlastname) {
              $utlfullname = trim($utlothernames . ' ' . strtoupper($utllastname));
          }//endif
          else {
              $utlfullname = trim($utlothernames . ' ' . $utllastname);
          }//endelse
          $utladdress1 = stripslashes($row["address1"]);
          $utladdress2 = stripslashes($row["address2"]);
          $utladdress3 = stripslashes($row["address3"]);
          $utladdress4 = stripslashes($row["address4"]);
          $utlcity = stripslashes($row["city"]);
          $utlstate = stripslashes($row["state"]);
          $utlcountry = stripslashes($row["country"]);
          $utlpostal = $row["postal"];
          $utlemail = stripslashes($row["email"]);
          $utlaltemail = stripslashes($row["altemail"]);
          $utlphone = $row["phone"];
          $utlaltphone = $row["altphone"];
          $utlstopcorrespondence = $row["stopcorrespondence"];
          $utlstopenrol = $row["stopenrol"];
          return true;
      }//endif
      else {
          $utllastname = '';
          $utlothernames = '';
          $utlfullname = '';
          $utladdress1 = '';
          $utladdress2 = '';
          $utladdress3 = '';
          $utladdress4 = '';
          $utlcity = '';
          $utlstate = '';
          $utlcountry = '';
          $utlpostal = '';
          $utlemail = '';
          $utlaltemail = '';
          $utlphone = '';
          $utlaltphone = '';
          $utlstopcorrespondence = '';
          $utlstopenrol = '';
          return false;
      }//endelse
  }//endfunction
  
  function getEmailTemplate($templateid, & $to, & $subject, & $message, & $headers, $letterdata, $studentdata, $termdata, $stranddata, $unitdata, $subdisciplinedata, $locationdata, $fieldsdata, $attachmentname, & $attachment, $previewdata)
  {
      global $p, $db;
  
      $studentid = $studentdata[0];
      $termid = $termdata[0];
      $semester = $termdata[1];
      $year = $termdata[2];
      $locationid = $locationdata[0];
      getLocation($locationid, $utllocationname, $utlloadmoderationtype, $utlpartneruniversity, $utlexamshecup);
      $locationname = $utllocationname;
      $loadmoderationtype = $utlloadmoderationtype;
      $partneruniversity = $utlpartneruniversity;
      $examshecup = $utlexamshecup;
      $othernames = $studentdata[1];
      $lastname = $studentdata[2];
      $studentname = $othernames . ' ' . $lastname;
      $strandid = $stranddata[0];
      $strandname = $stranddata[1];
      getStrand($utlstrandid=$strandid, $utlstrandname, $utlstrandsubdisciplineid);//strandname passed in stranddata but just to make sure
      $subdisciplineid = $subdisciplinedata[0];
      if (empty($subdisciplineid)) {
          $subdisciplineid = $utlstrandsubdisciplineid;
      }//endif
      $subdiscipline = getSubdiscipline($subdisciplineid);
      $acaddivid = $subdiscipline["acaddivid"];
      $acaddivshortname = $subdiscipline["acaddivshortname"];
      $acaddivlongname = $subdiscipline["acaddivlongname"];
      $letterid = $letterdata[0];
      $alreadyreported = $letterdata[2];
    
      $preview = $previewdata[0];
  
      //studentdetails
    if (empty($studentdata[1]) && empty($studentdata[2])) {//othername && lastname
      getStudent($utlstudentid=$studentid, $utllastname, $utlothernames, $utlfullname, $utladdress1, $utladdress2, $utladdress3, $utladdress4, $utlcity, $utlstate, $utlcountry, $utlpostal, $utlemail, $utlaltemail, $utlphone, $utlaltphone, $utlstopcorrespondence, $utlstopenrol, _uppperlastnameON);
      
        $lastname = $utllastname;
        $othernames = $utlothernames;
        $address1 = $utladdress1;
        $address2 = $utladdress2;
        $address3 = $utladdress3;
        $address4 = $utladdress4;
        $email = $utlemail;
        $altemail = $utlaltemail;

        $citystatecountrypostal = '';
        if (!empty($utlcity)) {
            $citystatecountrypostal = $utlcity;
        }//endif
        if (!empty($utlstate)) {
            $citystatecountrypostal = trim($citystatecountrypostal . '   ' . $utlstate);
        }//endif
        if (!empty($utlpostal)) {
            $citystatecountrypostal = trim($citystatecountrypostal . '   ' . $utlpostal);
        }//endif
        if (!empty($utlcountry) && $utlcountry !== 'AUS') {
            $countrycode = $utlcountry;
            $sql = "select description
                from studentattributetype
                where studentattributecategoryid = '3'
                and `code` = '$countrycode'";

            $countrysql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php')."-037: ".mysqli_error($db));

            if (mysqli_num_rows($countrysql_ok) > 0) {
                $countryrow = mysqli_fetch_array($countrysql_ok) or die(basename(__FILE__, '.php')."-038: ".mysqli_error($db));

                $citystatecountrypostal = trim($citystatecountrypostal . '   ' . trim(stripslashes($countryrow["description"])));
            }//endif
        }//endif
      
        $studentdata[0] = $studentid;
        $studentdata[1] = $lastname;
        $studentdata[2] = $othernames;
        $studentdata[3] = $address1;
        $studentdata[4] = $address2;
        $studentdata[5] = $address3;
        $studentdata[6] = $address4;
        $studentdata[7] = $citystatecountrypostal;
        $studentdata[8] = $email;
        $studentdata[9] = $altemail;
    }//endif
      else {
          $othernames = $studentdata[1];
          $lastname = $studentdata[2];
          $address1 = trim($studentdata[3]);
          $address2 = trim($studentdata[4]);
          $address3 = trim($studentdata[5]);
          $address4 = trim($studentdata[6]);
          $citystatecountrypostal = $studentdata[7];
          $email = $studentdata[8];
          $altemail = $studentdata[9];
      }//endelse
    
      $studentaddress = $address1;
      if ($address2) {
          $studentaddress = $studentaddress . '<br>' . $address2;
      }//endif
      if ($address3) {
          $studentaddress = $studentaddress . '<br>' . $address3;
      }//endif
      if ($address4) {
          $studentaddress = $studentaddress . '<br>' . $address4;
      }//endif
      if ($citystatecountrypostal) {
          $studentaddress = $studentaddress . '<br>' . $citystatecountrypostal;
      }//endif
      $studentemails = $email;
      if ($altemail) {
          $studentemails = $studentemails . ', ' . $altemail;
      }//endif
                     
      if (!$preview && !$attachment) {
          $headers = $headers . "MIME-Version: 1.0" . "\r\n";
          $headers = $headers . "Content-type:text/html;charset=UTF-8" . "\r\n";
      }//endif
      
      //email
      $sql = "select *, tpi.shortname as templatepartitemshortname, tp.`type`, tp.shortname as templatepartshortname
            from templateitem as tpi
              inner join templatepart as tp
                on tp.templatepartid = tpi.templatepartid
            where tpi.templateid = '$templateid'
            and tpi.correspondencetype = 'E'
            order by 
              case tpi.recipientmode                        
                when 'F' then concat(1,tpi.shortname)
                when 'T' then concat(2,tpi.shortname)
                when 'C' then concat(3,tpi.shortname)
                when 'B' then concat(4,tpi.shortname)                         
                else 5
              end
             ,tpi.sequence";
      
      $mainsql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php')."-039: ".mysqli_error($db));

      for ($maini=0; $maini < mysqli_num_rows($mainsql_ok); $maini++) {
          $mainrow = mysqli_fetch_array($mainsql_ok) or die(basename(__FILE__, '.php')."-040: ".mysqli_error($db));
  
          $templatepartshortname = stripslashes($mainrow["templatepartshortname"]);
          $templatepartitemshortname = stripslashes($mainrow["templatepartitemshortname"]);
          $path = stripslashes($mainrow["path"]);
          $predefinedtext = stripslashes($mainrow["predefinedtext"]);
          $newlinepage = $mainrow["newlinepage"];
          $orientation = $mainrow["orientation"];
          $recipientmode = $mainrow["recipientmode"];
      
          if ($mainrow["type"]==99) {//email
      
              $returnemail='';
              $nextrecipientmode='';
              if (stripos($predefinedtext, "@") !== false) {
                  $returnemail = $predefinedtext;
              }//endif
        else {//email function
          switch ($predefinedtext) {
            case stripos($predefinedtext, '@') !== false:
              $returnemail = $predefinedtext;
              break;
            case 'coursecoordinator':
              $tempemail = '';
              foreach ($unitdata as $key=>$unitidloc) {
                  $unitid=substr($unitidloc, 0, 9);
                  if (!empty($unitid)) {
                      if ($preview) {
                          $sql = "select distinct usr.email
                            from unitstudent as us
                              inner join unituser as uu
                                on  uu.locationid = us.locationid
                                and uu.termid = us.termid
                                and uu.unitid = us.unitid
                              inner join user as usr
                                on usr.userid = uu.userid
                            where us.locationid = '$locationid' 
                            and us.termid = '$termid'
                            and us.unitid = '$unitid'
                            and ifnull(us.dropped,'') = ''
                            and uu.`type` = 'O'";
                      }//endif
                      else {
                          $sql = "select usr.email
                            from unitstudent as us
                              inner join unituser as uu
                                on  uu.locationid = us.locationid
                                and uu.termid = us.termid
                                and uu.unitid = us.unitid
                              inner join user as usr
                                on usr.userid = uu.userid
                            where us.studentid = '$studentid'
                            and us.termid = '$termid'
                            and us.unitid = '$unitid'
                            and ifnull(us.dropped,'') = ''
                            and uu.`type` = 'O'";
                      }//endelse
    
                      $usrsql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php')."-041: ".mysqli_error($db));
                    
                      for ($usri=0; $usri < mysqli_num_rows($usrsql_ok); $usri++) {
                          $usrrow = mysqli_fetch_array($usrsql_ok) or die(basename(__FILE__, '.php')."-042: ".mysqli_error($db));
    
                          if (!empty($tempemail)) {
                              $tempemail = trim($tempemail) . ", ";
                          }//endif
                                         
                          $tempemail = $tempemail . stripslashes($usrrow["email"]);
                      }//endfor
                  }//endif
              }//endfor
              
              $returnemail = $tempemail;
              
              break;
            case 'coursecoordinatorfcsa':
              $tempemail = '';
              
              if ($preview) {
                  $tempemail = 'n.flanders@federation.edu.au';
              }//endif
              else {
                  $sql = "select usr.email
                        from unitstudent as us
                          inner join unituser as uu
                            on  uu.locationid = us.locationid
                            and uu.termid = us.termid
                            and uu.unitid = us.unitid
                          inner join user as usr
                            on usr.userid = uu.userid
                        where us.studentid = '$studentid'
                        and us.termid = '$termid'
                        and us.grade in ('MN','MF')
                        and ifnull(us.dropped,'') = ''";
  
                  $usrsql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php')."-043: ".mysqli_error($db));
                  
                  for ($usri=0; $usri < mysqli_num_rows($usrsql_ok); $usri++) {
                      $usrrow = mysqli_fetch_array($usrsql_ok) or die(basename(__FILE__, '.php')."-044: ".mysqli_error($db));
  
                      if (!empty($tempemail)) {
                          $tempemail = trim($tempemail) . ", ";
                      }//endif
                                       
                      $tempemail = $tempemail . stripslashes($usrrow["email"]);
                  }//endfor
              }//endelse
            
              $returnemail = $tempemail;
              
              break;
            case 'currentuser':
               $returnemail = $_SESSION[$_GET["trid"] . "usremail"];
              break;
            case 'acaddivapc':
              getCoordinator($utltype='C', $utlsubdisciplineid=$subdisciplineid, $utlstrandid=false, $utllocationid=false, $utlcoordinatorid, $utltitlename, $utlposition, $utladdress, $utlroom, $utltelephone, $utlemail, $utlcoordinatoruserid);
              $returnemail = $utlemail;
              break;
            case 'acaddivappeals':
              getCoordinator($utltype='L', $utlsubdisciplineid=$subdisciplineid, $utlstrandid=false, $utllocationid=false, $utlcoordinatorid, $utltitlename, $utlposition, $utladdress, $utlroom, $utltelephone, $utlemail, $utlcoordinatoruserid);
              $returnemail = $utlemail;
              break;
            case 'acaddivwilplacement':
                getCoordinator($utltype='W', $utlsubdisciplineid=$subdisciplineid, $utlstrandid=false, $utllocationid=false, $utlcoordinatorid, $utltitlename, $utlposition, $utladdress, $utlroom, $utltelephone, $utlemail, $utlcoordinatoruserid);
                $returnemail = $utlemail;
                break;
            case 'acaddivdean':
              getCoordinator($utltype='X', $utlsubdisciplineid=$subdisciplineid, $utlstrandid=false, $utllocationid=false, $utlcoordinatorid, $utltitlename, $utlposition, $utladdress, $utlroom, $utltelephone, $utlemail, $utlcoordinatoruserid);
              $returnemail = $utlemail;
              break;
            case 'acaddivfdl':
              getCoordinator($utltype='B', $utlsubdisciplineid=$subdisciplineid, $utlstrandid=false, $utllocationid=false, $utlcoordinatorid, $utltitlename, $utlposition, $utladdress, $utlroom, $utltelephone, $utlemail, $utlcoordinatoruserid);
              $returnemail = $utlemail;
              break;
            case 'acaddivhead':
              getCoordinator($utltype='H', $utlsubdisciplineid=$subdisciplineid, $utlstrandid=false, $utllocationid=false, $utlcoordinatorid, $utltitlename, $utlposition, $utladdress, $utlroom, $utltelephone, $utlemail, $utlcoordinatoruserid);
              $returnemail = $utlemail;
              break;
            case 'acaddivplagiarismofficer':
              getCoordinator($utltype='O', $utlsubdisciplineid=$subdisciplineid, $utlstrandid=false, $utllocationid=false, $utlcoordinatorid, $utltitlename, $utlposition, $utladdress, $utlroom, $utltelephone, $utlemail, $utlcoordinatoruserid);
              $returnemail = $utlemail;
              break;
            case 'feduniesos':
              getCoordinator($utltype='E', $utlsubdisciplineid=false, $utlstrandid=false, $utllocationid=false, $utlcoordinatorid, $utltitlename, $utlposition, $utladdress, $utlroom, $utltelephone, $utlemail, $utlcoordinatoruserid);
              $returnemail = $utlemail;
              break;
            case 'feduniesosifalreadyreported':
              if ($alreadyreported) {
                  getCoordinator($utltype='E', $utlsubdisciplineid=false, $utlstrandid=false, $utllocationid=false, $utlcoordinatorid, $utltitlename, $utlposition, $utladdress, $utlroom, $utltelephone, $utlemail, $utlcoordinatoruserid);
                  $returnemail = $utlemail;
              }//endif
              break;
            case 'indigenouscentre':
              $sql = "SELECT code
                      FROM studentattribute as sa
                        inner join studentattributetype as sat
                          on sat.studentattributetypeid = sa.studentattributetypeid
                        inner join studentattributecategory as sac
                          on sac.studentattributecategoryid = sat.studentattributecategoryid
                      where studentid = '$studentid'
                      and sac.description = 'Ethnicity'
                      and sat.code in ('Aboriginal','Torres Str','ATSI')";
          
              $sql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php')."-045: ".mysqli_error($db));
                        
              //only return email if indigenous record found
              $returnemail = '';
              if (mysqli_num_rows($sql_ok) > 0) {
                  getCoordinator($utltype='G', $utlsubdisciplineid=false, $utlstrandid=false, $utllocationid=false, $utlcoordinatorid, $utltitlename, $utlposition, $utladdress, $utlroom, $utltelephone, $utlemail, $utlcoordinatoruserid);
                  $returnemail = $utlemail;
              }//endif
              break;
            case 'locationcorrespondence':
              getCoordinator($utltype='A', $utlsubdisciplineid=$subdisciplineid, $utlstrandid=false, $utllocationid=$locationid, $utlcoordinatorid, $utltitlename, $utlposition, $utladdress, $utlroom, $utltelephone, $utlemail, $utlcoordinatoruserid);
              $returnemail = $utlemail;
              break;
            case 'locationcorrespondenceCC':
              getCoordinator($utltype='N', $utlsubdisciplineid=$subdisciplineid, $utlstrandid=false, $utllocationid=$locationid, $utlcoordinatorid, $utltitlename, $utlposition, $utladdress, $utlroom, $utltelephone, $utlemail, $utlcoordinatoruserid);
              $returnemail = $utlemail;
              break;
            case 'partnerlecturer':
              $tempemail = '';
              foreach ($unitdata as $key=>$unitidloc) {
                  $unitid=substr($unitidloc, 0, 9);
                  if (!empty($unitid)) {
                      if ($preview) {
                          $sql = "select distinct usr.email
                            from unitstudent as us
                              inner join unituser as uu
                                on  uu.locationid = us.locationid
                                and uu.termid = us.termid
                                and uu.unitid = us.unitid
                              inner join user as usr
                                on usr.userid = uu.userid
                            where us.locationid = '$locationid' 
                            and us.termid = '$termid'
                            and us.unitid = '$unitid'                            
                            and ifnull(us.dropped,'') = ''
                            and uu.`type` in ('C','X')";
                      }//endif
                      else {
                          $sql = "select usr.email
                            from unitstudent as us
                              inner join unituser as uu
                                on  uu.locationid = us.locationid
                                and uu.termid = us.termid
                                and uu.unitid = us.unitid
                              inner join user as usr
                                on usr.userid = uu.userid
                            where us.studentid = '$studentid'
                            and us.termid = '$termid'
                            and us.unitid = '$unitid'
                            and ifnull(us.dropped,'') = ''
                            and uu.`type` in ('C','X')";
                      }//endelse
    
                      $usrsql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php')."-046: ".mysqli_error($db));
                    
                      for ($usri=0; $usri < mysqli_num_rows($usrsql_ok); $usri++) {
                          $usrrow = mysqli_fetch_array($usrsql_ok) or die(basename(__FILE__, '.php')."-047: ".mysqli_error($db));
    
                          if (!empty($tempemail)) {
                              $tempemail = trim($tempemail) . ", ";
                          }//endif
                                         
                          $tempemail = $tempemail . stripslashes($usrrow["email"]);
                      }//endfor
                  }//endif
              }//endfor
              
              $returnemail = $tempemail;
              
              break;
            case 'programcoordinator':
               getCoordinator($utltype='P', $utlsubdisciplineid=false, $utlstrandid=$strandid, $utllocationid=$locationid, $utlcoordinatorid, $utltitlename, $utlposition, $utladdress, $utlroom, $utltelephone, $utlemail, $utlcoordinatoruserid);
               $returnemail = $utlemail;
              break;
            case 'programcoordinatormultiple':
              if (count($unitdata) > 1 || in_array($templateid, array(23,24))) {//23,24 = previous unsatisfactory
                  getCoordinator($utltype='P', $utlsubdisciplineid=false, $utlstrandid=$strandid, $utllocationid=$locationid, $utlcoordinatorid, $utltitlename, $utlposition, $utladdress, $utlroom, $utltelephone, $utlemail, $utlcoordinatoruserid);
                  $returnemail = $utlemail;
              }//endif
              else {
                  $returnemail = '';
              }//endelse
              break;
            case 'studentemail':
              $returnemail = $email;
              if ($altemail && $altemail !== $email) {
                  $returnemail = $returnemail . ", " . $altemail;
              }//endif
              break;
          }//endswitch
        }//endelse

        if ($returnemail) {
            if ($recipientmode=='F') {
                if ($preview) {
                    $to = $to . "From: " . $returnemail . "<br>";
                }//endif
                else {
                    $headers = $headers . "From: " . $returnemail . "\r\n";
                }//endelse
            }//endif
          if ($recipientmode=='T') {
              if ($preview) {
                  $to = $to . "To: " . $returnemail . "<br>";
              }//endif
              else {
                  $to = $to . $returnemail . "\r\n";
              }//endelse
          }//endif
          if ($recipientmode=='B') {
              if ($preview) {
                  $to = $to . "Bcc: " . $returnemail . "<br>";
              }//endif
              else {
                  $headers = $headers . "Bcc: " . $returnemail . "\r\n";
              }//endelse
          }//endif
          if ($recipientmode=='C') {
              if ($preview) {
                  $to = $to . "Cc: " . $returnemail . "<br>";
              }//endif
              else {
                  $headers = $headers . "Cc: " . $returnemail . "\r\n";
              }//endelse
          }//endif
        }//endif
          }//endif

          if ($mainrow["type"]==2 || $mainrow["type"]==5) {//PDF || General file
        
              $filepathname = 'template/' . $path;
        
              if ($preview) {
                  if (stripos($message, "Attachment:") === false) {
                      $message = $message . "<br><br>";
                  }//endif
                  $message = $message . '<br><b>Attachment:</b> '.$path;
              }//endif
              else {
                  if (!$boundarycreated) {
                      $boundarycreated = true;
                      // Generate a boundary string
                      $semi_rand = md5(time());
                      $mime_boundary = "=={$semi_rand}";
  
                      // Add the headers for a file attachment
                      $headers .= "MIME-Version: 1.0\n" .
                        "Content-Type: multipart/mixed;\n" .
                        " boundary=\"{$mime_boundary}\"";
  
                      // Add a multipart boundary above the message
                      $message = "This is a multi-part message in MIME format.\n\n" .
            "--{$mime_boundary}\n" .
            "Content-Type: text/html; charset=\"utf-8\"\n" .
            "Content-Transfer-Encoding: 7bit\n\n" .
            $message . "\n\n";
                      $message .= "--{$mime_boundary}\n";
                  }//endif
          
                  // Base64 encode the file data
                  $data = chunk_split(base64_encode(file_get_contents($filepathname)));
          
                  $contenttype = "Content-Type: application/pdf;\n";
                  if ($mainrow["type"]==5) {//general file
                      $contenttype = "Content-Type: application/octet-stream;\n";
                  }//endif
  
                  // Add file attachment to the message
                  $message .= $contenttype .
           " name=\"{$path}\"\n" .
           "Content-Disposition: attachment;\n" .
           " filename=\"{$path}\"\n" .
           "Content-Transfer-Encoding: base64\n\n" .
           $data . "\n\n" .
           "--{$mime_boundary}\n";
              }//endelse
          }//endif
      
          if ($mainrow["type"]==3) {//function
      
              if (is_numeric($newlinepage)) {
                  $newlinepage++;
                  $message = $message . str_repeat('<br>', $newlinepage);
              }//endif
        
              switch ($path) {
          case 'attachevidence':
            $documentfile = 'misconduct/' . $letterid . '.pdf';
            if (file_exists($documentfile)) {
                if ($preview) {
                    if (stripos($message, "Attachment:") === false) {
                        $message = $message . "<br><br>";
                    }//endif
                    $message = $message . '<br><b>Attachment:</b> evidence.pdf';
                }//endif
                else {
                    if (!$boundarycreated) {
                        $boundarycreated = true;
                        // Generate a boundary string
                        $semi_rand = md5(time());
                        $mime_boundary = "=={$semi_rand}";
            
                        // Add the headers for a file attachment
                        $headers .= "MIME-Version: 1.0\n" .
                              "Content-Type: multipart/mixed;\n" .
                              " boundary=\"{$mime_boundary}\"";
            
                        // Add a multipart boundary above the message
                        $message = "This is a multi-part message in MIME format.\n\n" .
                  "--{$mime_boundary}\n" .
                  "Content-Type: text/html; charset=\"utf-8\"\n" .
                  "Content-Transfer-Encoding: 7bit\n\n" .
                  $message . "\n\n";
                        $message .= "--{$mime_boundary}\n";
                    }//endif
                
                    $documentfile = 'misconduct/' . $letterid . '.pdf';
                    $filename = "evidence.pdf";
                    if (file_exists($documentfile)) {
                        $file = fopen($documentfile, "rb");
                        $temp = fread($file, filesize($documentfile));
                        fclose($file);
                        $data = chunk_split(base64_encode($temp));
                        $message .= "Content-Type:{\"application/pdf\"};\n" .
                  " name=\"$filename\"\n" .
                  "Content-Disposition: attachment;\n" .
                  " filename=\"$filename\"\n" .
                  "Content-Transfer-Encoding: base64\n\n" .
                  $data . "\n\n" .
                  "--{$mime_boundary}\n";
                    }//endif
                }//endelse
            }//endif
            break;
          case 'emailheader':
            $message = $message . 'Dear ' . trim($othernames . ' ' . $lastname) . ' ('. $studentid .')';
            break;
          case 'acaddivname':
            $message = $message . $acaddivlongname;
            $message = $message . '<br>' . $_SESSION[$_GET["trid"] . "sysinstitution"];
            break;
          case 'signaturelocationcorrespondence':
            getCoordinator($utltype='A', $utlsubdisciplineid=$subdisciplineid, $utlstrandid=false, $utllocationid=$locationid, $utlcoordinatorid, $utltitlename, $utlposition, $utladdress, $utlroom, $utltelephone, $utlemail, $utlcoordinatoruserid);
            $message = $message . $utltitlename . '<br>' . $utlposition;
            break;
          case 'username':
            $message = $message . $_SESSION[$_GET["trid"] . "username"];
            break;
        }//endswitch
          }//endif
      
          if ($mainrow["type"]==4 || $mainrow["type"]==8) {//text - text function
      
              //look for any embedded fields
              if (substr_count($predefinedtext, '#') > 1) {
                  while (stripos($predefinedtext, '#Field') !== false) {
                      $field = substr($predefinedtext, strpos($predefinedtext, '#Field') + 6, 2);
                      $searchstr = '#Field' . sprintf('%02d', $field) . '#';
            
                      $idx = $field - 1;
            
                      if (stripos($predefinedtext, $searchstr) !== false) {
                          $predefinedtext = str_replace($searchstr, $fieldsdata[$idx], $predefinedtext);
                      }//endif
                  }//endwhile
              }//endif
        
              commontemplatefields($predefinedtext, $studentid, $studentname, $studentaddress, $studentemails, $strandid, $strandname, $locationid, $locationname, $acaddivid, $acaddivshortname, $acaddivlongname, $termid, $semester, $year, $subdisciplineid);
      
              if (is_numeric($newlinepage)) {
                  $newlinepage++;
                  $message = $message . str_repeat('<br>', $newlinepage);
              }//endif
        
              switch ($templatepartshortname) {
          case 'Email Subject':
            $subject = $predefinedtext;
            if ($preview) {
                $to = $to . "<br>Subject: " . $subject . "<br><br><br>";
            }//endif
            break;
          case 'Email Subject (Term)':
            $subject = $predefinedtext . ' (' . $semester . ', ' . $year .')';
            if ($preview) {
                $to = $to . "<br>Subject: " . $subject . "<br><br><br>";
            }//endif
            break;
          default:
            $message = $message . $predefinedtext;
            break;
        }//endswitch
          }//endif
      }//endfor
     
      if (empty($to)) {
          $to = $email;
          if ($altemail && $altemail !== $email) {
              $to = $to . ", " . $altemail;
          }//endif
      }//endif
    
    if (stripos($headers, "From:") === false) {
        $headers = $headers . "From: noreply@" . $_SESSION[$_GET["trid"] . "sysemailsuffix"] . "\r\n";
      
        if ($preview && stripos($to, "From:") === false) {
            $to = "From: noreply@" . $_SESSION[$_GET["trid"] . "sysemailsuffix"] . "\\n" . $to. "\\n" ;
        }//endif
    }//endif
    
    if ($attachment) {//generated letter
    
        if (!$boundarycreated) {
            $boundarycreated = true;
            // Generate a boundary string
            $semi_rand = md5(time());
            $mime_boundary = "=={$semi_rand}";
  
            // Add the headers for a file attachment
            $headers .= "MIME-Version: 1.0\n" .
                    "Content-Type: multipart/mixed;\n" .
                    " boundary=\"{$mime_boundary}\"";
  
            // Add a multipart boundary above the message
            $message = "This is a multi-part message in MIME format.\n\n" .
        "--{$mime_boundary}\n" .
        "Content-Type: text/html; charset=\"utf-8\"\n" .
        "Content-Transfer-Encoding: 7bit\n\n" .
        $message . "\n\n";
            $message .= "--{$mime_boundary}\n";
        }//endif

        // Base64 encode the file data
        $data = chunk_split(base64_encode($attachment));
      
        // Add file attachment to the message
        $message .= "Content-Type:{\"application/pdf\"};\n" .
       " name=\"{$attachmentname}\"\n" .
       "Content-Disposition: attachment;\n" .
       " filename=\"{$attachmentname}\"\n" .
       "Content-Transfer-Encoding: base64\n\n" .
       $data . "\n\n" .
       "--{$mime_boundary}\n";
    }//endif
      
      if ($preview && $attachmentname) {
          if (stripos($message, "Attachment:") === false) {
              $message = $message . "<br><br>";
          }//endif
          $message = $message . '<br><b>Attachment:</b> ' . $attachmentname;
      }//endif
        
      if (!$preview && $attachment) {
          $message .= "--{$mime_boundary}--";//tidy up for all attachements. out of if statement just in case no generated letter.
      }//endif
  }//endfunction
  
  function commontemplatefields(& $predefinedtext, $studentid, $studentname, $studentaddress, $studentemails, $strandid, $strandname, $locationid, $locationname, $acaddivid, $acaddivshortname, $acaddivlongname, $termid, $semester, $year, $subdisciplineid)
  {
      if (substr_count($predefinedtext, '{{') > 0) {
          while (stripos($predefinedtext, '{{') !== false) {
              preg_match('/{{(.*?)}}/', $predefinedtext, $match);
        
              switch ($match[1]) {
          case 'acaddivcode':
            $searchstr = '{{' . $match[1] . '}}';
            $predefinedtext = str_replace($searchstr, $acaddivid, $predefinedtext);
            break;
          case 'acaddivlongname':
            $searchstr = '{{' . $match[1] . '}}';
            $predefinedtext = str_replace($searchstr, $acaddivlongname, $predefinedtext);
            break;
          case 'acaddivshortname':
            $searchstr = '{{' . $match[1] . '}}';
            $predefinedtext = str_replace($searchstr, $acaddivshortname, $predefinedtext);
            break;
          case 'acaddivappealscontactdetails':
            getCoordinator($utltype='L', $utlsubdisciplineid=$subdisciplineid, $utlstrandid=false, $utllocationid=false, $utlcoordinatorid, $utltitlename, $utlposition, $utladdress, $utlroom, $utltelephone, $utlemail, $utlcoordinatoruserid);
            $replacetext = $utltitlename . ' on ' . $utltelephone . ' or at <a href="mailto:' . $utlemail . '">' . $utlemail .'</a>';
            //no position added
            $searchstr = '{{' . $match[1] . '}}';
            $predefinedtext = str_replace($searchstr, $replacetext, $predefinedtext);
            break;
          case 'acaddivappealscontactemail':
              getCoordinator($utltype='L', $utlsubdisciplineid=$subdisciplineid, $utlstrandid=false, $utllocationid=false, $utlcoordinatorid, $utltitlename, $utlposition, $utladdress, $utlroom, $utltelephone, $utlemail, $utlcoordinatoruserid);
              $replacetext =  $utlemail;
              //no position added
              $searchstr = '{{' . $match[1] . '}}';
              $predefinedtext = str_replace($searchstr, $replacetext, $predefinedtext);
              break;
          case 'acaddivwillplacementcontactdetails':
            getCoordinator($utltype='W', $utlsubdisciplineid=$subdisciplineid, $utlstrandid=false, $utllocationid=false, $utlcoordinatorid, $utltitlename, $utlposition, $utladdress, $utlroom, $utltelephone, $utlemail, $utlcoordinatoruserid);
            $replacetext = $utltitlename . ' on ' . $utltelephone . ' or at <a href="mailto:' . $utlemail . '">' . $utlemail .'</a>';
            //no position added
            $searchstr = '{{' . $match[1] . '}}';
            $predefinedtext = str_replace($searchstr, $replacetext, $predefinedtext);
            break;
          case 'acaddivwillplacementcontactemail':
              getCoordinator($utltype='W', $utlsubdisciplineid=$subdisciplineid, $utlstrandid=false, $utllocationid=false, $utlcoordinatorid, $utltitlename, $utlposition, $utladdress, $utlroom, $utltelephone, $utlemail, $utlcoordinatoruserid);
              $replacetext =  $utlemail;
              //no position added
              $searchstr = '{{' . $match[1] . '}}';
              $predefinedtext = str_replace($searchstr, $replacetext, $predefinedtext);
              break;
          case 'locationcode':
            $searchstr = '{{' . $match[1] . '}}';
            $predefinedtext = str_replace($searchstr, $locationid, $predefinedtext);
            break;
          case 'locationcorrespondencecontactdetails':
            getCoordinator($utltype='A', $utlsubdisciplineid=$subdisciplineid, $utlstrandid=false, $utllocationid=$locationid, $utlcoordinatorid, $utltitlename, $utlposition, $utladdress, $utlroom, $utltelephone, $utlemail, $utlcoordinatoruserid);
            $replacetext = $utltitlename . ' on ' . $utltelephone . ' or at <a href="mailto:' . $utlemail . '">' . $utlemail .'</a>';
            if ($utlposition) {
                $replacetext = $utlposition . ', ' . $replacetext;
            }//endif
            $searchstr = '{{' . $match[1] . '}}';
            $predefinedtext = str_replace($searchstr, $replacetext, $predefinedtext);
            break;
          case 'locationcorrespondenceCCcontactdetails':
            getCoordinator($utltype='N', $utlsubdisciplineid=$subdisciplineid, $utlstrandid=false, $utllocationid=$locationid, $utlcoordinatorid, $utltitlename, $utlposition, $utladdress, $utlroom, $utltelephone, $utlemail, $utlcoordinatoruserid);
            $replacetext = $utltitlename . ' on ' . $utltelephone . ' or at <a href="mailto:' . $utlemail . '">' . $utlemail .'</a>';
            if ($utlposition) {
                $replacetext = $utlposition . ', ' . $replacetext;
            }//endif
            $searchstr = '{{' . $match[1] . '}}';
            $predefinedtext = str_replace($searchstr, $replacetext, $predefinedtext);
            break;
          case 'locationname':
            $searchstr = '{{' . $match[1] . '}}';
            $predefinedtext = str_replace($searchstr, $locationname, $predefinedtext);
            break;
          case 'programcode':
            $searchstr = '{{' . $match[1] . '}}';
            $predefinedtext = str_replace($searchstr, $strandid, $predefinedtext);
            break;
          case 'programcoordinatorcontactdetails':
            getCoordinator($utltype='P', $utlsubdisciplineid=false, $utlstrandid=$strandid, $utllocationid=$locationid, $utlcoordinatorid, $utltitlename, $utlposition, $utladdress, $utlroom, $utltelephone, $utlemail, $utlcoordinatoruserid);
            $replacetext = $utltitlename . ' on ' . $utltelephone . ' or at <a href="mailto:' . $utlemail . '">' . $utlemail .'</a>';
            if ($utlposition) {
                $replacetext = $utlposition . ', ' . $replacetext;
            }//endif
            $searchstr = '{{' . $match[1] . '}}';
            $predefinedtext = str_replace($searchstr, $replacetext, $predefinedtext);
            break;
          case 'programname':
            $searchstr = '{{' . $match[1] . '}}';
            $predefinedtext = str_replace($searchstr, $strandname, $predefinedtext);
            break;
          case 'semesteryear':
            $searchstr = '{{' . $match[1] . '}}';
            $predefinedtext = str_replace($searchstr, $semester . ', ' . $year, $predefinedtext);
            break;
          case 'specialconsiderationcontactdetails':
            getCoordinator($utltype='Q', $utlsubdisciplineid=false, $utlstrandid=false, $utllocationid=$locationid, $utlcoordinatorid, $utltitlename, $utlposition, $utladdress, $utlroom, $utltelephone, $utlemail, $utlcoordinatoruserid);
            $replacetext = $utltitlename . ' on ' . $utltelephone . ' or at <a href="mailto:' . $utlemail . '">' . $utlemail .'</a>';
            if ($utlposition) {
                $replacetext = $utlposition . ', ' . $replacetext;
            }//endif
            $searchstr = '{{' . $match[1] . '}}';
            $predefinedtext = str_replace($searchstr, $replacetext, $predefinedtext);
            break;
          case 'studentaddress':
            $searchstr = '{{' . $match[1] . '}}';
            $predefinedtext = str_replace($searchstr, $studentaddress, $predefinedtext);
            break;
          case 'studentemails':
            $searchstr = '{{' . $match[1] . '}}';
            $predefinedtext = str_replace($searchstr, $studentemails, $predefinedtext);
            break;
          case 'studentid':
            $searchstr = '{{' . $match[1] . '}}';
            $predefinedtext = str_replace($searchstr, $studentid, $predefinedtext);
            break;
          case 'studentname':
            $searchstr = '{{' . $match[1] . '}}';
            $predefinedtext = str_replace($searchstr, trim($studentname), $predefinedtext);
            break;
          case 'studentfirstname':
            $searchstr = '{{' . $match[1] . '}}';
            $temp = explode(' ', $studentname);
            $studentfirstname = $temp[0];
            $predefinedtext = str_replace($searchstr, trim($studentfirstname), $predefinedtext);
            break;
          case 'termcode':
            $searchstr = '{{' . $match[1] . '}}';
            $predefinedtext = str_replace($searchstr, $termid, $predefinedtext);
            break;
          case 'industryplacement':
              getCoordinator($utltype='I', $utlsubdisciplineid=$subdisciplineid, $utlstrandid=false, $utllocationid=false, $utlcoordinatorid, $utltitlename, $utlposition, $utladdress, $utlroom, $utltelephone, $utlemail, $utlcoordinatoruserid);
              $replacetext = $utlemail;
              //no position added
              $searchstr = '{{' . $match[1] . '}}';
              $predefinedtext = str_replace($searchstr, $replacetext, $predefinedtext);
              break;
        }//endswitch
          }//endwhile
      }//endif
  }//endfunction
  
  function letterbreak($pdftype, $newlinepage, $orientation, $type)
  {
      if (empty($orientation)) {
          $orientation = 'P';
      }//endif
      if ($pdftype && $newlinepage=='P') {
          if ($type=='2') {//file
        $pdftype->AddPageByArray(array('orientation' => $orientation,'margin_left' => 15,'margin_right' => 15,'margin_top' => 20,'margin_bottom' => 15,'margin_header' => 8,'margin_footer' => 9.5));//deal with band on incoming pdf file
          }//endif
      else {
          if ($pdftype->pdfFirstPagePrinted) {
              $pdftype->AddPageByArray(array('orientation' => $orientation,'margin_left' => 15,'margin_right' => 15,'margin_top' => 20,'margin_bottom' => 15,'margin_header' => 8,'margin_footer' => 8));
          }//endif
          else {
              $pdftype->AddPageByArray(array('orientation' => $orientation,'resetpagenum' => 1,'margin_left' => 15,'margin_right' => 15,'margin_top' => 20,'margin_bottom' => 15,'margin_header' => 8,'margin_footer' => 8));
          }//endelse
      }//endelse
      }//endif
    
    if ($pdftype && is_numeric($newlinepage)) {
        $html = str_repeat('<br>', $newlinepage);
        $pdftype->writeHTML($html);
    }//endif
  }//endfunction
  
  function getLetterTemplate($templateid, & $pdf, & $tmppdf, & $specialpdf, $letterdata, $studentdata, $termdata, $stranddata, $unitdata, $subdisciplinedata, $locationdata, $fieldsdata, $previewdata)
  {
      global $p, $db;
    
      $studentid = $studentdata[0];
      $termid = $termdata[0];
      $semester = $termdata[1];
      $year = $termdata[2];
      $locationid = $locationdata[0];
      getLocation($locationid, $utllocationname, $utlloadmoderationtype, $utlpartneruniversity, $utlexamshecup);
      $locationname = $utllocationname;
      $loadmoderationtype = $utlloadmoderationtype;
      $partneruniversity = $utlpartneruniversity;
      $utlexamshecup = $utlutlexamshecup;
      $othernames = $studentdata[1];
      $lastname = $studentdata[2];
      $studentname = $othernames . ' ' . $lastname;
      $strandid = $stranddata[0];
      $strandname = $stranddata[1];
      getStrand($utlstrandid=$strandid, $utlstrandname, $utlstrandsubdisciplineid);//strandname passed in stranddata but just to make sure
      $subdisciplineid = $subdisciplinedata[0];
      if (empty($subdisciplineid)) {
          $subdisciplineid = $utlstrandsubdisciplineid;
      }//endif
      $subdiscipline = getSubdiscipline($subdisciplineid);
      $acaddivid = $subdiscipline["acaddivid"];
      $acaddivshortname = $subdiscipline["acaddivshortname"];
      $acaddivlongname = $subdiscipline["acaddivlongname"];
      $letterid = $letterdata[0];
      $preview = $previewdata[0];
      $headerheading = $letterdata[1];
    
      //studentdetails
    if (empty($studentdata[1]) && empty($studentdata[2])) {//othername && lastname
      getStudent($utlstudentid=$studentid, $utllastname, $utlothernames, $utlfullname, $utladdress1, $utladdress2, $utladdress3, $utladdress4, $utlcity, $utlstate, $utlcountry, $utlpostal, $utlemail, $utlaltemail, $utlphone, $utlaltphone, $utlstopcorrespondence, $utlstopenrol, _uppperlastnameON);
      
        $lastname = $utllastname;
        $othernames = $utlothernames;
        $address1 = $utladdress1;
        $address2 = $utladdress2;
        $address3 = $utladdress3;
        $address4 = $utladdress4;
        $email = $utlemail;
        $altemail = $utlaltemail;

        $citystatecountrypostal = '';
        if (!empty($utlcity)) {
            $citystatecountrypostal = $utlcity;
        }//endif
        if (!empty($utlstate)) {
            $citystatecountrypostal = trim($citystatecountrypostal . '   ' . $utlstate);
        }//endif
        if (!empty($utlpostal)) {
            $citystatecountrypostal = trim($citystatecountrypostal . '   ' . $utlpostal);
        }//endif
        if (!empty($utlcountry) && $utlcountry !== 'AUS') {
            $countrycode = $utlcountry;
            $sql = "select description
                from studentattributetype
                where studentattributecategoryid = '3'
                and `code` = '$countrycode'";

            $countrysql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php')."-048: ".mysqli_error($db));

            if (mysqli_num_rows($countrysql_ok) > 0) {
                $countryrow = mysqli_fetch_array($countrysql_ok) or die(basename(__FILE__, '.php')."-049: ".mysqli_error($db));

                $citystatecountrypostal = trim($citystatecountrypostal . '   ' . trim(stripslashes($countryrow["description"])));
            }//endif
        }//endif
      
        $studentdata[0] = $studentid;
        $studentdata[1] = $lastname;
        $studentdata[2] = $othernames;
        $studentdata[3] = $address1;
        $studentdata[4] = $address2;
        $studentdata[5] = $address3;
        $studentdata[6] = $address4;
        $studentdata[7] = $citystatecountrypostal;
        $studentdata[8] = $email;
        $studentdata[9] = $altemail;
    }//endif
      else {
          $othernames = $studentdata[1];
          $lastname = $studentdata[2];
          $address1 = trim($studentdata[3]);
          $address2 = trim($studentdata[4]);
          $address3 = trim($studentdata[5]);
          $address4 = trim($studentdata[6]);
          $citystatecountrypostal = $studentdata[7];
          $email = $studentdata[8];
          $altemail = $studentdata[9];
      }//endelse
    
      $studentaddress = $address1;
      if ($address2) {
          $studentaddress = $studentaddress . '<br>' . $address2;
      }//endif
      if ($address3) {
          $studentaddress = $studentaddress . '<br>' . $address3;
      }//endif
      if ($address4) {
          $studentaddress = $studentaddress . '<br>' . $address4;
      }//endif
      if ($citystatecountrypostal) {
          $studentaddress = $studentaddress . '<br>' . $citystatecountrypostal;
      }//endif
      $studentemails = $email;
      if ($altemail) {
          $studentemails = $studentemails . ', ' . $altemail;
      }//endif
                 
      $foldguide = false;
      //letter part
      $sql = "select *, tpi.shortname as templatepartitemshortname, tp.`type`, tp.shortname as templatepartshortname, tpi.templatepartid as templatetextid
            from templateitem as tpi
              left join templatepart as tp
                on tp.templatepartid = tpi.templatepartid
            where tpi.templateid = '$templateid'
            and tpi.correspondencetype = 'L'
            order by tpi.sequence";
      
      $mainsql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php')."-050: ".mysqli_error($db));

      $field = 0;
      $html = '';
      for ($maini=0; $maini < mysqli_num_rows($mainsql_ok); $maini++) {
          $mainrow = mysqli_fetch_array($mainsql_ok) or die(basename(__FILE__, '.php')."-051: ".mysqli_error($db));
  
          $templateitemid = $mainrow["templateitemid"];
          $templatetextid = $mainrow["templatetextid"];//only used for common text
          $templatepartshortname = stripslashes($mainrow["templatepartshortname"]);
          $templatepartitemshortname = stripslashes($mainrow["templatepartitemshortname"]);
          $path = stripslashes($mainrow["path"]);
          $predefinedtext = stripslashes($mainrow["predefinedtext"]);
          $newlinepage = $mainrow["newlinepage"];
          $orientation = $mainrow["orientation"];
          $recipientmode = $mainrow["recipientmode"];
          $sequence = $mainrow["sequence"];
      
          //$mainrow["type"]=='1'{//fields - not actually used. used within predefined text but come from fieldsdata relative position
      
          if ($mainrow["type"]=='2') {//file
      
              letterbreak($pdf, $newlinepage, $orientation, $mainrow["type"]);
              letterbreak($tmppdf, $newlinepage, $orientation, $mainrow["type"]);
              
              $filepath = 'template/' . $mainrow["path"];

              if ($pdf) {
                  $pdf->setSourceFile($filepath);

                  $pagecount = $pdf->setSourceFile($filepath);
  
                  $tplIdx = $pdf->importPage(1);
  
                  $pdf->useTemplate($tplIdx, 0, 0);
          
                  //following is where text is inserted into existing PDFs eg. School name. No longer used but left in as examples of positional text insert. See tmppdf below as well for identical code.
                  if ($mainrow["shortname"] == 'Attachment A - Appeal Process (Appeal Final Grade)') {
                      $pdf->SetFont('arial', '', 9);
                      $pdf->SetY(49);
                      $pdf->SetX(137);
                      $pdf->MultiCell(80, 5, $acaddivlongname, 0, 'L', 0);
                  }//endif
          
                  if ($mainrow["shortname"] == 'Attachment A - Appeal Process (Leave From Studies)') {
                      $pdf->SetFont('arial', '', 9);
                      $pdf->SetY(49);
                      $pdf->SetX(137);
                      $pdf->MultiCell(80, 5, $acaddivlongname, 0, 'L', 0);
                  }//endif
          
                  if ($mainrow["shortname"] == 'Attachment A - Appeal Process (Misconduct)') {
                      $pdf->SetFont('arial', '', 9);
                      $pdf->SetY(49);
                      $pdf->SetX(137);
                      $pdf->MultiCell(80, 5, $acaddivlongname, 0, 'L', 0);
                  }//endif
          
                  if ($mainrow["shortname"] == 'Attachment A - Appeal Process (Plagiarism)') {
                      $pdf->SetFont('arial', '', 9);
                      $pdf->SetY(34);
                      $pdf->SetX(22);
                      $pdf->MultiCell(50, 4, $acaddivlongname, 0, 'L', 0);
                      $pdf->SetY(38);
                      $pdf->SetX(69);
                      $pdf->MultiCell(60, 4, $acaddivlongname, 0, 'L', 0);
                      $pdf->SetY(46.5);
                      $pdf->SetX(137);
                      $pdf->MultiCell(80, 4, $acaddivlongname, 0, 'L', 0);
                      $pdf->SetY(77.5);
                      $pdf->SetX(137);
                      $pdf->MultiCell(80, 4, $acaddivlongname, 0, 'L', 0);
                  }//endif
          
                  if ($mainrow["shortname"] == 'Attachment A - Appeal Process (Unsatisfactory)') {
                      $pdf->SetFont('arial', '', 9);
                      $pdf->SetY(27);
                      $pdf->SetX(31);
                      $pdf->MultiCell(50, 4, $acaddivlongname, 0, 'L', 0);
                      $pdf->SetY(35.5);
                      $pdf->SetX(69);
                      $pdf->MultiCell(60, 4, $acaddivlongname, 0, 'L', 0);
                      $pdf->SetY(44);
                      $pdf->SetX(163);
                      $pdf->MultiCell(80, 4, $acaddivlongname, 0, 'L', 0);
                      $pdf->SetY(76);
                      $pdf->SetX(153.5);
                      $pdf->MultiCell(80, 4, $acaddivlongname, 0, 'L', 0);
                  }//endif

                  if ($pagecount > 1) {
                      for ($idx=2 ;$idx <= $pagecount; $idx++) {
                          $pdf->AddPageByArray(array('orientation' => $orientation,'margin_left' => 15,'margin_right' => 15,'margin_top' => 20,'margin_bottom' => 15,'margin_header' => 8,'margin_footer' => 9.5));
                          $tplIdx = $pdf->importPage($idx);
                          $pdf->useTemplate($tplIdx, 0, 0);
                      }//endfor
                  }//endif
              }//endif
        
        if ($tmppdf) {
            $tmppdf->setSourceFile($filepath);
                                           
            $tmppdfpagecount = $tmppdf->setSourceFile($filepath);
  
            $tplIdx = $tmppdf->importPage(1);
  
            $tmppdf->useTemplate($tplIdx, 0, 0);

            if ($mainrow["shortname"] == 'Attachment A - Appeal Process (Appeal Final Grade)') {
                $tmppdf->SetFont('arial', '', 9);
                $tmppdf->SetY(49);
                $tmppdf->SetX(137);
                $tmppdf->MultiCell(80, 5, $acaddivlongname, 0, 'L', 0);
            }//endif
          
            if ($mainrow["shortname"] == 'Attachment A - Appeal Process (Leave From Studies)') {
                $tmppdf->SetFont('arial', '', 9);
                $tmppdf->SetY(49);
                $tmppdf->SetX(137);
                $tmppdf->MultiCell(80, 5, $acaddivlongname, 0, 'L', 0);
            }//endif
          
            if ($mainrow["shortname"] == 'Attachment A - Appeal Process (Misconduct)') {
                $tmppdf->SetFont('arial', '', 9);
                $tmppdf->SetY(49);
                $tmppdf->SetX(137);
                $tmppdf->MultiCell(80, 5, $acaddivlongname, 0, 'L', 0);
            }//endif
          
            if ($mainrow["shortname"] == 'Attachment A - Appeal Process (Plagiarism)') {
                $tmppdf->SetFont('arial', '', 9);
                $tmppdf->SetY(34);
                $tmppdf->SetX(22);
                $tmppdf->MultiCell(50, 4, $acaddivlongname, 0, 'L', 0);
                $tmppdf->SetY(38);
                $tmppdf->SetX(69);
                $tmppdf->MultiCell(60, 4, $acaddivlongname, 0, 'L', 0);
                $tmppdf->SetY(46.5);
                $tmppdf->SetX(137);
                $tmppdf->MultiCell(80, 4, $acaddivlongname, 0, 'L', 0);
                $tmppdf->SetY(77.5);
                $tmppdf->SetX(137);
                $tmppdf->MultiCell(80, 4, $acaddivlongname, 0, 'L', 0);
            }//endif
          
            if ($mainrow["shortname"] == 'Attachment A - Appeal Process (Unsatisfactory)') {
                $tmppdf->SetFont('arial', '', 9);
                $tmppdf->SetY(27);
                $tmppdf->SetX(31);
                $tmppdf->MultiCell(50, 4, $acaddivlongname, 0, 'L', 0);
                $tmppdf->SetY(35.5);
                $tmppdf->SetX(69);
                $tmppdf->MultiCell(60, 4, $acaddivlongname, 0, 'L', 0);
                $tmppdf->SetY(44);
                $tmppdf->SetX(163);
                $tmppdf->MultiCell(80, 4, $acaddivlongname, 0, 'L', 0);
                $tmppdf->SetY(76);
                $tmppdf->SetX(153.5);
                $tmppdf->MultiCell(80, 4, $acaddivlongname, 0, 'L', 0);
            }//endif
          
            if ($tmppdfpagecount > 1) {
                for ($idx=2 ;$idx <= $tmppdfpagecount; $idx++) {
                    $tmppdf->AddPageByArray(array('orientation' => $orientation,'margin_left' => 15,'margin_right' => 15,'margin_top' => 20,'margin_bottom' => 15,'margin_header' => 8,'margin_footer' => 9.5));
                    $tplIdx = $tmppdf->importPage($idx);
                    $tmppdf->useTemplate($tplIdx, 0, 0);
                }//endfor
            }//endif
        }//endif
          }//endif
      
          if ($mainrow["type"]=='3') {//function
                
              if ($mainrow["path"]=='mergeevidence') {
                  $documentfile = 'misconduct/' . $letterid . '.pdf';
                  if (file_exists($documentfile)) {
                      letterbreak($pdf, $newlinepage, $orientation, $mainrow["type"]);
                      letterbreak($tmppdf, $newlinepage, $orientation, $mainrow["type"]);
                  }//endif
              }//endif
        else {
            letterbreak($pdf, $newlinepage, $orientation, $mainrow["type"]);
            letterbreak($tmppdf, $newlinepage, $orientation, $mainrow["type"]);
        }//endelse
                  
              switch ($mainrow["path"]) {
          case 'currentdate':
            $html = '<table width="100%" border="0" cellspacing="0" cellpadding="3"><tr><td align="left">'.date('jS F Y').'</td></tr></table>';
            break;
          case 'foldguides':
            $foldguide = true;
            break;
          case 'mergeevidence':
            $documentfile = 'misconduct/' . $letterid . '.pdf';
            if (file_exists($documentfile)) {
                if ($preview) {
                    $html = '<h1>Uploaded evidence merged here</h1>';
                    if ($pdf) {
                        $pdf->writeHTML($html);
                    }//endif
                
                    if ($tmppdf) {
                        $tmppdf->writeHTML($html);
                    }//endif
                }//endif
              else {
                  $filepath = 'misconduct/' . $letterid . '.pdf';
                
                  if ($pdf) {
                      $pdf->setSourceFile($filepath);
        
                      $pdfpagecount = $pdf->setSourceFile($filepath);
          
                      $tplIdx = $pdf->importPage(1);
          
                      $pdf->useTemplate($tplIdx, 0, 0);
                  
                      if ($pdfpagecount > 1) {
                          for ($idx=2 ;$idx <= $pdfpagecount; $idx++) {
                              $pdf->AddPageByArray(array('orientation' => $orientation,'margin_left' => 15,'margin_right' => 15,'margin_top' => 20,'margin_bottom' => 15,'margin_header' => 8,'margin_footer' => 9.5));
                              $tplIdx = $pdf->importPage($idx);
                              $pdf->useTemplate($tplIdx, 0, 0);
                          }//endfor
                      }//endif
                  }//endif
                  
                if ($tmppdf) {
                    $tmppdf->setSourceFile($filepath);
        
                    $tmppdfpagecount = $tmppdf->setSourceFile($filepath);
          
                    $tplIdx = $tmppdf->importPage(1);
          
                    $tmppdf->useTemplate($tplIdx, 0, 0);
                  
                    if ($tmppdfpagecount > 1) {
                        for ($idx=2 ;$idx <= $tmppdfpagecount; $idx++) {
                            $tmppdf->AddPageByArray(array('orientation' => $orientation,'margin_left' => 15,'margin_right' => 15,'margin_top' => 20,'margin_bottom' => 15,'margin_header' => 8,'margin_footer' => 9.5));
                            $tplIdx = $tmppdf->importPage($idx);
                            $tmppdf->useTemplate($tplIdx, 0, 0);
                        }//endfor
                    }//endif
                }//endif
              }//endif
            }//endif
            break;
          case 'signaturelocationcorrespondenceCC':
            getCoordinator($utltype='N', $utlsubdisciplineid=$subdisciplineid, $utlstrandid=false, $utllocationid=$locationid, $utlcoordinatorid, $utltitlename, $utlposition, $utladdress, $utlroom, $utltelephone, $utlemail, $utlcoordinatoruserid);
            $html = '
                <table width="100%" style="page-break-inside: avoid; autosize: 1;" border="0" cellspacing="0" cellpadding="3">
                ';
            $html = $html . '
                <tr><td>Yours sincerely</td></tr>
                ';
            $image = 'image/USRSIGNAT-' . $utlcoordinatoruserid . '.jpg';
            if (file_exists($image)) {
                $html = $html . '
                <tr><td style="height:60;"><img src="' . $image . '" style="border: 0" width="90" height="40"></td></tr>
                ';
            }//endif
            else {
                $html = $html . '
                <tr><td style="height:60;">&nbsp;</td></tr>
                ';
            }//endelse
            $html = $html . '
                <tr><td>'.$utltitlename.'</td></tr>
                ';
            if (!empty($utlposition)) {
                $html = $html . '
                <tr><td>'.$utlposition.'</td></tr>
                ';
            }//endif
            $html = $html . '
                </table>
                ';
            break;
          case 'signaturelocationcorrespondence':
            getCoordinator($utltype='A', $utlsubdisciplineid=$subdisciplineid, $utlstrandid=false, $utllocationid=$locationid, $utlcoordinatorid, $utltitlename, $utlposition, $utladdress, $utlroom, $utltelephone, $utlemail, $utlcoordinatoruserid);
            $html = '
                <table width="100%" style="page-break-inside: avoid; autosize: 1;" border="0" cellspacing="0" cellpadding="3">
                ';
            $html = $html . '
                <tr><td>Yours sincerely</td></tr>
                ';
            $image = 'image/USRSIGNAT-' . $utlcoordinatoruserid . '.jpg';
            if (file_exists($image)) {
                $html = $html . '
                <tr><td style="height:60;"><img src="' . $image . '" style="border: 0" width="90" height="40"></td></tr>
                ';
            }//endif
            else {
                $html = $html . '
                <tr><td style="height:60;">&nbsp;</td></tr>
                ';
            }//endelse
            $html = $html . '
                <tr><td>'.$utltitlename.'</td></tr>
                ';
            if (!empty($utlposition)) {
                $html = $html . '
                <tr><td>'.$utlposition.'</td></tr>
                ';
            }//endif
            $html = $html . '
                </table>
                ';
            break;
          case 'signaturedean':
            getCoordinator($utltype='X', $utlsubdisciplineid=$subdisciplineid, $utlstrandid=false, $utllocationid=false, $utlcoordinatorid, $utltitlename, $utlposition, $utladdress, $utlroom, $utltelephone, $utlemail, $utlcoordinatoruserid);
            $html = '
                <table width="100%" style="page-break-inside: avoid; autosize: 1;" border="0" cellspacing="0" cellpadding="3">
                ';
            $html = $html . '
                <tr><td>Yours sincerely</td></tr>
                ';
            $image = 'image/USRSIGNAT-' . $utlcoordinatoruserid . '.jpg';
            if (file_exists($image)) {
                $html = $html . '
                <tr><td style="height:60;"><img src="' . $image . '" style="border: 0" width="90" height="40"></td></tr>
                ';
            }//endif
            else {
                $html = $html . '
                <tr><td style="height:60;">&nbsp;</td></tr>
                ';
            }//endelse
            if ($preview) {
                $utltitlename = 'Professor Barney Gumble';
            }//endif
            $html = $html . '
                <tr><td>'.$utltitlename.'</td></tr>
                ';
            if (!empty($utlposition)) {
                $html = $html . '
                <tr><td>'.$utlposition.'</td></tr>
                ';
            }//endif
            $html = $html . '
                <tr><td>'.$acaddivlongname.'</td></tr>
                ';
            $html = $html . '
                </table>
                ';
            break;
          case 'signatureplagiarismofficer':
            getCoordinator($utltype='O', $utlsubdisciplineid=$subdisciplineid, $utlstrandid=false, $utllocationid=false, $utlcoordinatorid, $utltitlename, $utlposition, $utladdress, $utlroom, $utltelephone, $utlemail, $utlcoordinatoruserid);
    
            $html = '
                <table width="100%" style="page-break-inside: avoid; autosize: 1;" border="0" cellspacing="0" cellpadding="3">
                ';
            $html = $html . '
                <tr><td>Yours sincerely</td></tr>
                ';
            $image = 'image/USRSIGNAT-' . $utlcoordinatoruserid . '.jpg';
            if (file_exists($image)) {
                $html = $html . '
                <tr><td style="height:60;"><img src="' . $image . '" style="border: 0" width="90" height="40"></td></tr>
                ';
            }//endif
            else {
                $html = $html . '
                <tr><td style="height:60;">&nbsp;</td></tr>
                ';
            }//endelse
            if ($preview) {
                $utltitlename = 'Dr Barney Gumble';
            }//endif
            $html = $html . '
                <tr><td>'.$utltitlename.'</td></tr>
                ';
            if (!empty($utlposition)) {
                $html = $html . '
                <tr><td>'.$utlposition.'</td></tr>
                ';
            }//endif
            $html = $html . '
                <tr><td>'.$acaddivlongname.'</td></tr>
                ';
            $html = $html . '
                </table>
                ';
            break;
          case 'signatureprogramcoordinator':
            getCoordinator($utltype='P', $utlsubdisciplineid=false, $utlstrandid=$strandid, $utllocationid=$locationid, $utlcoordinatorid, $utltitlename, $utlposition, $utladdress, $utlroom, $utltelephone, $utlemail, $utlcoordinatoruserid);
            $html = '
                <table width="100%" style="page-break-inside: avoid; autosize: 1;" border="0" cellspacing="0" cellpadding="3">
                ';
            $html = $html . '
                <tr><td>Yours sincerely</td></tr>
                ';
            $image = 'image/USRSIGNAT-' . $utlcoordinatoruserid . '.jpg';
            if (file_exists($image)) {
                $html = $html . '
                <tr><td style="height:60;"><img src="' . $image . '" style="border: 0" width="90" height="40"></td></tr>
                ';
            }//endif
            else {
                $html = $html . '
                <tr><td style="height:60;">&nbsp;</td></tr>
                ';
            }//endelse
            if ($preview) {
                $utltitlename = 'Dr Seymour Skinner';
            }//endif
            $html = $html . '
                <tr><td>'.$utltitlename.'</td></tr>
                ';
            if (!empty($utlposition)) {
                $html = $html . '
                <tr><td>'.$utlposition.'</td></tr>
                ';
            }//endif
            $html = $html . '
                <tr><td>'.$acaddivlongname.'</td></tr>
                ';
            $html = $html . '
                </table>
                ';
            break;
          case 'signatureregistrar':
            getCoordinator($utltype='R', $utlsubdisciplineid=false, $utlstrandid=false, $utllocationid=false, $utlcoordinatorid, $utltitlename, $utlposition, $utladdress, $utlroom, $utltelephone, $utlemail, $utlcoordinatoruserid);
            $html = '
                <table width="100%" style="page-break-inside: avoid; autosize: 1;" border="0" cellspacing="0" cellpadding="3">
                ';
            $html = $html . '
                <tr><td>Yours sincerely</td></tr>
                ';
            $image = 'image/USRSIGNAT-' . $utlcoordinatoruserid . '.jpg';
            if (file_exists($image)) {
                $html = $html . '
                <tr><td style="height:60;"><img src="' . $image . '" style="border: 0" width="90" height="40"></td></tr>
                ';
            }//endif
            else {
                $html = $html . '
                <tr><td style="height:60;">&nbsp;</td></tr>
                ';
            }//endelse
            if ($preview) {
                $utltitlename = 'Marg Simpson';
            }//endif
            $html = $html . '
                <tr><td>'.$utltitlename.'</td></tr>
                ';
            if (!empty($utlposition)) {
                $html = $html . '
                <tr><td>'.$utlposition.'</td></tr>
                ';
            }//endif
            $html = $html . '
                <tr><td>'.$_SESSION[$_GET["trid"] . "sysinstitution"].'</td></tr>
                ';
            $html = $html . '
                </table>
                ';
            break;
          case 'studentletterheadername':
            nameaddress($pdf, $tmppdf, _dearON, _towhomOFF, $headerheading, $foldguide, $studentdata);
            break;
          case 'studentletterheadertowhom':
            nameaddress($pdf, $tmppdf, _dearOFF, _towhomON, $headerheading, $foldguide, $studentdata);
            break;
        }//endswitch
        
              //place name of any funciton that does work elsewhere here otherwise HTML is printed again
              if ($html && $mainrow["path"] !== 'studentplagiarismreport' && $mainrow["path"] !== 'mergeevidence') {
                  if ($pdf) {
                      $pdf->writeHTML($html);
                  }//endif
          
                  if ($tmppdf) {
                      $tmppdf->writeHTML($html);
                  }//endif
              }//endif
          }//endif
          
          if ($mainrow["type"]==4 || $mainrow["type"]==8) {//text - text / function
              //look for any embedded fields
              if (substr_count($predefinedtext, '#') > 1) {
                  while (stripos($predefinedtext, '#Field') !== false) {
                      $field = substr($predefinedtext, strpos($predefinedtext, '#Field') + 6, 2);
                      $searchstr = '#Field' . sprintf('%02d', $field) . '#';
            
                      $idx = $field -1;
            
                      if (stripos($predefinedtext, $searchstr) !== false) {
                          $predefinedtext = str_replace($searchstr, $fieldsdata[$idx], $predefinedtext);
                      }//endif
                  }//endwhile
              }//endif
        
              commontemplatefields($predefinedtext, $studentid, $studentname, $studentaddress, $studentemails, $strandid, $strandname, $locationid, $locationname, $acaddivid, $acaddivshortname, $acaddivlongname, $termid, $semester, $year, $subdisciplineid);
        
              if ($predefinedtext) {
                  if (!$specialpdf) {
                      letterbreak($pdf, $newlinepage, $orientation, $mainrow["type"]);
                      letterbreak($tmppdf, $newlinepage, $orientation, $mainrow["type"]);
                  }//endif
                  else {
                      letterbreak($specialpdf, $newlinepage, $orientation, $mainrow["type"]);
                  }//endelse
        
                  $html = $predefinedtext;
          
                  if (!$specialpdf) {
                      if ($pdf) {
                          $pdf->writeHTML($html);
                      }//endif
            
                      if ($tmppdf) {
                          $tmppdf->writeHTML($html);
                      }//endif
                  }//endif
          else {
              $specialpdf->writeHTML($html);
          }//endelse
              }//endif
          }//endif
      
          //$mainrow["type"]=='5')//general file not used for letters
      
          if ($mainrow["type"]=='6') {//heading
              //look for any embedded fields
              if (substr_count($predefinedtext, '#') > 1) {
                  while (stripos($predefinedtext, '#Field') !== false) {
                      $field = substr($predefinedtext, strpos($predefinedtext, '#Field') + 6, 2);
                      $searchstr = '#Field' . sprintf('%02d', $field) . '#';
            
                      $idx = $field -1;
            
                      if (stripos($predefinedtext, $searchstr) !== false) {
                          $predefinedtext = str_replace($searchstr, $fieldsdata[$idx], $predefinedtext);
                      }//endif
                  }//endwhile
              }//endif
        
              $headerheading = $predefinedtext;
          }//endif
      
          if (empty($mainrow["type"])) {//common text
      
              $templatetextid = $mainrow["templatetextid"] - 99999;//added in templateitemupdate
      
              $sql = "select content
                from templatetext
                where templatetextid = '$templatetextid'";
        
              $txtsql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php')."-052: ".mysqli_error($db));
  
              if (mysqli_num_rows($txtsql_ok) > 0) {
                  $txtrow = mysqli_fetch_array($txtsql_ok) or die(basename(__FILE__, '.php')."-053: ".mysqli_error($db));
          
                  $predefinedtext =  stripslashes($txtrow["content"]);
              }//endif
      
              commontemplatefields($predefinedtext, $studentid, $studentname, $studentaddress, $studentemails, $strandid, $strandname, $locationid, $locationname, $acaddivid, $acaddivshortname, $acaddivlongname, $termid, $semester, $year, $subdisciplineid);
      
              if ($predefinedtext) {
                  if (!$specialpdf) {
                      letterbreak($pdf, $newlinepage, $orientation, $mainrow["type"]);
                      letterbreak($tmppdf, $newlinepage, $orientation, $mainrow["type"]);
                  }//endif
                  else {
                      letterbreak($specialpdf, $newlinepage, $orientation, $mainrow["type"]);
                  }//endelse
        
                  $html = $predefinedtext;
          
                  if (!$specialpdf) {
                      if ($pdf) {
                          $pdf->writeHTML($html);
                      }//endif
            
                      if ($tmppdf) {
                          $tmppdf->writeHTML($html);
                      }//endif
                  }//endif
          else {
              $specialpdf->writeHTML($html);
          }//endelse
              }//endif
          }//endif
      }//endfor
  }//endfunction
               
  function getTemplateSubitem($templatesubitemid)
  {
      global $p, $db;

      $sql = "select description
            from templatesubitem              
            where templatesubitemid = '$templatesubitemid'";
      
      $sql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php')."-054: ".mysqli_error($db));

      $description = '';
      if (mysqli_num_rows($sql_ok) > 0) {
          $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__, '.php')."-055: ".mysqli_error($db));
      
          $description = stripslashes($row["description"]);
      }//endif
    
      return $description;
  }//endfunction
  
  function getStringBetween($string, $start, $end)
  {
      $temp = explode($start, $string);
      $temp = explode($end, $temp[1]);
      $temp = trim($temp[0]);
      return $temp;
  }//endfunction
  
  function resizejpeg($original_file, $destination_file, $square_size)
  {
        
        // get width and height of original image
      $imagedata = getimagesize($original_file);
      $original_width = $imagedata[0];
      $original_height = $imagedata[1];
        
      if ($original_width > $original_height) {
          $new_height = $square_size;
          $new_width = $new_height*($original_width/$original_height);
      }//endif
      if ($original_height > $original_width) {
          $new_width = $square_size;
          $new_height = $new_width*($original_height/$original_width);
      }//endif
      if ($original_height == $original_width) {
          $new_width = $square_size;
          $new_height = $square_size;
      }//endif
        
      $new_width = round($new_width);
      $new_height = round($new_height);
        
      // load the image
      if (substr_count(strtolower($original_file), '.jpg') or substr_count(strtolower($original_file), '.jpeg')) {
          $original_image = imagecreatefromjpeg($original_file);
      }//endif
        
      $smaller_image = imagecreatetruecolor($new_width, $new_height);
      $square_image = imagecreatetruecolor($square_size, $square_size);
        
      imagecopyresampled($smaller_image, $original_image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);
        
      if ($new_width>$new_height) {
          $difference = $new_width-$new_height;
          $half_difference =  round($difference/2);
          imagecopyresampled($square_image, $smaller_image, 0-$half_difference+1, 0, 0, 0, $square_size+$difference, $square_size, $new_width, $new_height);
      }//endif
      if ($new_height>$new_width) {
          $difference = $new_height-$new_width;
          $half_difference =  round($difference/2);
          imagecopyresampled($square_image, $smaller_image, 0, 0-$half_difference+1, 0, 0, $square_size, $square_size+$difference, $new_width, $new_height);
      }//endif
      if ($new_height == $new_width) {
          imagecopyresampled($square_image, $smaller_image, 0, 0, 0, 0, $square_size, $square_size, $new_width, $new_height);
      }//endif
        
      // save the smaller image FILE if destination file given
      if (substr_count(strtolower($destination_file), '.jpg')) {
          imagejpeg($square_image, $destination_file, 100);
      }//endif

      imagedestroy($original_image);
      imagedestroy($smaller_image);
      imagedestroy($square_image);
  }//endfunction
  
  function planstatus($status, $cscomplete, $optionname, $label, $APonly, $restrictaccess, $processingpleasewait, $checkforblankstatus)
  {
      global $p, $db;
    
      if (empty($optionname)) {
          $displaystatus = '';
          switch ($status) {
        case 'A':
          $displaystatus = "Approved";
          break;
        case 'C':
          if ($cscomplete) {
              $displaystatus = '<span title=\"Incomplete plan here but Completed status in Campus Solutions\">Completed*</span>';
          }//endif
          else {
              $displaystatus = 'Completed';
          }//endelse
          break;
        case 'D':
          $displaystatus = "Deleted";
          break;
        case 'U':
          $displaystatus = "Discontinued";
          break;
        case 'Y':
          $displaystatus = "Early Completion";
          break;
        case 'X':
          $displaystatus = "Early Exit";
          break;
        case 'G':
          $displaystatus = "Eligible to Graduate";
          break;
        case 'E':
          $displaystatus = "Excluded";
          break;
        case 'F':
          $displaystatus = "Failed to Enrol";
          break;
        case 'L':
          $displaystatus = "Leave";
          break;
        case 'P':
        case 'N':
        case 'R':
          $displaystatus = "Pending";
          break;
        case 'S':
          $displaystatus = "Suspended";
          break;
        case 'T':
          $displaystatus = "Transferred";
          break;
        case 'W':
          $displaystatus = "Withdrawn";
          break;
        }//endcase
        
          return $displaystatus;
      }//endif
      else {
          if ($processingpleasewait) {
              $statusOptions = '<select name="'.$optionname.'" onchange="processingpleasewait();">';
          }//endif
          else {
              $statusOptions = '<select name="'.$optionname.'">';
          }//endesle
      
          if ($label) {
              $statusOptions = $statusOptions . "\n<option value=''>--".$label."--</option>";
          }//endif
      
          //Approved status
          if (empty($restrictaccess) || $p->admin_access_allowed('BMSZ')) {
              if ($_POST[$optionname]=='A') {
                  $statusOptions = $statusOptions . "\n<option selected value='A'>Approved</option>";
              }//endif
              else {
                  $statusOptions = $statusOptions . "\n<option value='A'>Approved</option>";
              }//endelse
          }//endif
         
      //NOT just approved and pending
          if (!$APonly) {
              if (empty($restrictaccess) || $p->admin_access_allowed('Z') || ($p->admin_access_allowed('BMSZ'))) {
                  if ($_POST[$optionname]=='C') {
                      $statusOptions = $statusOptions . "\n<option selected value='C'>Completed</option>";
                  }//endif
                  else {
                      $statusOptions = $statusOptions . "\n<option value='C'>Completed</option>";
                  }//endelse
              }//endif
        if ($p->admin_access_allowed('Z')) {
            if ($_POST[$optionname]=='D') {
                $statusOptions = $statusOptions . "\n<option selected value='D'>Deleted</option>";
            }//endif
            else {
                $statusOptions = $statusOptions . "\n<option value='D'>Deleted</option>";
            }//endelse
        }//endif
        if (empty($restrictaccess) || $p->admin_access_allowed('BMSZ')) {
            if ($_POST[$optionname]=='U') {
                $statusOptions = $statusOptions . "\n<option selected value='U'>Discontinued</option>";
            }//endif
            else {
                $statusOptions = $statusOptions . "\n<option value='U'>Discontinued</option>";
            }//endelse
        }//endif
        if (empty($restrictaccess)|| $p->admin_access_allowed('Z') || (($p->admin_access_allowed('BMSZ') && ($p->extra_access($_SESSION[$_GET["trid"] . 'userid'], "pmc"))))) {
            if ($_POST[$optionname]=='Y') {
                $statusOptions = $statusOptions . "\n<option selected value='Y'>Early Completion</option>";
            }//endif
            else {
                $statusOptions = $statusOptions . "\n<option value='Y'>Early Completion</option>";
            }//endelse
        }//endif
        if (empty($restrictaccess) || $p->admin_access_allowed('BMSZ')) {
            if ($_POST[$optionname]=='X') {
                $statusOptions = $statusOptions . "\n<option selected value='X'>Early Exit</option>";
            }//endif
            else {
                $statusOptions = $statusOptions . "\n<option value='X'>Early Exit</option>";
            }//endelse
        }//endif
        if (empty($restrictaccess) || $p->admin_access_allowed('Z') || ($p->admin_access_allowed('BMSZ') && ($p->extra_access($_SESSION[$_GET["trid"] . 'userid'], "elgrad")))) {
            if ($_POST[$optionname]=='G') {
                $statusOptions = $statusOptions . "\n<option selected value='G'>Eligible to Graduate</option>";
            }//endif
            else {
                $statusOptions = $statusOptions . "\n<option value='G'>Eligible to Graduate</option>";
            }//endelse
        }//endif
        if (empty($restrictaccess) || $p->admin_access_allowed('BMSZ')) {
            if ($_POST[$optionname]=='E') {
                $statusOptions = $statusOptions . "\n<option selected value='E'>Excluded</option>";
            }//endif
            else {
                $statusOptions = $statusOptions . "\n<option value='E'>Excluded</option>";
            }//endelse
        }//endif
        if (empty($restrictaccess) || $p->admin_access_allowed('BEMPSZ')) {
            if ($_POST[$optionname]=='F') {
                $statusOptions = $statusOptions . "\n<option selected value='F'>Failed to Enrol</option>";
            }//endif
            else {
                $statusOptions = $statusOptions . "\n<option value='F'>Failed to Enrol</option>";
            }//endelse
        }//endif
        if (empty($restrictaccess) || $p->admin_access_allowed('BMSZ')) {
            if ($_POST[$optionname]=='L') {
                $statusOptions = $statusOptions . "\n<option selected value='L'>Leave</option>";
            }//endif
            else {
                $statusOptions = $statusOptions . "\n<option value='L'>Leave</option>";
            }//endelse
        }//endif
          }//endif
      
      $pendingarray = array('P','N','R');
          if ($checkforblankstatus) {
              $pendingarray = array('','P','N','R');
          }//endif

          if (in_array($_POST[$optionname], $pendingarray) || ($restrictaccess && ($p->admin_access_allowed('EP') && $_POST[$optionname] !== 'F'))) {
              $statusOptions = $statusOptions . "\n<option selected value='P'>Pending</option>";
          }//endif
          else {
              $statusOptions = $statusOptions . "\n<option value='P'>Pending</option>";
          }//endelse
      
          if (!$APonly) {
              if (empty($restrictaccess) || $p->admin_access_allowed('BMSZ')) {
                  if ($_POST[$optionname]=='S') {
                      $statusOptions = $statusOptions . "\n<option selected value='S'>Suspended</option>";
                  }//endif
                  else {
                      $statusOptions = $statusOptions . "\n<option value='S'>Suspended</option>";
                  }//endelse
              }//endif
        if (empty($restrictaccess) || $p->admin_access_allowed('BMSZ')) {
            if ($_POST[$optionname]=='T') {
                $statusOptions = $statusOptions . "\n<option selected value='T'>Transferred</option>";
            }//endif
            else {
                $statusOptions = $statusOptions . "\n<option value='T'>Transferred</option>";
            }//endelse
        }//endif
        if (empty($restrictaccess) || $p->admin_access_allowed('BMSZ')) {
            if ($_POST[$optionname]=='W') {
                $statusOptions = $statusOptions . "\n<option selected value='W'>Withdrawn</option>";
            }//endif
            else {
                $statusOptions = $statusOptions . "\n<option value='W'>Withdrawn</option>";
            }//endelse
        }//endif
          }//endif
      
      $statusOptions = $statusOptions . '</select>';
      
          return $statusOptions;
      }//endelse
  }//endfunction
  
  function removenonutf8($instring)
  {
      return mb_convert_encoding($instring, "UTF-8", "UTF-8");
  }//endfunction
  
  
  function published_terms()
  {
      global $p, $db;
   
  
      //Obtain todays date and subtract six months ()
      //Obtain todays date
      $today=date('d-m-Y');
      echo "\n" . 'Today is ' . $today;

    
      //obtain a list of active terms from fdlgrades
      $published_sql = "select distinct term.termid, termdate from locationterm inner join locationtermitem on locationtermitem.locationtermid=locationterm.locationtermid
    inner join term on term.termid = locationterm.termid
    and locationtermitem.description like 'Results%' 
    and hide!=1 and resultcheck=1
    order by termdate desc";
  
      $published_sql_ok = mysqli_query($db, $published_sql) or die(basename(__FILE__, '.php')."-001: ".mysqli_error($db));
      //echo "\n" . $published_sql;
    
      $indx=0;
      //echo "\n" . 'num rows was ' . mysqli_num_rows( $published_sql_ok);
      for ($idx=0; $idx < mysqli_num_rows($published_sql_ok); $idx++) {
          $published_row = mysqli_fetch_array($published_sql_ok) or die(basename(__FILE__, '.php')."-002: ".mysqli_error($db));
          $sem=$published_row["termid"];
          $term_date=$published_row["termdate"];
          $sixMonth = date('d-m-Y', strtotime($term_date) + (183 * 24 * 60 * 60));
  
          //Check to see if between 1 week prior to start and desc date if so then add to list of terms to use
          if (strtotime($sixMonth) > (strtotime($today))) {
              $published_sem[$indx]=$sem;
              $indx=$indx+1;
          }
      }
      return $published_sem;
  }

  
  function active_terms($type, $days_extra)
  {
      global $p, $db;
    
      //Calculate extra days to add to calculation
      $extra=0;
      if (isset($days_extra)) {
          $extra=$days_extra * 24 * 60 * 60;
      }

      //Add wildcard to end
      if (isset($type)) {
          $type=$type . '%';
          $desc_str=" and description like '$type'";
      }

      //Obtain todays date
      $today=date('d-m-Y');
    
      //Determine the current active terms
      $sem='';
    
      //setup array to store terms to use to query cs database
      $active_sem=array();
      $indx=0;
    
      //obtain a list of active terms from fdlgrades
      $active_sql = 'select termid from term where enrolcheck=1';
      $active_sql_ok = mysqli_query($db, $active_sql) or die(basename(__FILE__, '.php')."-001: ".mysqli_error($db));
    
      for ($active_idx=0; $active_idx < mysqli_num_rows($active_sql_ok); $active_idx++) {
          $active_row = mysqli_fetch_array($active_sql_ok) or die(basename(__FILE__, '.php')."-002: ".mysqli_error($db));
          $sem=$active_row["termid"];
       
          $desc_sql = "select * from locationtermitem, locationterm where termid='$sem' and locationterm.locationtermid = locationtermitem.locationtermid $desc_str";
          $desc_sql_ok = mysqli_query($db, $desc_sql) or die(basename(__FILE__, '.php')."-005: ".mysqli_error($db));

          //check to see if a desc day has been set in the calendar
          if (mysqli_num_rows($desc_sql_ok) > 0) {
              $desc_row = mysqli_fetch_array($desc_sql_ok) or die(basename(__FILE__, '.php')."-006: ".mysqli_error($db));

              //Add 7 days to desc date for refreshes
              $desc_date=strtotime($desc_row["termdate"])+ $extra;
          
              //Check to see if between 1 week prior to start and desc date if so then add to list of terms to use
              if ((strtotime($today) <= $desc_date)) {
                  $active_sem[$indx]=$sem;
                  $indx=$indx+1;
              }//endif
          }//endif
          else {
              echo "\n" . 'No ' . $desc . ' date set for ' . $sem;
          }//end else
      }

      return $active_sem;
  }

function unit_access_allowed($arguserid, $arglocationid, $argtermid, $argunitid, $argusertype)
{ //
    global $p, $db;
    $usertype=implode("','", $argusertype);
    $sql = "select *
            from unituser
            where locationid = '$arglocationid'
            and termid = '$argtermid'
            and unitid = '$argunitid'
            and userid = '$arguserid'
            and `type` in ('$usertype')";
    $st_sql_ok = mysqli_query($db, $sql) or die(basename(__FILE__, '.php') . ' ln ' . __LINE__ . ': ' .  mysqli_error($db));

    if (mysqli_num_rows($st_sql_ok) > 0) {
        return true;
    }
    return false;
}
