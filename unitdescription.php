<?php

include_once("basePage.php");
include_once("utils.php");
include_once("requisiteutils.php");
include_once("vendor/autoload.php");

class unitdescription_page extends basePage{
    
    function update_allowed($upto){//if upto is provided update is allowed up to that status
        
        if ($_SESSION[$_GET["trid"] . "udstatus"]=='' || $_SESSION[$_GET["trid"] . "udstatus"]==NULL || $_SESSION[$_GET["trid"] . "udstatus"]=='Q'){
            return true;
        }//endif
        
        if ($upto == 'R' && $_SESSION[$_GET["trid"] . "udstatus"]=='R'){//Ready to approve
            return true;
        }//endif
        
        if ($upto == 'A' && ($_SESSION[$_GET["trid"] . "udstatus"]=='R' || $_SESSION[$_GET["trid"] . "udstatus"]=='A')){//Unit level approved
            return true;
        }//endif
        
        if ($upto == 'P' && ($_SESSION[$_GET["trid"] . "udstatus"]=='R' || $_SESSION[$_GET["trid"] . "udstatus"]=='A'  || $_SESSION[$_GET["trid"] . "udstatus"]=='P')){//Unit level published
            return true;
        }//endif
        
        return false;
        
    }//endfunction
    
    function display_page(){
        
        global $p, $db, $subdisciplineid, $locationid, $termid, $unitid, $unitname, $unitlevel, $unitcreditpoint, $unitasced, $unitgradingbasis, $unitprofessionalengagement, $nosupplementary;
        
        $title = trim($unitname);
        
        if (isset($_POST["btnPDF"])){
            
            $pdf=new PDF(['mode' => 'utf-8','format' => 'A4','default_font_size' => 10,'default_font' => 'arial','margin_left' => 15,'margin_right' => 15,'margin_top' => 20,'margin_bottom' => 15,'margin_header' => 8,'margin_footer' => 8,'orientation' => 'P']);
            
            $file = 'COURSEDESC_' . $unitid . '_' . str_replace(' ','_',$locationid) .  '_' . substr($termid,0,4) . '-' . substr($termid,5,2) . '.pdf';
            //$pdf->pdfHeaderTitle = strtoupper($unitid . ' ' . $title);
            $pdf->pdfHeaderTitle = strtoupper($unitid);
            
            $a = $_SESSION[$_GET["trid"] . "sysname"] . ' UD_' . $unitid . '_'. $termid . date("_Y-m-d H:i:s");
            $b = 'CRICOS Provider Number: '. $_SESSION[$_GET["trid"] . "syscricosprovider"];
            //       $footer = '
            //           <table width="100%" style="vertical-align: bottom; font-size: 6pt;">
            //           <tr><td colspan="3"><img src="image/img_band.jpg" width="99.5%" height="6px"/></td></tr>
            //           <tr>
            //           <td width="33%">'.$a.'</td>
            //           <td width="33%" align="center">'.$b.'</td>
            //           <td width="33%" style="text-align: right;">{PAGENO} /{nb}</td>
            //           </tr>
            //           </table>';
            
            $footer = '
          <table width="100%" style="vertical-align: bottom; font-size: 6pt;">
          <tr>
          <td width="33%">'.$a.'</td>
          <td width="33%" align="center">'.$b.'</td>
          <td width="33%" style="text-align: right;">{PAGENO} /{nb}</td>
          </tr>
          </table>';
            
            $pdf->setHTMLFooter($footer);
            
            
            $pdf->AddPageByArray(array('margin_left' => 15,'margin_right' => 15,'margin_top' => 20,'margin_bottom' => 15,'margin_header' => 8,'margin_footer' => 8));
            
        }//endif
        
        if (!isset($_POST["btnPDF"])){
            echo '<script language="javascript">
                
      window.onerror = blockError;
                
      function blockError(){
        return true;
      }//endfunction
                
      sWidth = screen.width;
      sHeight = screen.height;
      sLeft = (sWidth - (sWidth *.9)) / 2;
      sTop = (sHeight - (sHeight *.9)) / 2;
                
      function unitdescriptionupdate(unitdescdetailkey, locationid, termid, unitid, udtype, rows, resequence, fieldtype){
        newWindow=window.open("unitdescriptionupdate.php?trid='.$_GET["trid"].'&unitdescdetailkey=" + unitdescdetailkey + "&locationid=" + locationid + "&termid=" + termid + "&unitid=" + unitid + "&udtype=" + udtype + "&rows=" + rows + "&resequence=" + resequence + "&fieldtype=" + fieldtype,"fdlgunitdescriptionupdate","resizable=yes, scrollbars=yes, menubar=yes, width=" + sWidth *.9  + ", height=" + sHeight *.8 + ", top=" + sTop + ", left=" + sLeft);
        newWindow.focus();
      }//endfunction
            
      function unitupdate(argunitid, argsubdisciplineid){
        newWindow=window.open("unitupdate.php?trid='.$_GET["trid"].'&unitid=" + argunitid + "&subdisciplineid=" + argsubdisciplineid,"fdlgunitupdate","resizable=yes, scrollbars=yes, menubar=yes, width=" + sWidth *.9  + ", height=" + sHeight *.8 + ", top=" + sTop + ", left=" + sLeft);
        newWindow.focus();
      }//endfunction
            
      function tasks(locationid,termid,unitid){
        newWindow=window.open("task.php?trid='.$_GET["trid"].'&locationid=" + locationid + "&termid=" + termid + "&unitid=" + unitid,"fdlgtask","resizable=yes, scrollbars=yes, menubar=yes, width=" + sWidth *.9  + ", height=" + sHeight *.8 + ", top=" + sTop + ", left=" + sLeft);
        newWindow.focus();
      }//endfunction
            
      function help(){
        newWindow=window.open("help.php?trid='.$_GET["trid"].'&goto=hlpunitdesc","fdlghelp","resizable=yes, scrollbars=yes, menubar=yes, width=" + sWidth *.9  + ", height=" + sHeight *.8 + ", top=" + sTop + ", left=" + sLeft + "");
        newWindow.focus();
      }//endfunction
            
      </script>
            
      </head>
            
      <body>
            
        <form name="frmunitdescription" method="post">
            
          <style type="text/css" media="screen">
            div.printonly{display: none;}
            span.boldred{font-weight: bold;}
            span.red{color: red;}
            span.maroon{color: maroon;}
            span.boldgray{color: gray; font-weight: bold;}
            span.gray{color: gray;}
            span.tiny{font-family: Arial; font-size: 11;}
            td{font-family: Arial; font-size: 14;}
            span.hlp{font-family: Arial; font-size: 18;}
          </style>
            
          <style type="text/css" media="print">
            div.noprint{display: none;}
            div.printonly{font-weight: bold; font-size: 48;}
            span.boldred{font-weight: bold;}
            span.tiny{font-family: Arial; font-size: 11;}
            td{font-family: Arial; font-size: 14;}
          </style>  ';
        }//endif
        
        $effectivetermid = '';
        
        //Check if we need to display former unit name
        $formername = '';
        $sql = "select *
            from csunit
            where csunitid = '$unitid'";
        
        $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-001: ".mysqli_error($db));
        
        if (mysqli_num_rows($sql_ok) > 0){
            $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-002: ".mysqli_error($db));
            
            $formername = $row["unitid"];
        }//endif
        
        $subdiscipline = getSubdiscipline($subdisciplineid);
        $acaddiv = $subdiscipline["acaddivlongname"];
        
        //check to see if current user is involved in this course. if not no update allowed.
        $allowupdate = true;
        $userid = $_SESSION[$_GET["trid"] . 'userid'];
        if (!in_array($_SESSION[$_GET["trid"] . "admin"], array('Z','S','M','P')) && !$p->extra_access($_SESSION[$_GET["trid"] . 'userid'],"udstatus")){
            $sql = "select *
              from unituser
              where locationid = '$locationid'
              and termid = '$termid'
              and unitid = '$unitid'
              and userid = '$userid'";
            
            $uusql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-003: ".mysqli_error($db));
            
            if (mysqli_num_rows($uusql_ok) == 0){
                $allowupdate = false;
            }//endif
        }//endif
        
        if ($_SESSION["mrkssysacaddivlabel"]){
            $acaddivlabel = $_SESSION["mrkssysacaddivlabel"];
        }//endif
        else {
            $acaddivlabel = $_SESSION[$_GET["trid"] . "sysacaddivlabel"];
        }//endelse
        
        if (!isset($_POST["btnPDF"])){
            
            echo '<div class="noprint">';
            echo '<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0">';
            echo '<tr><td align="center"><br>';
            echo '<b>Location:</b>&nbsp;' . pad($locationid,20,"padright") . '<b>Term:</b>&nbsp;'. pad($termid,20,"padright") . '<b>Course:</b>&nbsp;' . $unitid . '<br><br>';
            echo '</td></tr>';
            echo '</table>';
            
            echo '<table align="center" width="100%" border="1" bordercolor="#0000FF" cellpadding="6" cellspacing="0">';
            echo '<tr>';
            
            echo '<td align="center" colspan="10"><br>';
            if (empty($_GET["type"]) || $_GET["type"]!=='Z'){
                echo '<span class="hlp"><a href="javascript:help()" title=" Help ">?</a></span>';
                echo str_repeat('&nbsp;',3);
            }//endif
            
            echo '<input type="submit" name="btnPDF" value="PDF">';
            echo str_repeat('&nbsp;',3);
            echo '<input type="submit" name="btnCancel" value="Cancel">';
            
            echo '<br><br>';
            echo '</td></tr>';
            echo '</table>';
            
            echo '</div>';
            
            echo '<div class="printonly">!!! Use PDF version for printing !!!</div>';
            
            echo '<br><table align="center" width="100%" border="0" bordercolor="#0000FF" cellpadding="6" cellspacing="0">';
            
            switch ($_SESSION[$_GET["trid"] . "udstatus"]){
                case NULL:
                case '':
                    $udstatus = 'Draft';
                    $udstatuscolor='black';
                    break;
                case 'Q':
                    $udstatus = 'Resources Required';
                    $udstatuscolor='olive';
                    break;
                case 'R':
                    $udstatus = 'Ready for Approval';
                    $udstatuscolor='green';
                    break;
                case 'A':
                    $udstatus = 'Approved';
                    $udstatuscolor='green';
                    break;
                case 'P':
                    $udstatus = 'PUBLISHED';
                    $udstatuscolor='red';
                    break;
            }//endcase
            
            if ($_SESSION[$_GET["trid"] . "usertype"]!=='Z'){//Student doesn't need to see status
                if ($allowupdate && (($p->admin_access_allowed('MSZ') || $p->extra_access($_SESSION[$_GET["trid"] . 'userid'],"udstatus") || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('R','U','O')) && $p->update_allowed('A'))) && !in_array($_SESSION[$_GET["trid"] . "admin"], array('D','B','J')))){
                    echo '<tr><td width="15%"><a href="javascript:unitdescriptionupdate(\'\',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'status\',1,0,2)">Status:</a></td>';
                }//endif
                else {
                    echo '<tr><td width="15%"><b>Status: </b></td>';
                }//endelse
                echo '<td style="color: '.$udstatuscolor.'">'.$udstatus.'</td></tr>';
            }//endif
            
            //Unit description protection and noabrule for later use
            $sql = "select udprotected, noabrule, deliverymode
              from unitlocation
              where locationid = '$locationid'
              and termid = '$termid'
              and unitid = '$unitid'";
            
            $ulsql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-004: ".mysqli_error($db));
            
            $udprotected = 'No';
            if (mysqli_num_rows($ulsql_ok) > 0){
                $ulrow = mysqli_fetch_array($ulsql_ok) or die(basename(__FILE__,'.php')."-005: ".mysqli_error($db));
                
                if ($ulrow["udprotected"]){
                    $udprotected = 'Yes';
                }//endif
                $noabrule = $ulrow["noabrule"];
                $deliverymode = $ulrow["deliverymode"];
            }//endif
            
            if ($_SESSION[$_GET["trid"] . "usertype"]!=='Z'){//Student doesn't need to see status
                if ($allowupdate && (($p->admin_access_allowed('SZ') || in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O'))) && !in_array($_SESSION[$_GET["trid"] . "admin"], array('D','B','J')))){
                    echo '<tr><td width="15%"><a title="Only select Yes if you want this course description to be different from other locations." href="javascript:unitdescriptionupdate(\'\',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'prot\',1,0,7)">Protected:</a></td>';
                }//endif
                else {
                    echo '<tr><td width="15%"><b>Protected: </b></td>';
                }//endelse
                echo '<td>'.$udprotected.'</td></tr>';
            }//endif
            
            echo '<tr><td width="15%"><b>Title: </b></td><td>'.$title.'</td></tr>';
            
            echo '<tr><td><b>Code: </b></td><td>'.$unitid.'</td></tr>';
            
            if ($formername){
                echo '<tr><td><b>Formerly: </b></td><td>'.$formername.'</td></tr>';
            }//endif
            
            echo '<tr><td><b>School: </b></td><td>'.$acaddiv.'</td></tr>';
        }//endif
        else {
            $pdf->SetX(15);
            $pdf->SetFont('','B',10);
            $pdf->Cell('',10,'School',0,0,'L',0);
            $pdf->SetFont('','',10);
            $pdf->SetX(69);
            $pdf->Cell('',10,$acaddiv,0,1,'L',0);
            $pdf->SetFont('','B',10);
            $pdf->Cell('',10,'Course Title',0,0,'L',0);
            $pdf->SetFont('','',10);
            $pdf->SetX(69);
            $pdf->Cell('',10,$title,0,1,'L',0);
            if ($formername){
                $pdf->SetFont('','B',10);
                $pdf->Cell('',10,'Formerly',0,0,'L',0);
                $pdf->SetFont('','',10);
                $pdf->SetX(69);
                $pdf->Cell('',10,$formername,0,1,'L',0);
            }//endif
            $pdf->SetFont('','B',10);
            $pdf->Cell('',10,'Course ID',0,0,'L',0);
            $pdf->SetX(69);
            $pdf->SetFont('','',10);
            $pdf->Cell('',10,$unitid,0,1,'L',0);
        }//endelse
        //Credit points
        $credit = $unitcreditpoint;//Retrieved from getUnit
        if (isset($_POST["btnPDF"])){
            $pdf->SetFont('','B',10);
            $pdf->Cell('',10,'Credit Points',0,0,'L',0);
            $pdf->SetFont('','',10);
            $pdf->SetX(69);
            $pdf->Cell('',10,$credit,0,1,'L',0);
        }//endif
        else {
            echo '<tr><td><b>Credit Points: </b></td><td>'. $credit .'</td></tr>';
        }//endelse
        
        //Teaching period
        if (isset($_POST["btnPDF"])){
            $pdf->SetFont('','B',10);
            $pdf->Cell('',10,'Teaching Period',0,0,'L',0);
            $pdf->SetFont('','',10);
            $pdf->SetX(69);
            $pdf->Cell('',10,$termid,0,1,'L',0);
        }//endif
        else {
            echo '<tr><td><b>Teaching Period: </b></td><td>'. $termid .'</td></tr>';
        }//endelse
        
        //Author
        $sql = "select *
            from unitdescriptiondetail
            where locationid = '$locationid'
            and termid = '$termid'
            and unitid = '$unitid'
            and udtype='auth'";
        
        $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-006: ".mysqli_error($db));
        
        if (mysqli_num_rows($sql_ok)){
            $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-007: ".mysqli_error($db));
            
            $unitdescdetailkey = $row["unitdescdetailkey"];
            $author = stripslashes($row["content"]);
            if (isset($_POST["btnPDF"])){
                $pdf->SetFont('','B',10);
                $pdf->Cell('',10,'Author',0,0,'L',0);
                $pdf->SetFont('','',10);
                $pdf->SetX(69);
                $pdf->Cell('',10,$author,0,1,'L',0);
            }//endif
            else {
                if ($allowupdate && (($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O')) && $p->update_allowed(''))) && !in_array($_SESSION[$_GET["trid"] . "admin"], array('D','B','J')))){
                    echo '<tr><td><b>Author: </b></td><td><a href="javascript:unitdescriptionupdate(' . $unitdescdetailkey . ',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'auth\',1,0,1)">'. $author .'</a></td></tr>';
                }//endif
                else {
                    echo '<tr><td><b>Author: </b></td><td>'. $author .'</td></tr>';
                }//endelse
            }//endelse
        }//endif
        else {
            if (isset($_POST["btnPDF"])){
                $pdf->SetFont('','B',10);
                $pdf->Cell('',10,'Author',0,0,'L',0);
                $pdf->SetFont('','',10);
                $pdf->SetX(69);
                $pdf->Cell('',10,$author,0,1,'L',0);
            }//endif
            else {
                if ($allowupdate && (($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O')) && $p->update_allowed(''))) && !in_array($_SESSION[$_GET["trid"] . "admin"], array('D','B','J')))){
                    echo '<tr><td><a href="javascript:unitdescriptionupdate(\'\',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'auth\',1,0,1)">Author</a></td><td>&nbsp;</td></tr>';
                }//endif
            }//endelse
        }//endelse
        
        
        // establish effective term of outline for AQF and Graduate changes
        $sql = "select *
            from unitoutline as uo
            where uo.unitid = '$unitid'
            and concat(uo.effectivetermid,uo.stamptime) =
                (select max(concat(uo1.effectivetermid,uo1.stamptime))
                 from unitoutline as uo1
                 where uo1.unitid = uo.unitid
                 and uo1.effectivetermid <= '$termid'
                 and uo1.`status` in ('P','X'))";
        
        $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-008: ".mysqli_error($db));
        
        if (mysqli_num_rows($sql_ok)){
            $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-009: ".mysqli_error($db));
            
            $effectivetermid = $row["effectivetermid"];
            $unitoutlinekey = $row["unitoutlinekey"];
            $stamptime = $row["stamptime"];
        }//endif
        else {
            echo '<tr><td colspan="2" style="color:red; font-size:18; text-align:center;">Unable to locate a \'Published\' outline for this course</td></tr>';
            return;
        }//endelse
        
        // Program Level
        if ($effectivetermid > '2013/00'){
            $unitoutdetailkey = '\'\'';
            $sql = "select *
              from unitoutlinedetail
              where unitoutlinekey = '$unitoutlinekey'
              and uotype='prglvl'";
            
            $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-010: ".mysqli_error($db));
            
            if (mysqli_num_rows($sql_ok) > 0){
                $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-011: ".mysqli_error($db));
                
                $unitoutdetailkey = $row["unitoutdetailkey"];
                $content = stripslashes($row["content"]);
                $content_1 = stripslashes($row["content_1"]);
                $content_2 = stripslashes($row["content_2"]);
                
            }//endif
            
            
            //Requisites
            
            //Prerequisite
            $requisitetemp = trim(getPrerequisite($unitid, $roundbracket=true, $csreq=false, $ignoreubsas=false, $reqeffectivetermid=$termid));
            $requisitedisplay = trim(getPrerequisite($unitid, $roundbracket=true, $csreq=false, $ignoreubsas=true, $reqeffectivetermid=$termid));
            
            if (empty($requisitetemp)){
                $requisitetemp = 'Nil';
                $requisitedisplay = 'Nil';
            }//endif
            
            //Compare to outline. If different add warning.
            $warning = '';
            $outlinerequisite='';
            
            $sql = "select *
            from unitoutlinedetail
            where unitoutlinekey = '$unitoutlinekey'
            and uotype='prereq'";
            
            $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-012: ".mysqli_error($db));
            
            if (mysqli_num_rows($sql_ok) > 0){
                $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-013: ".mysqli_error($db));
                
                $outlinerequisite = $row["content"];
            }//endif
            
            $requisitecompare = str_replace('<span style="color: red;">OR</span>','OR',$requisitetemp);
            
            if (trim($outlinerequisite) !== $requisitecompare){
                $warning = '<span class="boldred">&nbsp;!!! WARNING: Outline details differ - </span><span class="red">' . trim($outlinerequisite) . '</span>';
            }//endif
            
            if (empty($warning)){
                $requisitetemp = $requisitedisplay;
            }//endif
            
            if (isset($_POST["btnPDF"])){
                $temp = $requisitetemp . $warning;
                $pdf->SetFont('','B',10);
                $pdf->Cell('',10,'Pre-requisites',0,0,'L',0);
                $pdf->SetFont('','',10);
                $pdf->SetX(69);
                $pdf->MultiCell('',10,$temp,0,'L',0);
            }//endif
            else {
                if ($p->admin_access_allowed('SZ')){
                    echo '<tr><td><a href="javascript:unitupdate(\'' . $unitid . '\',\''. $subdisciplineid .'\')">Pre-requisites:</a></td><td>'.$requisitetemp. $warning . '</td></tr>';
                }//endif
                else {
                    echo '<tr><td><b>Pre-requisites:</b></td><td>' . $requisitetemp . $warning . '</td></tr>';
                }//endelse
            }//endelse
            
            //Corequisite
            $requisitetemp = trim(getCorequisite($unitid, $roundbracket=true, $csreq=false, $ignoreubsas=false, $reqeffectivetermid=$termid));
            $requisitedisplay = trim(getCorequisite($unitid, $roundbracket=true, $csreq=false, $ignoreubsas=true, $reqeffectivetermid=$termid));
            
            if (empty($requisitetemp)){
                $requisitetemp = 'Nil';
                $requisitedisplay = 'Nil';
            }//endif
            
            //Compare to outline. If different add warning.
            $warning = '';
            $outlinerequisite='';
            $sql = "select *
            from unitoutlinedetail
            where unitoutlinekey = '$unitoutlinekey'
            and uotype='coreq'";
            
            $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-014: ".mysqli_error($db));
            
            if (mysqli_num_rows($sql_ok) > 0){
                $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-015: ".mysqli_error($db));
                
                $outlinerequisite = $row["content"];
            }//endif
            
            $requisitecompare = str_replace('<span style="color: red;">OR</span>','OR',$requisitetemp);
            
            if (trim($outlinerequisite) !== $requisitecompare){
                $warning = '<span class="boldred">&nbsp;!!! WARNING: Outline details differ - </span><span class="red">' . trim($outlinerequisite) . '</span>';
            }//endif
            
            if (empty($warning)){
                $requisitetemp = $requisitedisplay;
            }//endif
            
            if (isset($_POST["btnPDF"])){
                $temp = $requisitetemp . $warning;
                $pdf->SetFont('','B',10);
                $pdf->Cell('',10,'Co-requisites',0,0,'L',0);
                $pdf->SetFont('','',10);
                $pdf->SetX(69);
                $pdf->MultiCell('',10,$temp,0,'L',0);
            }//endif
            else {
                if ($p->admin_access_allowed('SZ')){
                    echo '<tr><td><a href="javascript:unitupdate(\'' . $unitid . '\',\''. $subdisciplineid .'\')">Co-requisites:</a></td><td>'.$requisitetemp. $warning . '</td></tr>';
                }//endif
                else {
                    echo '<tr><td><b>Co-requisites:</b></td><td>' . $requisitetemp . $warning . '</td></tr>';
                }//endelse
            }//endelse
            
            //Exclusions
            $requisitetemp = trim(getExclusion($unitid, $roundbracket=true, $csreq=false, $ignoreubsas=false, $reqeffectivetermid=$termid));
            $requisitedisplay = trim(getExclusion($unitid, $roundbracket=true, $csreq=false, $ignoreubsas=true, $reqeffectivetermid=$termid));
            
            if (empty($requisitetemp)){
                $requisitetemp = 'Nil';
                $requisitedisplay = 'Nil';
            }//endif
            
            //Compare to outline. If different add warning.
            $warning = '';
            $outlinerequisite='';
            $sql = "select *
            from unitoutlinedetail
            where unitoutlinekey = '$unitoutlinekey'
            and uotype='exclus'";
            
            $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-016: ".mysqli_error($db));
            
            if (mysqli_num_rows($sql_ok) > 0){
                $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-017: ".mysqli_error($db));
                
                $outlinerequisite = $row["content"];
            }//endif
            
            if (trim($outlinerequisite) !== $requisitetemp){
                $warning = '<span class="boldred">&nbsp;!!! WARNING: Outline details differ - </span><span class="red">' . trim($outlinerequisite) . '</span>';
            }//endif
            
            if (empty($warning)){
                $requisitetemp = $requisitedisplay;
            }//endif
            
            if (isset($_POST["btnPDF"])){
                $temp = $requisitetemp . $warning;
                $pdf->SetFont('','B',10);
                $pdf->Cell('',10,'Exclusions',0,0,'L',0);
                $pdf->SetFont('','',10);
                $pdf->SetX(69);
                $pdf->MultiCell('',10,$temp,0,'L',0);
            }//endif
            else {
                if ($p->admin_access_allowed('SZ')){
                    echo '<tr><td><a href="javascript:unitupdate(\'' . $unitid . '\',\''. $subdisciplineid .'\')">Exclusions:</a></td><td>'.$requisitetemp. $warning . '</td></tr>';
                }//endif
                else {
                    echo '<tr><td><b>Exclusions:</b></td><td>' . $requisitetemp . $warning . '</td></tr>';
                }//endelse
            }//endelse
            
            
            
            //ASCED
            $asced = $unitasced;//Retrived for getUnit
            if (isset($_POST["btnPDF"])){
                $pdf->SetFont('','B',10);
                $pdf->Cell('',10,'ASCED Code',0,0,'L',0);
                $pdf->SetFont('','',10);
                $pdf->SetX(69);
                $pdf->Cell('',10,$asced,0,1,'L',0);
            }//endif
            else {
                echo '<tr><td><b>ASCED Code: </b></td><td>'. $asced .'</td></tr>';
            }//endelse
            
            //Handbook summary
            if (isset($_POST["btnPDF"])){
                $pdf->SetFont('','B',10);
                
                $pdf->SetX(15);
                //$pdf->MultiCell('',5,$supplementaryassessment,0,'L',0);
                $pdf->Cell('',5,'Description of the Course for Handbook Entry:',0,1,'L',0);
            }//endif
            else {
                echo '<tr><td><b>Handbook Description:</b></td>';
            }//endelse
            
            $sql = "select *
            from unitoutlinedetail
            where unitoutlinekey = '$unitoutlinekey'
            and uotype='summ'";
            
            $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-018: ".mysqli_error($db));
            
            if (mysqli_num_rows($sql_ok)){
                $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-019: ".mysqli_error($db));
                $temp = stripslashes($row["content"]);
                
                if (isset($_POST["btnPDF"])){
                    $pdf->SetX(15);
                    
                    $temp = convert_for_html($temp,_tablesettingON,_striplinebreakOFF,_stripAtagOFF,_stripalllinefeedOFF);
                    
                    $html = '<div style="margin-left: 1mm; text-align: left;">'.$temp.'</div>';
                    $pdf->writeHTML($html);
                }//endif
                else {
                    echo '<td text-align: left">'. $temp .'</td></tr>';
                }//endelse
            }//endif
            
            //Grading basis
            $gradingbasis = $unitgradingbasis;//Retrieved from getUnit
            switch ($unitgradingbasis){
                case 'G':
                    $gradingbasis = 'Graded (HD, D, C, P, MF, F)';
                    break;
                case 'G':
                    $gradingbasis = 'Research Pass / Not Pass (O, P, F)';
                    break;
                case 'G':
                    $gradingbasis = 'Ungraded (S, UN)';
                    break;
            }//endswitch
            
            if (isset($_POST["btnPDF"])){
                $pdf->SetFont('','B',10);
                $pdf->Cell('',10,'Grade Scheme',0,0,'L',0);
                $pdf->SetFont('','',10);
                $pdf->SetX(69);
                $pdf->Cell('',10,$gradingbasis,0,1,'L',0);
            }//endif
            else {
                echo '<tr><td><b>Grade Scheme: </b></td><td>'. $gradingbasis .'</td></tr>';
            }//endelse
            
            //placement
            if ($termid >= '2017/27'){
                $sql = "select *
              from unitoutlinedetail
              where unitoutlinekey = '$unitoutlinekey'
              and uotype='place'";
                
                $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-020: ".mysqli_error($db));
                
                if (mysqli_num_rows($sql_ok)){
                    $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-021: ".mysqli_error($db));
                    
                    $placementcomponent = stripslashes($row["content"]);
                    
                    if (isset($_POST["btnPDF"])){
                        $pdf->SetFont('','B',10);
                        $pdf->Cell('',10,'Placement Component',0,0,'L',0);
                        $pdf->SetFont('','',10);
                        $pdf->SetX(69);
                        $pdf->Cell('',10,$placementcomponent,0,1,'L',0);
                    }//endif
                    else {
                        echo '<tr><td><b>Placement Component: </b></td><td>'. $placementcomponent .'</td></tr>';
                    }//endelse
                    
                }//endif
                else {
                    if (isset($_POST["btnPDF"])){
                        $pdf->SetFont('','B',10);
                        $pdf->Cell('',10,'Placement Component',0,0,'L',0);
                        $pdf->SetFont('','',10);
                        $pdf->SetX(69);
                        $pdf->Cell('',10,'No',0,1,'L',0);
                    }//endif
                    else {
                        echo '<tr><td><b>Placement Component: </b></td><td>No</td></tr>';
                    }//endelse
                }//endelse
            }//endif
            
            //supplementary assessment
            if ($termid >= '2019/03'){
                $sql = "select *
              from unitoutlinedetail
              where unitoutlinekey = '$unitoutlinekey'
              and uotype='supass'";
                
                $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-022: ".mysqli_error($db));
                
                if (mysqli_num_rows($sql_ok)){
                    $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-023: ".mysqli_error($db));
                    
                    $supplementaryassessment = stripslashes($row["content"]);
                    
                    if (isset($_POST["btnPDF"])){
                        $pdf->SetFont('','B',10);
                        $pdf->Cell('',10,'Supplementary Assessment',0,0,'L',0);
                        $pdf->SetFont('','',10);
                        $pdf->SetX(69);
                        $pdf->Cell('',10,$supplementaryassessment,0,0,'L',0);
                        $pdf->Ln();
                        if ($supplementaryassessment == 'Yes'){
                            $supplementaryassessment = "Where supplementary assessment is available a student must have failed overall in the course but gained a final mark of 45 per cent or above and submitted all major assessment tasks.";
                        }//endif
                        else {
                            $supplementaryassessment = "Supplementary assessment is not available to students who gain a fail grade in this course.";
                        }//endelse
                        $pdf->SetX(15);
                        $pdf->MultiCell('',5,$supplementaryassessment,0,'L',0);
                    }//endif
                    else {
                        echo '<tr><td><b>Supplementary Assessment: </b></td><td>'. $supplementaryassessment .'</td></tr>';
                    }//endelse
                    
                }//endif
                else {
                    if (isset($_POST["btnPDF"])){
                        $pdf->SetFont('','B',10);
                        $pdf->Cell('',10,'Supplementary Assessment',0,0,'L',0);
                        $pdf->SetFont('','',10);
                        $pdf->SetX(69);
                        $pdf->Cell('',10,'Yes',0,0,'L',0);
                        $pdf->Ln();
                        $supplementaryassessment = 'Where supplementary assessment is available a student must have failed overall in the course but gained a final mark of 45 per cent or above and submitted all major assessment tasks.';
                        $pdf->SetX(15);
                        $pdf->MultiCell('',5,$supplementaryassessment,0,'L',0);
                    }//endif
                    else {
                        echo '<tr><td><b>Supplementary Assessment: </b></td><td>Yes.&nbsp&nbspWhere supplementary assessment is available a student must have failed overall in the course but gained a final mark of 45 per cent or above and submitted all major assessment tasks.</td></tr>';
                    }//endelse
                    
                }//endelse
                
            }//endif
            
            
            
            //PROGRAM LEVEL ============================================================================================
            $sql = "select *
            from unitoutline as uo
            where uo.unitid = '$unitid'
            and concat(uo.effectivetermid,uo.stamptime) =
                (select max(concat(uo1.effectivetermid,uo1.stamptime))
                 from unitoutline as uo1
                 where uo1.unitid = uo.unitid
                 and uo1.effectivetermid <= '$termid'
                 and uo1.`status` in ('P','X'))";
            
            $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-008: ".mysqli_error($db));
            
            if (mysqli_num_rows($sql_ok)){
                $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-009: ".mysqli_error($db));
                
                $effectivetermid = $row["effectivetermid"];
                $unitoutlinekey = $row["unitoutlinekey"];
            }//endif
            else {
                echo '<tr><td colspan="2" style="color:red; font-size:18; text-align:center;">Unable to locate a \'Published\' outline for this course</td></tr>';
                return;
            }//endelse
            
            //Program level
            if (!isset($_POST["btnPDF"])){
                echo '<tr><td><b>Program Level:</b></td></tr>';
            }//endelse
            
            if (mysqli_num_rows($sql_ok) > 0){
                if (isset($_POST["btnPDF"])){
                    $pdf->SetFont('','B',10);
                    $pdf->Cell('',10,'Program Level',0,1,'L',0);
                }//endif
            }//endif
            
            if (mysqli_num_rows($sql_ok) > 0){
                
                list($intro1,$intro2,$intro3,$intro4,$intro5,$intro6) = explode('|', $content);
                list($inter1,$inter2,$inter3,$inter4,$inter5,$inter6) = explode('|', $content_1);
                list($advan1,$advan2,$advan3,$advan4,$advan5,$advan6) = explode('|', $content_2);
                
                if (isset($_POST["btnPDF"])){
                    
                    if (empty($intro1)){
                        $intro1 = '<img src="image/nopicture.jpg" height="10px"/>';
                    }//endif
                    else {
                        $intro1 = '<img src="image/tick.jpg" height="10px"/>';
                    }//endelse
                    if (empty($intro2)){
                        $intro2 = '<img src="image/nopicture.jpg" height="10px"/>';
                    }//endif
                    else {
                        $intro2 = '<img src="image/tick.jpg" height="10px"/>';
                    }//endelse
                    if (empty($intro3)){
                        $intro3 = '<img src="image/nopicture.jpg" height="10px"/>';
                    }//endif
                    else {
                        $intro3 = '<img src="image/tick.jpg" height="10px"/>';
                    }//endelse
                    if (empty($intro4)){
                        $intro4 = '<img src="image/nopicture.jpg" height="10px"/>';
                    }//endif
                    else {
                        $intro4 = '<img src="image/tick.jpg" height="10px"/>';
                    }//endelse
                    if (empty($intro5)){
                        $intro5 = '<img src="image/nopicture.jpg" height="10px"/>';
                    }//endif
                    else {
                        $intro5 = '<img src="image/tick.jpg" height="10px"/>';
                    }//endelse
                    if (empty($intro6)){
                        $intro6 = '<img src="image/nopicture.jpg" height="10px"/>';
                    }//endif
                    else {
                        $intro6 = '<img src="image/tick.jpg" height="10px"/>';
                    }//endelse
                    
                    if (empty($inter1)){
                        $inter1 = '<img src="image/nopicture.jpg" height="10px"/>';
                    }//endif
                    else {
                        $inter1 = '<img src="image/tick.jpg" height="10px"/>';
                    }//endelse
                    if (empty($inter2)){
                        $inter2= '<img src="image/nopicture.jpg" height="10px"/>';
                    }//endif
                    else {
                        $inter2 = '<img src="image/tick.jpg" height="10px"/>';
                    }//endelse
                    if (empty($inter3)){
                        $inter3= '<img src="image/nopicture.jpg" height="10px"/>';
                    }//endif
                    else {
                        $inter3 = '<img src="image/tick.jpg" height="10px"/>';
                    }//endelse
                    if (empty($inter4)){
                        $inter4= '<img src="image/nopicture.jpg" height="10px"/>';
                    }//endif
                    else {
                        $inter4 = '<img src="image/tick.jpg" height="10px"/>';
                    }//endelse
                    if (empty($inter5)){
                        $inter5= '<img src="image/nopicture.jpg" height="10px"/>';
                    }//endif
                    else {
                        $inter5 = '<img src="image/tick.jpg" height="10px"/>';
                    }//endelse
                    if (empty($inter6)){
                        $inter6= '<img src="image/nopicture.jpg" height="10px"/>';
                    }//endif
                    else {
                        $inter6 = '<img src="image/tick.jpg" height="10px"/>';
                    }//endelse
                    
                    if (empty($advan1)){
                        $advan1= '<img src="image/nopicture.jpg" height="10px"/>';
                    }//endif
                    else {
                        $advan1 = '<img src="image/tick.jpg" height="10px"/>';
                    }//endelse
                    if (empty($advan2)){
                        $advan2= '<img src="image/nopicture.jpg" height="10px"/>';
                    }//endif
                    else {
                        $advan2 = '<img src="image/tick.jpg" height="10px"/>';
                    }//endelse
                    if (empty($advan3)){
                        $advan3= '<img src="image/nopicture.jpg" height="10px"/>';
                    }//endif
                    else {
                        $advan3 = '<img src="image/tick.jpg" height="10px"/>';
                    }//endelse
                    if (empty($advan4)){
                        $advan4= '<img src="image/nopicture.jpg" height="10px"/>';
                    }//endif
                    else {
                        $advan4 = '<img src="image/tick.jpg" height="10px"/>';
                    }//endelse
                    if (empty($advan5)){
                        $advan5= '<img src="image/nopicture.jpg" height="10px"/>';
                    }//endif
                    else {
                        $advan5 = '<img src="image/tick.jpg" height="10px"/>';
                    }//endelse
                    if (empty($advan6)){
                        $advan6= '<img src="image/nopicture.jpg" height="10px"/>';
                    }//endif
                    else {
                        $advan6 = '<img src="image/tick.jpg" height="10px"/>';
                    }//endelse
                    
                    $pdf->SetFont('','B',10);
                    
                    $html = '
            <table width="100%" style="margin-left: 0;" border="1" cellspacing="0" cellpadding="3">
            <thead>
            <tr>
            <td colspan="7" style="background-color:#C0C0C0; font-weight: bold; text-align: center">AQF Level of Program</td>
            </tr>
            <tr>
            <tr>
            <td width="22%" style="background-color:#C0C0C0; font-weight: bold; text-align: center">&nbsp;</td>
            <td width="13%" style="background-color:#C0C0C0; font-weight: bold; text-align: center">5</td>
            <td width="13%" style="background-color:#C0C0C0; font-weight: bold; text-align: center">6</td>
            <td width="13%" style="background-color:#C0C0C0; font-weight: bold; text-align: center">7</td>
            <td width="13%" style="background-color:#C0C0C0; font-weight: bold; text-align: center">8</td>
            <td width="13%" style="background-color:#C0C0C0; font-weight: bold; text-align: center">9</td>
            <td width="13%" style="background-color:#C0C0C0; font-weight: bold; text-align: center">10</td>
            </tr>
            <tr>
            <td colspan="7" style="background-color:#C0C0C0; font-weight: bold; text-align: left">Level</td>
            </tr>
            </thead>
            <tr>
            <td width="22%">Introductory</td>
            <td width="13%" align="center">'.$intro1.'</td>
            <td width="13%" align="center">'.$intro2.'</td>
            <td width="13%" align="center">'.$intro3.'</td>
            <td width="13%" align="center">'.$intro4.'</td>
            <td width="13%" align="center">'.$intro5.'</td>
            <td width="13%" align="center">'.$intro6.'</td>
            </tr>
            <tr>
            <td width="22%">Intermediate</td>
            <td width="13%" align="center">'.$inter1.'</td>
            <td width="13%" align="center">'.$inter2.'</td>
            <td width="13%" align="center">'.$inter3.'</td>
            <td width="13%" align="center">'.$inter4.'</td>
            <td width="13%" align="center">'.$inter5.'</td>
            <td width="13%" align="center">'.$inter6.'</td>
            </tr>
            <tr>
            <td width="22%">Advanced</td>
            <td width="13%" align="center">'.$advan1.'</td>
            <td width="13%" align="center">'.$advan2.'</td>
            <td width="13%" align="center">'.$advan3.'</td>
            <td width="13%" align="center">'.$advan4.'</td>
            <td width="13%" align="center">'.$advan5.'</td>
            <td width="13%" align="center">'.$advan6.'</td>
            </tr>
            </table>';
                    
                    $pdf->SetX(15);
                    $pdf->writeHTML($html);
                    $pdf->Ln();
                    
                }//endif
                else {
                    
                    echo '<tr><td style="padding-left: 1.2cm" colspan="2">';
                    
                    echo '<table border="1" cellspacing="0" cellpadding="4">';
                    
                    echo '<tr>';
                    echo '<td bgcolor="#C0C0C0" >&nbsp;</td>';
                    echo '<td colspan="6" bgcolor="#C0C0C0" align="center"><b>AQF Level of Program</b></td>';
                    echo '</tr>';
                    
                    echo '<tr>';
                    echo '<td bgcolor="#C0C0C0">&nbsp;</td>';
                    echo '<td style="width:30px" align="center" bgcolor="#C0C0C0"><b><span title="Diploma">5</span></b></td>';
                    echo '<td width="15%" align="center" bgcolor="#C0C0C0"><b><span title="Advanced Diploma / Associate Degree">6</span></b></td>';
                    echo '<td width="15%" align="center" bgcolor="#C0C0C0"><b><span title="Bachelor Degree">7</span></b></td>';
                    echo '<td width="15%" align="center" bgcolor="#C0C0C0"><b><span title="Bachelor Honours Degree / Graduate Certificate / Graduate Diploma">8</span></b></td>';
                    echo '<td width="15%" align="center" bgcolor="#C0C0C0"><b><span title="Masters Degree (Research) / Masters Degree (Coursework / Masters Degree (Extended)">9</span></b></td>';
                    echo '<td width="15%" align="center" bgcolor="#C0C0C0"><b><span title="Doctoral Degree">10</span></b></td>';
                    echo '</tr>';
                    
                    echo '<tr>';
                    echo '<td WIdTH="10%" bgcolor="#C0C0C0" align="center"><b>Level</b></td>';
                    echo '<td colspan="6" bgcolor="#C0C0C0">&nbsp;</td>';
                    echo '</tr>';
                    
                    if (empty($intro1)){
                        $intro1 = str_repeat('&nbsp;',8);
                    }//endif
                    else {
                        $intro1 = '<img src="image/tick.jpg" style="border: 0" width="20" height="20">';
                    }//endelse
                    if (empty($intro2)){
                        $intro2 = str_repeat('&nbsp;',8);
                    }//endif
                    else {
                        $intro2 = '<img src="image/tick.jpg" style="border: 0" width="20" height="20">';
                    }//endelse
                    if (empty($intro3)){
                        $intro3 = str_repeat('&nbsp;',8);
                    }//endif
                    else {
                        $intro3 = '<img src="image/tick.jpg" style="border: 0" width="20" height="20">';
                    }//endelse
                    if (empty($intro4)){
                        $intro4 = str_repeat('&nbsp;',8);
                    }//endif
                    else {
                        $intro4 = '<img src="image/tick.jpg" style="border: 0" width="20" height="20">';
                    }//endelse
                    if (empty($intro5)){
                        $intro5 = str_repeat('&nbsp;',8);
                    }//endif
                    else {
                        $intro5 = '<img src="image/tick.jpg" style="border: 0" width="20" height="20">';
                    }//endelse
                    if (empty($intro6)){
                        $intro6 = str_repeat('&nbsp;',8);
                    }//endif
                    else {
                        $intro6 = '<img src="image/tick.jpg" style="border: 0" width="20" height="20">';
                    }//endelse
                    
                    echo '<tr>';
                    echo '<td align="center">Introductory</td>';
                    echo '<td align="center">'. $intro1 .'</td>';
                    echo '<td align="center">'. $intro2 .'</td>';
                    echo '<td align="center">'. $intro3 .'</td>';
                    echo '<td align="center">'. $intro4 .'</td>';
                    echo '<td align="center">'. $intro5 .'</td>';
                    echo '<td align="center">'. $intro6 .'</td>';
                    echo '</tr>';
                    
                    if (empty($inter1)){
                        $inter1 = str_repeat('&nbsp;',8);
                    }//endif
                    else {
                        $inter1 = '<img src="image/tick.jpg" style="border: 0" width="20" height="20">';
                    }//endelse
                    if (empty($inter2)){
                        $inter2 = str_repeat('&nbsp;',8);
                    }//endif
                    else {
                        $inter2 = '<img src="image/tick.jpg" style="border: 0" width="20" height="20">';
                    }//endelse
                    if (empty($inter3)){
                        $inter3 = str_repeat('&nbsp;',8);
                    }//endif
                    else {
                        $inter3 = '<img src="image/tick.jpg" style="border: 0" width="20" height="20">';
                    }//endelse
                    if (empty($inter4)){
                        $inter4 = str_repeat('&nbsp;',8);
                    }//endif
                    else {
                        $inter4 = '<img src="image/tick.jpg" style="border: 0" width="20" height="20">';
                    }//endelse
                    if (empty($inter5)){
                        $inter5 = str_repeat('&nbsp;',8);
                    }//endif
                    else {
                        $inter5 = '<img src="image/tick.jpg" style="border: 0" width="20" height="20">';
                    }//endelse
                    if (empty($inter6)){
                        $inter6 = str_repeat('&nbsp;',8);
                    }//endif
                    else {
                        $inter6 = '<img src="image/tick.jpg" style="border: 0" width="20" height="20">';
                    }//endelse
                    
                    echo '<tr>';
                    echo '<td align="center">Intermediate</td>';
                    echo '<td align="center">'. $inter1 .'</td>';
                    echo '<td align="center">'. $inter2 .'</td>';
                    echo '<td align="center">'. $inter3 .'</td>';
                    echo '<td align="center">'. $inter4 .'</td>';
                    echo '<td align="center">'. $inter5 .'</td>';
                    echo '<td align="center">'. $inter6 .'</td>';
                    echo '</tr>';
                    
                    if (empty($advan1)){
                        $advan1 = str_repeat('&nbsp;',8);
                    }//endif
                    else {
                        $advan1 = '<img src="image/tick.jpg" style="border: 0" width="20" height="20">';
                    }//endelse
                    if (empty($advan2)){
                        $advan2 = str_repeat('&nbsp;',8);
                    }//endif
                    else {
                        $advan2 = '<img src="image/tick.jpg" style="border: 0" width="20" height="20">';
                    }//endelse
                    if (empty($advan3)){
                        $advan3 = str_repeat('&nbsp;',8);
                    }//endif
                    else {
                        $advan3 = '<img src="image/tick.jpg" style="border: 0" width="20" height="20">';
                    }//endelse
                    if (empty($advan4)){
                        $advan4 = str_repeat('&nbsp;',8);
                    }//endif
                    else {
                        $advan4 = '<img src="image/tick.jpg" style="border: 0" width="20" height="20">';
                    }//endelse
                    if (empty($advan5)){
                        $advan5 = str_repeat('&nbsp;',8);
                    }//endif
                    else {
                        $advan5 = '<img src="image/tick.jpg" style="border: 0" width="20" height="20">';
                    }//endelse
                    if (empty($advan6)){
                        $advan6 = str_repeat('&nbsp;',8);
                    }//endif
                    else {
                        $advan6 = '<img src="image/tick.jpg" style="border: 0" width="20" height="20">';
                    }//endelse
                    
                    echo '<tr>';
                    echo '<td align="center">Advanced</td>';
                    echo '<td align="center">'. $advan1 .'</td>';
                    echo '<td align="center">'. $advan2 .'</td>';
                    echo '<td align="center">'. $advan3 .'</td>';
                    echo '<td align="center">'. $advan4 .'</td>';
                    echo '<td align="center">'. $advan5 .'</td>';
                    echo '<td align="center">'. $advan6 .'</td>';
                    echo '</tr>';
                    
                    echo '</table></td></tr>';
                }//endelse
            }//endif
            
        }//endif
        else {
            if ($unitlevel){//this if line has recently been introduced as part of AQF changes. No longer set an unit level but at unit outline time.
                switch ($unitlevel){
                    case 'A':
                        $level = 'Advanced';
                        break;
                    case 'I':
                        $level = 'Introductory';
                        break;
                }//endcase
                
                if (isset($_POST["btnPDF"])){
                    $pdf->SetFont('','B',10);
                    $pdf->Cell('',10,'Level:',0,0,'L',0);
                    $pdf->SetFont('','',10);
                    $pdf->SetX(69);
                    $pdf->Cell('',10,$level,0,1,'L',0);
                }//endif
                else {
                    echo '<tr><td><b>Level: </b></td><td>'. $level .'</td></tr>';
                }//endelse
            }//endif
        }//endelse
        
        
        if (!isset($_POST["btnPDF"])){
            echo '</table>';
        }//endif
        
        //ENF OF PROGRAM LEVEL ================================================================================================================================
        
        
        
        //Organisation
        if (!isset($_POST["btnPDF"])){
            echo '<table width="100%" cellpadding="6" cellspacing="0">';
        }//endif
        
        //Organisation heading
        if (isset($_POST["btnPDF"])){
            $pdf->SetFont('','B',12);
            $pdf->Ln();
            $pdf->Cell('',10,'Organisation',0,1,'L',0);
        }//endif
        
        //Organisation - General
        $sql = "select *
            from unitdescriptiondetail
            where locationid = '$locationid'
            and termid = '$termid'
            and unitid = '$unitid'
            and udtype='orggen'";
        
        $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-024: ".mysqli_error($db));
        
        if (mysqli_num_rows($sql_ok)){
            $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-025: ".mysqli_error($db));
            
            $unitdescdetailkey = $row["unitdescdetailkey"];
            if (!isset($_POST["btnPDF"])){
                if ($allowupdate && (($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O')) && $p->update_allowed(''))) && !in_array($_SESSION[$_GET["trid"] . "admin"], array('D','B','J')))){
                    echo '<tr><td><a href="javascript:unitdescriptionupdate('. $unitdescdetailkey . ',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'orggen\',5,0,1)">Organisation:</a></td></tr>';
                }//endif
                else {
                    echo '<tr><td><b>Organisation:</b></td></tr>';
                }//endelse
            }//endelse
            
            $temp = stripslashes($row["content"]);
            
            if (isset($_POST["btnPDF"])){
                $pdf->SetFont('','',10);
                $pdf->SetX(15);
                
                $temp = convert_for_html($temp,_tablesettingON,_striplinebreakOFF,_stripAtagOFF,_stripalllinefeedOFF);
                
                $html = '<div style="margin-left: 0; text-align: left;">'.$temp.'</div>';
                $pdf->writeHTML($html);
            }//endif
            else {
                echo '<tr><td style="padding-left: 0.6cm; text-align: left">'. $temp .'</td></tr>';
            }//endelse
        }//endif
        else {
            if (!isset($_POST["btnPDF"])){
                if ($allowupdate && (($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O')) && $p->update_allowed(''))) && !in_array($_SESSION[$_GET["trid"] . "admin"], array('D','B','J')))){
                    echo '<tr><td><a href="javascript:unitdescriptionupdate(\'\',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'orggen\',5,0,1)">Organisation:</a></td></tr>';
                }//endif
            }//endelse
        }//endelse
        
        //Delivery mode
        $sql = "select deliverymode
            from unitlocation
            where locationid = '$locationid'
            and termid = '$termid'
            and unitid = '$unitid'";
        
        $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-026: ".mysqli_error($db));
        
        if (mysqli_num_rows($sql_ok)){
            $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-027: ".mysqli_error($db));
            
            $deliverymode = $row["deliverymode"];
            
            if (!isset($_POST["btnPDF"])){
                if ($allowupdate && (($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O')) && $p->update_allowed(''))) && !in_array($_SESSION[$_GET["trid"] . "admin"], array('D','B','J')))){
                    echo '<tr><td style="padding-left: 0.6cm"><a href="javascript:unitdescriptionupdate(\'\',\'' .  $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'deliv\',1,0,10)">Delivery Mode:</a> ';
                }//endif
                else {
                    echo '<tr><td style="padding-left: 0.6cm"><b>Delivery Mode: </b>';
                }//endelse
            }//endelse
            if ($row["deliverymode"] == 'R'){
                $deliverymode = 'Regular semester';
            }//endif
            else {
                $deliverymode = 'Block';
            }//endelse
            
            if (isset($_POST["btnPDF"])){
                $pdf->SetX(15);
                $pdf->SetFont('','B',10);
                $pdf->Cell('',10,'Delivery Mode',0,1,'L',0);
                $pdf->SetFont('','',10);
                $pdf->SetX(15);
                $pdf->MultiCell('',5,$deliverymode,0,'J',0);
                
            }//endif
            else {
                echo '<tr><td style="padding-left: 1.2cm">'.  $deliverymode .'</td></tr>';
            }//endelse
        }//endif
        else {
            if (!isset($_POST["btnPDF"])){
                if ($allowupdate && (($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O')) && $p->update_allowed(''))) && !in_array($_SESSION[$_GET["trid"] . "admin"], array('D','B','J')))){
                    echo '<tr><td style="padding-left: 0.6cm"><a href="javascript:unitdescriptionupdate(\'\',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'deliv\',1,0,10)">Delivery Mode:</a> </td></tr>';
                }//endif
            }//endif
        }//endelse
        
        //Lecture / tutorials
        $sql = "select *
            from unitdescriptiondetail
            where locationid = '$locationid'
            and termid = '$termid'
            and unitid = '$unitid'
            and udtype='struct'";
        
        $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-028: ".mysqli_error($db));
        
        if (mysqli_num_rows($sql_ok)){
            $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-029: ".mysqli_error($db));
            
            $unitdescdetailkey = $row["unitdescdetailkey"];
            
            if (!isset($_POST["btnPDF"])){
                if ($allowupdate && (($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O')) && $p->update_allowed(''))) && !in_array($_SESSION[$_GET["trid"] . "admin"], array('D','B','J')))){
                    echo '<tr><td style="padding-left: 0.6cm"><a href="javascript:unitdescriptionupdate('. $unitdescdetailkey . ',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'struct\',8,0,1)">Structure (Lectures / Laboratories / Tutorials / Workshops / Field Trips / Excursions / Placements):</a> ';
                }//endif
                else {
                    echo '<tr><td style="padding-left: 0.6cm"><b>Structure (Lectures / Laboratories / Tutorials / Workshops / Field Trips / Excursions / Placements): </b>';
                }//endelse
            }//endelse
            $temp = stripslashes($row["content"]);
            
            if (isset($_POST["btnPDF"])){
                $pdf->SetX(15);
                $pdf->SetFont('','B',10);
                $pdf->Cell('',10,'Structure',0,1,'L',0);
                $pdf->SetFont('','',10);
                $pdf->SetX(15);
                
                $temp = convert_for_html($temp,_tablesettingON,_striplinebreakOFF,_stripAtagOFF,_stripalllinefeedOFF);
                
                $html = '<div style="margin-left: 0mm; text-align: left;">'.$temp.'</div>';
                $pdf->writeHTML($html);
                
            }//endif
            else {
                echo '<tr><td style="padding-left: 1.2cm">'.  $temp .'</td></tr>';
            }//endelse
        }//endif
        else {
            if (!isset($_POST["btnPDF"])){
                if ($allowupdate && (($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O')) && $p->update_allowed(''))) && !in_array($_SESSION[$_GET["trid"] . "admin"], array('D','B','J')))){
                    echo '<tr><td style="padding-left: 0.6cm"><a href="javascript:unitdescriptionupdate(\'\',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'struct\',8,0,1)">Structure (Lectures / Laboratories / Tutorials / Workshops / Field Trips / Excursions / Placements):</a> </td></tr>';
                }//endif
            }//endif
        }//endelse
        
        //Hardware / software
        $sql = "select *
            from unitdescriptiondetail
            where locationid = '$locationid'
            and termid = '$termid'
            and unitid = '$unitid'
            and udtype = 'hard'";
        
        $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-030: ".mysqli_error($db));
        
        if (mysqli_num_rows($sql_ok)){
            $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-031: ".mysqli_error($db));
            
            $unitdescdetailkey = $row["unitdescdetailkey"];
            if (!isset($_POST["btnPDF"])){
                if ($allowupdate && (($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O')) && $p->update_allowed(''))) && !in_array($_SESSION[$_GET["trid"] . "admin"], array('D','B','J')))){
                    echo '<tr><td style="padding-left: 0.6cm"><a href="javascript:unitdescriptionupdate('. $unitdescdetailkey . ',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'hard\',5,0,1)">Computer Hardware / Software:</a>&nbsp;<span style="color:red;">(to conduct the course)</span>';
                }//endif
                else {
                    echo '<tr><td style="padding-left: 0.6cm"><b>Computer Hardware / Software:</b>&nbsp;<span style="color:red;">(to conduct the course)</span>';
                }//endelse
            }//endelse
            $temp = stripslashes($row["content"]);
            
            if (!isset($_POST["btnPDF"])){
                echo '<tr><td style="padding-left: 1.2cm"><span class="gray">' . $temp .'</span></td></tr>';
            }//endelse
        }//endif
        else {
            if (!isset($_POST["btnPDF"])){
                if ($allowupdate && (($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O')) && $p->update_allowed(''))) && !in_array($_SESSION[$_GET["trid"] . "admin"], array('D','B','J')))){
                    echo '<tr><td style="padding-left: 0.6cm"><a href="javascript:unitdescriptionupdate(\'\',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'hard\',5,0,1)">Computer Hardware / Software:</a>&nbsp;<span style="color:red;">(to conduct the course)</span></td></tr>';
                }//endif
            }//endelse
        }//endelse
        
        //Technical Equipment
        $sql = "select *
            from unitdescriptiondetail
            where locationid = '$locationid'
            and termid = '$termid'
            and unitid = '$unitid'
            and udtype = 'equip'";
        
        $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-032: ".mysqli_error($db));
        
        if (mysqli_num_rows($sql_ok)){
            $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-033: ".mysqli_error($db));
            
            $unitdescdetailkey = $row["unitdescdetailkey"];
            if (!isset($_POST["btnPDF"])){
                if ($allowupdate && (($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O')) && $p->update_allowed(''))) && !in_array($_SESSION[$_GET["trid"] . "admin"], array('D','B','J')))){
                    echo '<tr><td style="padding-left: 0.6cm"><a href="javascript:unitdescriptionupdate('. $unitdescdetailkey . ',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'equip\',5,0,1)">Technical Equipment:</a>&nbsp;<span style="color:red;">(to conduct the course)</span>';
                }//endif
                else {
                    echo '<tr><td style="padding-left: 0.6cm"><b>Technical Equipment:</b>&nbsp;(to conduct the course)';
                }//endelse
            }//endelse
            $temp = stripslashes($row["content"]);
            
            if (!isset($_POST["btnPDF"])){
                echo '<tr><td style="padding-left: 1.2cm"><span class="gray">' . $temp .'</span></td></tr>';
            }//endelse
        }//endif
        else {
            if (!isset($_POST["btnPDF"])){
                if ($allowupdate && (($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O')) && $p->update_allowed(''))) && !in_array($_SESSION[$_GET["trid"] . "admin"], array('D','B','J')))){
                    echo '<tr><td style="padding-left: 0.6cm"><a href="javascript:unitdescriptionupdate(\'\',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'equip\',5,0,1)">Technical Equipment:</a>&nbsp;<span style="color:red;">(to conduct the course)</span></td></tr>';
                }//endif
            }//endelse
        }//endelse
        
        //Lecturer experience
        $sql = "select *
            from unitdescriptiondetail
            where locationid = '$locationid'
            and termid = '$termid'
            and unitid = '$unitid'
            and udtype = 'exp'";
        
        $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-034: ".mysqli_error($db));
        
        if (mysqli_num_rows($sql_ok)){
            $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-035: ".mysqli_error($db));
            
            $unitdescdetailkey = $row["unitdescdetailkey"];
            if (!isset($_POST["btnPDF"])){
                if ($allowupdate && (($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O')) && $p->update_allowed(''))) && !in_array($_SESSION[$_GET["trid"] . "admin"], array('D','B','J')))){
                    echo '<tr><td style="padding-left: 0.6cm"><a href="javascript:unitdescriptionupdate('. $unitdescdetailkey . ',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'exp\',4,0,1)">Lecturer Experience / Knowledge:</a>&nbsp;<span style="color:red;">(to conduct the course)</span>';
                }//endif
                else {
                    echo '<tr><td style="padding-left: 0.6cm"><b>Lecturer Experience / Knowledge:</b>&nbsp;<span style="color:red;">(to conduct the course)';
                }//endelse
            }//endelse
            $temp = stripslashes($row["content"]);
            
            if (!isset($_POST["btnPDF"])){
                echo '<tr><td style="padding-left: 1.2cm"><span class="gray">' . $temp .'</span></td></tr>';
            }//endelse
        }//endif
        else {
            if (!isset($_POST["btnPDF"])){
                if ($allowupdate && (($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O')) && $p->update_allowed(''))) && !in_array($_SESSION[$_GET["trid"] . "admin"], array('D','B','J')))){
                    echo '<tr><td style="padding-left: 0.6cm"><a href="javascript:unitdescriptionupdate(\'\',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'exp\',4,0,1)">Lecturer Experience / Knowledge:</a>&nbsp;<span style="color:red;">(to conduct the course)</td></tr>';
                }//endif
            }//endelse
        }//endelse
        
        //Staff
        if (!isset($_POST["btnPDF"])){
            if ($allowupdate && (($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('C','X','U','O')) && $p->update_allowed('P'))) && !in_array($_SESSION[$_GET["trid"] . "admin"], array('D','B','J')))){
                echo '<tr><td style="padding-left: 0.6cm"><a href="javascript:unitdescriptionupdate(\'\',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'staff\',1,1,3)">Staff:</a></td></tr>';
            }//endif
            else {
                echo '<tr><td style="padding-left: 0.6cm"><b>Staff:</b></td></tr>';
            }//endelse
        }//endelse
        
        //Check if staff info lives in unitdescriptiondetail. If not insert it from unituser
        
        //Course Coordinator
        if ($_SESSION[$_GET["trid"] . "moderationtype"]=='U'){
            $sql = "select *
              from unitdescriptiondetail
              where locationid = '$locationid'
              and termid = '$termid'
              and unitid = '$unitid'
              and udtype = 'staff'
              and content = 'O'";
            
            $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-036: ".mysqli_error($db));
            
            $coursecoordinatorfound = false;
            if (mysqli_num_rows($sql_ok) > 0){
                $coursecoordinatorfound = true;
            }//endif
            
            if (mysqli_num_rows($sql_ok) == 0){//none found
                $sql = "select usr.fullname, usr.room, usr.telephone, usr.email
                from unituser as uu
                  inner join user as usr
                    on usr.userid = uu.userid
                where uu.locationid = '$locationid'
                and uu.termid = '$termid'
                and uu.unitid = '$unitid'
                and uu.`type` = 'O'";
                
                $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-037: ".mysqli_error($db));
                
                for ($i=0; $i < mysqli_num_rows($sql_ok); $i++){
                    $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-038: ".mysqli_error($db));
                    
                    $sequence = 1;
                    
                    $sql = "select max(sequence) as maxseq
                  from unitdescriptiondetail
                  where locationid = '$locationid'
                  and termid = '$termid'
                  and unitid = '$unitid'
                  and udtype = 'staff'";
                    
                    $seqsql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-039: ".mysqli_error($db));
                    
                    if (mysqli_num_rows($seqsql_ok) > 0){
                        $seqrow = mysqli_fetch_array($seqsql_ok) or die(basename(__FILE__,'.php')."-040: ".mysqli_error($db));
                        
                        $sequence = $seqrow["maxseq"] + 1;
                    }//endif
                    
                    $udtype = 'staff';
                    $content = 'O';
                    $content_1 = stripslashes($row["fullname"]);
                    $content_2 = $row["room"];
                    $content_3 = $row["telephone"];
                    $content_4 = stripslashes($row["email"]);
                    
                    $temp = addslashes($row["fullname"]);
                    $temp1 = addslashes($row["email"]);
                    
                    $sql = "insert into unitdescriptiondetail
                  values (NULL, '$locationid','$termid','$unitid','$udtype', $sequence,'$content','$temp','$content_2','$content_3','$temp1','$content_5','$content_6','$content_7')";
                    
                    $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-041: ".mysqli_error($db));
                    
                }//endfor
            }//endif
        }//endif
        
        //Uni Lecturer
        if ($_SESSION[$_GET["trid"] . "moderationtype"]=='U'){
            $sql = "select *
              from unitdescriptiondetail
              where locationid = '$locationid'
              and termid = '$termid'
              and unitid = '$unitid'
              and udtype = 'staff'
              and content = 'U'";
            
            $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-042: ".mysqli_error($db));
            
            if (mysqli_num_rows($sql_ok) == 0 && !$coursecoordinatorfound){//none found and course coordinator
                $sql = "select usr.fullname, usr.room, usr.telephone, usr.email
                from unituser as uu
                  inner join user as usr
                    on usr.userid = uu.userid
                where uu.locationid = '$locationid'
                and uu.termid = '$termid'
                and uu.unitid = '$unitid'
                and uu.`type` = 'U'";
                
                $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-043: ".mysqli_error($db));
                
                for ($i=0; $i < mysqli_num_rows($sql_ok); $i++){
                    $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-044: ".mysqli_error($db));
                    
                    $sequence = 1;
                    
                    $sql = "select max(sequence) as maxseq
                  from unitdescriptiondetail
                  where locationid = '$locationid'
                  and termid = '$termid'
                  and unitid = '$unitid'
                  and udtype = 'staff'";
                    
                    $seqsql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-045: ".mysqli_error($db));
                    
                    if (mysqli_num_rows($seqsql_ok) > 0){
                        $seqrow = mysqli_fetch_array($seqsql_ok) or die(basename(__FILE__,'.php')."-046: ".mysqli_error($db));
                        
                        $sequence = $seqrow["maxseq"] + 1;
                    }//endif
                    
                    $udtype = 'staff';
                    $content = 'U';
                    $content_1 = stripslashes($row["fullname"]);
                    $content_2 = $row["room"];
                    $content_3 = $row["telephone"];
                    $content_4 = stripslashes($row["email"]);
                    
                    $temp = addslashes($row["fullname"]);
                    $temp1 = addslashes($row["email"]);
                    
                    $sql = "insert into unitdescriptiondetail
                  values (NULL, '$locationid','$termid','$unitid','$udtype', $sequence,'$content','$temp','$content_2','$content_3','$temp1','$content_5','$content_6','$content_7')";
                    
                    $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-047: ".mysqli_error($db));
                    
                }//endfor
            }//endif
        }//endif
        
        //Coordinating Lecturer now Partner Lead Lecturer
        $sql = "select *
            from unitdescriptiondetail
            where locationid = '$locationid'
            and termid = '$termid'
            and unitid = '$unitid'
            and udtype = 'staff'
            and content = 'C'";
        
        $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-048: ".mysqli_error($db));
        
        if (mysqli_num_rows($sql_ok) == 0){//none found
            $sql = "select usr.fullname, usr.room, usr.telephone, usr.email
              from unituser as uu
                  inner join user as usr
                    on usr.userid = uu.userid
              where uu.locationid = '$locationid'
              and uu.termid = '$termid'
              and uu.unitid = '$unitid'
              and uu.`type` = 'C'";
            
            $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-049: ".mysqli_error($db));
            
            for ($i=0; $i < mysqli_num_rows($sql_ok); $i++){
                $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-050: ".mysqli_error($db));
                
                $sequence = $i + 1;
                
                $udtype = 'staff';
                $content = 'C';
                $content_1 = stripslashes($row["fullname"]);
                $content_2 = $row["room"];
                $content_3 = $row["telephone"];
                $content_4 = stripslashes($row["email"]);
                
                $temp = addslashes($row["fullname"]);
                $temp1 = addslashes($row["email"]);
                
                $sql = "insert into unitdescriptiondetail
                values (NULL, '$locationid','$termid','$unitid','$udtype', $sequence,'$content','$temp','$content_2','$content_3','$temp1','$content_5','$content_6','$content_7')";
                
                $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-051: ".mysqli_error($db));
                
            }//endfor
            
        }//endif
        
        //Partner Secondary Lecturer
        $sql = "select *
            from unitdescriptiondetail
            where locationid = '$locationid'
            and termid = '$termid'
            and unitid = '$unitid'
            and udtype = 'staff'
            and content = 'S'";
        
        $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-052: ".mysqli_error($db));
        
        if (mysqli_num_rows($sql_ok) == 0){//none found
            $sql = "select usr.fullname, usr.room, usr.telephone, usr.email
              from unituser as uu
                  inner join user as usr
                    on usr.userid = uu.userid
              where uu.locationid = '$locationid'
              and uu.termid = '$termid'
              and uu.unitid = '$unitid'
              and uu.`type` = 'S'";
            
            $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-053: ".mysqli_error($db));
            
            for ($i=0; $i < mysqli_num_rows($sql_ok); $i++){
                $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-054: ".mysqli_error($db));
                
                $sequence = $i + 1;
                
                $udtype = 'staff';
                $content = 'S';
                $content_1 = stripslashes($row["fullname"]);
                $content_2 = $row["room"];
                $content_3 = $row["telephone"];
                $content_4 = stripslashes($row["email"]);
                
                $temp = addslashes($row["fullname"]);
                $temp1 = addslashes($row["email"]);
                
                $sql = "insert into unitdescriptiondetail
                values (NULL, '$locationid','$termid','$unitid','$udtype', $sequence,'$content','$temp','$content_2','$content_3','$temp1','$content_5','$content_6','$content_7')";
                
                $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-055: ".mysqli_error($db));
                
            }//endfor
            
        }//endif
        
        //Tutors    T = Partner Tutor, L = Uni Tutor
        $sql = "select *
            from unitdescriptiondetail
            where locationid = '$locationid'
            and termid = '$termid'
            and unitid = '$unitid'
            and udtype = 'staff'
            and content = 'T'";
        
        $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-056: ".mysqli_error($db));
        
        if (mysqli_num_rows($sql_ok) == 0){//none found
            $sql = "select usr.fullname, usr.room, usr.telephone, usr.email
              from unituser as uu
                  inner join user as usr
                    on usr.userid = uu.userid
              where uu.locationid = '$locationid'
              and uu.termid = '$termid'
              and uu.unitid = '$unitid'
              and uu.`type` = 'T'";
            
            $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-057: ".mysqli_error($db));
            
            for ($i=0; $i < mysqli_num_rows($sql_ok); $i++){
                $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-058: ".mysqli_error($db));
                
                $sequence = $i + 1;
                
                $udtype = 'staff';
                $content = 'T';
                $content_1 = stripslashes($row["fullname"]);
                $content_2 = $row["room"];
                $content_3 = $row["telephone"];
                $content_4 = stripslashes($row["email"]);
                
                $temp = addslashes($row["fullname"]);
                $temp1 = addslashes($row["email"]);
                
                $sql = "insert into unitdescriptiondetail
                values (NULL, '$locationid','$termid','$unitid','$udtype', $sequence,'$content','$temp','$content_2','$content_3','$temp1','$content_5','$content_6','$content_7')";
                
                $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-059: ".mysqli_error($db));
                
            }//endfor
            
        }//endif
        
        $sql = "select *
            from unitdescriptiondetail
            where locationid = '$locationid'
            and termid = '$termid'
            and unitid = '$unitid'
            and udtype = 'staff'
            and content = 'L'";
        
        $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-060: ".mysqli_error($db));
        
        if (mysqli_num_rows($sql_ok) == 0){//none found
            $sql = "select usr.fullname, usr.room, usr.telephone, usr.email
              from unituser as uu
                  inner join user as usr
                    on usr.userid = uu.userid
              where uu.locationid = '$locationid'
              and uu.termid = '$termid'
              and uu.unitid = '$unitid'
              and uu.`type` = 'L'";
            
            $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-061: ".mysqli_error($db));
            
            for ($i=0; $i < mysqli_num_rows($sql_ok); $i++){
                $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-062: ".mysqli_error($db));
                
                $sequence = $i + 1;
                
                $udtype = 'staff';
                $content = 'L';
                $content_1 = stripslashes($row["fullname"]);
                $content_2 = $row["room"];
                $content_3 = $row["telephone"];
                $content_4 = stripslashes($row["email"]);
                
                $temp = addslashes($row["fullname"]);
                $temp1 = addslashes($row["email"]);
                
                $sql = "insert into unitdescriptiondetail
                values (NULL, '$locationid','$termid','$unitid','$udtype', $sequence,'$content','$temp','$content_2','$content_3','$temp1','$content_5','$content_6','$content_7')";
                
                $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-063: ".mysqli_error($db));
                
            }//endfor
            
        }//endif
        
        $sql = "select *
            from unitdescriptiondetail
            where locationid = '$locationid'
            and termid = '$termid'
            and unitid = '$unitid'
            and udtype='staff'
            order by sequence";
        
        $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-064: ".mysqli_error($db));
        
        if (isset($_POST["btnPDF"])){
            $pdf->SetFont('','B',10);
            $pdf->SetX(15);
            $pdf->Cell('',10,'Staff',0,1,'L',0);
        }//endif
        
        if (isset($_POST["btnPDF"])){
            $html = '
            <table width="100%" style="margin-left: 0mm;" border="1" cellspacing="0" cellpadding="3">
            <thead>
            <tr>
            <td width="12%" style="background-color:#C0C0C0; font-weight: bold; text-align: center">Role</td>
            <td width="15%" style="background-color:#C0C0C0; font-weight: bold; text-align: center">Name</td>
            <td width="7%" style="background-color:#C0C0C0; font-weight: bold; text-align: center">Room</td>
            <td width="10%" style="background-color:#C0C0C0; font-weight: bold; text-align: center">Telephone</td>
            <td width="*" style="background-color:#C0C0C0; font-weight: bold; text-align: center">Email</td>
            </tr>
            </thead>';
        }//endif
        else {
            echo '<tr><td style="padding-left: 1.2cm">';
            echo '<table width="100%" border="1" cellpadding="6" cellspacing="0">';
            echo '<tr><td width="1%" bgcolor="#C0C0C0" align="center">&nbsp;</td>';
            echo '<td width="20%" bgcolor="#C0C0C0" align="center"><b>Rle</b></td>';
            echo '<td width="27%" bgcolor="#C0C0C0" align="center"><b>Name</b></td>';
            echo '<td width="11%" bgcolor="#C0C0C0" align="center"><b>Room</b></td>';
            echo '<td width="15%" bgcolor="#C0C0C0" align="center"><b>Telephone</b></td>';
            echo '<td width="26%" bgcolor="#C0C0C0" align="center"><b>Email</b></td></tr>';
        }//endif
        
        for ($i=0; $i < mysqli_num_rows($sql_ok); $i++){
            $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-065: ".mysqli_error($db));
            
            $unitdescdetailkey = $row["unitdescdetailkey"];
            $temp_0 = stripslashes($row["content"]);
            switch ($temp_0){
                case 'C':
                case 'S':
                case 'U':
                    $type= 'Lecturer';
                    break;
                case 'S':
                    $type= 'Supervisor';
                    break;
                case 'L':
                case 'T':
                    $type= 'Tutor';
                    break;
                case 'O':
                    $type= 'Course Coordinator';
                    break;
            }//endcase
            $temp_1 = stripslashes($row["content_1"]);
            $temp_2 = stripslashes($row["content_2"]);
            $temp_3 = stripslashes($row["content_3"]);
            $temp_4 = stripslashes($row["content_4"]);
            
            $nbr = $i +1 . '.';
            
            if (isset($_POST["btnPDF"])){
                
                $pdf->SetFont('','',10);
                $pdf->SetX(15);
                
                $html = $html . '
          <tr>
          <td>'.$type.'</td>
          <td>'.$temp_1.'</td>
          <td style="text-align: center;">'.$temp_2.'</td>
          <td style="text-align: center;">'.$temp_3.'</td>
          <td>'.$temp_4.'</td>
          </tr>';
                
            }//endif
            else {
                if (empty($temp_1)){
                    $temp_1 = '&nbsp;';
                }//endif
                if (empty($temp_2)){
                    $temp_2 = '&nbsp;';
                }//endif
                if (empty($temp_3)){
                    $temp_3 = '&nbsp;';
                }//endif
                if (empty($temp_4)){
                    $temp_4 = '&nbsp;';
                }//endif
                if ($allowupdate && (($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('C','X','U','O')) && $p->update_allowed('P'))) && !in_array($_SESSION[$_GET["trid"] . "admin"], array('D','B','J')))){
                    echo '<tr><td><a href="javascript:unitdescriptionupdate(' . $unitdescdetailkey . ',\'' .$locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'staff\',1,1,3)">'. $nbr . '</a></td>' . '<td style="vertical-align: top">'. $type .'<td style="vertical-align: top">'. $temp_1 .'</td><td width="10%" style="vertical-align: top; text-align: center">'. $temp_2 .'</td><td style="vertical-align: top; text-align: right">'. $temp_3 .'</td><td style="vertical-align: top">'. $temp_4 .'</td></tr>';
                }//endif
                else {
                    echo '<tr><td>' . $nbr .  '</td><td style="vertical-align: top">'. $type .'</td><td style="vertical-align: top">'. $temp_1 .'</td><td width="10%" style="vertical-align: top; text-align: center">'. $temp_2 .'</td><td style="vertical-align: top; text-align: right">'. $temp_3 .'</td><td style="vertical-align: top">'. $temp_4 .'</td></tr>';
                }//endelse
            }//endelse
        }//endfor
        
        if (isset($_POST["btnPDF"])){
            $html = $html . '</table>';
            $pdf->writeHTML($html);
            $pdf->Ln();
        }//endif
        else {
            echo '</td></tr>';
            echo '</table>';
        }//endif
        
        $sql = "select noabrule, deliverymode
            from unitlocation
            where locationid = '$locationid'
            and termid = '$termid'
            and unitid = '$unitid'";
        
        $ulsql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-066: ".mysqli_error($db));
        
        if (mysqli_num_rows($ulsql_ok) > 0){
            $ulrow = mysqli_fetch_array($ulsql_ok) or die(basename(__FILE__,'.php')."-067: ".mysqli_error($db));
            
            $noabrule = $ulrow["noabrule"];
            $deliverymode = $ulrow["deliverymode"];
        }//endif
        
        //Timetable
        if (!isset($_POST["btnPDF"])){
            if ($allowupdate && (($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('C','X','U','O')) && $p->update_allowed('P'))) && !in_array($_SESSION[$_GET["trid"] . "admin"], array('D','B','J')))){
                echo '<tr><td style="padding-left: 0.6cm"><a href="javascript:unitdescriptionupdate(\'\',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'time\',1,1,4)">Timetable:</a></td></tr>';
            }//endif
            else {
                echo '<tr><td style="padding-left: 0.6cm"><b>Timetable:</b></td></tr>';
            }//endelse
        }//endelse
        
        $sql = "select *
            from unitdescriptiondetail
            where locationid = '$locationid'
            and termid = '$termid'
            and unitid = '$unitid'
            and udtype='time'
            order by sequence";
        
        $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-068: ".mysqli_error($db));
        
        if (isset($_POST["btnPDF"])){
            $pdf->SetFont('','B',10);
            $pdf->SetX(15);
            $pdf->Cell('',10,'Timetable',0,1,'L',0);
        }//endif
        
        $colsneeded = false;
        $sql = "select *
            from unitdescriptiondetail
            where locationid = '$locationid'
            and termid = '$termid'
            and unitid = '$unitid'
            and udtype='time'
            and ifnull(content_2,'') = 'Local'
            order by sequence";
        
        $tempsql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-069: ".mysqli_error($db));
        
        if (mysqli_num_rows($tempsql_ok) == 1){
            $colsneeded = true;
        }//endif
        else {
            $sql = "select *
              from unitdescriptiondetail
              where locationid = '$locationid'
              and termid = '$termid'
              and unitid = '$unitid'
              and udtype='time'
              order by sequence";
            
            $tempsql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-070: ".mysqli_error($db));
        }//endelse
        
        if (isset($_POST["btnPDF"])){
            $pdf->SetFont('','B',10);
            $pdf->SetX(15);
            if ($deliverymode == 'B' && $colsneeded){
                $html = '
            <table width="100%" style="margin-left: 0mm;" border="1" cellspacing="0" cellpadding="3">
            <thead>
            <tr>
            <td style="background-color:#C0C0C0; font-weight: bold; text-align: center">Instructions</td>
            </tr>
            </thead>';
            }//endif
            else {
                $html = '
            <table width="100%" style="margin-left: 0mm;" border="1" cellspacing="0" cellpadding="3">
            <thead>
            <tr>
            <td width="15%" style="background-color:#C0C0C0; font-weight: bold; text-align: center">Type</td>
            <td width="15%" style="background-color:#C0C0C0; font-weight: bold; text-align: center">Day</td>
            <td width="20%" style="background-color:#C0C0C0; font-weight: bold; text-align: center">Time</td>
            <td width="15%" style="background-color:#C0C0C0; font-weight: bold; text-align: center">Room</td>
            <td style="background-color:#C0C0C0; font-weight: bold; text-align: center">Staff / Comment</td>
            </tr>
            </thead>';
            }//endelse
        }//endif
        else {
            if ($deliverymode == 'B' && $colsneeded){
                echo '<tr><td style="padding-left: 1.2cm">';
                echo '<table width="100%" border="1" cellpadding="6" cellspacing="0">';
                echo '<tr><td width="1%" bgcolor="#C0C0C0">&nbsp;</td>';
                echo '<td bgcolor="#C0C0C0" colspan="6" align="center"><b>Instructions</b></td></tr>';
            }//endif
            else {
                echo '<tr><td style="padding-left: 1.2cm">';
                echo '<table width="100%" border="1" cellpadding="6" cellspacing="0">';
                echo '<tr><td width="1%" bgcolor="#C0C0C0">&nbsp;</td>';
                echo '<td width="20%" bgcolor="#C0C0C0" align="center"><b>Type</b></td>';
                echo '<td width="15%" bgcolor="#C0C0C0" align="center"><b>Day</b></td>';
                echo '<td width="25%" bgcolor="#C0C0C0" align="center"><b>Time</b></td>';
                echo '<td width="11%" bgcolor="#C0C0C0" align="center"><b>Room</b></td>';
                echo '<td width="28%" bgcolor="#C0C0C0" align="center"><b>Staff / Comment</b></td></tr>';
            }//endelse
            
        }//endelse
        
        for ($i=0; $i < mysqli_num_rows($tempsql_ok); $i++){
            $row = mysqli_fetch_array($tempsql_ok) or die(basename(__FILE__,'.php')."-071: ".mysqli_error($db));
            
            $unitdescdetailkey = $row["unitdescdetailkey"];
            $temp_0 = stripslashes($row["content"]);
            $type = '';
            $typemerge = false;
            switch ($temp_0){
                case 'C':
                    $type = 'Consultation';
                    break;
                case 'E':
                    $type = 'Excursion';
                    break;
                case 'B':
                    $type = 'Laboratory';
                    break;
                case 'L':
                    $type = 'Lecture';
                    break;
                case 'N':
                    $type = 'No scheduled classes';
                    $typemerge = true;
                    break;
                case 'P':
                    $type = 'Practical';
                    break;
                case 'M':
                    $type = 'Team meeting';
                    break;
                case 'R':
                    $type = 'Refer to timetable';
                    $typemerge = true;
                    break;
                case 'S':
                    $type = 'Supervisor meeting';
                    break;
                case 'T':
                    $type = 'Tutorial';
                    break;
                case 'W':
                    $type = 'Workshop';
                    break;
            }//endcase
            $temp_1 = stripslashes($row["content_1"]);
            $type = $type . '&nbsp;' . $temp_1;
            $temp_2 = stripslashes($row["content_2"]);
            $temp_3 = stripslashes($row["content_3"]);
            $temp_4 = stripslashes($row["content_4"]);
            $temp_5 = stripslashes($row["content_5"]);
            
            $nbr = $i +1 . '.';
            
            if (isset($_POST["btnPDF"])){
                
                $type = str_replace('&nbsp;',' ',$type);
                
                $pdf->SetX(15);
                
                if ($type==' ' && empty($temp_2) && empty($temp_3) && empty($temp_4) && !$colsneeded){
                    $html = $html . '
            <tr>
            <td colspan="5">'.$temp_5.'</td>
            </tr>';
                }//endif
                elseif ($temp_2=='Local'){
                    $html = $html . '
            <tr>
            <td>Refer local timetable for day, time and room details for lectures, tutorials, and/or laboratories.</td>
            </tr>';
                }//endif
                else {
                    if ($typemerge){
                        $html = $html . '
              <tr>
              <td colspan="4">'.$type.'</td>
              <td>'.$temp_5.'</td>
              </tr>';
                    }//endif
                    else {
                        $html = $html . '
              <tr>
              <td>'.$type.'</td>
              <td>'.$temp_2.'</td>
              <td style="text-align: center;">'.$temp_3.'</td>
              <td style="text-align: center;">'.$temp_4.'</td>
              <td>'.$temp_5.'</td>
              </tr>';
                    }//endelse
                    
                }//endelse
                
            }//endif
            else {
                if (empty($temp_1)){
                    $temp_1 = '&nbsp;';
                }//endif
                if (empty($temp_2)){
                    $temp_2 = '&nbsp;';
                }//endif
                if (empty($temp_3)){
                    $temp_3 = '&nbsp;';
                }//endif
                if (empty($temp_4)){
                    $temp_4 = '&nbsp;';
                }//endif
                if (empty($temp_5)){
                    $temp_5 = '&nbsp;';
                }//endif
                
                if ($deliverymode == 'B' && $colsneeded && $temp_2 == 'Local'){
                    $temp_2 = 'Refer local timetable for day, time and room details for lectures, tutorials, and/or laboratories.';
                }//endif
                
                if ($allowupdate && (($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('C','X','U','O')) && $p->update_allowed('P'))) && !in_array($_SESSION[$_GET["trid"] . "admin"], array('D','B','J')))){
                    if ($type == '&nbsp;' && $temp_2 == '&nbsp;' && $temp_3 == '&nbsp;' && $temp_4 == '&nbsp;' && !$colsneeded){
                        echo '<tr><td><a href="javascript:unitdescriptionupdate(' . $unitdescdetailkey . ',\'' .$locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'time\',1,1,4)">'. $nbr . '</a></td>'  .'<td style="vertical-align: top">'. $temp_5 .'</td></tr>';
                    }//endif
                    else {
                        if ($typemerge){
                            echo '<tr><td><a href="javascript:unitdescriptionupdate(' . $unitdescdetailkey . ',\'' .$locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'time\',1,1,4)">'. $nbr . '</a></td>' . '<td style="vertical-align: top" colspan="4">'. $type .'<td style="vertical-align: top">'. $temp_5 .'</td></tr>';
                        }//endif
                        else {
                            echo '<tr><td><a href="javascript:unitdescriptionupdate(' . $unitdescdetailkey . ',\'' .$locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'time\',1,1,4)">'. $nbr . '</a></td>' . '<td style="vertical-align: top">'. $type .'<td style="vertical-align: top">'. $temp_2 .'</td><td style="vertical-align: top; text-align: center">'. $temp_3 .'</td><td style="vertical-align: top; text-align: center">'. $temp_4 .'</td><td style="vertical-align: top">'. $temp_5 .'</td></tr>';
                        }//endelse
                        
                    }//endelse
                }//endif
                else {
                    echo '<tr><td>' . $nbr . '</td><td style="vertical-align: top">'. $type .'<td style="vertical-align: top">'. $temp_2 .'</td><td style="vertical-align: top; text-align: center">'. $temp_3 .'</td><td style="vertical-align: top; text-align: center"">'. $temp_4 .'</td><td style="vertical-align: top">'. $temp_5 .'</td></tr>';
                }//endelse
            }//endelse
        }//endfor
        
        if (!isset($_POST["btnPDF"])){
            echo '</td></tr>';
            echo '</table>';
        }//endif
        else {
            $html = $html . '</table>';
            $pdf->writeHTML($html);
        }//endelse
        
        $temp = 'Additional consultation time can be booked by contacting the staff member concerned directly.';
        if($termid >='2020/17'){
            $temp = $temp . ' This is subject to change and students are encouraged to check with timetabling and the Moodle shell for updates.';
        }
        if (!isset($_POST["btnPDF"])){
            echo '<tr><td style="padding-left: 1.2cm">' . $temp . '</td></tr>';
        }//endif
        
        
        if (isset($_POST["btnPDF"])){
            $pdf->Ln();
            
            $pdf->SetX(15);
            $pdf->SetFont('','',10);
            $temp = convert_for_html($temp,_tablesettingON,_striplinebreakOFF,_stripAtagOFF,_stripalllinefeedOFF);
            
            $html = '<div style="margin-left: 0mm; text-align: left;">'.$temp.'</div>';
            $pdf->writeHTML($html);
            
            $pdf->Ln(1);
        }//endif
        
        
        //LEARNING OUTCOMES ===============================================================================
        $label = 'Objectives:';
        $type = 'objgen';
        if ($effectivetermid > '2013/00'){
            $label = 'Learning Outcomes';
            $type = 'logen';
        }//endif
        
        //Objectives heading
        if (isset($_POST["btnPDF"])){
            $pdf->SetFont('','B',10);
            $pdf->Cell('',10,$label,0,1,'L',0);
        }//endif
        else {
            echo '<tr><td><b>'. $label.' </b></td></tr>';
        }//endelse
        
        //Objectives - General
        $sql = "select *
            from unitoutlinedetail
            where unitoutlinekey = '$unitoutlinekey'
            and uotype='$type'";
        
        $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-072: ".mysqli_error($db));
        
        if (mysqli_num_rows($sql_ok)){
            $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-073: ".mysqli_error($db));
            
            $temp = stripslashes($row["content"]);
            
            if (isset($_POST["btnPDF"])){
                $pdf->SetX(15);
                
                $temp = convert_for_html($temp,_tablesettingOFF,_striplinebreakOFF,_stripAtagOFF,_stripalllinefeedOFF);
                
                $html = '<div style="margin-left: 20; text-align: left;">'.$temp.'</div>';
                $pdf->writeHTML($html);
            }//endif
            else {
                echo '<tr><td style="padding-left: 0.6cm; text-align: left">'. $temp .'</td></tr>';
            }//endelse
        }//endif
        
        if ($effectivetermid < '2013/00'){
            
            if (isset($_POST["btnPDF"])){
                $pdf->SetFont('','',10);
                $pdf->SetX(15);
                $pdf->Cell('',10,'After successfully completing this course, students should be able to:',0,1,'L',0);
            }//endif
            else {
                echo '<tr><td style="padding-left: 0.6cm"><br>After successfully completing this course, students should be able to:</td></tr>';
            }//endelse
        }//endif
        
        //Knowledge==============================================================================================
        if (!isset($_POST["btnPDF"])){
            echo '<tr><td style="Padding-left: 0.6cm"><b>Knowledge:</b></td></tr>';
        }//endelse
        
        $sql = "select *
            from unitoutlinedetail
            where unitoutlinekey = '$unitoutlinekey'
            and uotype='know'
            order by sequence";
        
        $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-074: ".mysqli_error($db));
        
        if (mysqli_num_rows($sql_ok) > 0){
            if (isset($_POST["btnPDF"])){
                $pdf->SetFont('','B',10);
                $pdf->SetX(15);
                $pdf->Cell('',10,'Knowledge',0,1,'L',0);
            }//endif
        }//endif
        
        $html = '
        <table width="100%" style="margin-left: 0mm;" border="0" cellspacing="0" cellpadding="3">';
        
        $cntr=1;
        for ($i=0; $i < mysqli_num_rows($sql_ok); $i++){
            $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-075: ".mysqli_error($db));
            
            $unitdescdetailkey = $row["unitdescdetailkey"];
            $temp = stripslashes($row["content"]);
            if (empty($row["heading"])){
                $nbr = 'K' . ($cntr++) . '.';
            }//endif
            else {
                $nbr = '';
                $temp = '<span style="font-weight: bold;">' . $temp . '</span>';
            }//endelse
            if (isset($_POST["btnPDF"])){
                $pdf->SetFont('','',10);
                $pdf->SetX(25);
                
                $temp = convert_for_html($temp,_tablesettingOFF,_striplinebreakOFF,_stripAtagOFF,_stripalllinefeedOFF);
                
                if (empty($row["heading"])){
                    $html = $html . '
            <tr>
            <td width="6%" style="vertical-align:top"><b>'.$nbr.'</b></td>
            <td>'.$temp.'</td>
            </tr>';
                }//endif
                else {
                    $html = $html . '
            <tr>
            <td colspan="2">'.$temp.'</td>
            </tr>';
                }//endelse
            }//endif
            else {
                echo '<tr><td style="padding-left: 1.2cm"><table width="100%"><tr><td style="vertical-align: top" width="1%">'. $nbr . '</td><td>' . $temp .'</td></tr></table></td></tr>';
            }//endelse
        }//endfor
        
        if (isset($_POST["btnPDF"]) && mysqli_num_rows($sql_ok) > 0){
            $html = $html . '
          </table>';
            $pdf->writeHTML($html);
        }//endif
        
        //Skills
        if (!isset($_POST["btnPDF"])){
            echo '<tr><td style="Padding-left: 0.6cm"><b>Skills:</b></td></tr>';
        }//endif
        
        $sql = "select *
            from unitoutlinedetail
            where unitoutlinekey = '$unitoutlinekey'
            and uotype='skil'
            order by sequence";
        
        $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-076: ".mysqli_error($db));
        
        if (mysqli_num_rows($sql_ok) > 0){
            if (isset($_POST["btnPDF"])){
                $pdf->SetFont('','B',10);
                $pdf->SetX(15);
                $pdf->Cell('',10,'Skills',0,1,'L',0);
            }//endif
        }//endif
        
        $html = '
        <table width="100%" style="margin-left: 0mm;" border="0" cellspacing="0" cellpadding="3">';
        
        $cntr=1;
        for ($i=0; $i < mysqli_num_rows($sql_ok); $i++){
            $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-077: ".mysqli_error($db));
            
            $unitdescdetailkey = $row["unitdescdetailkey"];
            $temp = stripslashes($row["content"]);
            if (empty($row["heading"])){
                $nbr = 'S' . ($cntr++) . '.';
            }//endif
            else {
                $nbr = '';
                $temp = '<span style="font-weight: bold;">' . $temp . '</span>';
            }//endelse
            if (isset($_POST["btnPDF"])){
                $pdf->SetFont('','',10);
                $pdf->SetX(25);
                
                $temp = convert_for_html($temp,_tablesettingOFF,_striplinebreakOFF,_stripAtagOFF,_stripalllinefeedOFF);
                
                if (empty($row["heading"])){
                    $html = $html . '
            <tr>
            <td width="6%" style="vertical-align:top"><b>'.$nbr.'</b></td>
            <td>'.$temp.'</td>
            </tr>';
                }//endif
                else {
                    $html = $html . '
            <tr>
            <td colspan="2">'.$temp.'</td>
            </tr>';
                }//endelse
            }//endif
            else {
                echo '<tr><td style="padding-left: 1.2cm"><table width="100%"><tr><td style="vertical-align: top" width="1%">'. $nbr . '</td><td>' . $temp .'</td></tr></table></td></tr>';
            }//endelse
        }//endfor
        
        if (isset($_POST["btnPDF"]) && mysqli_num_rows($sql_ok) > 0){
            $html = $html . '
          </table>';
            $pdf->writeHTML($html);
        }//endif
        
        if ($effectivetermid > '2013/00'){
            //Application
            if (!isset($_POST["btnPDF"])){
                echo '<tr><td style="Padding-left: 0.6cm"><b>Application of knowledge and skills:</b></td></tr>';
            }//endelse
            
            $sql = "select *
              from unitoutlinedetail
              where unitoutlinekey = '$unitoutlinekey'
              and uotype='applic'
              order by sequence";
            
            $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-078: ".mysqli_error($db));
            
            if (mysqli_num_rows($sql_ok) > 0){
                if (isset($_POST["btnPDF"])){
                    $pdf->SetFont('','B',10);
                    $pdf->SetX(15);
                    $pdf->Cell('',10,'Application of knowledge and skills',0,1,'L',0);
                }//endif
            }//endif
            
            $html = '
          <table width="100%" style="margin-left: 0mm;" border="0" cellspacing="0" cellpadding="3">';
            
            $cntr=1;
            for ($i=0; $i < mysqli_num_rows($sql_ok); $i++){
                $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-079: ".mysqli_error($db));
                
                $unitoutdetailkey = $row["unitoutdetailkey"];
                $temp = stripslashes($row["content"]);
                if (empty($row["heading"])){
                    $nbr = 'A' . ($cntr++) . '.';
                }//endif
                else {
                    $nbr = '';
                    $temp = '<span style="font-weight: bold;">' . $temp . '</span>';
                }//endelse
                if (isset($_POST["btnPDF"])){
                    $pdf->SetFont('','',10);
                    $pdf->SetX(15);
                    
                    $temp = convert_for_html($temp,_tablesettingOFF,_striplinebreakOFF,_stripAtagOFF,_stripalllinefeedOFF);
                    
                    if (empty($row["heading"])){
                        $html = $html . '
              <tr>
              <td width="6%" style="vertical-align:top"><b>'.$nbr.'</b></td>
              <td>'.$temp.'</td>
              </tr>';
                    }//endif
                    else {
                        $html = $html . '
              <tr>
              <td colspan="2">'.$temp.'</td>
              </tr>';
                    }//endelse
                }//endif
                else {
                    echo '<tr><td style="padding-left: 1.2cm"><table width="100%"><tr><td style="vertical-align: top" width="1%">'. $nbr . '</td><td>' . $temp .'</td></tr></table></td></tr>';
                }//endelse
            }//endfor
            
            if (isset($_POST["btnPDF"]) && mysqli_num_rows($sql_ok) > 0){
                $html = $html . '
            </table>';
                $pdf->writeHTML($html);
            }//endif
        }//endif
        
        
        //Content heading
        if (isset($_POST["btnPDF"])){
            $pdf->SetFont('','B',10);
            
            $pdf->SetX(15);
            $pdf->Cell('',10,'Content',0,1,'L',0);
            //$pdf->SetX(20);
            //$pdf->Cell('',10,'Scope:',0,1,'L',0);
        }//endif
        else {
            echo '<tr><td><b>Content:</b></td></tr>';
            echo '<tr><td style="Padding-left: 0.6cm"><b>Scope:</b></td></tr>';
        }//endelse
        
        //Content - General
        $sql = "select *
            from unitoutlinedetail
            where unitoutlinekey = '$unitoutlinekey'
            and uotype='congen'";
        
        $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-086: ".mysqli_error($db));
        
        if (mysqli_num_rows($sql_ok)){
            $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-087: ".mysqli_error($db));
            
            $unitdescdetailkey = $row["unitdescdetailkey"];
            
            $temp = stripslashes($row["content"]);
            
            if (isset($_POST["btnPDF"])){
                $pdf->SetX(25);
                
                $temp = convert_for_html($temp,_tablesettingON,_striplinebreakOFF,_stripAtagOFF,_stripalllinefeedOFF);
                
                $html = '<div style="margin-left: 10mm; text-align: left;">'.$temp.'</div>';
                $pdf->writeHTML($html);
                $pdf->Ln();
            }//endif
            else {
                echo '<tr><td style="padding-left: 1.2cm; text-align: left">'. $temp .'</td></tr>';
            }//endelse
        }//endif
        
        //Content - Items
        
        $sql = "select *
            from unitoutlinedetail
            where unitoutlinekey = '$unitoutlinekey'
            and uotype='cont'
            order by sequence";
        
        $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-088: ".mysqli_error($db));
        
        if (mysqli_num_rows($sql_ok) > 0){
            if (isset($_POST["btnPDF"])){
                $pdf->SetFont('','',10);
                $pdf->SetX(15);
                $pdf->Cell(40,5,'Topics may include:',0,1,'L');
            }//endif
            else {
                echo '<tr><td style="padding-left: 1.2cm"></b>Topics may include:</b></td></tr>';
            }//endelse
        }//endif
        
        $html = '';
        for ($i=0; $i < mysqli_num_rows($sql_ok); $i++){
            $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-089: ".mysqli_error($db));
            
            $unitdescdetailkey = $row["unitdescdetailkey"];
            $temp = stripslashes($row["content"]);
            
            $nbr = $i +1 . '.';
            if (isset($_POST["btnPDF"])){
                
                $temp = convert_for_html($temp,_tablesettingON,_striplinebreakOFF,_stripAtagOFF,_stripalllinefeedOFF);
                
                $html = $html . '<li>' . $temp . '</li>';
            }//endif
            else {
                echo '<tr><td style="padding-left: 1.2cm"><table width="100%"><tr><td style="vertical-align: top" width="1%">'. $nbr . '</td><td>' . $temp .'</td></tr></table></td></tr>';
            }//endelse
        }//endfor
        
        if (isset($_POST["btnPDF"]) && $html){
            $html = '<ul>' . $html . '</ul>';
            
            $html = '<div style="margin-left: 0mm; text-align: left;">'.$html.'</div>';
            $pdf->writeHTML($html);
        }//endif
        
        
        
        //VALUES==========================================================================================================================================================
        if ($effectivetermid > '2013/00'){            
            //Values - General
            $sql = "select *
              from unitoutlinedetail
              where unitoutlinekey = '$unitoutlinekey'
              and uotype='valgrd'";
            
            $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-080: ".mysqli_error($db));
            
            if (mysqli_num_rows($sql_ok)){
                $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-081: ".mysqli_error($db));
                
                $unitoutdetailkey = $row["unitoutdetailkey"];
                if (!isset($_POST["btnPDF"])){
                    echo '<tr><td><b>Values and Graduate Attributes:</b></td></tr>';
                }//endelse
                $temp = stripslashes($row["content"]);
                
                if (isset($_POST["btnPDF"])){
                    $pdf->SetX(15);
                    
                    $temp = convert_for_html($temp,_tablesettingOFF,_striplinebreakOFF,_stripAtagOFF,_stripalllinefeedOFF);
                    
                    $html = '<div style="margin-left: 20; text-align: left;">'.$temp.'</div>';
                    $pdf->writeHTML($html);
                }//endif
                else {
                    echo '<tr><td style="padding-left: 0.6cm; text-align: left">'. $temp .'</td></tr>';
                }//endelse
            }//endif
            else {
                if (!isset($_POST["btnPDF"])){
                    echo '<tr><td><b>Values and Graduate Attributes:</b></td></tr>';
                }//endelse
            }//endelse
        }//endif
        
        //Values
        if (!isset($_POST["btnPDF"])){
            echo '<tr><td style="padding-left: 0.6cm"><b>Values:</b></td></tr>';
        }//endelse
        
        $sql = "select *
            from unitoutlinedetail
            where unitoutlinekey = '$unitoutlinekey'
            and uotype='valu'
            order by sequence";
        
        $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-082: ".mysqli_error($db));
        
        if (mysqli_num_rows($sql_ok) > 0){
            if (isset($_POST["btnPDF"])){
                $pdf->SetFont('','B',10);
                $pdf->SetX(15);
                $pdf->Cell('',10,'Values',0,1,'L',0);
            }//endif
        }//endif
        
        $html = '
        <table width="100%" style="margin-left: 0mm;" border="0" cellspacing="0" cellpadding="3">';
        
        $cntr=1;
        for ($i=0; $i < mysqli_num_rows($sql_ok); $i++){
            $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-083: ".mysqli_error($db));
            
            $unitdescdetailkey = $row["unitdescdetailkey"];
            $temp = stripslashes($row["content"]);
            if (empty($row["heading"])){
                $nbr = 'V' . ($cntr++) . '.';
            }//endif
            else {
                $nbr = '';
                $temp = '<span style="font-weight: bold;">' . $temp . '</span>';
            }//endelse
            if (isset($_POST["btnPDF"])){
                $pdf->SetFont('','',10);
                $pdf->SetX(15);
                
                $temp = convert_for_html($temp,_tablesettingOFF,_striplinebreakOFF,_stripAtagOFF,_stripalllinefeedOFF);
                
                if (empty($row["heading"])){
                    $html = $html . '
            <tr>
            <td width="6%" style="vertical-align:top"><b>'.$nbr.'</b></td>
            <td>'.$temp.'</td>
            </tr>';
                }//endif
                else {
                    $html = $html . '
            <tr>
            <td colspan="2" >'.$temp.'</td>
            </tr>';
                }//endelse
            }//endif
            else {
                echo '<tr><td style="padding-left: 1.2cm"><table width="100%"><tr><td style="vertical-align: top" width="1%">'. $nbr . '</td><td>' . $temp .'</td></tr></table></td></tr>';
            }//endelse
        }//endfor
        
        if (isset($_POST["btnPDF"]) && mysqli_num_rows($sql_ok) > 0){
            $html = $html . '
          </table>';
            $pdf->writeHTML($html);
        }//endif
        
        
        //GRADUATE ATTRIBUTES
        if($effectivetermid > '2019/99' && (strtotime($stamptime) > strtotime('2020-02-01'))) {
            $sql = "select *
                from unitoutlinedetail
                where unitoutlinekey = '$unitoutlinekey'
                and uotype like 'ga%'";
    
            $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-084: ".mysqli_error($db));
        }
        else if ($effectivetermid > '2013/00'){
            $unitoutdetailkey = '\'\'';
            $sql = "select *
              from unitoutlinedetail
              where unitoutlinekey = '$unitoutlinekey'
              and uotype='grad'";
            
            $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-084: ".mysqli_error($db));
            
            if (mysqli_num_rows($sql_ok) > 0){
                $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-085: ".mysqli_error($db));
                
                $unitoutdetailkey = $row["unitoutdetailkey"];
                $content = stripslashes($row["content"]);
                if ($row["content_1"]=='L'){
                    $content_1 = 'Low';
                }//endif
                if ($row["content_1"]=='M'){
                    $content_1 = 'Medium';
                }//endif
                if ($row["content_1"]=='H'){
                    $content_1 = 'High';
                }//endif
                $content_2 = stripslashes($row["content_2"]);
                $content_3 = '';
                if ($row["content_3"]=='L'){
                    $content_3 = 'Low';
                }//endif
                if ($row["content_3"]=='M'){
                    $content_3 = 'Medium';
                }//endif
                if ($row["content_3"]=='H'){
                    $content_3 = 'High';
                }//endif
                $content_4 = stripslashes($row["content_4"]);
                $content_5 = '';
                if ($row["content_5"]=='L'){
                    $content_5 = 'Low';
                }//endif
                if ($row["content_5"]=='M'){
                    $content_5 = 'Medium';
                }//endif
                if ($row["content_5"]=='H'){
                    $content_5 = 'High';
                }//endif
                $content_6 = stripslashes($row["content_6"]);
                $content_7 = '';
                if ($row["content_7"]=='L'){
                    $content_7 = 'Low';
                }//endif
                if ($row["content_7"]=='M'){
                    $content_7 = 'Medium';
                }//endif
                if ($row["content_7"]=='H'){
                    $content_7 = 'High';
                }//endif

            }//endif
        }
            //Grad attributes ============================================================================================================
            if (!isset($_POST["btnPDF"])){
                echo '<tr><td style="padding-left: 0.6cm"><b>Graduate Attributes:</b></td></tr>';
            }//endelse
            
            if (mysqli_num_rows($sql_ok) > 0){
                if (isset($_POST["btnPDF"])){
                    $pdf->SetFont('','B',10);
                    $pdf->SetX(15);
                    $pdf->Cell('',10,'Graduate Attributes',0,1,'L',0);
                }//endif
            }//endif
            
            if (mysqli_num_rows($sql_ok) > 0){
                
                if (isset($_POST["btnPDF"])){
                    
                    $pdf->SetX(15);
                    
                    $html = '';
                    
                    if ($effectivetermid > '2019/99' && (strtotime($stamptime) > strtotime('2020-02-01'))){
                        $html = $html . '
              <table width="100%" style="margin-left: 0mm;" border="0" cellspacing="0" cellpadding="3">
              <tr>
              <td>';
                        $html = $html . 'The Federation University ' . $_SESSION[$_GET["trid"] . "sysabbreviation"];
                        $html = $html . ' graduate attributes (GA) are entrenched in the <a href="http://policy.federation.edu.au/university/general/statement_of_graduate_attributes/ch1.pdf">Higher Education Graduate Attributes Policy </a>(LT1228). FedUni graduates develop these graduate attributes through their engagement in explicit learning and teaching and assessment tasks that are embedded in all FedUni programs. ';
                        $html = $html . 'Graduate attribute attainment typically follows an incremental development process mapped through program progression. One or more graduate attributes must be evident in the specified learning outcomes and assessment for each FedUni course, and all attributes must be directly assessed in each program.</td></tr>';
                        
                    }
                    else if ($effectivetermid > '2016/99'){
                        $html = $html . '
              <table width="100%" style="margin-left: 0mm;" border="0" cellspacing="0" cellpadding="3">
              <tr>
              <td>';
                        $html = $html . $_SESSION[$_GET["trid"] . "sysabbreviation"];
                        $html = $html . ' graduate attributes statement. To have graduates with knowledge, skills and competence that enable them to stand out as critical, creative and enquiring learners who are capable, flexible and work ready, and responsible, ethical and engaged citizens.</td></tr>';
                    }//endif
                    
                    //GRAD ATTRIBUTES======================================================================================================
                    if (empty($subdiscipline["freeformgraduateattribute"])){
                        $html = $html . '</table>';
                        
                        if($effectivetermid > '2019/99' && empty($subdiscipline["freeformgraduateattribute"]) && (strtotime($stamptime)  > strtotime('2020-02-01'))){
                            for($i=1;$i<6;$i++){
                                $ga_sel='ga'.$i;
                                $sql = "select *
                  from unitoutlinedetail
                  where unitoutlinekey = '$unitoutlinekey'
                  and uotype='$ga_sel'
                  order by sequence";
                                
                                $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-061: ".mysqli_error($db));
                                
                                if (mysqli_num_rows($sql_ok) > 0){
                                    $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-062: ".mysqli_error($db));
                                    $unitoutdetailkey = $row["unitoutdetailkey"];
                                    
                                    if (empty($row["content"])){
                                        $ga_content[$i]["con"] = 'Not applicable';
                                    }//endif
                                    else {
                                        $ga_content[$i]["con"]  = stripslashes($row["content"]);
                                    }//endelse
                                    
                                    
                                    if (empty($row["content_1"])){
                                        $ga_content[$i]["con1"]  = 'Not applicable';
                                    }//endif
                                    
                                    else {
                                        $ga_content[$i]["con1"] = stripslashes($row["content_1"]);
                                    }//endelse
                                    
                                    if (empty($row["content_2"])){
                                        $ga_content[$i]["con2"]= 'Not applicable';
                                    }//endif
                                    else {
                                        $ga_content[$i]["con2"] = stripslashes($row["content_2"]);
                                    }//endelse
                                    
                                    if (empty($row["content_3"])){
                                        $ga_content[$i]["con3"] = 'Not applicable';
                                    }//endif
                                    else {
                                        $ga_content[$i]["con3"] = stripslashes($row["content_3"]);
                                    }//endelse
                                    
                                    if (empty($row["content_4"])){
                                        $ga_content[$i]["con4"] = 'Not applicable';
                                    }//endif
                                    else {
                                        $ga_content[$i]["con4"] = stripslashes($row["content_4"]);
                                    }//endelse
                                }//end if
                                
                            }//end for
                            $html = $html . '
                        </table>
                        <br>';
                            $html = $html . '
                      <table  width="100%" style="margin-left: 0mm;" border="1" cellspacing="0" cellpadding="3">';
                            $html = $html . '<thead>
                      <tr>
                      <td colspan="2" width="30%" style="background-color:#C0C0C0; font-weight: bold; font-size: 9pt; text-align: left">Graduate attribute and descriptor</td>
                      <td colspan="4" width="*" style="background-color:#C0C0C0;font-weight: bold; font-size: 9pt; text-align: left">Development and acquisition of GAs in the course</td>
                      </tr>';
                            
                            $html = $html . '
                      <tr>
                      <td colspan="2" style="background-color:#C0C0C0;font-size: 9pt;text-align: justify;">&nbsp;</td>
                      <td style="text-align: left; font-size: 9pt;vertical-align: top"><b>Learning Outcomes</b> (KSA)</td>
                      <td style="text-align: left; font-size: 9pt;vertical-align: top"><b>Code</b><br>A. Direct <br> B. Indirect <br> N/A Not addressed</td>
                      <td style="text-align: left; font-size: 9pt;vertical-align: top"><b>Assessment task (AT#)</b></td>
                      <td style="text-align: left; font-size: 9pt;vertical-align: top"><b>Code</b><br>A. Certain <br> B. Likely <br>C. Possible <br> N/A Not likely</td>
                      </tr>
                      <tr>';
                            
                            $html = $html . '</thead>
                                
                      <tr>';
                            
                            $html = $html . '
                      <tr>
                      <td style="text-align: justify;font-size: 9pt;">GA 1<br>Thinkers</td>
                      <td style="text-align: left;font-size: 9pt">'.$ga_content[1]["con"].'</td>
                      <td style="text-align: center;vertical-align: top;font-size: 9pt">'.$ga_content[1]["con1"].'</td>
                      <td style="text-align: center;vertical-align: top;font-size: 9pt">'.$ga_content[1]["con2"].'</td>
                      <td style="text-align: center;font-size: 9pt;vertical-align: top">'.$ga_content[1]["con3"].'</td>
                      <td style="text-align: center;font-size: 9pt;vertical-align: top">'.$ga_content[1]["con4"].'</td>
                      </tr>
                      <tr>';
                            
                            $html = $html . '
                      <tr>
                      <td style="text-align: justify;font-size: 9pt;">GA 2<br>Innovators</td>
                      <td style="text-align: left;font-size: 9pt;">'.$ga_content[2]["con"].'</td>
                      <td style="text-align: center;font-size: 9pt;vertical-align: top">'.$ga_content[2]["con1"].'</td>
                      <td style="text-align: center;font-size: 9pt;vertical-align: top">'.$ga_content[2]["con2"].'</td>
                      <td style="text-align: center;font-size: 9pt;vertical-align: top">'.$ga_content[2]["con3"].'</td>
                      <td style="text-align: center;font-size: 9pt;vertical-align: top">'.$ga_content[2]["con4"].'</td>
                      </tr>
                      <tr>';
                            
                            $html = $html . '
                      <tr>
                      <td style="text-align: justify;font-size: 9pt;">GA 3<br>Citizens</td>
                      <td style="text-align: left;font-size: 9pt;">'.$ga_content[3]["con"].'</td>
                      <td style="text-align: center;font-size: 9pt;vertical-align: top">'.$ga_content[3]["con1"].'</td>
                      <td style="text-align: center;font-size: 9pt;vertical-align: top">'.$ga_content[3]["con2"].'</td>
                      <td style="text-align: center;font-size: 9pt;vertical-align: top">'.$ga_content[3]["con3"].'</td>
                      <td style="text-align: center;font-size: 9pt;vertical-align: top">'.$ga_content[3]["con4"].'</td>
                      </tr>
                      <tr>';
                            
                            $html = $html . '
                      <tr>
                      <td style="text-align: justify;font-size: 9pt;">GA 4<br>Communicators</td>
                      <td style="text-align: left;font-size: 9pt;">'.$ga_content[4]["con"].'</td>
                      <td style="text-align: center;font-size: 9pt;vertical-align: top">'.$ga_content[4]["con1"].'</td>
                      <td style="text-align: center;font-size: 9pt;vertical-align: top">'.$ga_content[4]["con2"].'</td>
                      <td style="text-align: center;font-size: 9pt;vertical-align: top">'.$ga_content[4]["con3"].'</td>
                      <td style="text-align: center;font-size: 9pt;vertical-align: top">'.$ga_content[4]["con4"].'</td>
                      </tr>
                      <tr>';
                            
                            $html = $html . '
                      <tr>
                      <td style="text-align: justify;font-size: 9pt;">GA 5<br>Leaders</td>
                      <td style="text-align: left;font-size: 9pt;">'.$ga_content[5]["con"].'</td>
                      <td style="text-align: center;font-size: 9pt;vertical-align: top">'.$ga_content[5]["con1"].'</td>
                      <td style="text-align: center;font-size: 9pt;vertical-align: top">'.$ga_content[5]["con2"].'</td>
                      <td style="text-align: center;font-size: 9pt;vertical-align: top">'.$ga_content[5]["con3"].'</td>
                      <td style="text-align: center;font-size: 9pt;vertical-align: top">'.$ga_content[5]["con4"].'</td>
                      </tr>';
                            
                            
                            
                            
                            
                        }
                        else {
                            $html = $html . '<br>
                      <table width="100%" style="margin-left: 0mm;" border="1" cellspacing="0" cellpadding="3">
                      <thead>
                      <tr>
                      <td width="25%" style="background-color:#C0C0C0; font-weight: bold; text-align: center">Attribute</td>
                      <td width="*" style="background-color:#C0C0C0; font-weight: bold; text-align: center">Brief Description</td>
                      <td width="15%" style="background-color:#C0C0C0; font-weight: bold; text-align: center">Focus</td>
                      </tr>
                      </thead>
                      <tr>';
                            if ($effectivetermid > '2016/99'){
                                $html = $html . '<td>Knowledge, skills and competence</td>';
                            }//endif
                            else {
                                $html = $html . '<td>Continuous Learning</td>';
                            }//endelse
                            $html = $html . '<td style="text-align: left;">'.$content.'</td>
                      <td style="text-align: center;">'.$content_1.'</td>
                      </tr>
                      <tr>';
                            if ($effectivetermid > '2016/99'){
                                $html = $html . '<td>Critical, creative and enquiring learners</td>';
                            }//endif
                            else {
                                $html = $html . '<td>Self Reliance</td>';
                            }//endelse
                            $html = $html . '
                      <td style="text-align: left;">'.$content_2.'</td>
                      <td style="text-align: center;">'.$content_3.'</td>
                      </tr>
                      <tr>';
                            if ($effectivetermid > '2016/99'){
                                $html = $html . '<td>Capable, flexible and work ready</td>';
                            }//endif
                            else {
                                $html = $html . '<td>Engaged Citizenship</td>';
                            }//endelse
                            $html = $html . '
                      <td style="text-align: left;">'.$content_4.'</td>
                      <td style="text-align: center;">'.$content_5.'</td>
                      </tr>
                      <tr>';
                            if ($effectivetermid > '2016/99'){
                                $html = $html . '<td>Responsible, ethical and engaged citizens</td>';
                            }//endif
                            else {
                                $html = $html . '<td>Social Responsibility</td>';
                            }//endelse
                            $html = $html . '
                      <td style="text-align: left;">'.$content_6.'</td>
                      <td style="text-align: center;">'.$content_7.'</td>
                      </tr>';
                        }
                        $html = $html . '</table>';
                    }//endif
                    else {
                        $html = $html . '</table>';
                        $html = $html . '<br>
              <table width="100%" style="margin-left: 0mm;" border="0" cellspacing="0" cellpadding="3">';
                        $html = $html . '<tr><td>';
                        $html = $html . $content;
                        $html = $html . '</td></td></table>';
                    }//endelse
                    
                    
                    
                    
                    $pdf->writeHTML($html);
                    
                    
                }//endif
                else {
                    
                    if (empty($content)){
                        $content = '&nbsp;';
                    }//endif
                    if (empty($content_1)){
                        $content_1 = '&nbsp;';
                    }//endif
                    if (empty($content_2)){
                        $content_2 = '&nbsp;';
                    }//endif
                    if (empty($content_3)){
                        $content_3 = '&nbsp;';
                    }//endif
                    if (empty($content_4)){
                        $content_4 = '&nbsp;';
                    }//endif
                    if (empty($content_5)){
                        $content_5 = '&nbsp;';
                    }//endif
                    if (empty($content_6)){
                        $content_6 = '&nbsp;';
                    }//endif
                    if (empty($content_7)){
                        $content_7 = '&nbsp;';
                    }//endif
                    echo '<tr><td style="padding-left: 1.2cm">';
                    
                    if($effectivetermid > '2019/99' && empty($subdiscipline["freeformgraduateattribute"]) && (strtotime($stamptime)  > strtotime('2020-02-01'))) {
                        $ga_content=array();
                        if ($effectivetermid > '2019/99' && empty($subdiscipline["freeformgraduateattribute"])){
                            $unitoutdetailkey = '';
                            for($i=1;$i<6;$i++){
                                $ga_sel='ga'.$i;
                                $sql = "select *
                  from unitoutlinedetail
                  where unitoutlinekey = '$unitoutlinekey'
                  and uotype='$ga_sel'
                  order by sequence";
                                
                                $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-061: ".mysqli_error($db));
                                
                                if (mysqli_num_rows($sql_ok) > 0){
                                    $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-062: ".mysqli_error($db));
                                    $unitoutdetailkey = $row["unitoutdetailkey"];
                                    
                                    if (empty($row["content"])){
                                        $ga_content[$i]["con"] = 'Not applicable';
                                    }//endif
                                    else {
                                        $ga_content[$i]["con"]  = stripslashes($row["content"]);
                                    }//endelse
                                    
                                    
                                    if (empty($row["content_1"])){
                                        $ga_content[$i]["con1"]  = 'Not applicable';
                                    }//endif
                                    
                                    else {
                                        $ga_content[$i]["con1"] = stripslashes($row["content_1"]);
                                    }//endelse
                                    
                                    if (empty($row["content_2"])){
                                        $ga_content[$i]["con2"]= 'Not applicable';
                                    }//endif
                                    else {
                                        $ga_content[$i]["con2"] = stripslashes($row["content_2"]);
                                    }//endelse
                                    
                                    if (empty($row["content_3"])){
                                        $ga_content[$i]["con3"] = 'Not applicable';
                                    }//endif
                                    else {
                                        $ga_content[$i]["con3"] = stripslashes($row["content_3"]);
                                    }//endelse
                                    
                                    if (empty($row["content_4"])){
                                        $ga_content[$i]["con4"] = 'Not applicable';
                                    }//endif
                                    else {
                                        $ga_content[$i]["con4"] = stripslashes($row["content_4"]);
                                    }//endelse
                                }//end if
                                
                            }//end for
                        }//endif
                        echo '<table width="100%" border="0" cellspacing="0" cellpadding="3">
              <tr>
              <td>';
                        echo 'The Federation University ' . $_SESSION[$_GET["trid"] . "sysabbreviation"];
                        echo ' graduate attributes (GA) are entrenched in the Higher Education Graduate Attributes Policy (LT1228). ';
                        echo  $_SESSION[$_GET["trid"] . "sysabbreviation"] . ' graduates develop these graduate attributes through their engagement in ';
                        echo 'explicit learning and teaching and assessment tasks that are embedded in all ';
                        echo  $_SESSION[$_GET["trid"] . "sysabbreviation"] . ' programs. Graduate attribute attainment typically follows and incremental development';
                        echo 'process mapped through program progression. One or more graduate attributes must be evident in the specified learning outcomes and assessment for each FedUni course, and all attributes must be directly assessed in each program.';
                        echo '</td>
              </tr>
              </table>
              <br>';
                        
                    }
                    else if ($effectivetermid > '2016/99'){
                        echo '<table width="100%" border="0" cellspacing="0" cellpadding="3">
              <tr>
              <td>';
                        echo $_SESSION[$_GET["trid"] . "sysabbreviation"];
                        echo ' graduate attributes statement. To have graduates with knowledge, skills and competence that enable them to stand out as critical, creative and enquiring learners who are capable, flexible and work ready, and responsible, ethical and engaged citizens.</td>
              </tr>
              </table>
              <br>';
                    }//endif
                    
                    if($effectivetermid > '2019/99' && empty($subdiscipline["freeformgraduateattribute"]) && (strtotime($stamptime) > strtotime('2020-02-01'))) {
                        echo '<table width="70%" border="1" cellspacing="0" cellpadding="4">';
                        echo '<tr>';
                        echo '<td colspan ="2" width="50%" bgcolor="#C0C0C0" align="center"><b>Graduate attribute and descriptor</b></td>';
                        echo '<td colspan ="4" width="50%" bgcolor="#C0C0C0" align="center"><b>Development and acquisition of GAs in the course</b></td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td colspan ="2"  width="50%" bgcolor="#C0C0C0" align="center"><b>&nbsp;</b></td>';
                        echo '<td width="13%" bgcolor="#C0C0C0" valign="top" align="left"><b>Learning outcomes (KSA)</b></td>';
                        echo '<td width="13%" bgcolor="#C0C0C0" valign="top" align="left"><b>Code</b><br> A: Direct<br> B: Indirect <br>N/A. Not Assessed</td>';
                        echo '<td width="13%" bgcolor="#C0C0C0" valign="top" align="left"><b>Assessment task (AT#)</b></td>';
                        echo '<td width="13%" bgcolor="#C0C0C0" valign="top" align="left"><b>Code:</b> <br> A. Certain <br> B. Likely <br> C. Possible <br> N/A. Not</td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td ">GA 1 Thinkers</td>';
                        echo '<td ">'.$ga_content[1]["con"].'</td>';
                        echo '<td ">'.$ga_content[1]["con1"].'</td>';
                        echo '<td ">'.$ga_content[1]["con2"].'</td>';
                        echo '<td ">'.$ga_content[1]["con3"].'</td>';
                        echo '<td ">'.$ga_content[1]["con4"].'</td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td ">GA 2 Innovators</td>';
                        echo '<td ">'.$ga_content[2]["con"].'</td>';
                        echo '<td ">'.$ga_content[2]["con1"].'</td>';
                        echo '<td ">'.$ga_content[2]["con2"].'</td>';
                        echo '<td ">'.$ga_content[2]["con3"].'</td>';
                        echo '<td ">'.$ga_content[2]["con4"].'</td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td ">GA 3 Citizens</td>';
                        echo '<td ">'.$ga_content[3]["con"].'</td>';
                        echo '<td ">'.$ga_content[3]["con1"].'</td>';
                        echo '<td ">'.$ga_content[3]["con2"].'</td>';
                        echo '<td ">'.$ga_content[3]["con3"].'</td>';
                        echo '<td ">'.$ga_content[3]["con4"].'</td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td>GA 4 Communicators</td>';
                        echo '<td ">'.$ga_content[4]["con"].'</td>';
                        echo '<td ">'.$ga_content[4]["con1"].'</td>';
                        echo '<td ">'.$ga_content[4]["con2"].'</td>';
                        echo '<td ">'.$ga_content[4]["con3"].'</td>';
                        echo '<td ">'.$ga_content[4]["con4"].'</td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td>GA 5 Leaders</td>';
                        echo '<td ">'.$ga_content[5]["con"].'</td>';
                        echo '<td ">'.$ga_content[5]["con1"].'</td>';
                        echo '<td ">'.$ga_content[5]["con2"].'</td>';
                        echo '<td ">'.$ga_content[5]["con3"].'</td>';
                        echo '<td ">'.$ga_content[5]["con4"].'</td>';
                        echo '</tr>';
                        echo '</table>';
                        
                    }
                    else if (empty($subdiscipline["freeformgraduateattribute"])){
                        echo '<table width="100%" border="1" cellspacing="0" cellpadding="4">';
                        
                        echo '<tr>';
                        echo '<td width="20%" bgcolor="#C0C0C0" align="center"><b>Attribute</b></td>';
                        echo '<td width="50%" bgcolor="#C0C0C0" align="center"><b>Brief Description</b></td>';
                        echo '<td width="15%" bgcolor="#C0C0C0" align="center"><b>Focus</b></td>';
                        echo '</tr>';
                        
                        //attribute 1
                        echo '<tr>';
                        if ($effectivetermid > '2016/99'){
                            echo '<td>Knowledge, skills and competence</td>';
                        }//endif
                        else {
                            echo '<td>Continuous Learning</td>';
                        }//endelse
                        echo '<td>' . $content . '</td>';
                        echo '<td align="center">' . $content_1 . '</td>';
                        echo '</tr>';
                        //attribute 2
                        echo '<tr>';
                        if ($effectivetermid > '2016/99'){
                            echo '<td>Critical, creative and enquiring learners</td>';
                        }//endif
                        else {
                            echo '<td>Self Reliance</td>';
                        }//endelse
                        echo '<td>' . $content_2 . '</td>';
                        echo '<td align="center">' . $content_3 . '</td>';
                        echo '</tr>';
                        //attribute 3
                        echo '<tr>';
                        if ($effectivetermid > '2016/99'){
                            echo '<td>Capable, flexible and work ready</td>';
                        }//endif
                        else {
                            echo '<td>Engaged Citizenship</td>';
                        }//endelse
                        echo '<td>' . $content_4 . '</td>';
                        echo '<td align="center">' . $content_5 . '</td>';
                        echo '</tr>';
                        //attribute 4
                        echo '<tr>';
                        if ($effectivetermid > '2016/99'){
                            echo '<td>Responsible, ethical and engaged citizens</td>';
                        }//endif
                        else {
                            echo '<td>Social Responsibility</td>';
                        }//endelse
                        echo '<td>' . $content_6 . '</td>';
                        echo '<td align="center">' . $content_7 . '</td>';
                        echo '</tr>';
                        
                        echo '</table>';
                    }//endif
                    else {
                        echo $content;
                    }//endelse
                    echo '</td></tr>';
                }//endelse
            
        }//endif
        //END OF VALUES=========================================================================================
        
        
        
        //Assessment ========================================================================================================
        if (isset($_POST["btnPDF"])){
            $pdf->SetX(15);
            $pdf->SetFont('','B',10);
            $pdf->Cell('',10,'Learning Tasks and Assessment',0,1,'L',0);
        }//endif
        else {
            echo '<tr><td style="padding-left: 0.6cm"><b>Learning Tasks and Assessment:</b></td></tr>';
        }//endelse
        
        //Assessment - General
        $sql = "select *
            from unitoutlinedetail
            where unitoutlinekey = '$unitoutlinekey'
            and uotype='assgen'";
        
        $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-092: ".mysqli_error($db));
        
        if (mysqli_num_rows($sql_ok)){
            $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-093: ".mysqli_error($db));
            
            $temp = stripslashes($row["content"]);
            
            if (isset($_POST["btnPDF"])){
                $pdf->SetX(15);
                
                $temp = convert_for_html($temp,_tablesettingON,_striplinebreakOFF,_stripAtagOFF,_stripalllinefeedOFF);
                
                $html = '<div style="margin-left: 0mm; text-align: left;">'.$temp.'</div>';
                $pdf->writeHTML($html);
                $pdf->Ln();
            }//endif
            else {
                echo '<tr><td style="padding-left: 1.2cm; text-align: left">'. $temp .'</td></tr>';
            }//endelse
        }//endif
        
        //Learning Tasks and Assessment - Additional
        $sql = "select *
            from unitdescriptiondetail
            where locationid = '$locationid'
            and termid = '$termid'
            and unitid = '$unitid'
            and udtype='lrnadd'";
        
        $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-094: ".mysqli_error($db));
        
        if (mysqli_num_rows($sql_ok)){
            $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-095: ".mysqli_error($db));
            
            $unitdescdetailkey = $row["unitdescdetailkey"];
            if (!isset($_POST["btnPDF"])){
                if ($allowupdate && ($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O')) && $p->update_allowed('')))){
                    echo '<tr><td style="Padding-left: 0.6cm"><a href="javascript:unitdescriptionupdate('. $unitdescdetailkey . ',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'lrnadd\',5,0,1)">Additional Comments:</a></td></tr>';
                }//endif
                else {
                    echo '<tr><td style="padding-left: 1.2cm"><b>Additional Comments:</b></td></tr>';
                }//endelse
            }//endelse
            $temp = stripslashes($row["content"]);
            
            if (isset($_POST["btnPDF"])){
                $pdf->SetFont('','',10);
                $pdf->SetX(15);
                
                $temp = convert_for_html($temp,_tablesettingON,_striplinebreakOFF,_stripAtagOFF,_stripalllinefeedOFF);
                
                $html = '<div style="margin-left: 0mm; text-align: left;">'.$temp.'</div>';
                $pdf->writeHTML($html);
            }//endif
            else {
                echo '<tr><td style="padding-left: 1.2cm; text-align: left">'. $temp .'</td></tr>';
            }//endelse
        }//endif
        else {
            if (!isset($_POST["btnPDF"])){
                if ($allowupdate && ($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O')) && $p->update_allowed('')))){
                    echo '<tr><td style="padding-left: 1.2cm"><a href="javascript:unitdescriptionupdate(\'\',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'lrnadd\',5,0,1)">Additional Comments:</a></td></tr>';
                }//endif
            }//endif
        }//endelse
        
        //Assessment extract from outline
        $sql = "select *
            from unitoutlinedetail
            where unitoutlinekey = '$unitoutlinekey'
            and uotype='assess'
            and ifnull(content_3,'') > ''";
        
        $content3sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-096: ".mysqli_error($db));
        
        $sql = "select *
            from unitoutlinedetail
            where unitoutlinekey = '$unitoutlinekey'
            and uotype='assess'
            order by sequence";
        
        $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-097: ".mysqli_error($db));
        
        if (mysqli_num_rows($content3sql_ok) > 0){
            if (!isset($_POST["btnPDF"])){
                echo '<tr><td style="padding-left: 1.2cm"><span class="boldgray">Course Outline Assessment:</span></td></tr>';
                echo '<tr><td style="padding-left: 1.8cm">';
                echo '<table width="100%" border="1" cellpadding="6" cellspacing="0">';
                echo '<tr><td width="20%" bgcolor="#C0C0C0" align="center"><span class="boldgray">Learning Outcomes Assessed</span></td>';
                echo '<td width="35%" bgcolor="#C0C0C0" align="center"><span class="boldgray">Learning Task</span></td>';
                echo '<td width="30%" bgcolor="#C0C0C0" align="center"><span class="boldgray">Assessment</span></td>';
                echo '<td width="15%" bgcolor="#C0C0C0" align="center"><span class="boldgray">Weighting</span></td></tr>';
            }//endif
            else {
                
                $html = '
            <table width="100%" style="margin-left: 0mm;" border="1" cellspacing="0" cellpadding="3">
            <thead>
            <tr>
            <td style="background-color:#C0C0C0; font-weight: bold; text-align: center">Learning Outcomes Assessed</td>
            <td style="background-color:#C0C0C0; font-weight: bold; text-align: center">Assessment Task</td>
            <td style="background-color:#C0C0C0; font-weight: bold; text-align: center">Assessment Type</td>
            </tr>
            </thead>';
                
            }//endelse
        }//endif
        else {
            if (!isset($_POST["btnPDF"])){
                echo '<tr><td style="padding-left: 1.2cm"><span class="boldgray">Course Outline Assessment:</span></td></tr>';
                echo '<tr><td style="padding-left: 1.8cm">';
                echo '<table width="100%" border="1" cellpadding="6" cellspacing="0">';
                echo '<tr><td width="45%" bgcolor="#C0C0C0" align="center"><span class="boldgray">Learning Task</span></td>';
                echo '<td width="50%" bgcolor="#C0C0C0" align="center"><span class="boldgray">Assessment</span></td>';
                echo '<td width="15%" bgcolor="#C0C0C0" align="center"><span class="boldgray">Weighting</span></td></tr>';
            }//endif
            else {
                
                $html = '
            <table width="100%" style="margin-left: 0mm;" border="1" cellspacing="0" cellpadding="3">
            <thead>
            <tr>
            <td style="background-color:#C0C0C0; font-weight: bold; text-align: center">Assessment Task</td>
            <td style="background-color:#C0C0C0; font-weight: bold; text-align: center">Assessment Type</td>
            <td style="background-color:#C0C0C0; font-weight: bold; text-align: center">Weighting</td>
            </tr>
            </thead>';
            }//endelse
        }//endelse
        
        for ($i=0; $i < mysqli_num_rows($sql_ok); $i++){
            $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-098: ".mysqli_error($db));
            
            $unitoutdetailkey = $row["unitoutdetailkey"];
            $temp_0 = stripslashes($row["content"]);
            $temp_1 = stripslashes($row["content_1"]);
            $temp_2 = stripslashes($row["content_2"]);
            $temp_3 = stripslashes($row["content_3"]);
            
            if (!isset($_POST["btnPDF"])){
                if (empty($temp_3)){
                    $temp_3 = '&nbsp;';
                }//endif
                if (empty($temp_0)){
                    $temp_0 = '&nbsp;';
                }//endif
                if (empty($temp_1)){
                    $temp_1 = '&nbsp;';
                }//endif
                if (empty($temp_2)){
                    $temp_2 = '&nbsp;';
                }//endif
                if (mysqli_num_rows($content3sql_ok) > 0){
                    echo '<tr>' . '<td style="vertical-align: center">'. $temp_3 .'</td><td style="vertical-align: top;"><span class="gray">'. $temp_0 .'</span><td style="vertical-align: top"><span class="gray">'. $temp_1 .'</span></td><td width="10%" align="center" style="vertical-align: top"><span class="gray">'. $temp_2 .'</span></td></tr>';
                }//endif
                else {
                    echo '<tr>' . '<td style="vertical-align: top;"><span class="gray">'. $temp_0 .'</span><td style="vertical-align: top"><span class="gray">'. $temp_1 .'</span></td><td width="10%" align="center" style="vertical-align: top"><span class="gray">'. $temp_2 .'</span></td></tr>';
                }//endelse
            }//endif
            else {
                if (mysqli_num_rows($content3sql_ok) > 0){
                    
                    $html = $html . '
            <tr>
            <td>'.$temp_3.'</td>
            <td>'.$temp_0.'</td>
            <td>'.$temp_1.'</td>
            </tr>';
                    
                }//endif
                
            }//endelse
        }//endfor
        
        if (!isset($_POST["btnPDF"])){
            echo '</table>';
        }//endif
        elseif (mysqli_num_rows($content3sql_ok) > 0){
            
            $pdf->SetX(15);
            $html = $html . '</table>';
            $pdf->writeHTML($html);
            $pdf->Ln();
        }//endelse
        
        //Guidelines
        if (!isset($_POST["btnPDF"]) && ($p->admin_access_allowed('SZ') || in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O','R')))){
            
            $sql = "select udg.*, udgt.`name`, udgt.unitdescriptionguidelinetypeid
              from unitdescriptionsubdisciplineguideline as udsg
                inner join unitdescriptionguideline as udg
                  on udg.udsubdisciplineguidelineid = udsg.udsubdisciplineguidelineid
                inner join unitdescriptionguidelinetype as udgt
                  on udgt.unitdescriptionguidelinetypeid = udsg.unitdescriptionguidelinetypeid
              where udsg.subdisciplineid = 9999
              and udg.effectivetermid <= '$termid'
              and not exists (select udg1.*
                              from unitdescriptionsubdisciplineguideline as udsg1
                                inner join unitdescriptionguideline as udg1
                                  on udg1.udsubdisciplineguidelineid = udsg1.udsubdisciplineguidelineid
                              where udsg1.unitdescriptionguidelinetypeid = udsg.unitdescriptionguidelinetypeid
                              and udsg1.subdisciplineid = '$unitsubdisciplineid'
                              and udg1.effectivetermid >= '$termid'
                              and ifnull(udg1.content,'') <> '')";
            
            $gensql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-099: ".mysqli_error($db));
            
            $sql = "select udg.*, udgt.`name`, udgt.unitdescriptionguidelinetypeid
              from unit as u
                inner join unitdescriptionsubdisciplineguideline as udsg
                  on udsg.subdisciplineid = u.subdisciplineid
                inner join unitdescriptionguideline as udg
                  on udg.udsubdisciplineguidelineid = udsg.udsubdisciplineguidelineid
                inner join unitdescriptionguidelinetype as udgt
                  on udgt.unitdescriptionguidelinetypeid = udsg.unitdescriptionguidelinetypeid
              where u.unitid = '$unitid'
              and effectivetermid = (select max(udg1.effectivetermid)
                                     from unitdescriptionguideline as udg1
                                     where udg1.udsubdisciplineguidelineid = udg.udsubdisciplineguidelineid
                                     and udg1.effectivetermid <= '$termid')";
            
            $subsql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-100: ".mysqli_error($db));
            
            if (mysqli_num_rows($gensql_ok) > 0 || mysqli_num_rows($subsql_ok) > 0){
                echo '<br><span class="boldgray">Guidelines:</span><br><br>';
            }//endif
            
            $genericguidelinearray = array();
            for ($i=0; $i < mysqli_num_rows($gensql_ok); $i++){
                $row = mysqli_fetch_array($gensql_ok) or die(basename(__FILE__,'.php')."-101: ".mysqli_error($db));
                
                $unitdescriptionguidelinetypeid = $row["unitdescriptionguidelinetypeid"];
                
                array_push($genericguidelinearray,$unitdescriptionguidelinetypeid);
                
                $temp = stripslashes($row["content"]);
                $name = stripslashes($row["name"]);
                
                echo str_repeat('&nbsp;',6);
                echo '<span class="boldgray">' . $name . ':</span><br><br>';
                
                echo '<table width="100%"><tr><td style="padding-left: 1.2cm; vertical-align: top" width="1%"><span class="boldgray">' . $temp .'</span></td></tr></table><br>';
                
            }//endfor
            
            for ($i=0; $i < mysqli_num_rows($subsql_ok); $i++){
                $row = mysqli_fetch_array($subsql_ok) or die(basename(__FILE__,'.php')."-102: ".mysqli_error($db));
                
                $unitdescriptionguidelinetypeid = $row["unitdescriptionguidelinetypeid"];
                
                if (!in_array($unitdescriptionguidelinetypeid, $genericguidelinearray)){
                    $temp = stripslashes($row["content"]);
                    $name = stripslashes($row["name"]);
                    
                    echo str_repeat('&nbsp;',6);
                    echo '<span class="boldgray">' . $name . ':</span><br><br>';
                    
                    echo '<table width="100%"><tr><td style="padding-left: 1.2cm; vertical-align: top" width="1%"><span class="boldgray">' . $temp .'</span></td></tr></table><br>';
                    
                }//endif
                
            }//endfor
            
            echo '</td></tr>';
            
        }//endif
        
        //Unit tasks
        $sql = "select *
            from unittask
            where locationid = '$locationid'
            and termid = '$termid'
            and unitid = '$unitid'
            order by taskid";
        
        $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-103: ".mysqli_error($db));
        
        if (isset($_POST["btnPDF"])){
            
            $pdf->SetFont('','',10);
            $pdf->SetX(15);
            $pdf->MultiCell('',5,"The following tasks will be graded.",0,'J',0);
            $pdf->Ln();
            
            $html = '
          <table width="100%" style="margin-left: 0mm;" border="1" cellspacing="0" cellpadding="3">
          <thead>
          <tr>';
            
            if (empty($noabrule)){
                if ($deliverymode=='R'){
                    
                    $html = $html . '
              <td width="30%" style="background-color:#C0C0C0; font-weight: bold; text-align: center">Task</td>
              <td style="background-color:#C0C0C0; font-weight: bold; text-align: center">Released</td>
              ';
                }//endif
                else {
                    
                    $html = $html . '
              <td width="30%" style="background-color:#C0C0C0; font-weight: bold; text-align: center">Task</td>
              ';
                }//endelse
                
                if (!$unitprofessionalengagement){
                    
                    $html = $html . '
              <td style="background-color:#C0C0C0; font-weight: bold; text-align: center">Due</td>';
                }//endif
                
                $html = $html . '
            <td style="background-color:#C0C0C0; font-weight: bold; text-align: center">Weighting</td>
            <td style="background-color:#C0C0C0; font-weight: bold; text-align: center">Type</td>
            ';
                
                $html = $html . '
            </tr></thead>';
            }//endif
            else {
                if ($deliverymode=='R'){
                    
                    $html = $html . '
              <td width="30%" style="background-color:#C0C0C0; font-weight: bold; text-align: center">Task</td>
              <td style="background-color:#C0C0C0; font-weight: bold; text-align: center">Released</td>
              ';
                }//endif
                else {
                    
                    $html = $html . '
              <td width="30%" style="background-color:#C0C0C0; font-weight: bold; text-align: center">Task</td>
              ';
                }//endelse
                
                if (!$unitprofessionalengagement){
                    $html = $html . '
              <td style="background-color:#C0C0C0; font-weight: bold; text-align: center">Due</td>';
                }//endif
                
                
                $html = $html . '
            <td style="background-color:#C0C0C0; font-weight: bold; text-align: center">Weighting</td>
            ';
                
                $html = $html . '
            </tr></thead>';
            }//endelse
            
        }//endif
        else {
            if ($allowupdate && ($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O')) && $p->update_allowed('')))){
                echo '<tr><td style="padding-left: 1.2cm"><a href="javascript:tasks(\''.$locationid .'\',\'' . $termid . '\',\'' . $unitid. '\')">Assessment:</a></td></tr>';
            }//endif
            else {
                echo '<tr><td style="padding-left: 1.2cm"><b>Assessment:</b></td></tr>';
            }//endelse
            
            echo '<tr><td style="padding-left: 1.8cm; text-align: left">The following tasks will be graded.</td></tr>';
            
            echo '<td style="padding-left: 1.8cm">';
            echo '<table width="100%" border="1" cellpadding="6" cellspacing="0"><tr>';
            if (empty($noabrule)){
                echo '<td width="*" bgcolor="#C0C0C0" align="center"><b>Task</b></td>';
            }//endif
            else {
                echo '<td width="*" bgcolor="#C0C0C0" align="center"><b>Task</b></td>';
            }//endelse
            
            if ($deliverymode=='R'){
                echo '<td width="15%" bgcolor="#C0C0C0" align="center"><b>Released</b></td>';
            }//endif
            if (!$unitprofessionalengagement){
                echo '<td width="25%" bgcolor="#C0C0C0" align="center"><b>Due</b></td>';
            }//endif
            echo '<td width="10%" bgcolor="#C0C0C0" align="center"><b>Weighting</b></td>';
            if (empty($noabrule)){
                echo '<td width="6%" bgcolor="#C0C0C0" align="center"><b>Type</b></td>';
            }//endif
            
            if ($_SESSION[$_GET["trid"] . "moderationtype"]!=='U'){
                echo '<td width="1%" bgcolor="#C0C0C0" align="center">&nbsp;</td>';
            }//endif
            
            echo '</tr>';
            
        }//endelse
        
        for ($i=0; $i < mysqli_num_rows($sql_ok); $i++){
            $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-104: ".mysqli_error($db));
            
            $unitdescdetailkey = $row["unitdescdetailkey"];
            $taskid = $row["taskid"];
            $description = $row["description"];
            $given = $row["given"];
            $due = $row["due"];
            $splitweek = $row["splitweek"];
            $dueday = $row["dueday"];
            $duetime = $row["duetime"];
            $weight = $row["weight"];
            $type = $row["type"];
            $samplerequired = $row["samplerequired"];
            
            //Check if displaying subtasks
            $displaysubtask = false;
            
            $sql = "select *
              from subtask
              where locationid = '$locationid'
              and termid = '$termid'
              and unitid = '$unitid'
              and taskid = '$taskid'";
            
            $subtasksql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-105: ".mysqli_error($db));
            
            $subtask = array();
            
            for ($subtaski=0; $subtaski < mysqli_num_rows($subtasksql_ok); $subtaski++){
                $subtaskrow = mysqli_fetch_array($subtasksql_ok) or die(basename(__FILE__,'.php')."-106: ".mysqli_error($db));
                
                if (empty($subtaskrow["unitdescription"])){
                    $displaysubtask = true;
                    $subtask["$subtaski"]["subtaskdescription"] = $subtaskrow["description"];
                    $subtask["$subtaski"]["subtaskdue"] = $subtaskrow["due"];
                    $subtask["$subtaski"]["subtasksplitweek"] = $subtaskrow["splitweek"];
                    
                    if ($subtask["$subtaski"]["subtaskdue"] == 'Other'){
                        $subtask["$subtaski"]["subtaskdatedue"] = date('D, M j, Y - H:i',strtotime($subtaskrow["duetime"]));
                    }//endif
                    else {
                        if (empty($subtaskrow["dueday"])){
                            $subtask["$subtaski"]["subtaskdueday"] = '4';
                        }//endif
                        else {
                            $subtask["$subtaski"]["subtaskdueday"] = $subtaskrow["dueday"];
                        }//endelse
                        
                        if (empty($subtask["$subtaski"]["subtaskdueday"])){
                            $subtask["$subtaski"]["subtaskdueday"] = '5';
                        }//endif
                        
                        if (empty($subtaskrow["duetime"])){
                            $subtask["$subtaski"]["subtaskduetime"] = '16:00';
                        }//endif
                        else {
                            $subtask["$subtaski"]["subtaskduetime"] = $subtaskrow["duetime"];
                        }//endelse
                        
                        $subtask["$subtaski"]["subtaskduedate"] = '';
                        if (is_numeric($subtask["$subtaski"]["subtaskdueday"])){
                            $subtask["$subtaski"]["subtaskduedate"] = getDueDate($locationid, $termid, $taskid, $subtask["$subtaski"]["subtaskdue"],$subtask["$subtaski"]["subtasksplitweek"], $subtask["$subtaski"]["subtaskdueday"], $subtask["$subtaski"]["subtaskduetime"]);
                        }//endif
                        
                        if ($subtask["$subtaski"]["subtaskduedate"] && !empty($subtask["$subtaski"]["subtaskdue"]) && is_numeric($subtask["$subtaski"]["subtaskdue"])){
                            $subtask["$subtaski"]["subtaskdatedue"] = date('D, M j, Y - H:i',$subtask["$subtaski"]["subtaskduedate"]);
                        }//endif
                        else {
                            $subtask["$subtaski"]["subtaskdatedue"] = $subtask["$subtaski"]["subtaskdue"];
                            if ($subtask["$subtaski"]["subtaskdueday"] == 'a'){
                                $subtask["$subtaski"]["subtaskdatedue"] = 'In timetabled lecture';
                            }//endif
                            if ($subtask["$subtaski"]["subtaskdueday"] == 'b'){
                                $subtask["$subtaski"]["subtaskdatedue"] = 'In timetabled laboratory';
                            }//endif
                            if ($subtask["$subtaski"]["subtaskdueday"] == 'c'){
                                $subtask["$subtaski"]["subtaskdatedue"] = 'In timetabled tutorial';
                            }//endif
                        }//endelse
                    }//endelse
                    
                }//endif
                
            }//endfor
            
            if ($due == 'Other'){
                $datedue = date('D, M j, Y - H:i',strtotime($duetime));
            }//endif
            else if (($due == 'End of exam') && ($termid>='2020/17')){
                $datedue = 'End of final test period';
            }//endif
            else {
                if (empty($row["dueday"])){
                    $dueday = '4';
                }//endif
                else {
                    $dueday = $row["dueday"];
                }//endelse
                
                if (empty($dueday)){
                    $dueday = '5';
                }//endif
                
                if (empty($row["duetime"])){
                    $duetime = '16:00';
                }//endif
                else {
                    $duetime = $row["duetime"];
                }//endelse
                
                $duedate = '';
                if (is_numeric($dueday)){
                    $duedate = getDueDate($locationid, $termid, $taskid, $due, $splitweek, $dueday, $duetime);
                }//endif
                
                if ($duedate && !empty($due) && is_numeric($due)){
                    $datedue = date('D, M j, Y - H:i',$duedate);
                }//endif
                else {
                    $datedue = $due;
                    if ($dueday=='a'){
                        $datedue = 'In timetabled lecture';
                    }//endif
                    if ($dueday=='b'){
                        $datedue = 'In timetabled laboratory';
                    }//endif
                    if ($dueday=='c'){
                        $datedue = 'In timetabled tutorial';
                    }//endif
                }//endelse
            }//endelse
            
            if (empty($given)){
                $given = '&nbsp;';
            }//endif
            else if (($given == 'End of exam' || $given == 'Exam period') && ($termid>='2020/17')){
                $given = 'Final test period';
            }//endif
            else {
                if (is_numeric($given)){
                    $given = 'Week ' . $given;
                }//endif
            }//endelse
            
            if (empty($datedue)){
                $datedue = '&nbsp;';
            }//endif
            
            if (empty($weight)){
                $weight = '&nbsp;';
            }//endif
            
            $pct='';
            if ($weight > 0){
                $pct = '%';
            }//endif
            
            if (isset($_POST["btnPDF"])){
                
                $given = str_replace('&nbsp;',' ',$given);
                $datedue = str_replace('&nbsp;',' ',$datedue);
                
                if ($weight=='&nbsp;'){
                    $weight = ' ';
                }//endif
                else {
                    $weight = $weight . '%';
                }//endelse
                
                $pdf->SetFont('','',10);
                $pdf->SetX(25);
                
                if ($datedue !== $due && $due !== 'Other' && $due !='End of exam'){
                    $datedue = $datedue . " (Week " . $due . ")";
                }//endif
                if (is_numeric($datedue)){
                    $datedue = 'Week ' . $datedue;
                }//endif
                
                if (empty($noabrule)){
                    if ($displaysubtask){
                        
                        $datedue = '';
                        reset($subtask);
                        while (list($key,$value) = each($subtask)){
                            
                            $description = $description . "<br>     " . $value["subtaskdescription"];
                            
                            $datedue = $datedue . "<br>" . $value["subtaskdatedue"];
                            if ($value["subtaskdatedue"] !== $value["subtaskdue"]  && $value["subtaskdue"] !== 'Other' && $value["subtaskdatedue"] !==' '){
                                $datedue = $datedue . ' <span class="tiny">(Week ' . $value["subtaskdue"] . ')</span>';
                            }//endif
                            if (is_numeric($value["subtaskdatedue"])){
                                $datedue = 'Week ' . $value["subtaskdatedue"];
                            }//endif
                            
                        }//endwhile
                        
                    }//endif
                    
                    if ($deliverymode=='R'){
                        
                        $html = $html . '
                    <tr>
                    <td>'.$description.'</td>
                    <td style="text-align: center;">'.$given.'</td>';
                                
                                if (!$unitprofessionalengagement){
                                    $html = $html . '<td style="text-align: center;">'.$datedue.'</td>';
                                }//endif
                                
                                $html = $html . '<td style="text-align: center;">'.$weight.'</td>
                    <td style="text-align: center;">'.$type.'</td>
                    </tr>';
                    }//endif
                    else {
                        
                        $html = $html . '
            <tr>
            <td>'.$description.'</td>';
                        
                        if (!$unitprofessionalengagement){
                            $html = $html . '
              <td style="text-align: center;">'.$datedue.'</td>';
                        }//endif
                        
                        $html = $html . '
            <td style="text-align: center;">'.$weight.'</td>
            <td style="text-align: center;">'.$type.'</td>
            </tr>';
                    }//endelse
                    
                }//endif
                else {
                    if ($displaysubtask){
                        
                        $datedue = '';
                        reset($subtask);
                        while (list($key,$value) = each($subtask)){
                            
                            $description = $description . "<br>     " . $value["subtaskdescription"];
                            
                            $datedue = $datedue . "<br>" . $value["subtaskdatedue"];
                            if ($value["subtaskdatedue"] !== $value["subtaskdue"] && $value["subtaskdue"] !=='Other' && $value["subtaskdatedue"] !==' '){
                                $datedue = $datedue . ' <span class="tiny">(Week ' . $value["subtaskdue"] . ')</span>';
                            }//endif
                            if (is_numeric($value["subtaskdatedue"])){
                                $datedue = 'Week ' . $value["subtaskdatedue"];
                            }//endif
                            
                        }//endwhile
                        
                    }//endif
                    
                    if ($deliverymode=='R'){
                        
                        $html = $html . '
            <tr>
            <td>'.$description.'</td>
            <td style="text-align: center;">'.$given.'</td>';
                        if (!$unitprofessionalengagement){
                            $html = $html . '<td style="text-align: center;">'.$datedue.'</td>';
                        }//endif
                        
                        $html = $html . '<td style="text-align: center;">'.$weight.'</td>
            </tr>';
                    }//endif
                    else {
                        
                        $html = $html . '
            <tr>
            <td>'.$description.'</td>
            <td style="text-align: center;">'.$datedue.'</td>
            <td style="text-align: center;">'.$weight.'</td>
            </tr>';
                    }//endelse
                    
                }//endelse
                
            }//endif
            else {
                if ($datedue !== $due && $datedue !=='&nbsp;' && $due !== 'Other' && $due != 'End of exam'){
                    $datedue = $datedue . ' <br><span class="tiny">(Week ' . $due . ')</span>';
                }//endif
                if (is_numeric($datedue)){
                    $datedue = 'Week ' . $datedue;
                }//endif
                if ($due == 'Other'){
                    $datedue = $datedue . ' <br><span class="tiny">(Other)</span>';
                }//endif
                
                if ($displaysubtask){
                    echo '<tr></td><td>';
                    
                    echo $description;
                    
                    reset($subtask);
                    while (list($key,$value) = each($subtask)){
                        
                        echo '<br><br>';
                        echo str_repeat('&nbsp;',3);
                        echo '<span class="maroon">' . $value["subtaskdescription"] . '</span>';
                        
                    }//endwhile
                    
                    if ($deliverymode=='R'){
                        echo '</td><td align="center"; VALIGN="top">'. $given .'</td><td align="center">';
                    }//endif
                    else {
                        echo '</td><td align="center">';
                    }//endif
                    
                    reset($subtask);
                    while (list($key,$value) = each($subtask)){
                        
                        if ($value["subtaskdatedue"] !== $value["subtaskdue"] && $value["subtaskdatedue"] !=='&nbsp;' && $value["subtaskdue"] !=='Other'){
                            $value["subtaskdatedue"] = $value["subtaskdatedue"] . ' <br><span class="tiny">(Week ' . $value["subtaskdue"] . ')</span>';
                        }//endif
                        if (is_numeric($value["subtaskdatedue"])){
                            $value["subtaskdatedue"] = 'Week ' . $value["subtaskdatedue"];
                        }//endif
                        if ($value["subtaskdue"] =='Other'){
                            $value["subtaskdatedue"] = $value["subtaskdatedue"] . ' <br><span class="tiny">(Other)</span>';
                        }//endif
                        
                        if ($deliverymode=='R'){
                            echo '<br><br>';
                            echo str_repeat('&nbsp;',3);
                            echo '<span class="maroon">' . $value["subtaskdatedue"] . '</span>';
                        }//endif
                        else {
                            echo '<br><br>';
                            echo str_repeat('&nbsp;',3);
                            echo '<span class="maroon">' . $value["subtaskdatedue"] . '</span>';
                        }//endelse
                        
                    }//endwhile
                    
                    echo '</td><td align="center"; VALIGN="top">'. $weight . $pct;
                    
                    if (empty($noabrule)){
                        echo '</td><td align="center"; VALIGN="top">'. $type;
                    }//endif
                    
                    if ($_SESSION[$_GET["trid"] . "moderationtype"]!=='U'){
                        if (!empty($samplerequired)){
                            echo '</td><td align="center">'. '*';
                        }//endif
                        else {
                            echo '</td><td align="center">&nbsp;';
                        }//endelse
                    }//endif
                    echo '</td></tr>';
                }//endif
                else {
                    echo '<tr></td><td>';
                    if ($deliverymode=='R'){
                        if (!$unitprofessionalengagement){
                            echo $description . '</td><td align="center">'. $given .'</td><td align="center">'. $datedue .'</td><td align="center">'. $weight . $pct;
                        }//endif
                        else {
                            echo $description . '</td><td align="center">'. $given .'</td><td align="center">'. $weight . $pct;
                        }//endelse
                    }//endif
                    else {
                        if (!$unitprofessionalengagement){
                            echo $description . '</td><td align="center">'. $datedue .'</td><td align="center">'. $weight . $pct;
                        }//endif
                        else {
                            echo $description . '</td><td align="center">'. $weight . $pct;
                        }//endelse
                    }//endelse
                    
                    if (empty($noabrule)){
                        echo '</td><td align="center">'. $type;
                    }//endif
                    
                    if ($_SESSION[$_GET["trid"] . "moderationtype"]!=='U'){
                        if (!empty($samplerequired)){
                            echo '</td><td align="center">'. '*';
                        }//endif
                        else {
                            echo '</td><td align="center">&nbsp;';
                        }//endelse
                    }//endif
                }//endif
                
            }//endelse
        }//endfor
        
        if (isset($_POST["btnPDF"])){
            $html = $html . '</table>';
            $pdf->SetX(25);
            
            $pdf->writeHTML($html);
            
            $pdf->Ln();
        }//endif
        else {
            echo '</td></tr>';
            echo '</table>';
        }//endif
        
        //Assessment General
        $sql = "select *
            from unitdescriptiondetail
            where locationid = '$locationid'
            and termid = '$termid'
            and unitid = '$unitid'
            and udtype='tskdet'";
        
        $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-107: ".mysqli_error($db));
        
        if (mysqli_num_rows($sql_ok)){
            $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-108: ".mysqli_error($db));
            
            $unitdescdetailkey = $row["unitdescdetailkey"];
            if (!isset($_POST["btnPDF"])){
                if ($allowupdate && ($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O')) && $p->update_allowed('')))){
                    echo '<tr><td style="padding-left: 1.8cm"><a href="javascript:unitdescriptionupdate('. $unitdescdetailkey . ',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'tskdet\',5,0,1)">Task Detail:</a></td></tr>';
                }//endif
                else {
                    echo '<tr><td style="padding-left: 1.8cm"><b>Task Detail:</b></td></tr>';
                }//endelse
            }//endelse
            $temp = stripslashes($row["content"]);
            
            if (isset($_POST["btnPDF"])){
                
                $pdf->SetX(15);
                $temp = convert_for_html($temp,_tablesettingON,_striplinebreakOFF,_stripAtagOFF,_stripalllinefeedOFF);
                
                $html = '<div style="margin-left: 0mm; text-align: left;">'.$temp.'</div>';
                $pdf->writeHTML($html);
                $pdf->Ln(1);
            }//endif
            else {
                echo '<tr><td style="padding-left: 1.8cm; text-align: left">'. $temp .'</td></tr>';
            }//endelse
        }//endif
        else {
            if (!isset($_POST["btnPDF"])){
                if ($allowupdate && ($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O')) && $p->update_allowed('')))){
                    echo '<tr><td style="padding-left: 1.8cm"><a href="javascript:unitdescriptionupdate(\'\',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'tskdet\',5,0,1)">Task Detail:</a></td></tr>';
                }//endif
            }//endif
        }//endelse
        
        //Assessment General - Landscape
        $sql = "select *
            from unitdescriptiondetail
            where locationid = '$locationid'
            and termid = '$termid'
            and unitid = '$unitid'
            and udtype='tskdtl'";
        
        $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-109: ".mysqli_error($db));
        
        if (mysqli_num_rows($sql_ok)){
            $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-110: ".mysqli_error($db));
            
            $unitdescdetailkey = $row["unitdescdetailkey"];
            if (!isset($_POST["btnPDF"])){
                if ($allowupdate && ($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O')) && $p->update_allowed('')))){
                    echo '<tr><td style="padding-left: 1.8cm"><a href="javascript:unitdescriptionupdate('. $unitdescdetailkey . ',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'tskdtl\',5,0,1)">Task Detail (Landscape):</a></td></tr>';
                }//endif
                else {
                    echo '<tr><td style="padding-left: 1.8cm"><b>Task Detail (Landscape):</b></td></tr>';
                }//endelse
            }//endelse
            $temp = stripslashes($row["content"]);
            
            if (isset($_POST["btnPDF"])){
                
                
                $pdf->AddPageByArray(array('orientation' => 'L','margin_left' => 15,'margin_right' => 15,'margin_top' => 20,'margin_bottom' => 15,'margin_header' => 8,'margin_footer' => 8));
                $pdf->SetFont('','',10);
                
                $pdf->SetX(25);
                $temp = convert_for_html($temp,_tablesettingON,_striplinebreakOFF,_stripAtagOFF,_stripalllinefeedOFF);
                $pdf->writeHTML($temp);
                
                
                $pdf->AddPageByArray(array('margin_left' => 15,'margin_right' => 15,'margin_top' => 20,'margin_bottom' => 15,'margin_header' => 8,'margin_footer' => 8));
            }//endif
            else {
                $temp = str_replace('border="0"','border="1"',$temp);
                echo '<tr><td style="padding-left: 1.8cm; text-align: left">'. $temp .'</td></tr>';
            }//endelse
        }//endif
        else {
            if (!isset($_POST["btnPDF"])){
                if ($allowupdate && ($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O')) && $p->update_allowed('')))){
                    echo '<tr><td style="padding-left: 1.8cm"><a href="javascript:unitdescriptionupdate(\'\',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'tskdtl\',5,0,1)">Task Detail (Landscape):</a></td></tr>';
                }//endif
            }//endif
        }//endelse
        
        //Recommended time
        if (!$unitprofessionalengagement){
            
            $sql = "select *
              from unitdescriptiondetail
              where locationid = '$locationid'
              and termid = '$termid'
              and unitid = '$unitid'
              and udtype='acttm'
              order by sequence";
            
            $acttmsql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-111: ".mysqli_error($db));
            
            //group 3 rules - Recommended time - General comment (generic)
            $sql = "select udrt.*
              from unitdescriptionsubdisciplinerule as udsr
                inner join unitdescriptionruletype as udrt
                  on udrt.unitdescriptionruletypeid = udsr.unitdescriptionruletypeid
                inner join unitdescriptionrule as udr
                  on udr.udsubdisciplineruleid = udsr.udsubdisciplineruleid
              where udsr.subdisciplineid = 9999
              and ifnull(udrt.hide,'') = ''
              and udrt.`group` = 3
              and udr.effectivetermid <= '$termid'
              and not exists (select udr1.*
                              from unitdescriptionsubdisciplinerule as udsr1
                                inner join unitdescriptionrule as udr1
                                  on udr1.udsubdisciplineruleid = udsr1.udsubdisciplineruleid
                              where udsr1.unitdescriptionruletypeid = udsr.unitdescriptionruletypeid
                              and udsr1.subdisciplineid = '$subdisciplineid'
                              and udr1.effectivetermid >= '$termid'
                              and ifnull(udr1.content,'') <> '')
              order by udsr.sequence";
            
            $actgengensql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-112: ".mysqli_error($db));
            
            //group 3 rules - Recommended time - General comment (subdiscipline)
            $sql = "select udrt.*
              from unitdescriptionsubdisciplinerule as udsr
                inner join unitdescriptionruletype as udrt
                  on udrt.unitdescriptionruletypeid = udsr.unitdescriptionruletypeid
              where udsr.subdisciplineid = '$subdisciplineid'
              and ifnull(udrt.hide,'') = ''
              and udrt.`group` = 3
              order by udsr.sequence";
            
            $actgensubsql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-113: ".mysqli_error($db));
            
            if (isset($_POST["btnPDF"])){
                if (mysqli_num_rows($acttmsql_ok) || mysqli_num_rows($actgengensql_ok) || mysqli_num_rows($actgensubsql_ok)){
                    $pdf->SetX(15);
                    $pdf->SetFont('','B',10);
                    $pdf->Cell('',8,'Recommended time per learning activity',0,1,'L',0);
                    $pdf->SetFont('','',10);
                }//endif
            }//endif
            else {
                echo '<tr><td style="padding-left: 0.8cm"><b>Recommended time per learning activity:</b></td></tr>';
            }//endelse
            
            $genericruletypearray = array();
            if (mysqli_num_rows($actgengensql_ok)){
                
                $actgengenrow = mysqli_fetch_array($actgengensql_ok) or die(basename(__FILE__,'.php')."-114: ".mysqli_error($db));
                
                $unitdescriptionruletypeid = $actgengenrow["unitdescriptionruletypeid"];
                array_push($genericruletypearray,$unitdescriptionruletypeid);
                $name = stripslashes($actgengenrow["name"]);
                
                $sql = "select udr.*
                from unitdescriptionsubdisciplinerule as udsr
                  inner join unitdescriptionrule as udr
                    on udr.udsubdisciplineruleid = udsr.udsubdisciplineruleid
                where udsr.unitdescriptionruletypeid = '$unitdescriptionruletypeid'
                and udsr.subdisciplineid = 9999
                and udr.effectivetermid = (select max(udr1.effectivetermid)
                                           from unitdescriptionrule as udr1
                                           where udr1.udsubdisciplineruleid = udr.udsubdisciplineruleid
                                           and udr1.effectivetermid <= '$termid')
                and ifnull(udr.content,'') <> ''";
                
                $actgengendetsql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-115: ".mysqli_error($db));
                
                if (mysqli_num_rows($actgengendetsql_ok)){
                    
                    $actgengendetrow = mysqli_fetch_array($actgengendetsql_ok) or die(basename(__FILE__,'.php')."-116: ".mysqli_error($db));
                    
                    $temp = stripslashes($actgengendetrow["content"]);
                    
                    if (isset($_POST["btnPDF"])){
                        
                        $pdf->SetX(15);
                        $temp = convert_for_html($temp,_tablesettingOFF,_striplinebreakOFF,_stripAtagOFF,_stripalllinefeedOFF);
                        
                        $html = '<div style="margin-left:0mm; text-align: left;">'.$temp.'</div>';
                        $pdf->writeHTML($html);
                        
                    }//endif
                    else {
                        echo '<tr><td style="padding-left: 1.8cm; text-align: left">'. $temp .'</td></tr>';
                    }//endelse
                }//endif
                
            }//endif
            
            if (mysqli_num_rows($actgensubsql_ok)){
                
                $actgensubrow = mysqli_fetch_array($actgensubsql_ok) or die(basename(__FILE__,'.php')."-117: ".mysqli_error($db));
                
                $unitdescriptionruletypeid = $actgensubrow["unitdescriptionruletypeid"];
                $name = stripslashes($actgensubrow["name"]);
                
                $sql = "select udr.*
                from unitdescriptionsubdisciplinerule as udsr
                  inner join unitdescriptionrule as udr
                    on udr.udsubdisciplineruleid = udsr.udsubdisciplineruleid
                where udsr.unitdescriptionruletypeid = '$unitdescriptionruletypeid'
                and udsr.subdisciplineid = '$subdisciplineid'
                and udr.effectivetermid = (select max(udr1.effectivetermid)
                                           from unitdescriptionrule as udr1
                                           where udr1.udsubdisciplineruleid = udr.udsubdisciplineruleid
                                           and udr1.effectivetermid <= '$termid')
                and ifnull(udr.content,'') <> ''";
                
                $actgensubdetsql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-118: ".mysqli_error($db));
                
                if (mysqli_num_rows($actgensubdetsql_ok) && !in_array($unitdescriptionruletypeid, $genericruletypearray)){
                    
                    $actgensubdetrow = mysqli_fetch_array($actgensubdetsql_ok) or die(basename(__FILE__,'.php')."-119: ".mysqli_error($db));
                    
                    $temp = stripslashes($actgensubdetrow["content"]);
                    
                    if (isset($_POST["btnPDF"])){
                        
                        $pdf->SetX(15);
                        $temp = convert_for_html($temp,_tablesettingOFF,_striplinebreakOFF,_stripAtagOFF,_stripalllinefeedOFF);
                        
                        $html = '<div style="margin-left:0mm; text-align: left;">'.$temp.'</div>';
                        $pdf->writeHTML($html);
                        
                    }//endif
                    else {
                        echo '<tr><td style="padding-left: 1.8cm; text-align: left">'. $temp .'</td></tr>';
                    }//endelse
                }//endif
                
            }//endif
            
            //Recommended time - Activity
            if (!isset($_POST["btnPDF"])){
                if ($allowupdate && ((($p->admin_access_allowed('SZ') && $p->update_allowed('P')) || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('X','U','O')) && $p->update_allowed(''))) && !in_array($_SESSION[$_GET["trid"] . "admin"], array('D','B','J')))){
                    echo '<tr><td style="padding-left: 1.8cm"><a href="javascript:unitdescriptionupdate(\'\',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'acttm\',1,1,11)">Activity:</a></td></tr>';
                }//endif
                else {
                    echo '<tr><td style="padding-left: 1.8cm"><b>Activity:</b></td></tr>';
                }//endelse
            }//endelse
            
            $html = '';
            if (mysqli_num_rows($acttmsql_ok) > 0){
                if (isset($_POST["btnPDF"])){
                    $html = '
              <table width="100%" style="margin-left: 0mm;" border="1" cellspacing="0" cellpadding="3">
              <thead>
              <tr>
              <td width="45%" style="background-color:#C0C0C0; font-weight: bold; text-align: center">Learning Activity</td>
              <td style="background-color:#C0C0C0; font-weight: bold; text-align: center">Description</td>
              <td width="10%" style="background-color:#C0C0C0; font-weight: bold; text-align: center">Hours</td>
              </tr>
              </thead>';
                }//endif
                else {
                    echo '<tr><td style="padding-left: 1.8cm">';
                    echo '<table width="100%" border="1" cellpadding="6" cellspacing="0">';
                    echo '<tr><td width="1%" bgcolor="#C0C0C0">&nbsp;</td>';
                    echo '<td width="45%" bgcolor="#C0C0C0" align="center"><b>Learning Activity</b></td>';
                    echo '<td width="*" bgcolor="#C0C0C0" align="center"><b>Description</b></td>';
                    echo '<td width="10%" bgcolor="#C0C0C0" align="center"><b>Hours</b></td></tr>';
                }//endelse
            }//endif
            
            $totaltime = 0;
            for ($i=0; $i < mysqli_num_rows($acttmsql_ok); $i++){
                $row = mysqli_fetch_array($acttmsql_ok) or die(basename(__FILE__,'.php')."-120: ".mysqli_error($db));
                
                $unitdescdetailkey = $row["unitdescdetailkey"];
                $temp_0 = stripslashes($row["content"]);
                $temp_1 = stripslashes($row["content_1"]);
                $temp_2 = stripslashes($row["content_2"]);
                
                $nbr = $i +1 . '.';
                $totaltime = $totaltime + $temp_2;
                
                if (isset($_POST["btnPDF"])){
                    
                    $pdf->SetX(30);
                    
                    $html = $html . '
              <tr>
              <td>'.$temp_0.'</td>
              <td>'.$temp_1.'</td>
              <td style="text-align: center;">'.$temp_2.'</td>
              </tr>';
                    
                }//endif
                else {
                    if ($allowupdate && ((($p->admin_access_allowed('SZ') && $p->update_allowed('P')) || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('X','U','O')) && $p->update_allowed(''))) && !in_array($_SESSION[$_GET["trid"] . "admin"], array('D','B','J')))){
                        echo '<tr><td><a href="javascript:unitdescriptionupdate(' . $unitdescdetailkey . ',\'' .$locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'acttm\',1,1,11)">'. $nbr . '</a></td>' . '<td>'. $temp_0 .'<td>'. $temp_1 .'<td align="center">'. $temp_2 .'</td></tr>';
                    }//endif
                    else {
                        echo '<tr><td>' . $nbr . '</td><td>'. $temp_0 .'<td>'. $temp_1 .'</td><td align="center">'. $temp_2 .'</td></tr>';
                    }//endelse
                }//endelse
            }//endfor
            
            if (mysqli_num_rows($acttmsql_ok) > 0){
                if (isset($_POST["btnPDF"])){
                    $html = $html . '
              <tr>
              <td colspan="2" align="right">Total:</td>
              <td style="text-align: center;">'.$totaltime.'</td>
              </tr>';
                }//endif
                else {
                    $totalcreditpoint = $unitcreditpoint * 10;
                    if ($totaltime < $totalcreditpoint){
                        $totaltime = $totaltime . '<br><br><span style="color: red;">Should total ' . $totalcreditpoint. '</span>';
                    }//endif
                    echo '<tr><td align="right" colspan="3"><b>Total:</b></td><td align="center">'. $totaltime .'</td></tr>';
                }//endelse
            }//endif
            
            if (mysqli_num_rows($acttmsql_ok) > 0){
                if (isset($_POST["btnPDF"])){
                    $html = $html . '</table>';
                    $pdf->Ln(3);
                    $pdf->writeHTML($html);
                    $pdf->Ln();
                }//endif
                else {
                    echo '</td></tr></table><br>';
                }//endelse
            }//endif
        }//endif
        
        //Submission and Return of Student Work
        $sql = "select *
            from unitdescriptiondetail
            where locationid = '$locationid'
            and termid = '$termid'
            and unitid = '$unitid'
            and udtype='subret'";
        
        $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-121: ".mysqli_error($db));
        
        if (mysqli_num_rows($sql_ok)){
            $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-122: ".mysqli_error($db));
            
            $unitdescdetailkey = $row["unitdescdetailkey"];
            if (!isset($_POST["btnPDF"])){
                if ($allowupdate && ($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O')) && $p->update_allowed('')))){
                    echo '<tr><td style="padding-left: 1.2cm"><a href="javascript:unitdescriptionupdate('. $unitdescdetailkey . ',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'subret\',5,0,1)">Submission and Return of Student Work</a></td></tr>';
                }//endif
                else {
                    echo '<tr><td style="padding-left: 1.2cm"><b>Submission and Return of Student Work:</b></td></tr>';
                }//endelse
            }//endelse
            $temp = stripslashes($row["content"]);
            
            if (isset($_POST["btnPDF"])){
                
                $pdf->SetX(15);
                $pdf->SetFont('','B',10);
                $pdf->Cell('',10,'Submission and Return of Student Work',0,1,'L',0);
                $pdf->SetFont('','',10);
                
                $pdf->SetX(15);
                $temp = convert_for_html($temp,_tablesettingOFF,_striplinebreakOFF,_stripAtagOFF,_stripalllinefeedOFF);
                
                $html = '<div style="margin-left: 0mm; text-align: left;">'.$temp.'</div>';
                $pdf->writeHTML($html);
                
            }//endif
            else {
                echo '<tr><td style="padding-left: 1.8cm; text-align: left">'. $temp .'</td></tr>';
            }//endelse
        }//endif
        else {
            if (!isset($_POST["btnPDF"])){
                if ($allowupdate && ($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O')) && $p->update_allowed('')))){
                    echo '<tr><td style="padding-left: 1.2cm"><a href="javascript:unitdescriptionupdate(\'\',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'subret\',5,0,1)">Submission and Return of Student Work:</a></td></tr>';
                }//endif
            }//endif
        }//endelse
        
        if (!$unitprofessionalengagement){
            //Final exam
            $sql = "select *
              from unitdescriptiondetail
              where locationid = '$locationid'
              and termid = '$termid'
              and unitid = '$unitid'
              and udtype='exam'";
            
            $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-123: ".mysqli_error($db));
            
            $finalexamfound = false;
            if (mysqli_num_rows($sql_ok)){
                
                $finalexamfound = true;
                
                $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-124: ".mysqli_error($db));
                
                $unitdescdetailkey = $row["unitdescdetailkey"];
                $exammaterialsallowed = stripslashes($row["content"]);
                $examduration = stripslashes($row["content_1"]);
                $exammaterials = stripslashes($row["content_2"]);
                if($termid >= '2020/17'){
                    $temp = 'The final test in this course will take place in the end of term final test period. It will be a ';
                }
                else {
                    $temp = 'The final exam in this course will take place in the end of term final exam period. It will be a ';
                }
                
                if (isset($_POST["btnPDF"])){
                    
                    $pdf->SetX(15);
                    $temp = $temp . $examduration . ' test and students will ';
                    $pdf->SetFont('','B',10);
                    if($termid >= '2020/17'){
                        $pdf->Cell('',10,'Final Test',0,1,'L',0);
                    }
                    else {
                        $pdf->Cell('',10,'Final Exam',0,1,'L',0);
                    }
                    $pdf->SetFont('','',10);
                    
                    $pdf->SetX(15);
                    if ($exammaterialsallowed == 'No'){
                        $temp = $temp . '<b>NOT</b> be permitted to take in any materials.';
                        
                        $pdf->SetX(15);
                        $temp = convert_for_html($temp,_tablesettingOFF,_striplinebreakOFF,_stripAtagOFF,_stripalllinefeedOFF);
                        
                        $html = '<div style="margin-left: 0mm; text-align: left;">'.$temp.'</div>';
                        $pdf->writeHTML($html);
                        $pdf->Ln();
                    }//endif
                    else {
                        $temp = $temp . ' be permitted to take in ' . $exammaterials . '.';
                        $pdf->MultiCell('',5,$temp,0,'J',0);
                    }//endelse
                    
                }//endif
                else {
                    $temp1 = trim($examduration);
                    if (empty($temp1)){
                        $examduration = '_';
                    }//endif
                    if ($allowupdate && ($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O')) && $p->update_allowed('')))){
                        if($termid >='2020/17'){
                            echo '<tr><td style="padding-left: 1.2cm"><b>Final Test:</b></td></tr>';
                        }
                        else {
                            echo '<tr><td style="padding-left: 1.2cm"><b>Final Exam:</b></td></tr>';
                        }
                        if ($exammaterialsallowed == 'No'){
                            echo '<tr><td style="padding-left: 1.8cm">' . $temp . '<a href="javascript:unitdescriptionupdate(' . $unitdescdetailkey . ',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'exam\',1,0,5)">' . $examduration . '</a> test and students will <b>NOT</b> be permitted to take in any materials.</td></tr>';
                        }//endif
                        else {
                            echo '<tr><td style="padding-left: 1.8cm">' . $temp . '<a href="javascript:unitdescriptionupdate(' . $unitdescdetailkey . ',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'exam\',1,0,5)">' . $examduration . '</a> test and students will be permitted to take in ' . '<a href="javascript:unitdescriptionupdate(' . $unitdescdetailkey . ',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'exam\',1,0,5)">' . $exammaterials . '</a>.</td></tr>';
                        }//endelse
                    }//endif
                    else {
                        if($termid >='2020/17'){
                            echo '<tr><td style="padding-left: 1.2cm"><b>Final Test:</b></td></tr>';
                        }
                        else{
                            echo '<tr><td style="padding-left: 1.2cm"><b>Final Exam:</b></td></tr>';
                        }
                        if ($exammaterialsallowed == 'No'){
                            if($termid >='2020/17'){
                                echo '<tr><td style="padding-left: 1.8cm">' . $temp . $examduration . ' test and students will <b>NOT</b> be permitted to take in any materials.</td></tr>';
                            }
                            else {
                                echo '<tr><td style="padding-left: 1.8cm">' . $temp . $examduration . ' exam and students will <b>NOT</b> be permitted to take in any materials.</td></tr>';
                            }
                        }//endif
                        else {
                            if($termid >='2020/17'){
                                echo '<tr><td style="padding-left: 1.8cm">' . $temp . $examduration . ' test and students will be permitted to take in ' . $exammaterials . '.</td></tr>';
                            }
                            else {
                                echo '<tr><td style="padding-left: 1.8cm">' . $temp . $examduration . ' exam  and students will be permitted to take in ' . $exammaterials . '.</td></tr>';
                            }
                        }//endelse
                    }//endelse
                }//endelse
            }//endif
            else {
                if (!isset($_POST["btnPDF"])){
                    if ($allowupdate && ($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O')) && $p->update_allowed('')))){
                        if($termid >='2020/17'){
                            echo '<tr><td style="padding-left: 1.2cm"><a href="javascript:unitdescriptionupdate(\'\',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'exam\',1,0,5)">Final Examination/Test:</a></td><td>&nbsp;</td></tr>';
                        }
                        else {
                            echo '<tr><td style="padding-left: 1.2cm"><a href="javascript:unitdescriptionupdate(\'\',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'exam\',1,0,5)">Final Exam:</a></td><td>&nbsp;</td></tr>';
                        }
                    }//endif
                }//endif
                else {
                    $pdf->Ln();
                }//endelse
            }//endelse
        }//endif
        
        
        //CLOSING THE LOOP ======================================================================================================
        //Student feedback
        $sql = "select *
            from unitdescriptiondetail
            where locationid = '$locationid'
            and termid = '$termid'
            and unitid = '$unitid'
            and udtype = 'stdfbk'";
        
        $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-125: ".mysqli_error($db));
        
        if (mysqli_num_rows($sql_ok)){
            $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-126: ".mysqli_error($db));
            
            $unitdescdetailkey = $row["unitdescdetailkey"];
            $temp = stripslashes($row["content"]);
            
            if (!isset($_POST["btnPDF"])){
                if ($allowupdate && (($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O')) && $p->update_allowed('P'))) && !in_array($_SESSION[$_GET["trid"] . "admin"], array('D','B','J')))){
                    echo '<tr><td style="padding-left: 1.2cm"><a href="javascript:unitdescriptionupdate('. $unitdescdetailkey . ',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'stdfbk\',4,0,12)">Closing the Loop / Student Feedback:</a> ';
                }//endif
                else {
                    echo '<tr><td style="padding-left: 1.2cm"><b>Closing the Loop / Student Feedback: </b>';
                }//endelse
                
                echo '<tr><td style="padding-left: 1.8cm">' . $temp .'</td></tr>';
                
            }//endif
            else {
                $pdf->SetX(15);
                $pdf->SetFont('','B',10);
                $pdf->Cell('',10,'Closing the Loop / Student Feedback',0,1,'L',0);
                $pdf->SetFont('','',10);
                
                $temp = convert_for_html($temp,_tablesettingON,_striplinebreakOFF,_stripAtagOFF,_stripalllinefeedOFF);
                
                $html = '<div style="margin-left: 0mm; text-align: left;">'.$temp.'<br><br></div>';
                $pdf->writeHTML($html);
            }//endelse
            
        }//endif
        else {
            if (!isset($_POST["btnPDF"])){
                if ($allowupdate && (($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O')) && $p->update_allowed('P'))) && !in_array($_SESSION[$_GET["trid"] . "admin"], array('D','B','J')))){
                    echo '<tr><td style="padding-left: 1.2cm"><a href="javascript:unitdescriptionupdate(\'\',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'stdfbk\',4,0,12)">Closing the Loop / Student Feedback:</a> </td></tr>';
                }//endif
            }//endelse
        }//endelse
        
        
        //PROFESSIONAL ENGAGEMENT =============================================================================================================
        $unitprofessionalengagementsql = '';
        if ($unitprofessionalengagement){
            $unitprofessionalengagementsql = " and ifnull(udrt.offengagementunit,'') = '' ";
        }//endif
        
        
        //ASSESSMENT CRITERIA ====================================================================================================================
        //group 1 rules - Assessment criteria (generic)
        $sql = "select udrt.*
            from unitdescriptionsubdisciplinerule as udsr
              inner join unitdescriptionruletype as udrt
                on udrt.unitdescriptionruletypeid = udsr.unitdescriptionruletypeid
              inner join unitdescriptionrule as udr
                on udr.udsubdisciplineruleid = udsr.udsubdisciplineruleid
            where udsr.subdisciplineid = 9999
            and ifnull(udrt.hide,'') = ''
            $unitprofessionalengagementsql
            and udrt.`group` = 1
            and udr.effectivetermid <= '$termid'
            and not exists (select udr1.*
                            from unitdescriptionsubdisciplinerule as udsr1
                              inner join unitdescriptionrule as udr1
                                on udr1.udsubdisciplineruleid = udsr1.udsubdisciplineruleid
                            where udsr1.unitdescriptionruletypeid = udsr.unitdescriptionruletypeid
                            and udsr1.subdisciplineid = '$subdisciplineid'
                            and udr1.effectivetermid >= '$termid'
                            and ifnull(udr1.content,'') <> '')
            order by udsr.sequence";
            
            $typesql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-127: ".mysqli_error($db));
            
            $genericruletypearray = array();
            for ($i=0; $i < mysqli_num_rows($typesql_ok); $i++){
                $typerow = mysqli_fetch_array($typesql_ok) or die(basename(__FILE__,'.php')."-128: ".mysqli_error($db));
                
                $unitdescriptionruletypeid = $typerow["unitdescriptionruletypeid"];
                array_push($genericruletypearray,$unitdescriptionruletypeid);
                $name = stripslashes($typerow["name"]);
                
                $sql = "select udr.*
              from unitdescriptionsubdisciplinerule as udsr
                inner join unitdescriptionrule as udr
                  on udr.udsubdisciplineruleid = udsr.udsubdisciplineruleid
              where udsr.unitdescriptionruletypeid = '$unitdescriptionruletypeid'
              and udsr.subdisciplineid = 9999
              and udr.effectivetermid = (select max(udr1.effectivetermid)
                                         from unitdescriptionrule as udr1
                                         where udr1.udsubdisciplineruleid = udr.udsubdisciplineruleid
                                         and udr1.effectivetermid <= '$termid')
              and ifnull(udr.content,'') <> ''";
                
                $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-129: ".mysqli_error($db));
                
                if (mysqli_num_rows($sql_ok)){
                    $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-130: ".mysqli_error($db));
                    
                    $temp = stripslashes($row["content"]);
                    
                    if ((empty($noabrule) && strpos($name,'(A/B rule ON)') !== false) || (!empty($noabrule) && strpos($name,'(A/B rule OFF)') !== false)){
                        $name = str_replace(' (A/B rule ON)','',$name);
                        $name = str_replace(' (A/B rule OFF)','',$name);
                        
                        $temphtml = strtolower($temp);
                        
                        if (isset($_POST["btnPDF"])){
                            $pdf->SetX(15);
                            $pdf->SetFont('','B',10);
                            $pdf->MultiCell('',5,$name,0,'J',0);
                            $pdf->SetFont('','',10);
                            $pdf->Ln();
                            
                            $pdf->SetX(15);
                            $temp = convert_for_html($temp,_tablesettingOFF,_striplinebreakOFF,_stripAtagOFF,_stripalllinefeedOFF);
                            
                            $html = '<div style="margin-left: 0mm; text-align: left;">'.$temp.'</div>';
                            $pdf->writeHTML($html);
                            
                            $pdf->Ln();
                        }//endif
                        else {
                            echo '<tr><td style="vertical-align: top; padding-left: 1.2cm;"><b>' . $name . '</b></td></tr>';
                            echo '<tr><td style="padding-left: 1.8cm; text-align: left">'. $temp .'</td></tr>';
                        }//endelse
                    }//endif
                    
                }//endif
                
            }//endfor
            
            
            //group 1 rules - Assessment criteria (subdiscipline)
            $sql = "select udrt.*
            from unitdescriptionsubdisciplinerule as udsr
              inner join unitdescriptionruletype as udrt
                on udrt.unitdescriptionruletypeid = udsr.unitdescriptionruletypeid
            where udsr.subdisciplineid = '$subdisciplineid'
            and ifnull(udrt.hide,'') = ''
            and udrt.`group` = 1
            $unitprofessionalengagementsql
            order by udsr.sequence";
            
            $typesql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-131: ".mysqli_error($db));
            
            for ($i=0; $i < mysqli_num_rows($typesql_ok); $i++){
                $typerow = mysqli_fetch_array($typesql_ok) or die(basename(__FILE__,'.php')."-132: ".mysqli_error($db));
                
                $unitdescriptionruletypeid = $typerow["unitdescriptionruletypeid"];
                $name = stripslashes($typerow["name"]);
                
                $sql = "select udr.*
              from unitdescriptionsubdisciplinerule as udsr
                inner join unitdescriptionrule as udr
                  on udr.udsubdisciplineruleid = udsr.udsubdisciplineruleid
              where udsr.unitdescriptionruletypeid = '$unitdescriptionruletypeid'
              and udsr.subdisciplineid = '$subdisciplineid'
              and udr.effectivetermid = (select max(udr1.effectivetermid)
                                         from unitdescriptionrule as udr1
                                         where udr1.udsubdisciplineruleid = udr.udsubdisciplineruleid
                                         and udr1.effectivetermid <= '$termid')
              and ifnull(udr.content,'') <> ''";
                
                $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-133: ".mysqli_error($db));
                
                if (mysqli_num_rows($sql_ok) && !in_array($unitdescriptionruletypeid, $genericruletypearray)){
                    $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-134: ".mysqli_error($db));
                    
                    $temp = stripslashes($row["content"]);
                    
                    if ((empty($noabrule) && strpos($name,'(A/B rule ON)') !== false) || (!empty($noabrule) && strpos($name,'(A/B rule OFF)') !== false)){
                        $name = str_replace(' (A/B rule ON)','',$name);
                        $name = str_replace(' (A/B rule OFF)','',$name);
                        
                        $temphtml = strtolower($temp);
                        
                        if (isset($_POST["btnPDF"])){
                            $pdf->SetX(15);
                            $pdf->SetFont('','B',10);
                            $pdf->MultiCell('',5,$name,0,'J',0);
                            $pdf->SetFont('','',10);
                            $pdf->Ln();
                            
                            $pdf->SetX(15);
                            $temp = convert_for_html($temp,_tablesettingOFF,_striplinebreakOFF,_stripAtagOFF,_stripalllinefeedOFF);
                            
                            $html = '<div style="margin-left: 0mm; text-align: left;">'.$temp.'</div>';
                            $pdf->writeHTML($html);
                            
                            $pdf->Ln();
                        }//endif
                        else {
                            echo '<tr><td style="vertical-align: top; padding-left: 1.2cm;"><b>' . $name . '</b></td></tr>';
                            echo '<tr><td style="padding-left: 1.8cm; text-align: left">'. $temp .'</td></tr>';
                        }//endelse
                    }//endif
                    
                }//endif
                
            }//endfor
            
            //group 2 rules - general (generic)
            $sql = "select udrt.*
            from unitdescriptionsubdisciplinerule as udsr
              inner join unitdescriptionruletype as udrt
                on udrt.unitdescriptionruletypeid = udsr.unitdescriptionruletypeid
              inner join unitdescriptionrule as udr
                on udr.udsubdisciplineruleid = udsr.udsubdisciplineruleid
            where udsr.subdisciplineid = 9999
            and ifnull(udrt.hide,'') = ''
            $unitprofessionalengagementsql
            and udrt.`group` = 2
            and udr.effectivetermid <= '$termid'
            and not exists (select udr1.*
                            from unitdescriptionsubdisciplinerule as udsr1
                              inner join unitdescriptionrule as udr1
                                on udr1.udsubdisciplineruleid = udsr1.udsubdisciplineruleid
                            where udsr1.unitdescriptionruletypeid = udsr.unitdescriptionruletypeid
                            and udsr1.subdisciplineid = '$subdisciplineid'
                            and udr1.effectivetermid >= '$termid'
                            and ifnull(udr1.content,'') <> '')
            order by udsr.sequence";
            
            $typesql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-135: ".mysqli_error($db));
            $genericruletypearray = array();
            for ($i=0; $i < mysqli_num_rows($typesql_ok); $i++){
                $typerow = mysqli_fetch_array($typesql_ok) or die(basename(__FILE__,'.php')."-136: ".mysqli_error($db));
                
                $unitdescriptionruletypeid = $typerow["unitdescriptionruletypeid"];
                
                //Check to see if array already contains a rule for this type - if not add
                if(!in_array($unitdescriptionruletypeid,$genericruletypearray)){
                    array_push($genericruletypearray,$unitdescriptionruletypeid);
                    
                    
                    $name = stripslashes($typerow["name"]);
                    if($termid >= '2020/17' && $name == 'Exam Eligibility') {
                        $displayname='Final Test Eligibility';
                    }
                    else {
                        $displayname=$name;
                    }
                    
                    if ($name !== 'Exam Eligibility' || ($name == 'Exam Eligibility' && $finalexamfound)){
                        $sql = "select udr.*
                    from unitdescriptionsubdisciplinerule as udsr
                      inner join unitdescriptionrule as udr
                        on udr.udsubdisciplineruleid = udsr.udsubdisciplineruleid
                    where udsr.unitdescriptionruletypeid = '$unitdescriptionruletypeid'
                    and udsr.subdisciplineid = 9999
                    and udr.effectivetermid = (select max(udr1.effectivetermid)
                                               from unitdescriptionrule as udr1
                                               where udr1.udsubdisciplineruleid = udr.udsubdisciplineruleid
                                               and udr1.effectivetermid <= '$termid')
                    and ifnull(udr.content,'') <> ''";
                        
                        $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-137: ".mysqli_error($db));
                        
                        if (mysqli_num_rows($sql_ok)){
                            $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-138: ".mysqli_error($db));
                            
                            $temp = stripslashes($row["content"]);
                            
                            $temphtml = strtolower($temp);
                            
                            if (isset($_POST["btnPDF"])){
                                $pdf->SetX(15);
                                $pdf->SetFont('','B',10);
                                $pdf->MultiCell('',5,$displayname,0,'J',0);
                                $pdf->SetFont('','',10);
                                $pdf->Ln();
                                
                                $pdf->SetX(15);
                                $temp = convert_for_html($temp,_tablesettingOFF,_striplinebreakOFF,_stripAtagOFF,_stripalllinefeedOFF);
                                
                                $html = '<div style="margin-left: 0mm; text-align: left;">'.$temp.'</div>';
                                $pdf->writeHTML($html);
                                
                                $pdf->Ln();
                            }//endif
                            
                            else {
                                echo '<tr><td style="vertical-align: top; padding-left: 1.2cm;"><b>' . $displayname . ':</b></td></tr>';
                                echo '<tr><td style="padding-left: 1.8cm; text-align: left">'. $temp .'</td></tr>';
                            }//endelse
                            
                        }//endif
                    }//endif
                }//endif - check to see if already in the array
                
            }//endfor
            
            //group 2 rules - general (subdiscipline)
            $sql = "select udrt.*
            from unitdescriptionsubdisciplinerule as udsr
              inner join unitdescriptionruletype as udrt
                on udrt.unitdescriptionruletypeid = udsr.unitdescriptionruletypeid
            where udsr.subdisciplineid = '$subdisciplineid'
            and ifnull(udrt.hide,'') = ''
            $unitprofessionalengagementsql
            and udrt.`group` = 2
            order by udsr.sequence";
            
            $typesql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-139: ".mysqli_error($db));
            
            for ($i=0; $i < mysqli_num_rows($typesql_ok); $i++){
                $typerow = mysqli_fetch_array($typesql_ok) or die(basename(__FILE__,'.php')."-140: ".mysqli_error($db));
                
                $unitdescriptionruletypeid = $typerow["unitdescriptionruletypeid"];
                $name = stripslashes($typerow["name"]);
                if($termid >= '2020/17' && $name == 'Exam Eligibility') {
                    $displayname='Final Test Eligibility';
                }
                else {
                    $displayname=$name;
                }
                if (($name !== 'Exam Eligibility' || ($name == 'Exam Eligibility' && $finalexamfound)) && !in_array($unitdescriptionruletypeid, $genericruletypearray)){
                    $sql = "select udr.*
                from unitdescriptionsubdisciplinerule as udsr
                  inner join unitdescriptionrule as udr
                    on udr.udsubdisciplineruleid = udsr.udsubdisciplineruleid
                where udsr.unitdescriptionruletypeid = '$unitdescriptionruletypeid'
                and udsr.subdisciplineid = '$subdisciplineid'
                and udr.effectivetermid = (select max(udr1.effectivetermid)
                                           from unitdescriptionrule as udr1
                                           where udr1.udsubdisciplineruleid = udr.udsubdisciplineruleid
                                           and udr1.effectivetermid <= '$termid')
                and ifnull(udr.content,'') <> ''";
                    
                    $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-141: ".mysqli_error($db));
                    
                    if (mysqli_num_rows($sql_ok)){
                        $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-142: ".mysqli_error($db));
                        
                        $temp = stripslashes($row["content"]);
                        
                        $temphtml = strtolower($temp);
                        

                        if (isset($_POST["btnPDF"])){
                            $pdf->SetX(15);
                            $pdf->SetFont('','B',10);
                            $pdf->MultiCell('',5,$displayname,0,'J',0);
                            $pdf->SetFont('','',10);
                            $pdf->Ln();
                            
                            $pdf->SetX(15);
                            $temp = convert_for_html($temp,_tablesettingOFF,_striplinebreakOFF,_stripAtagOFF,_stripalllinefeedOFF);
                            
                            $html = '<div style="margin-left: 0mm; text-align: left;">'.$temp.'</div>';
                            $pdf->writeHTML($html);
                            
                            $pdf->Ln();
                        }//endif
                        
                        else {
                            echo '<tr><td style="vertical-align: top; padding-left: 1.2cm;"><b>' . $displayname . ':</b></td></tr>';
                            echo '<tr><td style="padding-left: 1.8cm; text-align: left">'. $temp .'</td></tr>';
                        }//endelse
                        
                    }//endif
                }//endif
                
            }//endfor
            
            if (isset($_POST["btnPDF"])){
                $pdf->SetX(15);
                $pdf->SetFont('','B',12);
                $pdf->Cell('',10,'Materials',0,1,'L',0);
            }//endif
            else {
                echo '<tr><td style="padding-left: 0.6cm"><b>Materials:</b></td></tr>';
            }//endelse
            
            //Student resources
            $sql = "select *
            from unitdescriptiondetail
            where locationid = '$locationid'
            and termid = '$termid'
            and unitid = '$unitid'
            and udtype='rsrce'";
            
            $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-143: ".mysqli_error($db));
            
            if (mysqli_num_rows($sql_ok)){
                $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-144: ".mysqli_error($db));
                
                $unitdescdetailkey = $row["unitdescdetailkey"];
                
                if (!isset($_POST["btnPDF"])){
                    if ($allowupdate && ($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O')) && $p->update_allowed('')))){
                        echo '<tr><td style="padding-left: 0.6cm"><a href="javascript:unitdescriptionupdate('. $unitdescdetailkey . ',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'rsrce\',5,0,1)">General:</a>&nbsp;<span style="color:red;">(required by students)';
                    }//endif
                    else {
                        echo '<tr><td style="padding-left: 0.6cm"><b>General:&nbsp;<span style="color:red;">(required by students)</b>';
                    }//endelse
                }//endelse
                $temp = stripslashes($row["content"]);
                
                if (isset($_POST["btnPDF"])){
                    
                    $pdf->SetX(15);
                    $pdf->SetFont('','B',10);
                    $pdf->Cell('',10,'General',0,1,'L',0);
                    $pdf->SetFont('','',10);
                    
                    $pdf->SetX(15);
                    
                    $temp = convert_for_html($temp,_tablesettingON,_striplinebreakOFF,_stripAtagOFF,_stripalllinefeedOFF);
                    
                    $html = '<div style="margin-left: 0mm; text-align: left;">'.$temp.'</div>';
                    $pdf->writeHTML($html);
                }//endif
                else {
                    echo '<tr><td style="padding-left: 1.2cm">'.  $temp .'</td></tr>';
                }//endelse
            }//endif
            else {
                if (!isset($_POST["btnPDF"])){
                    if ($allowupdate && ($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O')) && $p->update_allowed('')))){
                        echo '<tr><td style="padding-left: 0.6cm"><a href="javascript:unitdescriptionupdate(\'\',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'rsrce\',5,0,1)">General:</a>&nbsp;<span style="color:red;">(required by students)</td></tr>';
                    }//endif
                }//endelse
            }//endelse
            
            //Safety equipment
            $sql = "select *
            from unitdescriptiondetail
            where locationid = '$locationid'
            and termid = '$termid'
            and unitid = '$unitid'
            and udtype='safe'";
            
            $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-145: ".mysqli_error($db));
            
            if (mysqli_num_rows($sql_ok)){
                $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-146: ".mysqli_error($db));
                
                $unitdescdetailkey = $row["unitdescdetailkey"];
                
                if (!isset($_POST["btnPDF"])){
                    if ($allowupdate && ($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O')) && $p->update_allowed('')))){
                        echo '<tr><td style="padding-left: 0.6cm"><a href="javascript:unitdescriptionupdate('. $unitdescdetailkey . ',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'safe\',5,0,1)">Safety Equipment:</a>&nbsp;<span style="color:red;">(required by students)</span>';
                    }//endif
                    else {
                        echo '<tr><td style="padding-left: 0.6cm"><b>Safety Equipment:&nbsp;<span style="color:red;">(required by students)</span></b>';
                    }//endelse
                }//endelse
                $temp = stripslashes($row["content"]);
                
                if (isset($_POST["btnPDF"])){
                    
                    $pdf->SetX(15);
                    $pdf->SetFont('','B',10);
                    $pdf->Cell('',10,'Safety Equipment',0,1,'L',0);
                    $pdf->SetFont('','',10);
                    
                    $pdf->SetX(15);
                    $temp = convert_for_html($temp,_tablesettingON,_striplinebreakOFF,_stripAtagOFF,_stripalllinefeedOFF);
                    
                    $html = '<div style="margin-left: 0mm; text-align: left;">'.$temp.'</div>';
                    $pdf->writeHTML($html);
                }//endif
                else {
                    echo '<tr><td style="padding-left: 1.2cm">'.  $temp .'</td></tr>';
                }//endelse
            }//endif
            else {
                if (!isset($_POST["btnPDF"])){
                    if ($allowupdate && ($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O')) && $p->update_allowed('')))){
                        echo '<tr><td style="padding-left: 0.6cm"><a href="javascript:unitdescriptionupdate(\'\',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'safe\',5,0,1)">Safety Equipment:</a>&nbsp;<span style="color:red;">(required by students)</span></td></tr>';
                    }//endif
                }//endelse
            }//endelse
            
            //Reading heading
            if (isset($_POST["btnPDF"])){
                $pdf->SetX(15);
                $pdf->SetFont('','B',10);
                $pdf->Cell('',10,'Reading',0,1,'L',0);
            }//endif
            
            //Reading - General
            $sql = "select *
            from unitdescriptiondetail
            where locationid = '$locationid'
            and termid = '$termid'
            and unitid = '$unitid'
            and udtype='readg'";
            
            $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-147: ".mysqli_error($db));
            
            if (mysqli_num_rows($sql_ok)){
                $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-148: ".mysqli_error($db));
                
                $unitdescdetailkey = $row["unitdescdetailkey"];
                if (!isset($_POST["btnPDF"])){
                    if ($allowupdate && ($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O')) && $p->update_allowed('')))){
                        echo '<tr><td style="Padding-left: 0.6cm"><a href="javascript:unitdescriptionupdate('. $unitdescdetailkey . ',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'readg\',3,0,1)">Reading:</a></td></tr>';
                    }//endif
                    else {
                        echo '<tr><td style="padding-left: 0.6cm"><b>Reading:</b></td></tr>';
                    }//endelse
                }//endelse
                $temp = stripslashes($row["content"]);
                
                if (isset($_POST["btnPDF"])){
                    
                    $pdf->SetFont('','',10);
                    
                    $pdf->SetX(15);
                    $temp = convert_for_html($temp,_tablesettingON,_striplinebreakOFF,_stripAtagOFF,_stripalllinefeedOFF);
                    
                    $html = '<div style="margin-left: 0; text-align: left;">'.$temp.'</div>';
                    $pdf->writeHTML($html);
                }//endif
                else {
                    echo '<tr><td style="padding-left: 1.2cm; text-align: left">'. $temp .'</td></tr>';
                }//endelse
            }//endif
            else {
                if (!isset($_POST["btnPDF"])){
                    if ($allowupdate && ($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O')) && $p->update_allowed('')))){
                        echo '<tr><td style="Padding-left: 0.6cm"><a href="javascript:unitdescriptionupdate(\'\',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'readg\',3,0,1)">Reading:</a></td></tr>';
                    }//endif
                    else {
                        echo '<tr><td style="padding-left: 0.6cm"><b>Reading:</b></td></tr>';
                    }//endelse
                }//endelse
            }//endelse
            
            //Textbooks
            if (!isset($_POST["btnPDF"])){
                if ($allowupdate && ($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O')) && $p->update_allowed('')))){
                    echo '<tr><td style="padding-left: 1.2cm"><a href="javascript:unitdescriptionupdate(\'\',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'txtbk\',1,1,6)">Textbooks:</a>&nbsp;<span style="color:red;">(required by students)</td></tr>';
                }//endif
                else {
                    echo '<tr><td style="padding-left: 1.2cm"><b>Textbooks:</b>&nbsp;<span style="color:red;">(required by students)</td></tr>';
                }//endelse
            }//endelse
            
            $sql = "select *
            from unitdescriptiondetail
            where locationid = '$locationid'
            and termid = '$termid'
            and unitid = '$unitid'
            and udtype='txtbk'
            order by sequence";
            
            $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-149: ".mysqli_error($db));
            
            if (mysqli_num_rows($sql_ok) > 0){
                if (isset($_POST["btnPDF"])){
                    $pdf->SetFont('','B',10);
                    $pdf->SetX(15);
                    $pdf->Cell('',10,'Textbooks',0,1,'L',0);
                }//endif
            }//endif
            
            for ($i=0; $i < mysqli_num_rows($sql_ok); $i++){
                $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-150: ".mysqli_error($db));
                
                $unitdescdetailkey = $row["unitdescdetailkey"];
                $temp_0 = stripslashes($row["content"]);
                $temp_1 = stripslashes($row["content_1"]);
                $temp_2 = stripslashes($row["content_2"]);
                $temp_3 = stripslashes($row["content_3"]);
                $temp_4 = stripslashes($row["content_4"]);
                $temp_5 = stripslashes($row["content_5"]);
                $temp_6 = stripslashes($row["content_6"]);
                $temp_7 = stripslashes($row["content_7"]);
                
                if (!empty($temp_1) && substr($temp_1,-1,1)!=='.'){
                    $temp_1 = $temp_1 . '.';
                }//endif
                
                if (!empty($temp_3) && substr($temp_3,-1,1)!=='.'){
                    $temp_3 = $temp_3 . '.';
                }//endif
                
                if (!empty($temp_4) && substr($temp_4,-1,1)!=='.'){
                    $temp_4 = $temp_4 . '.';
                }//endif
                
                switch ($temp_0){
                    case 'B':
                        $isbn = '';
                        if (!empty($temp_7)){
                            $isbn = '&nbsp;ISBN:&nbsp;' . $temp_7;
                        }//endif
                        $temp = $temp_1;
                        if (empty($temp_2)){
                            $temp_2 = 'N/A';
                        }//endif
                        $temp = $temp . '&nbsp;(' . $temp_2 . ').&nbsp;<i>' . $temp_3 . '</i>&nbsp;' . $temp_4 . $isbn;
                        if (empty($temp_6)){
                            $temp = $temp . '&nbsp;'. $temp_5;
                        }//endif
                        else {
                            $temp = $temp . '&nbsp;<a TARGET="_blank" href="'. $temp_6 . '">' . $temp_5 .'</a>';
                        }//endelse
                        break;
                    case 'J':
                        if (!empty($temp_5) && substr($temp_5,-1,1)!=='.'){
                            $temp_5 = $temp_5 . '.';
                        }//endif
                        if (!empty($temp_6) && substr($temp_6,-1,1)!=='.'){
                            $temp_6 = $temp_6 . '.';
                        }//endif
                        if (!empty($temp_6)){
                            $temp_6 = '&nbsp;Pages&nbsp;' . $temp_6;
                        }//endif
                        $temp = $temp_1;
                        if (empty($temp_2)){
                            $temp_2 = 'N/A';
                        }//endif
                        $temp = $temp . '&nbsp;(' . $temp_2 . ').&nbsp;' . $temp_3 . '&nbsp;<i>' . $temp_4 . '</i>&nbsp;'. $temp_5. '&nbsp;'. $temp_6;
                        break;
                    case 'W':
                        $temp = $temp_1 . '&nbsp;<i>' . $temp_3 . '</i>&nbsp;' . $temp_4 . '&nbsp;Retrieved ' . $temp_2;
                        if (empty($temp_6)){
                            $temp = $temp . '.&nbsp;from'. $temp_5;
                        }//endif
                        else {
                            $temp = $temp . ',&nbsp;from&nbsp;<a TARGET="_blank" href="'. $temp_6 . '">' . $temp_5 .'</a>';
                        }//endelse
                        break;
                    case 'H':
                        $temp = $temp_1;
                        break;
                }//endcase
                
                $nbr = $i +1 . '.';
                $temp = str_replace('&nbsp;',' ',$temp);
                if (isset($_POST["btnPDF"])){
                    $pdf->SetFont('','',10);
                    
                    $pdf->SetX(15);
                    $temp = convert_for_html($temp,_tablesettingOFF,_striplinebreakOFF,_stripAtagOFF,_stripalllinefeedOFF);
                    
                    $html = '<div style="margin-left: 0mm; text-indent: -28px; padding-left: 28px;">'.$temp.'</div>';
                    $pdf->writeHTML($html);
                    
                    $pdf->Ln(3);
                }//endif
                else {
                    if ($allowupdate && ($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O')) && $p->update_allowed('')))){
                        echo '<tr><td style="padding-left: 1.8cm"><table width="100%"><tr><td style="vertical-align: top" width="1%"><a href="javascript:unitdescriptionupdate(' . $unitdescdetailkey . ',\'' .$locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'txtbk\',1,1,6)">'. $nbr . '</a></td><td>' . $temp .'</td></tr></table></td></tr>';
                    }//endif
                    else {
                        if ($_SESSION[$_GET["trid"] . "udstatus"]=='P'){
                            echo '<tr><td style="padding-left: 1.8cm"><table width="100%"><tr><td>' . $temp .'</td></tr></table></td></tr>';
                        }//endif
                        else {
                            echo '<tr><td style="padding-left: 1.8cm"><table width="100%"><tr><td style="vertical-align: top" width="1%">'. $nbr . '</td><td>' . $temp .'</td></tr></table></td></tr>';
                        }//endelse
                    }//endelse
                }//endelse
            }//endfor
            
            
            
            
            
            //References  ++++++++++++++++++++++++++++++++++++++++++++++++++
            if (!isset($_POST["btnPDF"])){
                if ($allowupdate && ($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O')) && $p->update_allowed('')))){
                    echo '<tr><td style="padding-left: 1.2cm"><a href="javascript:unitdescriptionupdate(\'\',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'refrnc\',1,1,6)">References:</a></td></tr>';
                }//endif
                else {
                    echo '<tr><td style="padding-left: 1.2cm"><b>References</b></td></tr>';
                }//endelse
            }//endelse
            
            $sql = "select *
            from unitdescriptiondetail
            where locationid = '$locationid'
            and termid = '$termid'
            and unitid = '$unitid'
            and udtype='refrnc'
            order by sequence";
            
            $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-151: ".mysqli_error($db));
            
            if (mysqli_num_rows($sql_ok) > 0){
                if (isset($_POST["btnPDF"])){
                    $pdf->SetFont('','B',10);
                    $pdf->SetX(15);
                    $pdf->Cell('',10,'References:',0,1,'L',0);
                }//endif
            }//endif
            
            for ($i=0; $i < mysqli_num_rows($sql_ok); $i++){
                $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-152: ".mysqli_error($db));
                
                $unitdescdetailkey = $row["unitdescdetailkey"];
                $temp_0 = stripslashes($row["content"]);
                $temp_1 = stripslashes($row["content_1"]);
                $temp_2 = stripslashes($row["content_2"]);
                $temp_3 = stripslashes($row["content_3"]);
                $temp_4 = stripslashes($row["content_4"]);
                $temp_5 = stripslashes($row["content_5"]);
                $temp_6 = stripslashes($row["content_6"]);
                $temp_7 = stripslashes($row["content_7"]);
                
                if (!empty($temp_1) && substr($temp_1,-1,1)!=='.'){
                    $temp_1 = $temp_1 . '.';
                }//endif
                
                if (!empty($temp_3) && substr($temp_3,-1,1)!=='.'){
                    $temp_3 = $temp_3 . '.';
                }//endif
                
                if (!empty($temp_4) && substr($temp_4,-1,1)!=='.'){
                    $temp_4 = $temp_4 . '.';
                }//endif
                
                switch ($temp_0){
                    case 'B':
                        $isbn = '';
                        if (!empty($temp_7)){
                            $isbn = '&nbsp;ISBN:&nbsp;' . $temp_7;
                        }//endif
                        $temp = $temp_1;
                        if (empty($temp_2)){
                            $temp_2 = 'N/A';
                        }//endif
                        $temp = $temp . '&nbsp;(' . $temp_2 . ').&nbsp;<i>' . $temp_3 . '</i>&nbsp;' . $temp_4 . $isbn;
                        if (empty($temp_6)){
                            $temp = $temp . '&nbsp;'. $temp_5;
                        }//endif
                        else {
                            $temp = $temp . '&nbsp;<a TARGET="_blank" href="'. $temp_6 . '">' . $temp_5 .'</a>';
                        }//endelse
                        break;
                    case 'J':
                        if (!empty($temp_5) && substr($temp_5,-1,1)!=='.'){
                            $temp_5 = $temp_5 . '.';
                        }//endif
                        if (!empty($temp_6) && substr($temp_6,-1,1)!=='.'){
                            $temp_6 = $temp_6 . '.';
                        }//endif
                        if (!empty($temp_6)){
                            $temp_6 = '&nbsp;Pages&nbsp;' . $temp_6;
                        }//endif
                        $temp = $temp_1;
                        if (empty($temp_2)){
                            $temp_2 = 'N/A';
                        }//endif
                        $temp = $temp. '&nbsp;(' . $temp_2 . ').&nbsp;' . $temp_3 . '&nbsp;<i>' . $temp_4 . '</i>&nbsp;'. $temp_5. '&nbsp;'. $temp_6;
                        break;
                    case 'W':
                        $temp = $temp_1 . '&nbsp;<i>' . $temp_3 . '</i>&nbsp;' . $temp_4 . '&nbsp;Retrieved ' . $temp_2;
                        if (empty($temp_6)){
                            $temp = $temp . '.&nbsp;from'. $temp_5;
                        }//endif
                        else {
                            $temp = $temp . ',&nbsp;from&nbsp;<a TARGET="_blank" href="'. $temp_6 . '">' . $temp_5 .'</a>';
                        }//endelse
                        break;
                    case 'H':
                        $temp = $temp_1;
                        break;
                }//endcase
                
                $nbr = $i +1 . '.';
                $temp = str_replace('&nbsp;',' ',$temp);
                if (isset($_POST["btnPDF"])){
                    $pdf->SetFont('','',10);
                    
                    $pdf->SetX(15);
                    $temp = convert_for_html($temp,_tablesettingOFF,_striplinebreakOFF,_stripAtagOFF,_stripalllinefeedOFF);
                    
                    $html = '<div style="margin-left: 0mm; text-indent: -28px; padding-left: 28px;">'.$temp.'</div>';
                    $pdf->writeHTML($html);
                    
                    $pdf->Ln(3);
                }//endif
                else {
                    if ($allowupdate && ($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O')) && $p->update_allowed('')))){
                        echo '<tr><td style="padding-left: 1.8cm"><table width="100%"><tr><td style="vertical-align: top" width="1%"><a href="javascript:unitdescriptionupdate(' . $unitdescdetailkey . ',\'' .$locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'refrnc\',1,1,6)">'. $nbr . '</a></td><td>' . $temp .'</td></tr></table></td></tr>';
                    }//endif
                    else {
                        if ($_SESSION[$_GET["trid"] . "udstatus"]=='P'){
                            echo '<tr><td style="padding-left: 1.8cm"><table width="100%"><tr><td>' . $temp .'</td></tr></table></td></tr>';
                        }//endif
                        else {
                            echo '<tr><td style="padding-left: 1.8cm"><table width="100%"><tr><td style="vertical-align: top" width="1%">'. $nbr . '</td><td>' . $temp .'</td></tr></table></td></tr>';
                        }//endelse
                    }//endelse
                }//endelse
            }//endfor
            
            //Safari
            if (!isset($_POST["btnPDF"])){
                if ($allowupdate && ($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O')) && $p->update_allowed('')))){
                    echo '<tr><td style="padding-left: 1.2cm"><a href="javascript:unitdescriptionupdate(\'\',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'safari\',1,1,6)">eBook / Online:</a></td></tr>';
                }//endif
                else {
                    echo '<tr><td style="padding-left: 1.2cm"><b>eBook / Online</b></td></tr>';
                }//endelse
            }//endelse
            
            $sql = "select *
            from unitdescriptiondetail
            where locationid = '$locationid'
            and termid = '$termid'
            and unitid = '$unitid'
            and udtype='safari'
            order by sequence";
            
            $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-153: ".mysqli_error($db));
            
            if (mysqli_num_rows($sql_ok) > 0){
                if (isset($_POST["btnPDF"])){
                    $pdf->SetFont('','B',10);
                    $pdf->SetX(15);
                    $pdf->Cell('',10,'eBook / Online',0,1,'L',0);
                }//endif
            }//endif
            
            for ($i=0; $i < mysqli_num_rows($sql_ok); $i++){
                $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-154: ".mysqli_error($db));
                
                $unitdescdetailkey = $row["unitdescdetailkey"];
                $temp_0 = stripslashes($row["content"]);
                $temp_1 = stripslashes($row["content_1"]);
                $temp_2 = stripslashes($row["content_2"]);
                $temp_3 = stripslashes($row["content_3"]);
                $temp_4 = stripslashes($row["content_4"]);
                $temp_5 = stripslashes($row["content_5"]);
                $temp_6 = stripslashes($row["content_6"]);
                $temp_7 = stripslashes($row["content_7"]);
                
                if (!empty($temp_1) && substr($temp_1,-1,1)!=='.'){
                    $temp_1 = $temp_1 . '.';
                }//endif
                
                if (!empty($temp_3) && substr($temp_3,-1,1)!=='.'){
                    $temp_3 = $temp_3 . '.';
                }//endif
                
                if (!empty($temp_4) && substr($temp_4,-1,1)!=='.'){
                    $temp_4 = $temp_4 . '.';
                }//endif
                
                switch ($temp_0){
                    case 'B':
                        
                        $temp = $temp_1;
                        if (empty($temp_2)){
                            $temp_2 = 'N/A';
                        }//endif
                        $temp = $temp . '&nbsp;(' . $temp_2 . ').&nbsp;<i>' . $temp_3 . '</i>&nbsp;' . $temp_4;
                        if (empty($temp_6)){
                            $temp = $temp . '&nbsp;'. $temp_5;
                        }//endif
                        else {
                            $temp = $temp . '&nbsp;<a TARGET="_blank" href="'. $temp_6 . '">' . $temp_5 .'</a>';
                        }//endelse
                        break;
                    case 'J':
                        if (!empty($temp_5) && substr($temp_5,-1,1)!=='.'){
                            $temp_5 = $temp_5 . '.';
                        }//endif
                        if (!empty($temp_6) && substr($temp_6,-1,1)!=='.'){
                            $temp_6 = $temp_6 . '.';
                        }//endif
                        if (!empty($temp_6)){
                            $temp_6 = '&nbsp;Pages&nbsp;' . $temp_6;
                        }//endif
                        $temp = $temp_1;
                        if (empty($temp_2)){
                            $temp_2 = 'N/A';
                        }//endif
                        $temp = $temp . '&nbsp;(' . $temp_2 . ').&nbsp;' . $temp_3 . '&nbsp;<i>' . $temp_4 . '</i>&nbsp;'. $temp_5. '&nbsp;'. $temp_6;
                        break;
                    case 'W':
                        $temp = $temp_1 . '&nbsp;<i>' . $temp_3 . '</i>&nbsp;' . $temp_4 . '&nbsp;Retrieved ' . $temp_2;
                        if (empty($temp_6)){
                            $temp = $temp . '.&nbsp;from'. $temp_5;
                        }//endif
                        else {
                            $temp = $temp . ',&nbsp;from&nbsp;<a TARGET="_blank" href="'. $temp_6 . '">' . $temp_5 .'</a>';
                        }//endelse
                        break;
                    case 'H':
                        $temp = $temp_1;
                        break;
                }//endcase
                
                $nbr = $i +1 . '.';
                $temp = str_replace('&nbsp;',' ',$temp);
                if (isset($_POST["btnPDF"])){
                    $pdf->SetFont('','',10);
                    
                    $pdf->SetX(15);
                    $temp = convert_for_html($temp,_tablesettingOFF,_striplinebreakOFF,_stripAtagOFF,_stripalllinefeedOFF);
                    
                    $html = '<div style="margin-left: 0mm; text-indent: -28px; padding-left: 28px;">'.$temp.'</div>';
                    $pdf->writeHTML($html);
                    
                    $pdf->Ln(3);
                }//endif
                else {
                    if ($allowupdate && ($p->admin_access_allowed('SZ') || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O')) && $p->update_allowed('')))){
                        echo '<tr><td style="padding-left: 1.8cm"><table width="100%"><tr><td style="vertical-align: top" width="1%"><a href="javascript:unitdescriptionupdate(' . $unitdescdetailkey . ',\'' .$locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'safari\',1,1,6)">'. $nbr . '</a></td><td>' . $temp .'</td></tr></table></td></tr>';
                    }//endif
                    else {
                        if ($_SESSION[$_GET["trid"] . "udstatus"]=='P'){
                            echo '<tr><td style="padding-left: 1.8cm"><table width="100%"><tr><td>' . $temp .'</td></tr></table></td></tr>';
                        }//endif
                        else {
                            echo '<tr><td style="padding-left: 1.8cm"><table width="100%"><tr><td style="vertical-align: top" width="1%">'. $nbr . '</td><td>' . $temp .'</td></tr></table></td></tr>';
                        }//endelse
                    }//endelse
                }//endelse
            }//endfor
            
            //Copyright
            $temp = 'Note that some material in lectures, assignments and other resources provided to students may contain direct quotations from the text book(s) and references listed.';
            if (isset($_POST["btnPDF"])){
                $pdf->SetFont('','',10);
                $pdf->SetX(15);
                $pdf->MultiCell('',5,$temp,0,1,'J',0);
            }//endif
            else {
                echo '<tr><td style="padding-left: 1.2cm; text-align: left"><br>'.$temp.'<br></td></tr>';
            }//endelse
            
            //Sequence===========================================================================================================================
            if (!isset($_POST["btnPDF"])){
                if ($allowupdate && ((($p->admin_access_allowed('SZ') && $p->update_allowed('P')) || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('X','U','O')) && $p->update_allowed('')) && !in_array($_SESSION[$_GET["trid"] . "admin"], array('D','B','J'))))){
                    echo '<tr><td style="Padding-left: 0.6cm"><a href="javascript:unitdescriptionupdate(\'\',\'' . $locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'seq\',1,1,9)">Sequence:</a></td></tr>';
                }//endif
                else {
                    echo '<tr><td style="Padding-left: 0.6cm"><b>Sequence:</b></td></tr>';
                }//endelse
            }//endelse
            
            //Sequence heading
            $temp = 'The following is an <b>approximate</b> guide to the sequence of topics in this course.';
            
            if (!isset($_POST["btnPDF"])){
                echo '<tr><td style="Padding-left: 0.6cm">' . $temp . '</td></tr>';
            }//endif
            
            $sql = "select *
            from unitdescriptiondetail
            where locationid = '$locationid'
            and termid = '$termid'
            and unitid = '$unitid'
            and udtype='seq'
            order by sequence";
            
            $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-090: ".mysqli_error($db));
            
            if (isset($_POST["btnPDF"])){
                if ( mysqli_num_rows($sql_ok) > 0){
                    $pdf->SetFont('','B',10);
                    $pdf->SetX(15);
                    $pdf->Cell('',10,'Sequence',0,1,'L',0);
                    $pdf->SetFont('','',10);
                    $pdf->SetX(25);
                    
                    $temp = convert_for_html($temp,_tablesettingOFF,_striplinebreakOFF,_stripAtagOFF,_stripalllinefeedOFF);
                    
                    $html = '<div style="margin-left: 0mm; text-align: left;">'.$temp.'</div>';
                    $pdf->writeHTML($html);
                    
                    $pdf->Ln();
                    
                    $html = '
            <table width="100%" style="margin-left: 0mm;" border="1" cellspacing="0" cellpadding="3">
            <thead>
            <tr>
            <td width="15%" style="background-color:#C0C0C0; font-weight: bold; text-align: center">Week(s)</td>
            <td style="background-color:#C0C0C0; font-weight: bold; text-align: center">Topic(s)</td>
            </tr>
            </thead>';
                }//endif
                
            }//endif
            else {
                echo '<tr><td style="padding-left: 1.2cm">';
                echo '<table width="100%" border="1" cellpadding="6" cellspacing="0">';
                echo '<tr><td width="1%" bgcolor="#C0C0C0">&nbsp;</td>';
                echo '<td width="10%" bgcolor="#C0C0C0" align="center"><b>Week(s)</b></td>';
                echo '<td width="*" bgcolor="#C0C0C0" align="center"><b>Topic(s)</b></td></tr>';
            }//endelse
            
            for ($i=0; $i < mysqli_num_rows($sql_ok); $i++){
                $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-091: ".mysqli_error($db));
                
                $unitdescdetailkey = $row["unitdescdetailkey"];
                $temp_0 = stripslashes($row["content"]);
                $temp_1 = stripslashes($row["content_1"]);
                
                $nbr = $i +1 . '.';
                
                if (isset($_POST["btnPDF"])){
                    
                    $pdf->SetX(15);
                    
                    $html = $html . '
            <tr>
            <td style="text-align: center;">'.$temp_0.'</td>
            <td>'.$temp_1.'</td>
            </tr>';
                    
                }//endif
                else {
                    if (empty($temp_1)){
                        $temp_1 = '&nbsp;';
                    }//endif
                    if ($allowupdate && ((($p->admin_access_allowed('SZ') && $p->update_allowed('P')) || (in_array($_SESSION[$_GET["trid"] . "usertype"], array('X','U','O')) && $p->update_allowed(''))) && !in_array($_SESSION[$_GET["trid"] . "admin"], array('D','B','J')))){
                        echo '<tr><td><a href="javascript:unitdescriptionupdate(' . $unitdescdetailkey . ',\'' .$locationid . '\',\''. $termid . '\',\''. $unitid . '\',\'seq\',1,1,9)">'. $nbr . '</a></td>' . '<td style="vertical-align: middle" align="center">'. $temp_0 .'<td style="vertical-align: top">'. $temp_1 .'</td></tr>';
                    }//endif
                    else {
                        echo '<tr><td>' . $nbr . '</td><td style="vertical-align: middle" align="center">'. $temp_0 .'<td style="vertical-align: top">'. $temp_1 .'</td></tr>';
                    }//endelse
                }//endelse
            }//endfor
            
            if (isset($_POST["btnPDF"])){
                if (mysqli_num_rows($sql_ok) > 0){
                    $html = $html . '</table>';
                    $pdf->writeHTML($html);
                    $pdf->Ln();
                }//endif
            }//endif
            else {
                echo '</td></tr></table><br>';
            }//endelse
            //END OF SEQUENCE ===========================================================================
            
            
            //Adopted Reference Style
            $sql = "select *
            from unitoutlinedetail
            where unitoutlinekey = '$unitoutlinekey'
            and uotype='style'";
            
            $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-155: ".mysqli_error($db));
            
            if (mysqli_num_rows($sql_ok)){
                $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-156: ".mysqli_error($db));
                
                $unitdescdetailkey = $row["unitdescdetailkey"];
                switch ($row["content"]){
                    case 'A':
                        $style = 'Australian Harvard';
                        break;
                    case 'C':
                        $style = 'Chicago';
                        break;
                    case 'M':
                        $style = 'MLA';
                        break;
                    case 'O':
                        $style = 'Other';
                        break;
                    case 'P':
                        $style = 'APA';
                        break;
                    case 'T':
                        $style = 'Turabian';
                        break;
                }//endcase
                $pleasespecify = '';
                $stylepdf = $style;
                if ($row["content_1"]!==''){
                    $pleasespecify = '(' . $row["content_1"] . ')';
                    $stylepdf = $style . '  (' . $row["content_1"] . ')'; ;
                }//endif
                
                if (isset($_POST["btnPDF"])){
                    $pdf->SetX(15);
                    $pdf->SetFont('','B',10);
                    $pdf->Cell('',10,'Adopted Reference Style',0,1,'L',0);
                    $pdf->SetFont('','',10);
                    $pdf->SetX(15);
                    $pdf->Cell('',10,$stylepdf,0,1,'L',0);
                }//endif
                else {
                    echo '<tr><td style="padding-left: 0.6cm"><b>Adopted Reference Style:</b></td></tr>';
                    echo '<tr><td style="padding-left: 0.6cm">'. $style .str_repeat('&nbsp;',2). $pleasespecify .'</td></tr>';
                }//endelse
            }//endif
            else {
                if (isset($_POST["btnPDF"])){
                    $pdf->SetFont('','B',10);
                    $pdf->Cell('',10,'Adopted Reference Style:',0,1,'L',0);
                    $pdf->SetFont('','',10);
                    $pdf->SetX(15);
                    $pdf->Cell('',10,$stylepdf,0,1,'L',0);
                }//endif
                else {
                    echo '<tr><td style="padding-left: 0.6cm><b>Adopted Reference Style:</b></td><td>&nbsp;</td></tr>';
                }//endelse
            }//endelse
            
            //Professional Standards / Competencies
            if (!isset($_POST["btnPDF"])){
                echo '<tr><td style="padding-left: 0.6cm"><br><b>Professional Standards / Competencies</b><br><br></td></tr>';
            }//endif
            
            $unitoutdetailkey = '';
            $sql = "select *
            from unitoutlinedetail
            where unitoutlinekey = '$unitoutlinekey'
            and uotype='prgatt'
            order by sequence";
            
            $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-157: ".mysqli_error($db));
            
            if (isset($_POST["btnPDF"])){
                if (mysqli_num_rows($sql_ok) > 0){
                    
                    
                    $pdf->AddPageByArray(array('margin_left' => 15,'margin_right' => 15,'margin_top' => 20,'margin_bottom' => 15,'margin_header' => 8,'margin_footer' => 8));
                    $pdf->SetX(15);
                    
                    $pdf->SetFont('','B',10);
                    $pdf->Cell('',6,'Professional Standards / Competencies:',0,1,'L',0);
                    
                }//endif
            }//endif
            else {
                echo '<tr><td style="padding-left: 0.6cm">';
                echo '<table width="100%" style="margin-top: -15px;" border="1" cellpadding="6" cellspacing="0">';
                echo '<tr>';
                echo '<td bgcolor="#C0C0C0" align="center"><b>Standard / Competency</b></td>';
            }//endelse
            
            $html = '
        <table width="100%" style="margin-left: 20;" border="0" cellspacing="0" cellpadding="3">';
            $linebreak = '';
            
            for ($i=0; $i < mysqli_num_rows($sql_ok); $i++){
                $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-158: ".mysqli_error($db));
                $unitoutdetailkey = $row["unitoutdetailkey"];
                
                $unitattributestandardversionid = stripslashes($row["content"]);
                
                $sql = "select uasv.*, uas.description as standarddescription
              from unitattributestandardversion as uasv
                inner join unitattributestandard as uas
                  on uas.unitattributestandardid = uasv.unitattributestandardid
              where uasv.unitattributestandardversionid = '$unitattributestandardversionid'
              and uasv.unitdescription = 'Y'";
                
                $attsql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-159: ".mysqli_error($db));
                
                if (mysqli_num_rows($attsql_ok) > 0){
                    
                    $attrow = mysqli_fetch_array($attsql_ok) or die(basename(__FILE__,'.php')."-160: ".mysqli_error($db));
                    
                    $description = stripslashes($attrow["standarddescription"]) . ': ' . stripslashes($attrow["description"]);
                    $showassessed = $attrow["showassessed"];
                    $ratinglevel = $attrow["ratinglevel"];
                    
                    $nbr = $i +1 . '.';
                    
                    $untattdata=array();
                    $previousuac = '';
                    $previousua = '';
                    $previousuad = '';
                    $previousunitid = '';
                    $previousual = '';
                    
                    if ($ratinglevel=='D'){
                        $sql = "select uo.unitid,
                         uoda.unitattributedetailid,
                         uoda.assessed,
                         uoda.`level`,
                         uac.description as uacdescription,
                         uac.sequence as uacsequence,
                         ua.unitattributeid,
                         ua.description  as uadescription,
                         ua.sequence as uasequence,
                         uad.unitattributedetailid,
                         uad.description as uaddescription,
                         uad.clarification as uadclarification,
                         uad.sequence as uadsequence,
                         ual.unitattributelevelid,
                         ual.label as uallabel,
                         ual.description as ualdescription,
                         ual.sequence as ualsequence,
                         u.`name`
                  from unitoutlinedetailattribute as uoda
                    inner join unitattributedetail as uad
                      on uad.unitattributedetailid = uoda.unitattributedetailid
                    inner join unitattribute as ua
                      on ua.unitattributeid = uad.unitattributeid
                    inner join unitattributecategory as uac
                      on uac.unitattributecategoryid = ua.unitattributecategoryid
                    inner join unitattributelevel as ual
                      on ual.unitattributelevelid = uoda.`level`
                    inner join unitoutlinedetail as uod
                      on uod.unitoutdetailkey = uoda.unitoutdetailkey
                    inner join unitoutline as uo
                      on uo.unitoutlinekey = uod.unitoutlinekey
                    inner join unit as u
                      on u.unitid = uo.unitid
                  where uoda.unitoutdetailkey = '$unitoutdetailkey'";
                    }//endif
                    else {
                        $sql = "select uo.unitid,
                         uoda.unitattributedetailid,
                         uoda.assessed,
                         uoda.`level`,
                         uac.description as uacdescription,
                         uac.sequence as uacsequence,
                         ua.unitattributeid,
                         ua.description  as uadescription,
                         ua.sequence as uasequence,
                         ual.unitattributelevelid,
                         ual.label as uallabel,
                         ual.description as ualdescription,
                         ual.sequence as ualsequence,
                         u.`name`
                  from unitoutlinedetailattribute as uoda
                    inner join unitattribute as ua
                      on ua.unitattributeid = uoda.unitattributedetailid
                    inner join unitattributecategory as uac
                      on uac.unitattributecategoryid = ua.unitattributecategoryid
                    inner join unitattributelevel as ual
                      on ual.unitattributelevelid = uoda.`level`
                    inner join unitoutlinedetail as uod
                      on uod.unitoutdetailkey = uoda.unitoutdetailkey
                    inner join unitoutline as uo
                      on uo.unitoutlinekey = uod.unitoutlinekey
                    inner join unit as u
                      on u.unitid = uo.unitid
                  where uoda.unitoutdetailkey = '$unitoutdetailkey'";
                    }//endif
                    
                    $attsql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-161: ".mysqli_error($db));
                    
                    for ($atti=0; $atti < mysqli_num_rows($attsql_ok); $atti++){
                        $attrow = mysqli_fetch_array($attsql_ok) or die(basename(__FILE__,'.php')."-162: ".mysqli_error($db));
                        
                        $untattdata[$idx]["unitid"] = $attrow["unitid"];
                        $untattdata[$idx]["unitattributedetailid"] = $attrow["unitattributedetailid"];
                        $untattdata[$idx]["assessed"] = $attrow["assessed"];
                        $untattdata[$idx]["level"] = $attrow["level"];
                        $untattdata[$idx]["uacdescription"] = $attrow["uacdescription"];
                        $untattdata[$idx]["uacsequence"] = $attrow["uacsequence"];
                        $untattdata[$idx]["uadescription"] = $attrow["uadescription"];
                        $untattdata[$idx]["uasequence"] = $attrow["uasequence"];
                        if ($ratinglevel=='D'){
                            $untattdata[$idx]["uaddescription"] = $attrow["uaddescription"];
                            $untattdata[$idx]["uadclarification"] = $attrow["uadclarification"];
                            $untattdata[$idx]["uadsequence"] = $attrow["uadsequence"];
                        }//endif
                        else {
                            $untattdata[$idx]["uaddescription"] = '';
                            $untattdata[$idx]["uadclarification"] = '';
                            $untattdata[$idx]["uadsequence"] = '';
                        }//endelse
                        $untattdata[$idx]["uallabel"] = $attrow["uallabel"];
                        $untattdata[$idx]["ualdescription"] = $attrow["ualdescription"];
                        $untattdata[$idx]["ualsequence"] = $attrow["ualsequence"];
                        $untattdata[$idx]["unitname"] = stripslashes($attrow["name"]);
                        
                        $idx++;
                        
                    }//endfor
                    
                    if (isset($_POST["btnPDF"])){
                        
                        $pdf->SetX(15);
                        
                        $description = convert_for_html($description,_tablesettingOFF,_striplinebreakOFF,_stripAtagOFF,_stripalllinefeedOFF);
                        
                        $html = $html .'
                <tr>
                <td colspan="3" style="font-weight: bold;"><br>' . $description.'</td>
                </tr>';
                        
                        if ($showassessed == 'Y'){
                            
                            $html = $html .'
                  <tr>
                  <td style="background-color:#C0C0C0;">Attribute</td>
                  <td width="12%" style="background-color:#C0C0C0; text-align: center">Assessed</td>
                  <td width="15%" style="background-color:#C0C0C0; text-align: center">Level</td>
                  </tr><br>';
                        }//endif
                        else {
                            
                            $html = $html .'
                  <tr>
                  <td colspan="2" style="background-color:#C0C0C0;">Attribute</td>
                  <td width="15%" style="background-color:#C0C0C0; text-align: center">Level</td>
                  </tr><br>';
                        }//endelse
                        
                    }//endif
                    if (empty($description)){
                        $description = '&nbsp;';
                    }//endif
                    
                    if (!isset($_POST["btnPDF"])){
                        echo '<tr><td colspan="5" style="vertical-align: top; color: red;">'. $description  .'</td></tr>';
                        
                        echo '<br><tr><td colspan="5"><table width="100%" align="center" border="0" cellpadding="6" cellspacing="0">';
                        
                        echo '<tr>';
                        echo '<td width="*" align="center" bgcolor="#C0C0C0" colspan="4"><b>Attribute</b></td>';
                        if ($showassessed == 'Y'){
                            echo '<td width="10%" align="center" bgcolor="#C0C0C0"><b>Assessed</b></td>';
                        }//endif
                        echo '<td width="10%" align="center" bgcolor="#C0C0C0"><b>Level</b></td>';
                        echo '</tr>';
                    }//endif
                    
                    usort($untattdata, 'sortAttUnit');
                    $break = false;
                    
                    foreach ($untattdata as $attdata=>$attrow){
                        
                        $unitid = $attrow["unitid"];
                        $unitname = $attrow["unitname"];
                        
                        if ($attrow["uacdescription"] !== $previousuac){
                            if (isset($_POST["btnPDF"])){
                                
                                $attrow["uacdescription"] = convert_for_html($attrow["uacdescription"],_tablesettingOFF,_striplinebreakOFF,_stripAtagOFF,_stripalllinefeedOFF);
                                
                                $html = $html .'
                    <tr>
                    <td colspan="3"><br>' . $attrow["uacdescription"].'</td>
                    </tr>';
                            }//endif
                            else {
                                echo '<tr>';
                                echo '<td colspan="4">';
                                echo $attrow["uacdescription"];
                                echo '</td>';
                                echo '</tr>';
                            }//endelse
                            $previousua = '';
                            $previousuad = '';
                        }//endif
                        
                        if ($attrow["uadescription"] !== $previousua){
                            if (isset($_POST["btnPDF"])){
                                
                                if ($ratinglevel == 'A'){
                                    if ($showassessed == 'Y'){
                                        
                                        $html = $html .'
                    <tr>
                    <td><br>'.$attrow["uadescription"].'</td>
                    <td width="12%" style="text-align: center">'.$assesseddisplay.'</td>
                    <td width="15%" style="text-align: center">'.$attrow["uallabel"].'</td>
                    </tr>';
                                    }//endif
                                    else {
                                        
                                        $attrow["uadescription"] = convert_for_html($attrow["uadescription"],_tablesettingOFF,_striplinebreakOFF,_stripAtagOFF,_stripalllinefeedOFF);
                                        
                                        $html = $html .'
                    <tr>
                    <td style="padding-left: 0.3cm; text-align: left;" colspan="2"><br>'.$attrow["uadescription"].'</td>
                    <td width="15%" style="text-align: center">'.$attrow["uallabel"].'</td>
                    </tr>';
                                    }//endelse
                                    
                                }//endif
                                else {
                                    
                                    $attrow["uadescription"] = convert_for_html($attrow["uadescription"],_tablesettingOFF,_striplinebreakOFF,_stripAtagOFF,_stripalllinefeedOFF);
                                    
                                    $html = $html . '
                    <tr>
                    <td style="padding-left: 0.3cm;"><br>'.$attrow["uadescription"].'</td>
                    </tr>';
                                }//endelse
                                
                            }//endif
                            else {
                                echo '<tr>';
                                echo '<td width="2%"></td>';
                                echo '<td colspan="3">';
                                echo $attrow["uadescription"];
                                echo '</td>';
                            }//endelse
                            $previousuad = '';
                            
                        }//endif
                        
                        $assesseddisplay = '&nbsp;';
                        if ($attrow["assessed"]){
                            $assesseddisplay = 'Yes';
                        }//endif
                        else {
                            $assesseddisplay = 'No';
                        }//endif
                        
                        if (isset($_POST["btnPDF"])){
                            if ($ratinglevel == 'D'){
                                
                                if ($showassessed == 'Y'){
                                    
                                    $attrow["uaddescription"] = convert_for_html($attrow["uaddescription"],_tablesettingOFF,_striplinebreakOFF,_stripAtagOFF,_stripalllinefeedOFF);
                                    
                                    $html = $html . '
                    <tr>
                    <td style="padding-left: 0.6cm; text-align: left;"><br>'.$attrow["uaddescription"].'</td>
                    <td width="12%" style="text-align: center">'.$assesseddisplay.'</td>
                    <td width="15%" style="text-align: center">'.$attrow["uallabel"].'</td>
                    </tr>';
                                    
                                }//endif
                                else {
                                    
                                    $attrow["uaddescription"] = convert_for_html($attrow["uaddescription"],_tablesettingOFF,_striplinebreakOFF,_stripAtagOFF,_stripalllinefeedOFF);
                                    
                                    $html = $html . '
                    <tr>
                    <td><br>'.$attrow["uaddescription"].'</td>
                    <td width="15%" style="text-align: center">'.$attrow["uallabel"].'</td>
                    </tr>';
                                    
                                }//endelse
                                
                            }//endif
                            
                        }//endif
                        else {
                            if ($ratinglevel == 'D'){
                                echo '</tr><tr>';
                                echo '<td width="2%"></td>';
                                echo '<td width="2%"></td>';
                                echo '<td width="*">' . '<span title="'.$attrow["uadclarification"].'">' . $attrow["uaddescription"] . '</span></td>';
                            }//endif
                            
                            if ($showassessed == 'Y'){
                                echo '<td width="2%"></td>';
                                echo '<td width="10%" align="center">' . $assesseddisplay . '</td>';
                            }//endif
                            else {
                                echo '<td width="2%"></td>';
                            }//endif
                            echo '<td width="10%" align="center">' . '<span title="'.$attrow["ualdescription"].'"</span>' . $attrow["uallabel"] . '</td>';
                            echo '</tr>';
                        }//endelse
                        
                        $previousuac = $attrow["uacdescription"];
                        $previousua = $attrow["uadescription"];
                        $previousuad = $attrow["uaddescription"];
                        $previousunitid = $attrow["unitid"];
                        $previousual = $attrow["uallabel"];
                        
                    }//endfor
                    if (!isset($_POST["btnPDF"])){
                        echo '</td></tr>';
                        echo '</table>';
                    }//endif
                    
                }//endif
                
            }//endfor
            
            if (isset($_POST["btnPDF"])){
                $html = $html . '</table>';
                $pdf->writeHTML($html);
                $pdf->Ln();
            }//endif
            else {
                echo '</td></tr>';
            }//endif
            
            if (!isset($_POST["btnPDF"])){
                echo '</table>';
                echo '</form>';
            }//endif
            else {
                $file = $unitid . '-' . $unitname . '.pdf';
                $pdf->Output($file,'D');
            }//endif
            
    }//endfunction
    
    function process_form(){
        
        global $p, $db, $subdisciplineid, $locationid, $termid, $unitid, $unitname, $unitlevel, $unitcreditpoint, $unitasced, $unitgradingbasis, $unitprofessionalengagement, $nosupplementary;
        
        if (isset($_POST["btnCancel"])){
            echo "<script language='javascript'> window.onerror = blockError; function blockError(){return true;} opener.parent.document.frmpeerreviewunitupdate.submit();</script>";
            echo "<script language='javascript'> this.close(); </script>";
        }//endif
        
        $sql_ok = $p->db_connect() or die(basename(__FILE__,'.php')."-163: ".mysqli_error($db));
        
        $locationid = $_SESSION[$_GET["trid"] . "lid"];
        $termid = $_SESSION[$_GET["trid"] . "tid"];
        $unitid = $_SESSION[$_GET["trid"] . "uid"];
        
        //Following gets only needed for entry from fdlMarks or peerreview
        if ($_GET["locationid"]){
            $locationid = $_GET["locationid"];
        }//endif
        
        if ($_GET["termid"]){
            $termid = $_GET["termid"];
        }//endif
        
        if ($_GET["unitid"]){
            $unitid = $_GET["unitid"];
        }//endif
        
        if (!getUnit($unitid, $unitname, $unitlevel, $unitcreditpoint, $unitasced, $unitsubdisciplineid, $unitdisciplineid, $unitacaddivid, $unitgradingbasis, $unitprofessionalengagement, $weboutline, $nosupplementary)){
            $unitname = 'No course found';
        }//endif
        
        $subdisciplineid = $unitsubdisciplineid;
        
        $sql = "select udstatus
            from unitlocation
            where locationid = '$locationid'
            and termid = '$termid'
            and unitid = '$unitid'";
        
        $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-164: ".mysqli_error($db));
        
        if (mysqli_num_rows($sql_ok)<>0){
            $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-165: ".mysqli_error($db));
            
            $_SESSION[$_GET["trid"] . "udstatus"] = $row["udstatus"];
        }//endif
        
        //If unit coordinator check if current data exists. If not create it from previous term.
        if (in_array($_SESSION[$_GET["trid"] . "usertype"], array('U','O'))){
            
            $sql = "select *
              from unitlocation
              where locationid = '$locationid'
              and termid = '$termid'
              and unitid = '$unitid'";
            
            $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-166: ".mysqli_error($db));
            
            if (mysqli_num_rows($sql_ok)<>0){//unitlocation exists so get unit description info
                
                //Author
                $sql = "select *
                from unitdescriptiondetail
                where locationid = '$locationid'
                and termid = '$termid'
                and unitid = '$unitid'
                and udtype='auth'";
                
                $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-167: ".mysqli_error($db));
                
                if (mysqli_num_rows($sql_ok)==0){//none found so use coordinator info
                    
                    $sql = "select usr.fullname
                  from unituser uu
                    inner join user as usr
                      on usr.userid = uu.userid
                  where uu.locationid = '$locationid'
                  and uu.termid = '$termid'
                  and uu.unitid = '$unitid'
                  and uu.`type` in ('O','U')";
                    
                    $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-168: ".mysqli_error($db));
                    
                    if (mysqli_num_rows($sql_ok)>0){
                        $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-169: ".mysqli_error($db));
                        
                        $fullname = addslashes($row["fullname"]);
                        
                        //insert new data
                        $sql = "insert into unitdescriptiondetail
                    values (NULL, '$locationid','$termid','$unitid','auth', 0,'$fullname',NULL,NULL,NULL,NULL,NULL,NULL,NULL)";
                        
                        $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-170: ".mysqli_error($db));
                        
                    }//endif
                }//endif
                
                //Check if unit description exists. if not create from previous
                $sql = "select *
                from unitdescriptiondetail
                where locationid = '$locationid'
                and termid = '$termid'
                and unitid = '$unitid'
                and udtype not in ('auth','staff','time','stdfbk')";
                
                $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-171: ".mysqli_error($db));
                
                if (mysqli_num_rows($sql_ok)==0){//none found so try previous term
                    
                    $sql = "select distinct termid
                  from unitdescriptiondetail
                  where locationid = '$locationid'
                  and termid = (select max(termid)
                                from unitdescriptiondetail
                                where locationid = '$locationid'
                                and termid < '$termid'
                                and unitid = '$unitid'
                                and udtype in ('seq','struct','tskdet','subret'))
                  and unitid = '$unitid'
                  and udtype in ('seq','struct','tskdet','subret')";
                    
                    $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-172: ".mysqli_error($db));
                    
                    if (mysqli_num_rows($sql_ok)==0){//none found so try copy master
                        
                        $sql = "select *
                    from unitdescriptiondetail as udd
                      inner join location as l
                        on l.locationid = udd.locationid
                    where l.copymaster = true
                    and udd.udtype not in ('auth','staff','time','stdfbk')
                    and udd.termid = (select max(udd1.termid)
                                    from unitdescriptiondetail as udd1
                                      inner join location as l1
                                        on l1.locationid = udd1.locationid
                                    where l1.copymaster = true
                                    and udd1.termid < '$termid'
                                    and udd1.unitid = '$unitid')
                    and udd.unitid = '$unitid'";
                        
                        $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-173: ".mysqli_error($db));
                        
                        for ($i=0; $i < mysqli_num_rows($sql_ok); $i++){
                            $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-174: ".mysqli_error($db));
                            
                            $udtype = $row["udtype"];
                            $sequence = $row["sequence"];
                            $content = $row["content"];
                            $content_1 = addslashes($row["content_1"]);
                            $content_2 = addslashes($row["content_2"]);
                            $content_3 = addslashes($row["content_3"]);
                            $content_4 = addslashes($row["content_4"]);
                            $content_5 = addslashes($row["content_5"]);
                            $content_6 = addslashes($row["content_6"]);
                            $content_7 = addslashes($row["content_7"]);
                            $content=str_replace("'",'`',$content);
                            $content_1=str_replace("'",'`',$content_1);
                            $content_2=str_replace("'",'`',$content_2);
                            $content_3=str_replace("'",'`',$content_3);
                            $content_4=str_replace("'",'`',$content_4);
                            $content_5=str_replace("'",'`',$content_5);
                            $content_6=str_replace("'",'`',$content_6);
                            $content_7=str_replace("'",'`',$content_7);
                            
                            //insert new data
                            $insertsql = "insert into unitdescriptiondetail
                            values (NULL, '$locationid','$termid','$unitid','$udtype', $sequence,'$content','$content_1','$content_2','$content_3','$content_4','$content_5','$content_6','$content_7')";
                            
                            $insertsql_ok = mysqli_query($db,$insertsql) or die(basename(__FILE__,'.php')."-175: ".mysqli_error($db));
                            
                        }//endfor
                        
                    }//endif
                    else {// Previous term found
                        $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-176: ".mysqli_error($db));
                        
                        $previoustermid = $row["termid"];
                        
                        $sql = "select *
                    from unitdescriptiondetail
                    where locationid = '$locationid'
                    and termid = '$previoustermid'
                    and unitid = '$unitid'
                    and udtype not in ('auth','staff','time','stdfbk')";
                        
                        $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-177: ".mysqli_error($db));
                        
                        for ($i=0; $i < mysqli_num_rows($sql_ok); $i++){
                            $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-178: ".mysqli_error($db));
                            
                            $udtype = $row["udtype"];
                            $sequence = $row["sequence"];
                            $content = $row["content"];
                            $content_1 = addslashes($row["content_1"]);
                            $content_2 = addslashes($row["content_2"]);
                            $content_3 = addslashes($row["content_3"]);
                            $content_4 = addslashes($row["content_4"]);
                            $content_5 = addslashes($row["content_5"]);
                            $content_6 = addslashes($row["content_6"]);
                            $content_7 = addslashes($row["content_7"]);
                            $content=str_replace("'",'`',$content);
                            $content_1=str_replace("'",'`',$content_1);
                            $content_2=str_replace("'",'`',$content_2);
                            $content_3=str_replace("'",'`',$content_3);
                            $content_4=str_replace("'",'`',$content_4);
                            $content_5=str_replace("'",'`',$content_5);
                            $content_6=str_replace("'",'`',$content_6);
                            $content_7=str_replace("'",'`',$content_7);
                            
                            //insert new data
                            $insertsql = "insert into unitdescriptiondetail
                            values (NULL, '$locationid','$termid','$unitid','$udtype', $sequence,'$content','$content_1','$content_2','$content_3','$content_4','$content_5','$content_6','$content_7')";
                            
                            $insertsql_ok = mysqli_query($db,$insertsql) or die(basename(__FILE__,'.php')."-179: ".mysqli_error($db));
                            
                        }//endfor
                        
                    }//endelse
                    
                }//endif
                
            }//endif
            
        }//endif
        
    }//endfunction
    
    function __construct(){
        basePage::basePageFunction();
    }//endfunction
    
}//endclass

class PDF extends \Mpdf\Mpdf{
    
    var $pdfHeaderTitle;
    var $pdfFirstPagePrinted;
    var $pdfFirstPageTitlePrinted;
    
    function Header($content = ''){
        
        $oldleftmargin = $this->lMargin;
        
        if (!$this->pdfFirstPagePrinted){
            //$this->Image('image/img_logo.jpg',123,0,84,28);
            $this->Image('image/img_logo_first_2019.jpg',0,0,550,140);
            $this->Ln(9);
            if ($_SESSION[$_GET["trid"] . "udstatus"]!=='P'){
                $this->SetFont('','B',10);
                $this->SetTextColor(255,192,203);
                $this->SetY(40);
                $this->Cell('',10,'Course Description Incomplete - Preview use only',0,1,'L');
                $this->SetTextColor(0,0,0);
            }//endif
            else {
                $this->SetFont('','B',16);
            }//endelse
            $this->SetLeftMargin(10);
            $this->SetFont('','',28);
            $this->SetY(55);
            $this->SetX(15);
            $this->Cell('arial',10,'Course Description (Higher Education)',0,1,'L');
            $this->SetY(55);
            $this->SetLeftMargin(10);
            $this->SetFont('','B',20);
            
            $this->SetX(10);
            
        }//endif
        
        $this->pdfFirstPagePrinted = true;
        
        if ($this->pdfFirstPageTitlePrinted){
            $this->Image('image/img_logo_2019.jpg',6.8,15.2,55.5,14.6);
            //$this->Image('image/line_header.png',120,0,50,50);
            $this->SetLeftMargin(10);
            $this->SetFont('arial','',12);
            $this->SetY(15);
            $this->SetX(125);
            $this->Cell('',10,'Course Description (Higher Education)',0,1,'L');
            $this->SetFont('arial','',10);
            $this->SetY(22);
            $this->SetX(125);
            if(strlen($this->pdfHeaderTitle) < 40){
                $this->Cell('',10,$this->pdfHeaderTitle,0,0,'L');
            }
            else {
                $this->MultiCell('',4,$this->pdfHeaderTitle,0,0,'L');
            }
            
            $this->Ln(3);
            $this->SetLeftMargin(10);
            $this->SetFont('','B',20);
            
            
            if ($_SESSION[$_GET["trid"] . "udstatus"]!=='P'){
                
                $this->SetFont('','B',10);
                $this->SetTextColor(255,192,203);
                $this->SetX(20);
                $this->SetY(30);
                $this->Cell('',10,'Course Description Incomplete - Preview use only',0,1,'L');
            }//endif
            else {
                $this->SetFont('','B',16);
            }//endelse
        }//endif
        
        $this->SetLeftMargin(10);
        $this->SetFont('','B',20);
        
        $this->SetX(10);
        
        $this->pdfFirstPageTitlePrinted = true;
        $this->Ln();
        
        $this->SetLeftMargin($oldleftmargin);
        
    }//endfunction
    
}//endclass

// MAIN
$p = new unitdescription_page();

//Usertype Z coming from fdlMarks
if ($_GET["type"]=='Z'){
    $_SESSION['mrkusertype'] = 'Z';
}//endif

if ($p->access_denied('BMPSZ','CLMORSTUXZ',$p->extra_access($_SESSION[$_GET["trid"] . 'userid'],"udstatus"))){
    exit;
}//endif

$p->process_form();

// "mrksysinstitution" used if coming from fdlMarks
$heading = $_SESSION[$_GET["trid"] . "sysname"] . " --> " . $_SESSION[$_GET["trid"] . "sysinstitution"] . $_SESSION["mrksysinstitution"] . " --> Course Description";

if (!isset($_POST["btnPDF"])){
    $p->display_html_header($heading);
}//endif

$p->display_page();

if (!isset($_POST["btnPDF"])){
    $p->display_html_footer();
}//endif

?>
