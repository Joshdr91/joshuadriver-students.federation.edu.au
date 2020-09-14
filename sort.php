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
 * @param [type] $x 
 * @param [type] $y
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
     * @param [type] $x jg;lkdfjg
     * @param [type] $y
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
 * @param [type] $x
 * @param [type] $y
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
 * @param [type] $x
 * @param [type] $y
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
 * @param [type] $x
 * @param [type] $y
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
 * @param [type] $x
 * @param [type] $y
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
 * @param [type] $x
 * @param [type] $y
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
 * @param [type] $x
 * @param [type] $y
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
 * @param [type] $x
 * @param [type] $y
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
 * @param [type] $x
 * @param [type] $y
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
 * @param [type] $x
 * @param [type] $y
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
 * @param [type] $x
 * @param [type] $y
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
 * @param [type] $x
 * @param [type] $y
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
 * @param [type] $x
 * @param [type] $y
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
 * @param [type] $x
 * @param [type] $y
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
 * @param [type] $x
 * @param [type] $y
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
 * @param [type] $x
 * @param [type] $y
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
 * @param [type] $x
 * @param [type] $y
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
 * @param [type] $x
 * @param [type] $y
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
 * @param [type] $x
 * @param [type] $y
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
 * @param [type] $x
 * @param [type] $y
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
 * @param [type] $x
 * @param [type] $y
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
 * @param [type] $x
 * @param [type] $y
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
