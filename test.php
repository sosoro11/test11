
<aside class="right-side">
  <section class="content-header">
    <h1>
      <strong>qa관리</strong>
    </h1>
  </section>
  <section class="content">
    이벤트 명 : <?=$sTitle?>
  <hr>

  <div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
      <li id ='game' class='active'><a href="/event2/qa_manage/main/<?=$event_idx?>/<?=$ssn?>">게임</a></li>
      <li id='reward'><a href="/event2/qa_manage/qa_reward/<?=$event_idx?>/<?=$ssn?>">보상여부</a></li>
    </ul>

    <div class="tab-content clearfix">

    <p>
    <pre>
    <b>컬럼 설명</b>
    - 괄호안의 영문명은 변경 될 수 있습니다. <br>
    게임 유저 번호 (user_id)
    이벤트참여 날짜 (dt)
    플레이모드 번호 (play_mode)
    월드번호 (world_idx)
    이벤트참여주 (partkey_week)
    계정번호 (account_idx)
    </pre>
    </p>

    <p>
    ※ partkey_week 알아보기 <br>
    dt 에 해당하는 날짜 : <input type='date' name="partkey_dt" id="partkey_dt" /><br>

    <div id="date_partkey">
    </div>

    <button type="button" name="" class="btn btn-default  btn-sm" id="partkey_sel">partkey_week 조회하기</button>
    </p>

    <div id='game_select'>
      <div class="row">
        <div class="col-xs-12">
          <form id ="frm">
          <table>
            <thead>
              <tr>
                  <th width="5"></th>
                  <th width=""></th>
                  <th width=""></th>
                  <th width=""></th>
              </tr>
            </thead>
<?php foreach ($aWhere as $k=>$v) {?>
<tr>
  <td></td>
  <td><?=$v['column_name']?></td>
  <td><input type="<?php if($v['data_type']=='date') echo 'date'; else echo "number";?>" class="form-control pri" name='<?=$v['column_name']?>' value=""></td>
  <td></td>
</tr>
<?php }?>
          </table>
          </form>

            <div class="box-footer">
            <div class="box-tools">
              <button type="button" name="saveItemsBtn" onclick="frm_submit();" class="btn btn-primary" id="save">조회하기</button>&nbsp;&nbsp;&nbsp;
            </div>
          </div>

        </div>
      </div>


      <br>
      <div class="row">
      <div class="col-xs-12">
        <form id="frm_modi">
        <table class="table table-bordered" id="selectinfo">

        </table>
        </form>

        <div class="box-footer" id="btns" style="display:none">
          <div class="box-tools">
            <button type="button" name="saveItemsBtn" onclick="btn_click('modi');" class="btn btn-primary" id="">변경하기</button>&nbsp;&nbsp;&nbsp;
            <button type="button" name="delItemsBtn" onclick="btn_click('del');" class="btn btn-primary" id="">삭제하기</button>
          </div>
        </div>

      </div>
      </div>

    </div>

    </div>
  </div>
  </section>
</aside>
<script>
//partkey_week
$('#partkey_sel').click(function(){
  var sDate = $('#partkey_dt').val();
  if (!sDate)
  {
    alert('날짜를 입력해주세요');
    $('#partkey_dt').focus();
    return false;
  }

  $.post(
    "/event2/qa_manage/getPartkeyWeek",
    {
      'dt' : sDate,
    },
    function (data, status)
    {
      if (status == 'success' && data.code == 1)
      {
        alert('조회되었습니다');
        $('#date_partkey').html("partkey_week : <font color='red'>" +data.partkeyweek+ "</font");
      }
      else if (status == 'success' && data.code == 2)
      {
        alert('조회실패하였습니다.');
      }
      else
      {
        alert('시스템 에러가 발생하였습니다.');
      }
    }

  );
});
function chk_val()
{
  var cnt = 0;
  $('.pri').each(function(){
    if ($.trim($(this).val()) == '')
    {
      cnt++;
    }
  });
  return cnt;
}

function frm_submit()
{
  var chk = chk_val();
  if (chk > 0)
  {
    alert('조회 할 상세항목을 모두 입력해주세요.');
    return false;
  }
    $.ajax({
    type : "POST",
    url : "/event2/qa_manage/getGameStatus/<?=$event_idx?>/<?=$ssn?>",
    data : $('#frm').serialize(),
    dataType : 'json',
    success : function(ret) {

      if (ret.code > 0)
      {
        alert('조회 되었습니다.');
        var output = "";
        $("#selectinfo").empty();
        $.each( ret.data, function(k, v) {
          //console.log(k+":"+v.c_val);
          var intype = 'number';
          if (v.data_type == 'date' || v.data_type == 'datetime')
            intype = v.data_type;

          if (v.c_key == 'PRI' || v.c_key == 'MUL')
            var readonly = 'readonly';

          output +='<tr><td><input type="hidden" class="form-control" name="key[]" value="'+v.c_key+'" />'+k+'</td>';
          output +='<td><input type="'+intype+'" class="form-control" name="'+k+'" value="'+v.c_val+'" '+readonly+' /></td></tr>';
        });

        $('#btns').css('display', 'block');
        $('#selectinfo').append(output);

        //location.reload();
      }
      else
      {
        alert('조회 내역이 없습니다.');
        $("#selectinfo").empty();
        $('#btns').css('display', 'none');
      }
    },
    error : function(e) {
      alert('시스템 에러가 발생하였습니다.');
    }
  });

}
function btn_click(mode)
{
  var g_url = "/event2/qa_manage/setGameStatusUpdate/<?=$event_idx?>/<?=$ssn?>";
  var msg = "변경하였습니다";
  var msg_e = "변경실패하였습니다";

  if (mode =='del') {
    g_url = "/event2/qa_manage/delGameStatus/<?=$event_idx?>/<?=$ssn?>";
    msg = "삭제하였습니다.";
    msg_e = "삭제실패하였습니다.";
  }

  $.ajax({
    type : "POST",
    url : g_url,
    data : $('#frm_modi').serialize(),
    dataType : 'json',
    success : function (ret) {
      if (ret.code > 0)
      {
        alert(msg);
        if (mode =='del') {
          $("#selectinfo").empty();
          $('#btns').css('display', 'none');
        }

        return false;

      }
    },
    error : function(e) {
      alert(msg_e);
      return false;
    }
  });

}

</script>


