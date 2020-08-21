<?php

  function getCorequisite($unitid, $roundbracket, $csreq, $ignoreubsas, $reqeffectivetermid)
  {
      global $p, $db;

      if (empty($csreq)) {
          $csreq = 'requisite';
      }//endif
      else {
          $csreq = 'csrequisite';
      }//endelse
      $corequisitefinal = '';

      $ubsassql = '';
      if ($ignoreubsas) {
          $ubsassql = ' and length(requnitid) > 6 ';
      }//endif
    
      $reqeffectivetermidsql = " and req.reqeffectivetermid =
                                  (select max(req1.reqeffectivetermid)
                                   from $csreq as req1
                                   where req1.unitid = req.unitid
                                   and req1.reqeffectivetermid <= '$reqeffectivetermid') ";
      if (empty($reqeffectivetermid)) {
          $reqeffectivetermidsql = " and req.reqeffectivetermid =
                                  (select max(req1.reqeffectivetermid)
                                   from $csreq as req1
                                   where req1.unitid = req.unitid) ";
      }//endif

      //Category: Free-form
      $reqsql = "select *
               from $csreq as req
               where unitid = '$unitid'
               and category = 'F'
               and `type` = 'C'
               $reqeffectivetermidsql ";

      $reqsql_ok = mysqli_query($db, $reqsql) or die(basename(__FILE__, '.php')."-001: ".mysqli_error($db));

      for ($reqi=0; $reqi < mysqli_num_rows($reqsql_ok); $reqi++) {
          $reqrow = mysqli_fetch_array($reqsql_ok) or die(basename(__FILE__, '.php')."-002: ".mysqli_error($db));

          $requnitid = stripslashes($reqrow["requnitid"]);

          $corequisitefinal = $corequisitefinal . '{' . $requnitid . '} ';
      }//endfor

      //Category: Standard
      //Corequisite
      $reqsql = "select *
               from $csreq as req
               where unitid = '$unitid'
               and category = 'S'
               and `type` = 'C'
               and ifnull(linkedgroupid,'') = ''
               $ubsassql
               $reqeffectivetermidsql
               order by reqgroup, requnitid";

      $reqsql_ok = mysqli_query($db, $reqsql) or die(basename(__FILE__, '.php')."-003: ".mysqli_error($db));

      $previousreqgroup = '';
      $requnits = '{';
      for ($reqi=0; $reqi < mysqli_num_rows($reqsql_ok); $reqi++) {
          $reqrow = mysqli_fetch_array($reqsql_ok) or die(basename(__FILE__, '.php')."-004: ".mysqli_error($db));

          $reqgroup = $reqrow["reqgroup"];
          $requnitid = stripslashes($reqrow["requnitid"]);
          $optionality = $reqrow["optionality"];

          if ($previousreqgroup && $reqgroup !== $previousreqgroup) {
              $requnits = $requnits . '} ';

              $corequisitefinal = $corequisitefinal . $requnits;

              $requnits = '{';
          }//endif

          if ($optionality) {
              if ($requnits == '{') {
                  $requnits = $requnits . $requnitid;
              }//endif
              else {
                  $requnits = $requnits . ' or ' . $requnitid;
              }//endelse
          }//endif
      else {
          if ($requnits == '{') {
              $requnits = $requnits . $requnitid;
          }//endif
          else {
              $requnits = $requnits . ' and ' . $requnitid;
          }//endelse
      }//endelse

      $previousreqgroup = $reqgroup;
      }//endfor

      if ($requnits !== '{') {
          $requnits = $requnits . '} ';
          $corequisitefinal = $corequisitefinal . $requnits;
      }//endif

      //Corequisite = linked group
      $reqsql = "select *
               from $csreq as req
               where unitid = '$unitid'
               and category = 'S'
               and `type` = 'C'
               and ifnull(linkedgroupid,'') > ''
               $ubsassql
               $reqeffectivetermidsql
               order by linkedgroupid, reqgroup, requnitid";

      $reqsql_ok = mysqli_query($db, $reqsql) or die(basename(__FILE__, '.php')."-005: ".mysqli_error($db));

      $previousreqgroup = '';
      $previouslinkgroup = '';
      $requnits = '{';
      for ($reqi=0; $reqi < mysqli_num_rows($reqsql_ok); $reqi++) {
          $reqrow = mysqli_fetch_array($reqsql_ok) or die(basename(__FILE__, '.php')."-006: ".mysqli_error($db));

          $reqgroup = $reqrow["reqgroup"];
          $requnitid = stripslashes($reqrow["requnitid"]);
          $optionality = $reqrow["optionality"];
          $linkedgroupid = $reqrow["linkedgroupid"];

          if ($previousreqgroup && $reqgroup !== $previousreqgroup) {
              if ($previouslinkgroup == $linkedgroupid) {
                  $requnits = $requnits . '} <span style="color: red;">OR</span> ';

                  $corequisitefinal = $corequisitefinal . $requnits;

                  $requnits = '{';
              }//endif

              if ($requnits !== '{') {
                  $requnits = $requnits . '} ';

                  $corequisitefinal = $corequisitefinal . $requnits;
              }//endif

              $requnits = '{';
          }//endif

          if ($optionality) {
              if ($requnits == '{') {
                  $requnits = $requnits . $requnitid;
              }//endif
              else {
                  $requnits = $requnits . ' or ' . $requnitid;
              }//endelse
          }//endif
      else {
          if ($requnits == '{') {
              $requnits = $requnits . $requnitid;
          }//endif
          else {
              $requnits = $requnits . ' and ' . $requnitid;
          }//endelse
      }//endelse

      $previousreqgroup = $reqgroup;
          $previouslinkgroup = $linkedgroupid;
      }//endfor

      if ($requnits !== '{') {
          $requnits = $requnits . '} ';
          $corequisitefinal = $corequisitefinal . $requnits;
      }//endif

      //Category: credit point / level / subject-area
      //Corequisite
      $reqsql = "select *
               from $csreq as req
               where unitid = '$unitid'
               and category = 'D'
               and `type` = 'C'
               and ifnull(linkedgroupid,'') = ''
               $reqeffectivetermidsql
               order by reqgroup, requnitid";

      $reqsql_ok = mysqli_query($db, $reqsql) or die(basename(__FILE__, '.php')."-007: ".mysqli_error($db));

      $previousreqgroup = '';
      $requnits = '{';
      for ($reqi=0; $reqi < mysqli_num_rows($reqsql_ok); $reqi++) {
          $reqrow = mysqli_fetch_array($reqsql_ok) or die(basename(__FILE__, '.php')."-008: ".mysqli_error($db));

          $reqgroup = $reqrow["reqgroup"];
          $requnitid = stripslashes($reqrow["requnitid"]);
          $optionality = $reqrow["optionality"];
          $creditpoints = $reqrow["creditpoints"];
          $reqminimumlevel = $reqrow["reqminimumlevel"];
          $reqmaximumlevel = $reqrow["reqmaximumlevel"];

          if ($previousreqgroup && $reqgroup !== $previousreqgroup) {
              $requnits = $requnits . ' subject-area';
              $temp = str_replace('{', '{At least ' . $previouscreditpoints . ' credit points from ', $requnits);
              switch ($previousreqminimumlevel) {
          case '':
          case '#':
          case '0':
            $temp = $temp . ' at any level';
            break;
          default:
            $temp = $temp . ' at ' . $previousreqminimumlevel * 1000 . '-' . $previousreqmaximumlevel . '999 level';
            break;
        }//endswitch
              $temp = $temp . '} ';
              $corequisitefinal = $corequisitefinal . $temp;

              $requnits = '{';
          }//endif

          if ($optionality) {
              if ($requnits == '{') {
                  $requnits = $requnits . $requnitid;
              }//endif
              else {
                  $requnits = $requnits . ' or ' . $requnitid;
              }//endelse
          }//endif
      else {
          if ($requnits == '{') {
              $requnits = $requnits . $requnitid;
          }//endif
          else {
              $requnits = $requnits . ' and ' . $requnitid;
          }//endelse
      }//endelse

      $previousreqgroup = $reqgroup;
          $previouscreditpoints=$creditpoints;
          $previousreqminimumlevel=$reqminimumlevel;
          $previousreqmaximumlevel=$reqmaximumlevel;
      }//endfor

      if ($previousreqgroup) {
          $requnits = $requnits . ' subject-area';
          $temp = str_replace('{', '{At least ' . $creditpoints . ' credit points from ', $requnits);
          switch ($reqminimumlevel) {
        case '':
        case '#':
        case '0':
          $temp = $temp . ' at any level';
          break;
        default:
          $temp = $temp . ' at ' . $previousreqminimumlevel * 1000 . '-' . $previousreqmaximumlevel . '999 level';
          break;
      }//endswitch
          $temp = $temp . '} ';
          $corequisitefinal = $corequisitefinal . $temp;
      }//endif

      //Category: credit point / gpalevel / subject-area
      //Corequisite
      $reqsql = "select *
               from $csreq as req
               where unitid = '$unitid'
               and category = 'G'
               and `type` = 'C'
               and ifnull(linkedgroupid,'') = ''
               $reqeffectivetermidsql
               order by reqgroup, requnitid";

      $reqsql_ok = mysqli_query($db, $reqsql) or die(basename(__FILE__, '.php')."-009: ".mysqli_error($db));

      $previousreqgroup = '';
      $requnits = '{';
      for ($reqi=0; $reqi < mysqli_num_rows($reqsql_ok); $reqi++) {
          $reqrow = mysqli_fetch_array($reqsql_ok) or die(basename(__FILE__, '.php')."-010: ".mysqli_error($db));

          $reqgroup = $reqrow["reqgroup"];
          $requnitid = stripslashes($reqrow["requnitid"]);
          $optionality = $reqrow["optionality"];
          $creditpoints = $reqrow["creditpoints"];
          $reqminimumlevel = $reqrow["reqminimumlevel"];

          if ($previousreqgroup && $reqgroup !== $previousreqgroup) {
              $requnits = $requnits . ' subject-area';
              $temp = str_replace('{', '{At least ' . $previouscreditpoints . ' credit points from ', $requnits);
              $temp = $temp . ' at GPA ' . $previousreqminimumlevel  . ' or above';
              $temp = $temp . '} ';
              $corequisitefinal = $corequisitefinal . $temp;

              $requnits = '{';
          }//endif

          if ($optionality) {
              if ($requnits == '{') {
                  $requnits = $requnits . $requnitid;
              }//endif
              else {
                  $requnits = $requnits . ' or ' . $requnitid;
              }//endelse
          }//endif
      else {
          if ($requnits == '{') {
              $requnits = $requnits . $requnitid;
          }//endif
          else {
              $requnits = $requnits . ' and ' . $requnitid;
          }//endelse
      }//endelse

      $previousreqgroup = $reqgroup;
          $previouscreditpoints=$creditpoints;
          $previousreqminimumlevel=$reqminimumlevel;
      }//endfor

      if ($previousreqgroup) {
          $requnits = $requnits . ' subject-area';
          $temp = str_replace('{', '{At least ' . $creditpoints . ' credit points from ', $requnits);
          $temp = $temp . ' at GPA ' . $previousreqminimumlevel  . ' or above';
          $temp = $temp . '} ';
          $corequisitefinal = $corequisitefinal . $temp;
      }//endif

      //Category: credit point / level / subject-area
      //Corequisite
      $reqsql = "select *
               from $csreq as req
               where unitid = '$unitid'
               and category = 'P'
               and `type` = 'C'
               and ifnull(linkedgroupid,'') = ''
               $reqeffectivetermidsql
               order by reqgroup, requnitid";

      $reqsql_ok = mysqli_query($db, $reqsql) or die(basename(__FILE__, '.php')."-011: ".mysqli_error($db));

      $previousreqgroup = '';
      $requnits = '{';
      for ($reqi=0; $reqi < mysqli_num_rows($reqsql_ok); $reqi++) {
          $reqrow = mysqli_fetch_array($reqsql_ok) or die(basename(__FILE__, '.php')."-012: ".mysqli_error($db));

          $reqgroup = $reqrow["reqgroup"];
          $requnitid = stripslashes($reqrow["requnitid"]);
          $optionality = $reqrow["optionality"];
          $creditpoints = $reqrow["creditpoints"];
          $reqminimumlevel = $reqrow["reqminimumlevel"];
          $reqmaximumlevel = $reqrow["reqmaximumlevel"];

          if ($previousreqgroup && $reqgroup !== $previousreqgroup) {
              $requnits = 'program ' . $requnits;
              $temp = str_replace('{', '{At least ' . $previouscreditpoints . ' credit points from ', $requnits);
              $temp = $temp . '} ';
              $corequisitefinal = $corequisitefinal . $temp;

              $requnits = '{';
          }//endif

          if ($optionality) {
              if ($requnits == '{') {
                  $requnits = $requnits . $requnitid;
              }//endif
              else {
                  $requnits = $requnits . ' or ' . $requnitid;
              }//endelse
          }//endif

      $previousreqgroup = $reqgroup;
          $previouscreditpoints=$creditpoints;
          $previousreqminimumlevel=$reqminimumlevel;
          $previousreqmaximumlevel=$reqmaximumlevel;
      }//endfor

      if ($previousreqgroup) {
          $requnits = 'program ' . $requnits;
          $temp = str_replace('{', '{At least ' . $creditpoints . ' credit points from ', $requnits);
          $temp = $temp . '} ';
          $corequisitefinal = $corequisitefinal . $temp;
      }//endif

      //Category: List
      $reqsql = "select *
               from $csreq as req
               where unitid = '$unitid'
               and category = 'L'
               and `type` = 'C'
               and ifnull(linkedgroupid,'') = ''
               $reqeffectivetermidsql
               order by reqgroup, requnitid";

      $reqsql_ok = mysqli_query($db, $reqsql) or die(basename(__FILE__, '.php')."-013: ".mysqli_error($db));

      $previousreqgroup = '';
      $requnits = '{';
      for ($reqi=0; $reqi < mysqli_num_rows($reqsql_ok); $reqi++) {
          $reqrow = mysqli_fetch_array($reqsql_ok) or die(basename(__FILE__, '.php')."-014: ".mysqli_error($db));

          $reqgroup = $reqrow["reqgroup"];
          $requnitid = stripslashes($reqrow["requnitid"]);
          $optionality = $reqrow["optionality"];
          $creditpoints = $reqrow["creditpoints"];

          if ($previousreqgroup && $reqgroup !== $previousreqgroup) {
              $requnits = str_replace('{', '{At least ' . $creditpoints . ' credit points from ', $requnits);
              $requnits = $requnits . '} ';

              $corequisitefinal = $corequisitefinal . $requnits;

              $requnits = '{';
          }//endif

          $previousreqgroup = $reqgroup;

          if ($optionality) {
              if ($requnits == '{') {
                  $requnits = $requnits . $requnitid;
              }//endif
              else {
                  $requnits = $requnits . ' or ' . $requnitid;
              }//endelse
          }//endif
      else {
          if ($requnits == '{') {
              $requnits = $requnits . $requnitid;
          }//endif
          else {
              $requnits = $requnits . ' and ' . $requnitid;
          }//endelse
      }//endelse
      }//endfor

      if ($requnits !== '{') {
          $requnits = str_replace('{', '{At least ' . $creditpoints . ' credit points from ', $requnits);
          $requnits = $requnits . '} ';

          $corequisitefinal = $corequisitefinal . $requnits;
      }//endif

      //bracket type
      if ($roundbracket) {
          $corequisitefinal = str_replace('{', '(', $corequisitefinal);
          $corequisitefinal = str_replace('}', ')', $corequisitefinal);
      }//endif

      return $corequisitefinal;
  }//endfunction

  function getExclusion($unitid, $roundbracket, $csreq, $ignoreubsas, $reqeffectivetermid)
  {
      global $p, $db;

      if (empty($csreq)) {
          $csreq = 'requisite';
      }//endif
      else {
          $csreq = 'csrequisite';
      }//endelse
      $exrequisitefinal = '';

      $ubsassql = '';
      if ($ignoreubsas) {
          $ubsassql = ' and length(requnitid) > 6 ';
      }//endif
    
      $reqeffectivetermidsql = " and req.reqeffectivetermid =
                                  (select max(req1.reqeffectivetermid)
                                   from $csreq as req1
                                   where req1.unitid = req.unitid
                                   and req1.reqeffectivetermid <= '$reqeffectivetermid') ";
      if (empty($reqeffectivetermid)) {
          $reqeffectivetermidsql = " and req.reqeffectivetermid =
                                  (select max(req1.reqeffectivetermid)
                                   from $csreq as req1
                                   where req1.unitid = req.unitid) ";
      }//endif

      //Category: Free-form
      $reqsql = "select *
               from $csreq as req
               where unitid = '$unitid'
               and category = 'F'
               and `type` = 'E'
               $reqeffectivetermidsql";

      $reqsql_ok = mysqli_query($db, $reqsql) or die(basename(__FILE__, '.php')."-015: ".mysqli_error($db));

      for ($reqi=0; $reqi < mysqli_num_rows($reqsql_ok); $reqi++) {
          $reqrow = mysqli_fetch_array($reqsql_ok) or die(basename(__FILE__, '.php')."-016: ".mysqli_error($db));

          $requnitid = stripslashes($reqrow["requnitid"]);

          $exrequisitefinal = $exrequisitefinal . '&lt;' . $requnitid . '&gt; ';
      }//endfor

      //Exclusions
      $reqsql = "select *
               from $csreq as req
               where unitid = '$unitid'
               and category = 'S'
               and `type` = 'E'
               $ubsassql
               $reqeffectivetermidsql
               order by reqgroup, requnitid";

      $reqsql_ok = mysqli_query($db, $reqsql) or die(basename(__FILE__, '.php')."-017: ".mysqli_error($db));

      $previousreqgroup = '';
      $requnits = '&lt;';
      for ($reqi=0; $reqi < mysqli_num_rows($reqsql_ok); $reqi++) {
          $reqrow = mysqli_fetch_array($reqsql_ok) or die(basename(__FILE__, '.php')."-018: ".mysqli_error($db));

          $reqgroup = $reqrow["reqgroup"];
          $requnitid = stripslashes($reqrow["requnitid"]);
          $optionality = $reqrow["optionality"];

          if ($previousreqgroup && $reqgroup !== $previousreqgroup) {
              $requnits = $requnits . '&gt; ';

              $exrequisitefinal = $exrequisitefinal . $requnits;

              $requnits = '&lt;';
          }//endif

          $previousreqgroup = $reqgroup;

          if ($optionality) {
              if ($requnits == '&lt;') {
                  $requnits = $requnits . $requnitid;
              }//endif
              else {
                  $requnits = $requnits . ' or ' . $requnitid;
              }//endelse
          }//endif
      else {
          if ($requnits == '&lt;') {
              $requnits = $requnits . $requnitid;
          }//endif
          else {
              $requnits = $requnits . ' and ' . $requnitid;
          }//endelse
      }//endelse
      }//endfor

      if ($requnits !== '&lt;') {
          $requnits = $requnits . '&gt; ';
          $exrequisitefinal = $exrequisitefinal . $requnits;
      }//endif

      //bracket type
      if ($roundbracket) {
          $exrequisitefinal = str_replace('&lt;', '(', $exrequisitefinal);
          $exrequisitefinal = str_replace('&gt;', ')', $exrequisitefinal);
      }//endif

      return $exrequisitefinal;
  }//endfunction

  function getPrerequisite($unitid, $roundbracket, $csreq, $ignoreubsas, $reqeffectivetermid)
  {
      global $p, $db;

      if (empty($csreq)) {
          $csreq = 'requisite';
      }//endif
      else {
          $csreq = 'csrequisite';
      }//endelse
      $prerequisitefinal = '';

      $ubsassql = '';
      if ($ignoreubsas) {
          $ubsassql = ' and length(requnitid) > 6 ';
      }//endif
    
      $reqeffectivetermidsql = " and req.reqeffectivetermid =
                                  (select max(req1.reqeffectivetermid)
                                   from $csreq as req1
                                   where req1.unitid = req.unitid
                                   and req1.reqeffectivetermid <= '$reqeffectivetermid') ";
      if (empty($reqeffectivetermid)) {
          $reqeffectivetermidsql = " and req.reqeffectivetermid =
                                  (select max(req1.reqeffectivetermid)
                                   from $csreq as req1
                                   where req1.unitid = req.unitid) ";
      }//endif

      //Category: Free-form
      $reqsql = "select *
               from $csreq as req
               where unitid = '$unitid'
               and category = 'F'
               and `type` = 'P'
               $reqeffectivetermidsql";

      $reqsql_ok = mysqli_query($db, $reqsql) or die(basename(__FILE__, '.php')."-019: ".mysqli_error($db));

      for ($reqi=0; $reqi < mysqli_num_rows($reqsql_ok); $reqi++) {
          $reqrow = mysqli_fetch_array($reqsql_ok) or die(basename(__FILE__, '.php')."-020: ".mysqli_error($db));

          $requnitid = stripslashes($reqrow["requnitid"]);

          $prerequisitefinal = $prerequisitefinal . '[' . $requnitid . '] ';
      }//endfor

      //Category: Standard
      //Prerequisite
      $reqsql = "select *
               from $csreq as req
               where unitid = '$unitid'
               and category = 'S'
               and `type` = 'P'
               and ifnull(linkedgroupid,'') = ''
               $ubsassql
               $reqeffectivetermidsql
               order by reqgroup, requnitid";

      $reqsql_ok = mysqli_query($db, $reqsql) or die(basename(__FILE__, '.php')."-021: ".mysqli_error($db));

      $previousreqgroup = '';
      $requnits = '[';
      for ($reqi=0; $reqi < mysqli_num_rows($reqsql_ok); $reqi++) {
          $reqrow = mysqli_fetch_array($reqsql_ok) or die(basename(__FILE__, '.php')."-022: ".mysqli_error($db));

          $reqgroup = $reqrow["reqgroup"];
          $requnitid = stripslashes($reqrow["requnitid"]);
          $optionality = $reqrow["optionality"];

          if ($previousreqgroup && $reqgroup !== $previousreqgroup) {
              $requnits = $requnits . '] ';

              $prerequisitefinal = $prerequisitefinal . $requnits;

              $requnits = '[';
          }//endif

          if ($optionality) {
              if ($requnits == '[') {
                  $requnits = $requnits . $requnitid;
              }//endif
              else {
                  $requnits = $requnits . ' or ' . $requnitid;
              }//endelse
          }//endif
      else {
          if ($requnits == '[') {
              $requnits = $requnits . $requnitid;
          }//endif
          else {
              $requnits = $requnits . ' and ' . $requnitid;
          }//endelse
      }//endelse

      $previousreqgroup = $reqgroup;
      }//endfor

      if ($requnits !== '[') {
          $requnits = $requnits . '] ';
          $prerequisitefinal = $prerequisitefinal . $requnits;
      }//endif

      //Prerequisite = linked group
      $reqsql = "select *
               from $csreq as req
               where unitid = '$unitid'
               and category = 'S'
               and `type` = 'P'
               and ifnull(linkedgroupid,'') > ''
               $ubsassql
               $reqeffectivetermidsql
               order by linkedgroupid, reqgroup, requnitid";

      $reqsql_ok = mysqli_query($db, $reqsql) or die(basename(__FILE__, '.php')."-023: ".mysqli_error($db));

      $previousreqgroup = '';
      $previouslinkgroup = '';
      $requnits = '[';
      for ($reqi=0; $reqi < mysqli_num_rows($reqsql_ok); $reqi++) {
          $reqrow = mysqli_fetch_array($reqsql_ok) or die(basename(__FILE__, '.php')."-024: ".mysqli_error($db));

          $reqgroup = $reqrow["reqgroup"];
          $requnitid = stripslashes($reqrow["requnitid"]);
          $optionality = $reqrow["optionality"];
          $linkedgroupid = $reqrow["linkedgroupid"];

          if ($previousreqgroup && $reqgroup !== $previousreqgroup) {
              if ($previouslinkgroup == $linkedgroupid) {
                  $requnits = $requnits . '] <span style="color: red;">OR</span> ';

                  $prerequisitefinal = $prerequisitefinal . $requnits;

                  $requnits = '[';
              }//endif

              if ($requnits !== '[') {
                  $requnits = $requnits . '] ';

                  $prerequisitefinal = $prerequisitefinal . $requnits;
              }//endif

              $requnits = '[';
          }//endif

          if ($optionality) {
              if ($requnits == '[') {
                  $requnits = $requnits . $requnitid;
              }//endif
              else {
                  $requnits = $requnits . ' or ' . $requnitid;
              }//endelse
          }//endif
      else {
          if ($requnits == '[') {
              $requnits = $requnits . $requnitid;
          }//endif
          else {
              $requnits = $requnits . ' and ' . $requnitid;
          }//endelse
      }//endelse

      $previousreqgroup = $reqgroup;
          $previouslinkgroup = $linkedgroupid;
      }//endfor

      if ($requnits !== '[') {
          $requnits = $requnits . '] ';
          $prerequisitefinal = $prerequisitefinal . $requnits;
      }//endif

      //Category: credit point / gpa / subject-area
      //Prerequisite
      $reqsql = "select *
               from $csreq as req
               where unitid = '$unitid'
               and category = 'G'
               and `type` = 'P'
               and ifnull(linkedgroupid,'') = ''
               $reqeffectivetermidsql
               order by reqgroup, requnitid";

      $reqsql_ok = mysqli_query($db, $reqsql) or die(basename(__FILE__, '.php')."-025: ".mysqli_error($db));

      $previousreqgroup = '';
      $requnits = '[';
      for ($reqi=0; $reqi < mysqli_num_rows($reqsql_ok); $reqi++) {
          $reqrow = mysqli_fetch_array($reqsql_ok) or die(basename(__FILE__, '.php')."-026: ".mysqli_error($db));

          $reqgroup = $reqrow["reqgroup"];
          $requnitid = stripslashes($reqrow["requnitid"]);
          $optionality = $reqrow["optionality"];
          $creditpoints = $reqrow["creditpoints"];
          $reqminimumlevel = $reqrow["reqminimumlevel"];

          if ($previousreqgroup && $reqgroup !== $previousreqgroup) {
              $requnits = $requnits . ' subject-area';
              $temp = str_replace('[', '[At least ' . $previouscreditpoints . ' credit points from ', $requnits);
              $temp = $temp . ' at GPA ' . $previousreqminimumlevel  . ' or above';
              $temp = $temp . '] ';
              $prerequisitefinal = $prerequisitefinal . $temp;

              $requnits = '[';
          }//endif

          if ($optionality) {
              if ($requnits == '[') {
                  $requnits = $requnits . $requnitid;
              }//endif
              else {
                  $requnits = $requnits . ' or ' . $requnitid;
              }//endelse
          }//endif
      else {
          if ($requnits == '[') {
              $requnits = $requnits . $requnitid;
          }//endif
          else {
              $requnits = $requnits . ' and ' . $requnitid;
          }//endelse
      }//endelse

      $previousreqgroup = $reqgroup;
          $previouscreditpoints=$creditpoints;
          $previousreqminimumlevel=$reqminimumlevel;
      }//endfor

      if ($previousreqgroup) {
          $requnits = $requnits . ' subject-area';
          $temp = str_replace('[', '[At least ' . $creditpoints . ' credit points from ', $requnits);
          $temp = $temp . ' at GPA ' . $previousreqminimumlevel  . ' or above';
          $temp = $temp . '] ';
          $prerequisitefinal = $prerequisitefinal . $temp;
      }//endif

      //Category: credit point / level / subject-area
      //Prerequisite
      $reqsql = "select *
               from $csreq as req
               where unitid = '$unitid'
               and category = 'D'
               and `type` = 'P'
               and ifnull(linkedgroupid,'') = ''
               $reqeffectivetermidsql
               order by reqgroup, requnitid";

      $reqsql_ok = mysqli_query($db, $reqsql) or die(basename(__FILE__, '.php')."-027: ".mysqli_error($db));

      $previousreqgroup = '';
      $requnits = '[';
      for ($reqi=0; $reqi < mysqli_num_rows($reqsql_ok); $reqi++) {
          $reqrow = mysqli_fetch_array($reqsql_ok) or die(basename(__FILE__, '.php')."-028: ".mysqli_error($db));

          $reqgroup = $reqrow["reqgroup"];
          $requnitid = stripslashes($reqrow["requnitid"]);
          $optionality = $reqrow["optionality"];
          $creditpoints = $reqrow["creditpoints"];
          $reqminimumlevel = $reqrow["reqminimumlevel"];
          $reqmaximumlevel = $reqrow["reqmaximumlevel"];

          if ($previousreqgroup && $reqgroup !== $previousreqgroup) {
              $requnits = $requnits . ' subject-area';
              $temp = str_replace('[', '[At least ' . $previouscreditpoints . ' credit points from ', $requnits);
              switch ($previousreqminimumlevel) {
          case '':
          case '#':
          case '0':
            $temp = $temp . ' at any level';
            break;
          default:
            $temp = $temp . ' at ' . $previousreqminimumlevel * 1000 . '-' . $previousreqmaximumlevel . '999 level';
            break;
        }//endswitch
              $temp = $temp . '] ';
              $prerequisitefinal = $prerequisitefinal . $temp;

              $requnits = '[';
          }//endif

          if ($optionality) {
              if ($requnits == '[') {
                  $requnits = $requnits . $requnitid;
              }//emdif
              else {
                  $requnits = $requnits . ' or ' . $requnitid;
              }//endelse
          }//endif
      else {
          if ($requnits == '[') {
              $requnits = $requnits . $requnitid;
          }//endif
          else {
              $requnits = $requnits . ' and ' . $requnitid;
          }//endelse
      }//endelse

      $previousreqgroup = $reqgroup;
          $previouscreditpoints=$creditpoints;
          $previousreqminimumlevel=$reqminimumlevel;
          $previousreqmaximumlevel=$reqmaximumlevel;
      }//endfor

      if ($previousreqgroup) {
          $requnits = $requnits . ' subject-area';
          $temp = str_replace('[', '[At least ' . $creditpoints . ' credit points from ', $requnits);
          switch ($reqminimumlevel) {
        case '':
        case '0':
          $temp = $temp . ' at any level';
        break;
        default:
          $temp = $temp . ' at ' . $previousreqminimumlevel * 1000 . '-' . $previousreqmaximumlevel . '999 level';
          break;
      }//endswitch
          $temp = $temp . '] ';
          $prerequisitefinal = $prerequisitefinal . $temp;
      }//endif

      //Category: credit point / program
      //Prerequisite
      $reqsql = "select *
               from $csreq as req
               where unitid = '$unitid'
               and category = 'M'
               and `type` = 'P'
               and ifnull(linkedgroupid,'') = ''
               $reqeffectivetermidsql
               order by reqgroup, requnitid";

      $reqsql_ok = mysqli_query($db, $reqsql) or die(basename(__FILE__, '.php')."-029: ".mysqli_error($db));

      $previousreqgroup = '';
      $requnits = '[';
      for ($reqi=0; $reqi < mysqli_num_rows($reqsql_ok); $reqi++) {
          $reqrow = mysqli_fetch_array($reqsql_ok) or die(basename(__FILE__, '.php')."-030: ".mysqli_error($db));

          $reqgroup = $reqrow["reqgroup"];
          $requnitid = stripslashes($reqrow["requnitid"]);
          $optionality = $reqrow["optionality"];
          $creditpoints = $reqrow["creditpoints"];
          $reqminimumlevel = $reqrow["reqminimumlevel"];
          $reqmaximumlevel = $reqrow["reqmaximumlevel"];

          if ($previousreqgroup && $reqgroup !== $previousreqgroup) {
              $temp = str_replace('[', '[At least ' . $previouscreditpoints . ' credit points from program ', $requnits);
              $temp = $temp . '] ';
              $prerequisitefinal = $prerequisitefinal . $temp;

              $requnits = '[';
          }//endif

          if ($optionality) {
              if ($requnits == '[') {
                  $requnits = $requnits . $requnitid;
              }//endif
              else {
                  $requnits = $requnits . ' or ' . $requnitid;
              }//endelse
          }//endif

      $previousreqgroup = $reqgroup;
          $previouscreditpoints=$creditpoints;
          $previousreqminimumlevel=$reqminimumlevel;
          $previousreqmaximumlevel=$reqmaximumlevel;
      }//endfor

      if ($previousreqgroup) {
          $temp = str_replace('[', '[At least ' . $creditpoints . ' credit points from program ', $requnits);
          $temp = $temp . '] ';
          $prerequisitefinal = $prerequisitefinal . $temp;
      }//endif

      //Category: List
      $reqsql = "select *
               from $csreq as req
               where unitid = '$unitid'
               and category = 'L'
               and `type` = 'P'
               and ifnull(linkedgroupid,'') = ''
               $reqeffectivetermidsql
               order by reqgroup, requnitid";

      $reqsql_ok = mysqli_query($db, $reqsql) or die(basename(__FILE__, '.php')."-031: ".mysqli_error($db));

      $previousreqgroup = '';
      $requnits = '[';
      for ($reqi=0; $reqi < mysqli_num_rows($reqsql_ok); $reqi++) {
          $reqrow = mysqli_fetch_array($reqsql_ok) or die(basename(__FILE__, '.php')."-032: ".mysqli_error($db));

          $reqgroup = $reqrow["reqgroup"];
          $requnitid = stripslashes($reqrow["requnitid"]);
          $optionality = $reqrow["optionality"];
          $creditpoints = $reqrow["creditpoints"];

          if ($previousreqgroup && $reqgroup !== $previousreqgroup) {
              $requnits = str_replace('[', '[At least ' . $creditpoints . ' credit points from ', $requnits);
              $requnits = $requnits . '] ';

              $prerequisitefinal = $prerequisitefinal . $requnits;

              $requnits = '[';
          }//endif

          if ($optionality) {
              if ($requnits == '[') {
                  $requnits = $requnits . $requnitid;
              }//endif
              else {
                  $requnits = $requnits . ' or ' . $requnitid;
              }//endelse
          }//endif
      else {
          if ($requnits == '[') {
              $requnits = $requnits . $requnitid;
          }//endif
          else {
              $requnits = $requnits . ' and ' . $requnitid;
          }//endelse
      }//endelse

      $previousreqgroup = $reqgroup;
      }//endfor

      if ($requnits !== '[') {
          $requnits = str_replace('[', '[At least ' . $creditpoints . ' credit points from ', $requnits);
          $requnits = $requnits . '] ';

          $prerequisitefinal = $prerequisitefinal . $requnits;
      }//endif

      //Category: Program
      //Prerequisite
      $reqsql = "select *
               from $csreq as req
               where unitid = '$unitid'
               and category = 'P'
               and `type` = 'P'
               and ifnull(linkedgroupid,'') = ''
               $reqeffectivetermidsql
               order by reqgroup, requnitid";

      $reqsql_ok = mysqli_query($db, $reqsql) or die(basename(__FILE__, '.php')."-033: ".mysqli_error($db));

      $previousreqgroup = '';
      $requnits = '[';
      for ($reqi=0; $reqi < mysqli_num_rows($reqsql_ok); $reqi++) {
          $reqrow = mysqli_fetch_array($reqsql_ok) or die(basename(__FILE__, '.php')."-034: ".mysqli_error($db));

          $reqgroup = $reqrow["reqgroup"];
          $requnitid = stripslashes($reqrow["requnitid"]);
          $optionality = $reqrow["optionality"];

          if ($previousreqgroup && $reqgroup !== $previousreqgroup) {
              $requnits = $requnits . '] ';

              $prerequisitefinal = $prerequisitefinal . $requnits;

              $requnits = '[';
          }//endif

          if ($optionality) {
              if ($requnits == '[') {
                  $requnits = $requnits . 'Enrolled in program ' . $requnitid;
              }//endif
              else {
                  $requnits = $requnits . ' or ' . $requnitid;
              }//endelse
          }//endif

      $previousreqgroup = $reqgroup;
      }//endfor

      if ($requnits !== '[') {
          $requnits = $requnits . '] ';
          $prerequisitefinal = $prerequisitefinal . $requnits;
      }//endif

      //bracket type
      if ($roundbracket) {
          $prerequisitefinal = str_replace('[', '(', $prerequisitefinal);
          $prerequisitefinal = str_replace(']', ')', $prerequisitefinal);
      }//endif

      return $prerequisitefinal;
  }//endfunction

  function getRequisite($unitid, $roundbracket, $csreq, $ignoreubsas, $reqeffectivetermid)
  {
      $requisitefinal = '';

      $requisitefinal = $requisitefinal . getPrerequisite($unitid, $roundbracket, $csreq, $ignoreubsas, $reqeffectivetermid);

      $requisitefinal = $requisitefinal . getCorequisite($unitid, $roundbracket, $csreq, $ignoreubsas, $reqeffectivetermid);

      $requisitefinal = $requisitefinal . getExclusion($unitid, $roundbracket, $csreq, $ignoreubsas, $reqeffectivetermid);

      return trim($requisitefinal);
  }//endfunction
