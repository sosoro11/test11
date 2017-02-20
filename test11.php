<?php
class Qa_manage extends Base_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->manageModel = ftt_get_instance('event2/manage/manage_model', 'model');
    $this->qaModel = ftt_get_instance('event2/qa_manage/qatool_model', 'model');
    $this->dayTable = 'day_play_status';
  }

  public function main($event_idx, $ssn)
  {
    $list = $this->qaModel->getColumnwhere($ssn, $this->dayTable);
    $eventInfo = $this->manageModel->getEventInfo($event_idx);
    $aViewData = array(
      'aWhere' => $list,
      'event_idx' => $event_idx,
      'sTitle' => $eventInfo['event_title'],
      'ssn'=> $ssn
    );
    $this->layoutView('event2/qa_manage/qa_main', $aViewData, 'event2');
  }

  public function qa_reward($event_idx, $ssn)
  {
    $list = $this->qaModel->getColumnwhere($ssn, $this->applyTable);
    $allColumn = $this->qaModel->getColumnList($ssn, $this->applyTable);
    $eventInfo = $this->manageModel->getEventInfo($event_idx);
//echo"<pre>";print_r($list);echo"</pre>"; die;
    $aViewData = array(
      'aWhere' => $list,
      'aColumn' => $allColumn,
      'event_idx' => $event_idx,
      'sTitle' => $eventInfo['event_title'],
      'ssn'=> $ssn
    );
    $this->layoutView('event2/qa_manage/qa_reward', $aViewData, 'event2');
  }
  //게임조회 ajax
  public function getGameStatus($event_idx, $ssn)
  {
    $val = $this->input->post();

    $aList = $this->qaModel->getStatusInfo($val, $ssn, $this->dayTable);

    if (count($aList) > 0)
      $res = 1;
    else
      $res = 0;
    $this->responseJson(array('code'=> $res , 'data'=>$aList));
  }

  //게임변경 ajax
  public function setGameStatusUpdate($event_idx, $ssn)
  {
    $val = $this->input->post();

    //배열 만들기
    foreach ($val as $k=>$v)
    {
      if ($k == 'key') continue;
      else $aColumn[$k] = $v;
    }

    $res = $this->qaModel->setStatusUpdate($event_idx, $ssn, $aColumn, $this->dayTable);

    $this->responseJson(array('code' => $res));
  }

  //partkey week
  public function getPartkeyWeek()
  {
    $dt = $this->input->post('dt');
    $partkey = $this->qaModel->getPartkeyWeek($dt);

    if ($partkey > 0)
      $this->responseJson(array('code' => 1, 'partkeyweek' => $partkey));
    else
      $this->responseJson(array('code' => 2));
  }





}

