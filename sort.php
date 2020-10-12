<?php
/**
 * Php version 7.4
 *
 * @category Unitlook_Up_Display
 * @package  Fdlmarks_Courseplanelectivelookuppage_Class
 * @author   Spencer Booth-Jeffs <email@email.com>
 * @license  Federation University
 * @link     federation.edu.au 
 */

/**
 * Undocumented function
 *
 * @param [in Array] $x funcion fot sorting
 * @param [Array] $y
 * 
 * @return void
 */
function sorttask($x, $y)
{
    if (in_array($_SESSION[$_GET["trid"] . "usertype"], array('C','S','X','T'))) {
        switch ($_SESSION[$_GET["trid"] . "sorttask"]) {
    case '01':
      $sortfield = 'tpp01';
      break;
    case '02':
      $sortfield = 'tpp02';
      break;
    case '03':
      $sortfield = 'tpp03';
      break;
    case '04':
      $sortfield = 'tpp04';
      break;
    case '05':
      $sortfield = 'tpp05';
      break;
    case '06':
      $sortfield = 'tpp06';
      break;
    case '07':
      $sortfield = 'tpp07';
      break;
    case '08':
      $sortfield = 'tpp08';
      break;
    case '09':
      $sortfield = 'tpp09';
      break;
    case '10':
      $sortfield = 'tpp10';
      break;
    case '11':
      $sortfield = 'tpp11';
      break;
    case '12':
      $sortfield = 'tpp12';
      break;
    }//endcase
    }//endif
  else {
      switch ($_SESSION[$_GET["trid"] . "sorttask"]) {
    case '01':
      $sortfield = 'mod01';
      break;
    case '02':
      $sortfield = 'mod02';
      break;
    case '03':
      $sortfield = 'mod03';
      break;
    case '04':
      $sortfield = 'mod04';
      break;
    case '05':
      $sortfield = 'mod05';
      break;
    case '06':
      $sortfield = 'mod06';
      break;
    case '07':
      $sortfield = 'mod07';
      break;
    case '08':
      $sortfield = 'mod08';
      break;
    case '09':
      $sortfield = 'mod09';
      break;
    case '10':
      $sortfield = 'mod10';
      break;
    case '11':
      $sortfield = 'mod11';
      break;
    case '12':
      $sortfield = 'mod12';
      break;
    }//endcase
    }//endelse
    /**
     * Undocumented function
     *
     * @param [Varchar] $x 
     * @param [Varchar] $y
     * 
     * @return void
     */
    function sortname($x, $y)
    {
        if ($x[$sortfield]==$y[$sortfield]) {
            return (sortName($x, $y));
        }//endif
        elseif ($x[$sortfield] < $y[$sortfield]) {
            return -1;
        }//endelseif
        else {
            return 1;
        }//endelse
    }//endfunction
/**
 * Undocumented function
 *
 * @param [Varchar] $x Function for sorting Adminission
 * @param [Varchar] $y
 * 
 * @return void
 */
    function sortAdmission($x, $y)
    {
        if ($x['sacode']==$y['sacode']) {
            return (sortAdmissionvalue($x, $y));
        }//endif
        elseif ($x['sacode'] < $y['sacode']) {
            return -1;
        }//endelseif
        else {
            return 1;
        }//endelse
    }//endfunction

/**
 * Undocumented function
 *
 * @param [Varchar] $x Function for sorting AdmissionValue
 * @param [Varchar] $y
 * 
 * @return void
 */
    function sortAdmissionvalue($x, $y)
    {
        if ($x['saattributevalue']==$y['saattributevalue']) {
            return (sortTotal($x, $y));
        }//endif
        elseif ($x['saattributevalue'] < $y['saattributevalue']) {
            return -1;
        }//endelseif
        else {
            return 1;
        }//endelse
    }//endfunction
/**
 * Undocumented function
 *
 * @param [Varchar] $x Function for sorting AdmitType
 * @param [Varchar] $y
 * 
 * @return void
 */
    function sortAdmittype($x, $y)
    {
        if ($x['admittype']==$y['admittype']) {
            return (sortName($x, $y));
        }//endif
        elseif ($x['admittype'] < $y['admittype']) {
            return -1;
        }//endelseif
        else {
            return 1;
        }//endelse
    }//endfunction
/**
 * Undocumented function
 *
 * @param [Varchar] $x Function for sorting assesments 
 * @param [Varchar] $y
 * 
 * @return void
 */
    function sortAssessment($x, $y)
    {
        $sortfield = $_SESSION[$_GET["trid"] . "assessment"];
        if ($x[$sortfield]==$y[$sortfield]) {
            return 0;
        }//endif
        elseif ($x[$sortfield] < $y[$sortfield]) {
            return 1;
        }//endelseif
        else {
            return -1;
        }//endelse
    }//endfunction
/**
 * Undocumented function
 *
 * @param [Varchar] $x Function for sorting ApplDayNew
 * @param [Varchar] $y
 * 
 * @return void
 */
    function sortApplDaysNew($x, $y)
    {
        if ($x['days']==$y['days']) {
            return 0;
        }//endif
        elseif ($x['days'] < $y['days']) {
            return -1;
        }//endelseif
        else {
            return 1;
        }//endelse
    }//endfunction
/**
 * Undocumented function
 *
 * @param Varchar] $x Function for sorting ApplDaysOld
 * @param [Varchar] $y
 * 
 * @return void
 */
    function sortApplDaysOld($x, $y)
    {
        if ($x['days']==$y['days']) {
            return 0;
        }//endif
        elseif ($x['days'] < $y['days']) {
            return 1;
        }//endelseif
        else {
            return -1;
        }//endelse
    }//endfunction
/**
 * Undocumented function
 *
 * @param [varchar key] $x function for sorting applStrandID
 * @param [Varchar Key] $y
 * 
 * @return void
 */
    function sortApplStrandid($x, $y)
    {
        if ($x['strandid']==$y['strandid']) {
            return 0;
        }//endif
        elseif ($x['strandid'] < $y['strandid']) {
            return -1;
        }//endelseif
        else {
            return 1;
        }//endelse
    }
}//endfunction
/**
 * Undocumented function
 *
 * @param [Varchar Key] $x Function for sorting ApplStudentID
 * @param [Varchar Key] $y
 * 
 * @return void
 */
    function sortApplStudentid($x, $y)
    {
        if ($x['studentid']==$y['studentid']) {
            return (sortApplStrandid($x, $y));
        }//endif
        elseif ($x['studentid'] < $y['studentid']) {
            return -1;
        }//endelseif
        else {
            return 1;
        }//endelse
    }//endfunction
/**
 * Undocumented function
 *
 * @param [Varchar] $x Function for sorting AttAttributeDetail
 * @param [Varchar] $y
 * 
 * @return void
 */
    function sortAttAttributeDetail($x, $y)
    {
        if ($x['uadsequence']==$y['uadsequence']) {
            if ($x['unitid']==$y['unitid']) {
                return 0;
            }//endif
            elseif ($x['unitid'] < $y['unitid']) {
                return -1;
            }//endelseif
            else {
                return 1;
            }//endelse
        }//endif
  elseif ($x['uadsequence'] < $y['uadsequence']) {
      return -1;
  }//endelseif
        else {
            return 1;
        }//endelse
    }//endfunction
/**
 * Undocumented function
 *
 * @param [Varchar $x Function for sorting AttAttribute
 * @param [Varchar] $y
 * 
 * @return void
 */
    function sortAttAttribute($x, $y)
    {
        if ($x['uasequence']==$y['uasequence']) {
            return (sortAttAttributeDetail($x, $y));
        }//endif
        elseif ($x['uasequence'] < $y['uasequence']) {
            return -1;
        }//endelseif
        else {
            return 1;
        }//endelse
    }//endfunction
/**
 * Undocumented function
 *
 * @param [varchar] $x Function for sortig Attcategory
 * @param [Varchar] $y
 * @return void
 */
    function sortAttCategory($x, $y)
    {
        if ($x['uacsequence']==$y['uacsequence']) {
            return (sortAttAttribute($x, $y));
        }//endif
        elseif ($x['uacsequence'] < $y['uacsequence']) {
            return -1;
        }//endelseif
        else {
            return 1;
        }//endelse
    }//endfunction
/**
 * Undocumented function
 *
 * @param [Varchar] $x Function for sorting AttLevel
 * @param [Varchar] $y
 * @return void
 */
    function sortAttLevel($x, $y)
    {
        if ($x['uallabel']==$y['uallabel']) {
            return (sortAttCategory($x, $y));
        }//endif
        elseif ($x['uallabel'] < $y['uallabel']) {
            return -1;
        }//endelseif
        else {
            return 1;
        }//endelse
    }//endfunction
/**
 * Undocumented function
 *
 * @param [Varchar] $x Function for sorting AttStandard
 * @param [Varchar] $y
 * @return void
 */
    function sortAttStandard($x, $y)
    {
        if ($x['unitattributestandardversionid']==$y['unitattributestandardversionid']) {
            return (sortAttCategory($x, $y));
        }//endif
        elseif ($x['unitattributestandardversionid'] < $y['unitattributestandardversionid']) {
            return -1;
        }//endelseif
        else {
            return 1;
        }//endelse
    }//endfunction
/**
 * Undocumented function
 *
 * @param [Varchar] $x Function for sorting attunit
 * @param [Varchar] $y
 * 
 * @return void
 */
    function sortAttUnit($x, $y)
    {
        if ($x['unitid']==$y['unitid']) {
            return (sortAttCategory($x, $y));
        }//endif
        elseif ($x['unitid'] < $y['unitid']) {
            return -1;
        }//endelseif
        else {
            return 1;
        }//endelse
    }//endfunction
/**
 * Undocumented function
 *
 * @param [Varchar] $x Function for sorting birthcountry
 * @param [Varchar] $y
 * @return void
 */
    function sortBirthCountry($x, $y)
    {
        if ($x['birthcountry']==$y['birthcountry']) {
            return 0;
        }//endif
        elseif ($x['birthcountry'] < $y['birthcountry']) {
            return -1;
        }//endelseif
        else {
            return 1;
        }//endelse
    }//endfunction
/**
 * Undocumented function
 *
 * @param [Binary] $x function for sorting citizenship
 * @param [Binary] $y
 * @return void
 */
    function sortCitizenship($x, $y)
    {
        if ($x['citizenship']==$y['citizenship']) {
            return 0;
        }//endif
        elseif ($x['citizenship'] < $y['citizenship']) {
            return -1;
        }//endelseif
        else {
            return 1;
        }//endelse
    }//endfunction
/**
 * Undocumented function
 *
 * @param [Varchar key] $x functon for sorting classid
 * @param [Varchar key] $y
 * @return void
 */
    function sortClassid($x, $y)
    {
        if ($x['classid']==$y['classid']) {
            return (sortName($x, $y));
        }//endif
        elseif ($x['classid'] < $y['classid']) {
            return -1;
        }//endelseif
        else {
            return 1;
        }//endelse
    }//endfunction
/**
 * Undocumented function
 *
 * @param [Varchar] $x Function for sorting COECode
 * @param [Varchar] $y
 * @return void
 */
    function sortCOECode($x, $y)
    {
        if ($x['COECode']==$y['COECode']) {
            return (sortTotal($x, $y));
        }//endif
        elseif ($x['COECode'] < $y['COECode']) {
            return -1;
        }//endelseif
        else {
            return 1;
        }//endelse
    }//endfunction
/**
 * Undocumented function
 *
 * @param [Varchar] $x functio for sorting cohort
 * @param [Varchar] $y
 * @return void
 */
    function sortCohort($x, $y)
    {
        if ($x['cohort']==$y['cohort']) {
            return (sortName($x, $y));
        }//endif
        elseif ($x['cohort'] < $y['cohort']) {
            return -1;
        }//endelseif
        else {
            return 1;
        }//endelse
    }//endfunction
/**
 * Undocumented function
 *
 * @param [Varchar Key] $x function for sorting strandid
 * @param [Varchar] $y
 * @return void
 */
    function sortStrandid($x, $y)
    {
        if ($x['strandid']==$y['strandid']) {
            return (sortName($x, $y));
        }//endif
        elseif ($x['strandid'] < $y['strandid']) {
            return -1;
        }//endelseif
        else {
            return 1;
        }//endelse
    }//endfunction
/**
 * Undocumented function
 *
 * @param [Varchar] $x function for sorting CSImportIndex
 * @param [Varchar] $y
 * @return void
 */
    function sortCSImportIndex($x, $y)
    {
        if ($x['index']==$y['index']) {
            return 0;
        }//endif
        elseif ($x['index'] < $y['index']) {
            return -1;
        }//endelseif
        else {
            return 1;
        }//endelse
    }//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar Key] $x function for sorting CSImportStudentID
     * @param [Varchar Key] $y
     * 
     * @return void
     */
function sortCSImportStudentid($x, $y)
{
    if ($x['studentid']==$y['studentid']) {
        return (sortCSImportUnitid($x, $y));
    }//endif
    elseif ($x['studentid'] < $y['studentid']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar key] $x function for sorting CSImportUntiid
     * @param [Varchar key] $y
     * 
     * @return void
     */
function sortCSImportUnitid($x, $y)
{
    if ($x['unitid']==$y['unitid']) {
        return (sortCSImportIndex($x, $y));
    }//endif
    elseif ($x['unitid'] < $y['unitid']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [date] $x function for sorting duedate
     * @param [date] $y
     * 
     * @return void
     */
function sortDateDue($x, $y)
{
    if ($x['sortduedate']==$y['sortduedate']) {
        return (sortDateGiven($x, $y));
    }//endif
    elseif ($x['sortduedate'] < $y['sortduedate']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [date] $x function for sorting datagiven
     * @param [date] $y
     * 
     * @return void
     */
function sortDateGiven($x, $y)
{
    if ($x['sortgivendate']==$y['sortgivendate']) {
        return 0;
    }//endif
    elseif ($x['sortgivendate'] < $y['sortgivendate']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar key] $x function for sorting datelocationID
     * @param [Varchar key] $y
     * 
     * @return void
     */
function sortDateLocationid($x, $y)
{
    if ($x['locationid']==$y['locationid']) {
        return (sortDateUnitid($x, $y));
    }//endif
    elseif ($x['locationid'] < $y['locationid']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar key] $x function for sorting DateTaskid
     * @param [Varchar key] $y
     * 
     * @return void
     */
function sortDateTaskid($x, $y)
{
    if ($x['taskid']==$y['taskid']) {
        return (sortDateLocationid($x, $y));
    }//endif
    elseif ($x['taskid'] < $y['taskid']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar key] $x function for sorting DateUnitId
     * @param [Varchar key] $y
     * 
     * @return void
     */
function sortDateUnitid($x, $y)
{
    if ($x['unitid']==$y['unitid']) {
        return (sortDateGiven($x, $y));
    }//endif
    elseif ($x['unitid'] < $y['unitid']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [int] $x function for sorting dayselapsed
     * @param [int] $y
     * 
     * @return void
     */
function sortDayselapsed($x, $y)
{
    if ($x['dayselapsed']==$y['dayselapsed']) {
        return (sortStudentid($x, $y));
    }//endif
    elseif ($x['dayselapsed'] < $y['dayselapsed']) {
        return 1;
    }//endelseif
    else {
        return -1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Binary] $x function for sorting enrolled
     * @param [Binary] $y
     * 
     * @return void
     */
function sortEnrolled($x, $y)
{
    if ($x['enrolled']==$y['enrolled']) {
        return (sortTotal($x, $y));
    }//endif
    elseif ($x['enrolled'] < $y['enrolled']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Binary] $x function for sorting clash
     * @param [binary] $y
     * 
     * @return void
     */
function sortExamClash($x, $y)
{
    if ($x['clash']==$y['clash']) {
        return (sortExamStudentid($x, $y));
    }//endif
    elseif ($x['clash'] < $y['clash']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar Key] $x Function for sorting Examstudentid
     * @param [Varchar key] $y
     * 
     * @return void
     */
function sortExamStudentid($x, $y)
{
    if ($x['studentid']==$y['studentid']) {
        return (sortExamDay($x, $y));
    }//endif
    elseif ($x['studentid'] < $y['studentid']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar] $x  function for sorting ExamDay
     * @param [Varchar] $y
     * 
     * @return void
     */
function sortExamDay($x, $y)
{
    if ($x['day']==$y['day']) {
        return (sortExamSession($x, $y));
    }//endif
    elseif ($x['day'] < $y['day']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar] $x function for sorting ExamSession
     * @param [Varchar] $y
     * 
     * @return void
     */
function sortExamSession($x, $y)
{
    if ($x['session']==$y['session']) {
        return 0;
    }//endif
    elseif ($x['session'] < $y['session']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar] $x function for sorting file
     * @param [Varchar] $y
     * 
     * @return void
     */
function sortFile($x, $y)
{
    if ($x['type']==$y['type']) {
        return (sortFileDate($x, $y));
    }//endif
    elseif ($x['type'] < $y['type']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Date] $x function for sorting fileDate
     * @param [Date] $y
     * 
     * @return void
     */
function sortFileDate($x, $y)
{
    if ($x['date']==$y['date']) {
        return 0;
    }//endif
    elseif ($x['date'] < $y['date']) {
        return 1;
    }//endelseif
    else {
        return -1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar] $x 
     * @param [Varchar] $y function for sorting fullnae
     * 
     * @return void
     */
function sortFullname($x, $y)
{
    if ($x['fullname']==$y['fullname']) {
        return 0;
    }//endif
    elseif ($x['fullname'] < $y['fullname']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar key] $x function for sorting fullnameLocationID
     * @param [Varchar key] $y
     * 
     * @return void
     */
function sortFullnameLocationid($x, $y)
{
    if ($x['fullname']==$y['fullname']) {
        return (sortLocationid($x, $y));
    }//endif
    elseif ($x['fullname'] < $y['fullname']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar key] $x function for sorting fullnameTermid 
     * @param [Varchar key] $y
     * 
     * @return void
     */
function sortFullnameTermid($x, $y)
{
    if ($x['fullname']==$y['fullname']) {
        return (sortTermid($x, $y));
    }//endif
    elseif ($x['fullname'] < $y['fullname']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar key] $x function for sorting fullnameUnitID
     * @param [Varchar key] $y
     * 
     * @return void
     */
function sortFullnameUnitid($x, $y)
{
    if ($x['fullname']==$y['fullname']) {
        return (sortUnitid($x, $y));
    }//endif
    elseif ($x['fullname'] < $y['fullname']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar] $x function for sorting GGP
     * @param [Varchar] $y
     * 
     * @return void
     */
function sortGPA($x, $y)
{
    if ($x['gpa']==$y['gpa']) {
        return (sortName($x, $y));
    }//endif
    elseif ($x['gpa'] < $y['gpa']) {
        return 1;
    }//endelseif
    else {
        return -1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar] $x function for sorting grade
     * @param [Varchar] $y
     * 
     * @return void
     */
function sortGrade($x, $y)
{
    if ($x['grade']==$y['grade']) {
        return (sortTotal($x, $y));
    }//endif
    elseif ($x['grade'] < $y['grade']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar] $x function for sorting highlightSerachField3
     * @param [Varchar] $y
     * 
     * @return void
     */
function sortHighlightSearchField3($x, $y)
{
    if ($x['field3']==$y['field3']) {
        return (sortHighlightSearchField2($x, $y));
    }//endif
    elseif ($x['field3'] < $y['field3']) {
        return 1;
    }//endelseif
    else {
        return -1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar] $x function fort sorting highlightSerachField2
     * @param [Varchar] $y
     * 
     * @return void
     */
function sortHighlightSearchField2($x, $y)
{
    if ($x['field2']==$y['field2']) {
        return 0;
    }//endif
    elseif ($x['field2'] < $y['field2']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar] $x function for sorting highlighted
     * @param [Varchar] $y
     * 
     * @return void
     */
function sortHighlighted($x, $y)
{
    if ($x['highlighted']==$y['highlighted']) {
        return (sortName($x, $y));
    }//endif
    elseif ($x['highlighted'] < $y['highlighted']) {
        return 1;
    }//endelseif
    else {
        return -1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar] $x function for sorting Sampled
     * @param [Varchar] $y
     * 
     * @return void
     */
function sortSampled($x, $y)
{
    if ($x['highlightedsample']==$y['highlightedsample']) {
        return (sortName($x, $y));
    }//endif
    elseif ($x['highlightedsample'] < $y['highlightedsample']) {
        return 1;
    }//endelseif
    else {
        return -1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar] $x function for sorting InterLocation
     * @param [Varchar] $y
     * 
     * @return void
     */
function sortInterLocation($x, $y)
{
    if ($x['locationid']==$y['locationid']) {
        return (sortInterStudents($x, $y));
    }//endif
    elseif ($x['locationid'] < $y['locationid']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar] $x function for sorting InterStudents
     * @param [Varchar] $y
     * 
     * @return void
     */
function sortInterStudents($x, $y)
{
    if ($x['students']==$y['students']) {
        return 0;
    }//endif
    elseif ($x['students'] < $y['students']) {
        return 1;
    }//endelseif
    else {
        return -1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar Key] $x function for sorting iporganisationlocationid
     * @param [Varchar Key] $y
     * 
     * @return void
     */
function sortIPOrganisationlocationid($x, $y)
{
    if ($x['iporganisationlocationid']==$y['iporganisationlocationid']) {
        return 0;
    }//endif
    elseif ($x['iporganisationlocationid'] < $y['iporganisationlocationid']) {
        return 1;
    }//endelseif
    else {
        return -1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar] $x function for sorting category2
     * @param [Varchar] $y
     * 
     * @return void
     */
function sortCategory2($x, $y)
{
    if ($x['category2']==$y['category2']) {
        return (sortName($x, $y));
    }//endif
    elseif ($x['category2'] < $y['category2']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar] $x function for sorting strategylastreviewed
     * @param [Varchar] $y
     * 
     * @return void
     */
function sortStrategyLastReviewed($x, $y)
{
    if ($x['strategylastreviewed']==$y['strategylastreviewed']) {
        return 0;
    }//endif
    elseif ($x['strategylastreviewed'] < $y['strategylastreviewed']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar] $x function for sorting laptype
     * @param [Varchar] $y
     * 
     * @return void
     */
function sortLAPType($x, $y)
{
    if ($x['laptype']==$y['laptype']) {
        return 0;
    }//endif
    elseif ($x['laptype'] < $y['laptype']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar key] $x Function for sorting locationid
     * @param [Varchar key] $y
     * 
     * @return void
     */
function sortLocationid($x, $y)
{
    if ($x['locationid']==$y['locationid']) {
        return 0;
    }//endif
    elseif ($x['locationid'] < $y['locationid']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar] $x function fort sorting LocationName
     * @param [Varchar] $y
     * 
     * @return void
     */
function sortLocationidName($x, $y)
{
    if ($x['locationid']==$y['locationid']) {
        return (sortName($x, $y));
    }//endif
    elseif ($x['locationid'] < $y['locationid']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar key] $x function for sorting LocationUnitID
     * @param [Varchar key] $y
     * 
     * @return void
     */
function sortLocationidUnitid($x, $y)
{
    if ($x['locationid']==$y['locationid']) {
        return (sortUnitid($x, $y));
    }//endif
    elseif ($x['locationid'] < $y['locationid']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar key] $x Function fort sorting LocationTermID
     * @param [Varchar key] $y
     * 
     * @return void
     */
function sortLocationidTermid($x, $y)
{
    if ($x['locationid']==$y['locationid']) {
        return (sortTermid($x, $y));
    }//endif
    elseif ($x['locationid'] < $y['locationid']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar] $x function for sorting Lock
     * @param [Varchar] $y
     * 
     * @return void
     */
function sortLock($x, $y)
{
    if ($x['lock']==$y['lock']) {
        return (sortTotal($x, $y));
    }//endif
    elseif ($x['lock'] < $y['lock']) {
        return 1;
    }//endelseif
    else {
        return -1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Int] $x function for sorting Mark
     * @param [Int] $y
     * 
     * @return void
     */
function sortMark($x, $y)
{
    if ($x['mark']==$y['mark']) {
        return (sortStudentid($x, $y));
    }//endif
    elseif ($x['mark'] < $y['mark']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Int] $x Function for sorting modAtotal
     * @param [Int] $y
     * 
     * @return void
     */
function sortmodAtotal($x, $y)
{
    if (in_array($_SESSION[$_GET["trid"] . "usertype"], array('C','S','X','T'))) {
        $sortfield = 'tppAtotal';
    }//endelseif
    else {
        $sortfield = 'modAtotal';
    }//endelse

    if ($x[$sortfield]==$y[$sortfield]) {
        return (sortName($x, $y));
    }//endif
    elseif ($x[$sortfield] < $y[$sortfield]) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Int] $x function for sorting modBtotal
     * @param [Int] $y
     * 
     * @return void
     */
function sortmodBtotal($x, $y)
{
    if (in_array($_SESSION[$_GET["trid"] . "usertype"], array('C','S','X','T'))) {
        $sortfield = 'tppBtotal';
    }//endelseif
    else {
        $sortfield = 'modBtotal';
    }//endelse

    if ($x[$sortfield]==$y[$sortfield]) {
        return (sortName($x, $y));
    }//endif
    elseif ($x[$sortfield] < $y[$sortfield]) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar] $x function for sorting name
     * @param [Varchar] $y
     * 
     * @return void
     */
function sortName($x, $y)
{
    if ($x['name']==$y['name']) {
        return (sortStudentid($x, $y));
    }//endif
    elseif ($x['name'] < $y['name']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar] $x function for sorting refresh
     * @param [Varchar] $y
     * 
     * @return void
     */
function sortRefresh($x, $y)
{
    if ($x['refresh']==$y['refresh']) {
        return (sortStudentid($x, $y));
    }//endif
    elseif ($x['refresh'] < $y['refresh']) {
        return 1;
    }//endelseif
    else {
        return -1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar] $x function for sorting result
     * @param [Varchar] $y
     * 
     * @return void
     */
function sortResults($x, $y)
{
    if ($x['index']==$y['index']) {
        return (sortResultssubdisciplineid($x, $y));
    }//endif
    elseif ($x['index'] < $y['index']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar key] $x function for sorting ResultsSubbdisiplieid
     * @param [Varchar key] $y
     * 
     * @return void
     */
function sortResultssubdisciplineid($x, $y)
{
    if ($x['subdisciplineid']==$y['subdisciplineid']) {
        return (sortName($x, $y));
    }//endif
    elseif ($x['subdisciplineid'] < $y['subdisciplineid']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar] $x function for sorting role
     * @param [Varchar] $y
     * 
     * @return void
     */
function sortRole($x, $y)
{
    if ($x['role']==$y['role']) {
        return 0;
    }//endif
    elseif ($x['role'] < $y['role']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar] $x function for sortig csprogram
     * @param [Varchar] $y
     * 
     * @return void
     */
function sortCSProgram($x, $y)
{
    if ($x['csprogram']==$y['csprogram']) {
        return (sortName($x, $y));
    }//endif
    elseif ($x['csprogram'] < $y['csprogram']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar] $x function for sorting status 
     * @param [Varchar] $y
     * 
     * @return void
     */
function sortStatus($x, $y)
{
    if ($x['status']==$y['status']) {
        return 0;
    }//endif
    elseif ($x['status'] < $y['status']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar] $x function for sorting sequence
     * @param [Varchar] $y
     * 
     * @return void
     */
function sortSequence($x, $y)
{
    if ($x['sequence']==$y['sequence']) {
        return 0;
    }//endif
    elseif ($x['sequence'] < $y['sequence']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar key] $x function for sorting studentid 
     * @param [Varchar key] $y
     * 
     * @return void
     */
function sortStudentid($x, $y)
{
    if ($x['studentid']==$y['studentid']) {
        return 0;
    }//endif
    elseif ($x['studentid'] < $y['studentid']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar] $x function for sorting noteletter
     * @param [Varchar] $y
     * 
     * @return void
     */
function sortNoteLetter($x, $y)
{
    if ($x['noteletter']==$y['noteletter']) {
        return 0;
    }//endif
    elseif ($x['noteletter'] < $y['noteletter']) {
        return 1;
    }//endelseif
    else {
        return -1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar key] $x Function for sorting termid
     * @param [Varchar key] $y
     * 
     * @return void
     */
function sortTermid($x, $y)
{
    if ($x['termid']==$y['termid']) {
        return 0;
    }//endif
    elseif ($x['termid'] < $y['termid']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar] $x function for sortig termDesc
     * @param [Varchar] $y
     * 
     * @return void
     */
function sortTermidDesc($x, $y)
{
    if ($x['termid']==$y['termid']) {
        return 0;
    }//endif
    elseif ($x['termid'] < $y['termid']) {
        return 1;
    }//endelseif
    else {
        return -1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Int] $x function for sorting total 
     * @param [Int] $y
     * 
     * @return void
     */
function sortTotal($x, $y)
{
    if (in_array($_SESSION[$_GET["trid"] . "usertype"], array('C','S','X','T'))) {
        $sortfield = 'tpptotal';
    }//endelseif
    else {
        $sortfield = 'modtotal';
    }//endelse

    if ($x[$sortfield]==$y[$sortfield]) {
        return (sortName($x, $y));
    }//endif
    elseif ($x[$sortfield] < $y[$sortfield]) {
        return 1;
    }//endelseif
    else {
        return -1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar key] $x function for sorting taskid
     * @param [Varchar key] $y
     * 
     * @return void
     */
function sortTaskid($x, $y)
{
    if ($x['taskid']==$y['taskid']) {
        return 0;
    }//endif
    elseif ($x['taskid'] < $y['taskid']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar] $x function for sorting category1
     * @param [Varchar] $y
     * 
     * @return void
     */
function sortCategory1($x, $y)
{
    if ($x['category1']==$y['category1']) {
        return (sortName($x, $y));
    }//endif
    elseif ($x['category1'] < $y['category1']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar] $x function for sorting ImportCampus 
     * @param [Varchar] $y
     * 
     * @return void
     */
function sortImportCampus($x, $y)
{
    if ($x['campus']==$y['campus']) {
        return (sortImportTermid($x, $y));
    }//endif
    elseif ($x['campus'] < $y['campus']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar] $x function for soting ImportLocationid
     * @param [Varchar] $y
     * 
     * @return void
     */
function sortImportLocationid($x, $y)
{
    if ($x['locationid']==$y['locationid']) {
        return (sortImportTermid($x, $y));
    }//endif
    elseif ($x['locationid'] < $y['locationid']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar] $x function for sorting ImportTermid
     * @param [Varchar] $y
     * 
     * @return void
     */
function sortImportTermid($x, $y)
{
    if ($x['termid']==$y['termid']) {
        return (sortImportUnitid($x, $y));
    }//endif
    elseif ($x['termid'] < $y['termid']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar] $x function for sortig ImportUnitid
     * @param [Varchar] $y
     * 
     * @return void
     */
function sortImportUnitid($x, $y)
{
    if ($x['unitid']==$y['unitid']) {
        return 0;
    }//endif
    elseif ($x['unitid'] < $y['unitid']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar key] $x function for sorting UBASid
     * @param [Varchar key] $y
     * 
     * @return void
     */
function sortUBSASid($x, $y)
{
    if ($x['ubsasid']==$y['ubsasid']) {
        return 0;
    }//endif
    elseif ($x['ubsasid'] < $y['ubsasid']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar key] $x function for sorting modulecode
     * @param [Varchar key] $y
     * 
     * @return void
     */
function sortModulecode($x, $y)
{
    if ($x['modulecode']==$y['modulecode']) {
        return (sortModulename($x, $y));
    }//endif
    elseif ($x['modulecode'] < $y['modulecode']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar] $x function for sorting modulename
     * @param [Varchar] $y
     * 
     * @return void
     */
function sortModulename($x, $y)
{
    if ($x['modulename']==$y['modulename']) {
        return 0;
    }//endif
    elseif ($x['modulename'] < $y['modulename']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}//endfunction
    /**
     * Undocumented function
     *
     * @param [Varchar key] $x function for sorting unitid
     * @param [Varchar key] $y
     * 
     * @return void
     */
function sortUnitid($x, $y)
{
    if ($x['unitid']==$y['unitid']) {
        return 0;
    }//endif
    elseif ($x['unitid'] < $y['unitid']) {
        return -1;
    }//endelseif
    else {
        return 1;
    }//endelse
}
