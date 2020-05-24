<?php

include_once("basePage.php");

class courseplanelectivelookup_page extends basePage{

  function display_page(){ // Body of page.
  
    global $p, $db, $message;
    
    $sql_ok = $p->db_connect() or die(basename(__FILE__,'.php')."-01: ".mysqli_error($db));
    
    $formname = $_GET["formname"];
    $fieldname = $_GET["fieldname"];
    
    echo '
    <script language="javascript">
    
      window.onerror = blockError;
    
      function blockError(){
        return true;
      }//endfunction
      
      sWidth = screen.width;
	    sHeight = screen.height;
	    sLeft = (sWidth - (sWidth *.28)) /2;
	    sTop = (sHeight - (sHeight *.8)) /2;
    
      function returnunitid(argunitid){
        opener.parent.document.' . $formname . '.' . $fieldname . '.value=argunitid;
        opener.parent.' . $formname . '.submit();
        this.close();
      }//endfunction
      
      function help(){
        newWindow=window.open("help.php?goto=admcrseprgstructeleclkup","fdlghelp","resizable=yes, scrollbars=yes, menubar=yes, width=" + sWidth *.9  + ", height=" + sHeight *.8 + ", top=" + sTop + ", left=" + sLeft + "");
        newWindow.focus();
      }//endfunction
      
    </script>';
    
?>
    </head>
    
    <body topmargin="1" onload='document.frmcourseplanelectivelookup.txtDescription.focus()'>

      <form name="frmcourseplanelectivelookup" method="post">

        <style>
          span.small {font-family: Arial; font-size: 12}
          span.smallred {font-family: Arial; font-size: 12; color:red}
          span.boldred {color:red; font-weight:bold}     
          td {font-family: Arial; font-size: 14}
          span.hlp {font-family: Arial; font-size: 18}
        </style>
        
          
        <?php
          
          if ($_GET["courseplanid"]){
            $courseplanid = $_GET["courseplanid"];
          }//endif
                    
          $electivelookup = false;
          if ($_GET["preapproved"] == 'L'){
            $electivelookup = true;
          }//endif
          else {
            $preapproved = $_GET["preapproved"];
          }//endelse
          
          echo '<br>'; 
          echo '<table align="center" bgcolor="#e6e6fa" width="100%" border="1" bordercolor="#0000FF" cellpadding="3" cellspacing="0">';
          echo '<tr>';
          echo '<td align="center" colspan="2"><br>';
                    
          echo '&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="btnCancel" value="Cancel">';
          
          // Process elective items          
          if ($courseplanid){
            echo '<br><br><table align="center" width="100%" border="1" bordercolor="#0000FF" cellpadding="6" cellspacing="0">';
            echo '<tr>';
            echo '<td width="4%" bgcolor="#C0C0C0">&nbsp;</td>';
            echo '<td align="center" width="12%" bgcolor="#C0C0C0"><b>Group</b></td>';
            echo '<td align="center" bgcolor="#C0C0C0"><b>Specialisation Electives<span class="small"> (Bold = already used) </span><span class="smallred"> (Red = already used as non Specialisation) </span></b></td>';
            echo '</tr>';
          
            $previousgroup='';
            $bgcolor='';
            $usedunitid = $_GET["usedunitid"];
            $preapprovedsql = " and specialisationapprovedlookup = 'S' ";
            if ($preapproved){
              $preapprovedsql = " and `group` = '$preapproved' ";
            }//endif
            if ($electivelookup){
              $preapprovedsql = " and specialisationapprovedlookup = 'L' ";
            }//endif
            
            //Load courseplan
            $sql = "select *
                    from courseplanelective
                    where courseplanid = '$courseplanid'
                    $preapprovedsql
                    order by `group`
                            ,unitid";
          
            $sql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-02: ".mysqli_error($db));
          
            for ($i=0; $i < mysqli_num_rows($sql_ok); $i++){
              $row = mysqli_fetch_array($sql_ok) or die(basename(__FILE__,'.php')."-03: ".mysqli_error($db));
              
              echo '<tr>';
              
              $lineid = $row["lineid"];
              $group = $row["group"];
              $groupname = $row["groupname"];
              $unitid = $row["unitid"];
              $orunitid = $row["orunitid"];
              
              $sql = "select *
                      from unit
                      where unitid = '$unitid'";
          
              $unitidsql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-04: ".mysqli_error($db));
              
              $selectable = true;
              if (mysqli_num_rows($unitidsql_ok)){
              
                $unitidrow = mysqli_fetch_array($unitidsql_ok) or die(basename(__FILE__,'.php')."-05: ".mysqli_error($db));
                
                $temp = explode('|',$usedunitid);
                if (in_array($unitid, $temp) || in_array($unitid . '!', $temp)){
                  if (in_array($unitid . '!', $temp)){
                    $unitidname = '<span class="boldred">' .$unitid . ' ' . $unitidrow["name"] . '</span>';
                  }//endif
                  else {
                    $unitidname = '<b>' .$unitid . ' ' . $unitidrow["name"] . '</b>';
                    $selectable = false;
                  }//endelse
                }//endif
                else {
                  $unitidname = $unitid . ' ' . $unitidrow["name"];
                }//endelse
                
              }//endif
              else {
                $unitidname = $unitid;
              }//endelse
              
              if (!empty($orunitid)){
                $sql = "select *
                        from unit
                        where unitid = '$orunitid'";
            
                $orunitidsql_ok = mysqli_query($db,$sql) or die(basename(__FILE__,'.php')."-06: ".mysqli_error($db));
                
                if (mysqli_num_rows($orunitidsql_ok)){
                
                  $orunitidrow = mysqli_fetch_array($orunitidsql_ok) or die(basename(__FILE__,'.php')."-07: ".mysqli_error($db));
                  
                  $temp = explode('|',$usedunitid);
                  if (in_array($orunitid, $temp) || in_array($orunitid . '!', $temp)){
                    if (in_array($orunitid . '!', $temp)){
                      $orunitidname = '<span class="boldred">' .$orunitid . ' ' . stripslashes($orunitidrow["name"]) . '</span>';
                    }//endif
                    else {
                      $orunitidname = '<b>' .$orunitid . ' ' . stripslashes($orunitidrow["name"]) . '</b>';
                      $selectable = false;
                    }//endelse
                  }//endif
                  else {
                    $orunitidname = $orunitid . ' ' . stripslashes($orunitidrow["name"]);
                  }//endelse
                  
                  if (!empty($orunitid)){
                    if (in_array($orunitid, $temp) || in_array($orunitid . '!', $temp)){
                      if (in_array($orunitid . '!', $temp)){
                        $orunitidname = '<span class="boldred">' .$orunitid . ' ' . stripslashes($orunitidrow["name"]) . '</span>';
                      }//endif
                      else {
                        $orunitidname = '<b>' .$orunitid . ' ' . stripslashes($orunitidrow["name"]) . '</b>';
                      }//endelse
                    }//endif
                    else {
                      $orunitidname = $orunitid . ' ' . stripslashes($orunitidrow["name"]);
                    }//endelse
                  
                  }//endif
                  
                }//endif
                else {              
                  if (!empty($orunitid)){
                    $orunitidname = $orunitid;
                  }//endif
                }//endelse
              }//endif
                
            
              if ($group!= $previousgroup){
                $previousgroup = $group;
                if ($bgcolor == ' bgcolor="#FFFFFF"'){
                  $bgcolor = ' bgcolor="#FFCC99"';
                }//endif
                else {
                  $bgcolor = ' bgcolor="#FFFFFF"';
                }//endelse
              }//endif
            
              $selectedunitidname = 'chklocationchosen' . $i;
              
              $selectedunitid = "<input type='checkbox' onclick='javascript:returnunitid(\"$unitid\")' name='$selectedunitidname'>";
              if ($selectable){
                echo '<td ' . $bgcolor . ' align="center">' . $selectedunitid .'</td>';
              }//endif
              else {
                echo '<td ' . $bgcolor . ' align="center">&nbsp;</td>';
              }//endelse
              
              echo '<td ' . $bgcolor . ' align="center" title="Specialisation name: '.$groupname.'">'. $group . '</td>';  
              echo '<td ' . $bgcolor . ' align="left" title="Specialisation name: '.$groupname.'">'. $unitidname .'</a>';
              if (!empty($orunitid)){
                $selectedorunitidname = 'chkorunitidchosen' . $i;
                $selectedorunitid = "<input type='checkbox' onclick='javascript:returnunitid(\"$orunitid\")' name='$selectedorunitidname'>";
                
                if ($selectable){
                  echo '&nbsp;&nbsp;<span style="color:magenta; font-weight:bold;">OR</span>&nbsp;&nbsp;' . $orunitidname . '&nbsp;'. $selectedorunitid;
                }//endif
                else {
                  echo '&nbsp;&nbsp;<span style="color:magenta; font-weight:bold;">OR</span>&nbsp;&nbsp;' . $orunitidname;
                }//endelse
                
              }//endif
              
              echo '</td></tr>'; 
              
            }//endfor
            
            echo '</table>';
          }//endif    
                       
        ?>
            
      </form>

    <?php
    
  }//endfunction

  function process_page(){
  
    if (isset($_POST["btnCancel"])){
      echo "<script language='javascript'> this.close(); </script>";  
    }//endif
  }//endfunction
  
  function __construct(){
    basePage::basePageFunction();
  }//endfunction

}//endclass


// Instantiate this page
$p = new courseplanelectivelookup_page();

if (empty($_SESSION["mrkaccessallowed"])){
  exit;
}//endif

$p->process_page();

// Output page.
$heading = "fdlMarks --> " . $_SESSION["mrksysinstitution"] . " --> Specialisation Elective Lookup";
$p->display_html_header($heading);
$p->display_page();
$p->display_html_footer();

?>
