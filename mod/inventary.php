<?php
    session_start();
	if (empty($_SESSION['_user']) or empty($_SESSION['id'])){
		header("Location: ../index.php");	}	 

      if (isset($_POST['inshift'])) { $inshift=$_POST['inshift']; if ($inshift =='') { unset($inshift);} }
      if (isset($_POST['insumm'])) { $insumm=$_POST['insumm']; if ($insumm =='') { unset($insumm);} }

      $inshift = trim(htmlspecialchars(stripslashes($inshift)));
      $insumm  = trim(htmlspecialchars(stripslashes($insumm)));

    //$dblite = new SQLite3('../../db/webdb.db');



    $shiftQ = "SELECT _value FROM сonstants WHERE _name = 'shift';";
    $pathToDb = '../db/webdb.db';
    $dblite = new SQLite3($pathToDb);
    $SHIFT = '';
    $results = $dblite->query( $shiftQ);
    $row =  $results->fetchArray();
    if (empty($row['_value'])) {

    } else {
      $SHIFT = $row['_value'];
    }

    
?>

<!-- Modal -->
  <div class="modal fade" id="mysafiltershift" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="padding:35px 50px;">

          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h3>відфільтрувати дані</h3>
        </div>
        <div class="modal-body" style="padding:40px 50px;">
          <form role="form" action="index.php?whotsey=raportshift" method="post" >
                    
<!--                 <div class="input-group">
                  <span class="input-group-addon span-in-group">дата</span>
                  <input type="date"  class="form-control" name="indata">
                </div> -->


                <div class="input-group">
                  <span class="input-group-addon span-in-group">зміна</span>
                  <select  class="form-control" name="inshift">
                        <option value="all">не використовувати</option>
                        <?php
                          $shiftQ = 'SELECT _d, _shift FROM l GROUP BY _shift;';
                          $results = $dblite->query($shiftQ);
                          while ($row = $results->fetchArray()) {
                            $val = $row['_shift'];
                            $datashift = $row['_d'];
                            echo '<option value="'.$val.'">'.$datashift."  ".$val.'</option>';
                          }
                        ?>
                  </select>
                </div>
                
<!--                 <div class="input-group">
                  <span class="input-group-addon span-in-group">замовлення</span>
                  <input type="text"  class="form-control" name="inrecord">
                </div> -->
              <button type="submit" class="btn btn-success btn-block"><i class="fa fa-refresh" aria-hidden="true"></i> оновити</button>
          </form>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger btn-default pull-left" data-dismiss="modal"> ні дякую...</button>
        </div>
      </div>
      
    </div>
  </div> 


<?php   
  
  if (isset($_POST['inshift'])) { $inshift=$_POST['inshift']; if ($inshift =='') { unset($inshift);} }

  $inshift = trim(htmlspecialchars(stripslashes($inshift)));

  $and_shift = '';

  if (empty($inshift) or $inshift == "all") 
  {
      $and_shift = " and  _shift='".$SHIFT."'";
  }else{
      $SHIFT = $inshift;
      $and_shift = " and  _shift='".$inshift."'";
  }

    if (empty($insumm) or $insumm == "" ) {
    }else{
       $sumQins = "UPDATE record   SET  _param_R1 = ".$insumm."  WHERE _typer = 3  ".$and_shift.";";
       echo $sumQins;
       $results = $dblite->query( $sumQins);
    }   
?>

<div class="container-fluid text-center">
  <div class="row ">
    <div class="col-sm-12 ">
      <div class="col-sm-4 ">
      <h5>сумма поставки...</h>
      <form role="form" action="index.php?whotsey=inventary" method="post" >
                <div class="input-group">
                      <span class="input-group-addon span-in-group">сума</span>
                      <input type="text"  class="form-control" name="insumm">
                    </div> 
        <button type="submit" class="btn btn-success btn-block"><i class="fa fa-refresh" aria-hidden="true"></i> оновити</button>
      </form>  
      </div> 
    </div> 
  </div>
 <!--  <i class="fa fa-cubes fa-4x" aria-hidden="true"></i> -->
  
  <div class="row slideanim">
    <h4>інвентаризація...</h4>
      <div class="col-sm-12">
      <i class="fa fa-cubes fa-3x" aria-hidden="true"></i>
      <?php 
          $val = 0.00;
          $sumQrec = "SELECT sum(_sum),sum(_param_R1)FROM record WHERE _typer = 3  ".$and_shift.";";
          $results = $dblite->query( $sumQrec);
          $row =  $results->fetchArray();
          if (empty($row[0])) {

          } else { ?>
          <h4>залишок <?php  echo sprintf("%01.2f",$row[0]) ?> грн.</h4>

          <h4>прихід <?php  echo sprintf("%01.2f", $row[1]) ?> грн.</h4>

          <h3>всього <?php  echo sprintf("%01.2f", $row[0]+$row[1]) ?> грн.</h3>

          <p>поточна інвентаризація сума...</p>
          <p>інвентаризація створена на початок поточного дня...</p>
          <!--
          <i class="fa fa-cube fa-2x" aria-hidden="true"></i>
          <p><h4><?php  //echo sprintf("%01.2f",$row[1])  ?> грн.</h4></p>
          <p>обслуговування по закритих...</p>
          <i class="fa fa-cube fa-2x"  aria-hidden="true"></i>
          <p><h4><?php  //echo sprintf("%01.2f",$row[2])  ?> грн.</h4></p>
          <p>знижка по закритих...</p>
          <i class="fa fa-cube fa-2x" aria-hidden="true"></i>
          <p><h4><?php  //echo sprintf("%01.2f",$row[3])  ?> грн.</h4></p>
          <p>передоплата по закритих...</p> -->
          <?php      
          }
      ?>

    </div>
  </div>

  <div class="row slideanim">  
    <div class="col-sm-12">
     <!-- <i class="fa fa-cube  fa-3x" aria-hidden="true"></i>
      <h4>інвентаризація...</h4>-->
      <div class="panel-group" id="accordion">
      <?php 
       /*_typer, _data, _time, _number, _stage, _status, _table, _visitor, _user, _paymentt, _transactiont, _client, _received,
       _delivery, _sum, _discontt, _sumdiscont, _percentdisc, _totalgdiscont, _premiumt, _sumpremium, _percentpremium, 
       _totalgpremium, _prepaymentt, _sumprepayment, _percentprepay, _ekka, _msg, _admin, _service, _overhead, _sent, 
       _host, _write_, _param_T1, _param_T2, _param_DT1, _param_R1, _shift, _param_I1, _param_I2 */
       $recQrec = "SELECT * FROM record where _typer = 3   ".$and_shift." ;";
          $resultsR = $dblite->query($recQrec);
          if ($resultsR <> false ){
          while ($rowR = $resultsR->fetchArray()) {
      
             $table_colapse =  '<div class="panel">
                <div class="panel-heading">
                  
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#coll'.$rowR['_number'].'">
                     <table class="table table-condensed">
                      <tbody>
                      <tr>
                      <td>дата <p>'.$rowR['_data'].'</p></tb>
                      <td>час <p>'.$rowR['_time'].'</p></tb>
                      <td>номер  <p>'.$rowR['_number'].'</p></tb>
                      <td>статус <p>'.$rowR['_stage'].'</p></tb>
                      <td>стіл <p>'.$rowR['_table'].'</p></tb>
                      <td>оплата <p>'.$rowR['_paymentt'].'</p></tb>
                      <td>сума <p>'.$rowR['_sum'].'</p></tb>
                     <!-- <td>обслу-ння <p>'.$rowR['_sumpremium'].'</p></tb>
                      <td>знижка <p>'.$rowR['_sumdiscont'].'</p></tb>
                      <td>передоплата <p>'.$rowR['_sumprepayment'].'</p></tb>-->
                      </tr>
                      </tbody>
                      </table>
                    </a>
                </div>
                <div id="coll'.$rowR['_number'].'" class="panel-collapse collapse">
                  <div class="panel-body">
                    <table class="table table-condensed">
                    <tbody>
                    <tr>
                    <td>назва</td><td>кількість</td><td>ціна</td><td>сума</td><td>повідомлення</td>
                    </tr>';

                /*id, _isprint, _serialnum, _record, _cod, _barcode, _name, _group, _department, _printname, _printtyme, 
                _unit, _k, _price, _quantity, _sum, _sumdiscont, _sumpremium, _msg, _minimize_how, _ingroup, _ispremium, 
                _isdiscont, _isprintekka, _param_T1, _param_T2, _param_T3, _param_DT1, _param_DT2, _param_R1, _param_R2, 
                _param_I1, _param_I2 */

                   $goodrecQrec = "SELECT * FROM linerecord where _record = '".$rowR['_number']."' ;";
                  
                    $results = $dblite->query( $goodrecQrec);
                    if ($results <> false ){
                    while ($row = $results->fetchArray()) {

                    $table_colapse =  $table_colapse . '<tr>
                      <td>'.$row['_name'].'</td>
                      <td>'.$row['_quantity'].'</td>
                      <td>'.$row['_price'].'</td>
                      <td>'.$row['_sum'].'</td>
                      <td>'.$row['_msg'].'</td>
                      </tr>';
                    }}
                    $table_colapse =  $table_colapse .'</tbody>
                    </table>
                  </div>
                </div>
              </div>';
              echo $table_colapse;
                
          }}
    ?>
    </div>
    <br><br>
    </div>
  </div>
</div>

<?php $dblite->close(); ?>




