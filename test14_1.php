<?php
class Test14_1
{
  private function _makeComma($num)
  {
    //$num = "-323425.24";

    $isChk = false;
    if (strpos($num, "-") !== false)
    {
      $isChk = true;
    }

    if (strpos($num, ".") > 0) //소수점 처리
    {
      $pos = strpos($num,"."); //소수점위치
      $strTail = substr($num, $pos); //소수점위치부터 담기
      $num = substr($num, 0, $pos); //소수점 위치 직전까지 담기
    }

    for($i=0; $i<strlen($num); $i++)
    {
      if ($i!=0 && $i%3==(strlen($num)%3))
      {
         if (!$isChk || $i!=1) //-,200,000
          $str .= ",";
      }
      $str .= substr($num, $i, 1);
    }

   // echo $str.$strTail;
    return $str.$strTail;
  }

  public function index()
  {
    echo $this->_makeComma(-323425.24)."<br>";
    echo $this->_makeComma(2000000)."<br>";
    echo $this->_makeComma(2000)."<br>";
  }
	
}

