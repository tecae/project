<?php
    session_start();
	if (empty($_SESSION['_user']) or empty($_SESSION['id'])){
		header("Location: ../index.php");	}	 

      if (isset($_POST['inshift'])) { $inshift=$_POST['inshift']; if ($inshift =='') { unset($inshift);} }

      $inshift = trim(htmlspecialchars(stripslashes($inshift)));

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
                          $shiftQ = 'SELECT _data, _number FROM shift GROUP BY _number;';
                          $results = $dblite->query($shiftQ);
                          while ($row = $results->fetchArray()) {
                            $val = $row['_number'];
                            $datashift = $row['_data'];
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

?>
<!-- Container (Services Section) -->
<div id="services" class="container-fluid text-center">
  <h2>зміна</h2>
  <h4>№<?php  echo $SHIFT ?></h4>
  <br>
  <div class="row slideanim">

      <?php 
          $val = 0.00;
          $sumQrec = "SELECT sum(_sum),sum(_param_R1)FROM record WHERE _typer = 3 and _shift='".($SHIFT-1)."'";
          $results = $dblite->query( $sumQrec);
          $row =  $results->fetchArray();
          if (empty($row[0])) {

          } else { ?>

          <div class="col-sm-3">
            <i class="fa fa-cube fa-2x"  aria-hidden="true"></i>
             <p><h4><?php  echo sprintf("%01.2f",$row[0])  ?> грн.</h4></p>
            <p>попередня інвентаризація...</p>
          </div>  
          <div class="col-sm-3">
            <i class="fa fa-cube fa-2x"  aria-hidden="true"></i>
             <p><h4><?php  echo sprintf("%01.2f",$row[1])  ?> грн.</h4></p>
            <p>попередній прихід ...</p>
          </div>  


                <?php      
          }
      ?>
            <?php 
          $val = 0.00;
          $sumQrec = "SELECT sum(_sum),sum(_param_R1)FROM record WHERE _typer = 3 and _shift='".$SHIFT."';";
          $results = $dblite->query( $sumQrec);
          $row =  $results->fetchArray();
          if (empty($row[0])) {
            
          } else { ?>
          <div class="col-sm-3">
            <i class="fa fa-cube fa-2x"  aria-hidden="true"></i>
             <p><h4><?php  echo sprintf("%01.2f",$row[0])  ?> грн.</h4></p>
            <p>поточна інвентаризація...</p>
          </div>  
          <div class="col-sm-3">
            <i class="fa fa-cube fa-2x"  aria-hidden="true"></i>
             <p><h4><?php  echo sprintf("%01.2f",$row[1])  ?> грн.</h4></p>
            <p>поточний прихід...</p>
          </div>  
      <?php      
          }
      ?>
  </div>
  <br>
  <div class="row slideanim">
      <div class="col-sm-3">
      <i class="fa fa-cubes fa-3x" aria-hidden="true"></i>
      <?php 
          $val = 0.00;
          $sumQrec = "SELECT sum(_sum),sum(_sumpremium) ,sum(_sumdiscont),sum(_sumprepayment)FROM record WHERE _typer = 1 and _stage = 7  ".$and_shift.";";
          $results = $dblite->query( $sumQrec);
          $row =  $results->fetchArray();
          if (empty($row[0])) {

          } else { ?>
          <h4><?php  echo sprintf("%01.2f",$row[0]) ?> грн.</h4>
          <p>закриті...</p>
          <p>якщо зміна закрита...</p>
          <i class="fa fa-cube fa-2x" aria-hidden="true"></i>
          <p><h4><?php  echo sprintf("%01.2f",$row[1])  ?> грн.</h4></p>
          <p>обслуговування по закритих...</p>
          <i class="fa fa-cube fa-2x"  aria-hidden="true"></i>
          <p><h4><?php  echo sprintf("%01.2f",$row[2])  ?> грн.</h4></p>
          <p>знижка по закритих...</p>
          <i class="fa fa-cube fa-2x" aria-hidden="true"></i>
          <p><h4><?php  echo sprintf("%01.2f",$row[3])  ?> грн.</h4></p>
          <p>передоплата по закритих...</p>
          <?php      
          }
      ?>

    </div>
    <div class="col-sm-3">
      <i class="fa fa-cubes fa-3x" aria-hidden="true"></i>
      <?php 
          $val = 0.00;
          $sumQrec = "SELECT sum(_sum),sum(_sumpremium) ,sum(_sumdiscont),sum(_sumprepayment) FROM record WHERE _typer = 1 and _stage = 6  ".$and_shift.";";
          $results = $dblite->query( $sumQrec);
          $row =  $results->fetchArray();
          if (empty($row[0])) {

          } else {?>
          <h4><?php echo sprintf("%01.2f",$row[0]) ?> грн.</h4>
          <p>розраховані...</p>
          <p>відвідувач розрахувався за замовлення...</p>
          <i class="fa fa-cube fa-2x" aria-hidden="true"></i>
          <p><h4><?php echo sprintf("%01.2f",$row[1])?> грн.</h4></p>
          <p>обслуговування по розрахованим...</p>
          <i class="fa fa-cube fa-2x" aria-hidden="true"></i>
          <p><h4><?php echo sprintf("%01.2f",$row[2]) ?> грн.</h4></p>
          <p>знижка по розрахованим...</p>
          <i class="fa fa-cube fa-2x" aria-hidden="true"></i>
          <p><h4><?php echo sprintf("%01.2f",$row[3]) ?> грн.</h4></p>
          <p>передоплата по розрахованим...</p>

      <?php 
          }
      ?>
    </div>
    <div class="col-sm-3">
      <i class="fa fa-cube fa-3x" aria-hidden="true"></i>
      <?php 
          $val = 0.00;
          $sumQrec = "SELECT sum(_sum) FROM record WHERE  _typer = 1 and _stage = 2  ".$and_shift.";";
          $results = $dblite->query( $sumQrec);
          $row =  $results->fetchArray();
          if (empty($row[0])) {

          } else {?>
          <h4><?php echo sprintf("%01.2f",$row[0]) ?> грн.</h4>
          <p>не розраховано...</p>
          <p>рахунок надрукований...</p>
          <i class="fa fa-cube fa-2x" aria-hidden="true"></i>
          <p><h4><?php echo sprintf("%01.2f",$row[1]) ?> грн.</h4></p>
          <p>обслуговування ...</p>
          <i class="fa fa-cube fa-2x" aria-hidden="true"></i>
          <p><h4><?php echo sprintf("%01.2f",$row[2])?> грн.</h4></p>
          <p>знижка ...</p>
          <i class="fa fa-cube fa-2x" aria-hidden="true"></i>
          <p><h4><?php echo sprintf("%01.2f",$row[3]) ?> грн.</h4></p>
          <p>передоплата ...</p>
      <?php 
          }
      ?>
    </div>
    <div class="col-sm-3">
      <i class="fa fa-cube fa-3x" aria-hidden="true"></i>
      <?php 
          $val = 0.00;
          $sumQrec = "SELECT sum(_sum) FROM record WHERE _typer = 1 and _stage = 1  ".$and_shift.";";
          $results = $dblite->query( $sumQrec);
          $row =  $results->fetchArray();
          if (empty($row[0])) {

          } else {?>
          <h4><?php echo sprintf("%01.2f",$row[0]) ?> грн.</h4>
          <p>актівні... </p>
          <p>ті що обслуговуються ...</p>
          <i class="fa fa-cube fa-2x" aria-hidden="true"></i>
          <p><h4><?php echo sprintf("%01.2f",$row[1]) ?> грн.</h4></p>
          <p>обслуговування ...</p>
          <i class="fa fa-cube fa-2x" aria-hidden="true"></i>
          <p><h4><?php echo sprintf("%01.2f",$row[2])?> грн.</h4></p>
          <p>знижка ...</p>
          <i class="fa fa-cube fa-2x" aria-hidden="true"></i>
          <p><h4><?php echo sprintf("%01.2f",$row[3]) ?> грн.</h4></p>
          <p>передоплата ...</p>
      <?php 
          }
      ?>
    </div>
  </div>

  <br><br><!-- DELLLITE-->
  <div class="row slideanim">
    <div class="col-sm-12">
     <i class="fa fa-times-circle  fa-3x" aria-hidden="true"></i>
        <h4>видалені із замовлень позиції ...</h4>
      <table class="table">
          <thead class ="panel-heading">
            <tr>
              <th class="text-center">час друку</th>
              <th class="text-center">замовлення</th>
              <th class="text-center">назва</th>
              <th class="text-center">кількість</th>
              <th class="text-center">ціна</th>
              <th class="text-center">сума</th>
              <th class="text-center">повідомлення</th>
              <th class="text-center">час вида-ня</th>
              <th class="text-center">відпові-ний</th>
            </tr>
          </thead>
          <tbody>
            
              <?php 
                $delQrec = "SELECT * FROM dellinerecord WHERE _record in (SELECT _number FROM record where _typer = 1  ".$and_shift.") ;";
                $results = $dblite->query( $delQrec);
                if ($results <> false ){
                while ($row = $results->fetchArray()) {
              
                  echo "<tr>
                      <td>".  $row['_printtyme']."</td>
                      <td>".  $row['_record']   ."</td>
                      <td>".  $row['_name']     ."</td>
                      <td>".  $row['_quantity'] ."</td>
                      <td>".  $row['_price']    ."</td>
                      <td>".  $row['_sum']      ."</td>
                      <td>".  $row['_msg']      ."</td>
                      <td>".  $row['_timedel']  ."</td>
                      <td>".  $row['_admin']    ."</td></tr> ";
                  
                }}else{echo '<h3>видалені позиції відсутні... </h3>';}
              ?>
            
          </tbody>
        </table>
    </div>
  </div>
  <br><br>


  <div class="row slideanim">  
    <div class="col-sm-3">
     <i class="fa fa-cube fa-2x" aria-hidden="true"></i>
      <?php 
          $val = 0.00;
          $sumQrec = "SELECT COUNT(*) FROM record WHERE _typer = 1 and (_table = '0' or _table = 'З собою.')  ".$and_shift.";";
          $results = $dblite->query( $sumQrec);
          $row =  $results->fetchArray();
          if (empty($row[0])) {

          } else {
            $val = $row[0];
          }
      ?>
      <h4><?php  echo $val ?></h4>
      <p>з собою...</p>
      <?php 
          $val = 0.00;
          $sumQrec = "SELECT _time , _number FROM record WHERE  _typer = 1 and (_table = '0' or _table = 'З собою.')  ".$and_shift."  GROUP BY _time";
          $results = $dblite->query( $sumQrec);
          if ($results <> false ){
          while ($row = $results->fetchArray()) {
              echo '<p>час  '.$row['_time'].'<a>   №'.$row['_number'].'</a> </p>';
          }}
      ?>
    </div>
    <div class="col-sm-3">
     <i class="fa fa-cube fa-2x" aria-hidden="true"></i>

      <?php 
          $val = 0.00;
          $sumQrec = "SELECT _paymentt , sum(_sum) FROM record WHERE  _typer = 1   ".$and_shift."  GROUP BY _paymentt";
          $results = $dblite->query( $sumQrec);
          if ($results <> false ){
          while ($row = $results->fetchArray()) {
              $print_name = '';
              if ($row['_paymentt'] == 2) {
                $print_name = "[Безналичный расчет]";
              } elseif ($row['_paymentt'] == 1) {
                $print_name = "[За наличные]";
              } elseif ($row['_paymentt'] == 3) {
                $print_name = "[Спецобслуживание]";
              } elseif ($row['_paymentt'] == 4) {
                $print_name = "[Питание штата]";
              } elseif ($row['_paymentt'] == 5) {
                $print_name = "[Битая посуда]";
              } elseif ($row['_paymentt'] == 6) {
                $print_name = "[Списания]";
              }

              echo '<p>  '. $print_name .' =  '.$row[1].' </p>';
          }}
      ?>


     <p>по формам оплати...</p>
    </div>

      <div class="col-sm-6">
      <i class="fa fa-cube fa-2x" aria-hidden="true"></i>
      <div class="panel-group" id="accordiondep">

      <?php 
        $val = 0;
        $sumQrec = "SELECT _department, sum(_sum) FROM linerecord WHERE _record in (SELECT _number FROM record where _typer = 1  ".$and_shift.")  GROUP BY _department;";
        $results = $dblite->query( $sumQrec);
        if ($results <> false ){
        while ($row = $results->fetchArray()) {
          $val = $val + 1;
         $printS =  '<div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordiondep" href="#collapse'.$val.'">
                '.$row['_department'].' <p>'. sprintf("%01.3f",$row[1]) .' грн.</p>
                </a>
              </h4>
            </div>
            <div id="collapse'.$val.'" class="panel-collapse collapse">
              <div class="panel-body">
                                  <table class="table table-condensed">
                    <tbody>
                    <tr>';

              $sumQrec = "SELECT _name, _price, sum(_quantity), sum(_sum) FROM linerecord WHERE _department = '".$row['_department']."' and _record in (SELECT _number FROM record where _typer = 1  ".$and_shift.")  GROUP BY _name ORDER BY _group;";
              $resultslr = $dblite->query( $sumQrec);
                if ($resultslr <> false ){
                while ($rowlr = $resultslr->fetchArray()) {

                   $printS =  $printS . '<tr> <td> '.$rowlr['_name'].'</td> <td> ц.'.sprintf("%01.2f",$rowlr['_price']).' </td> <td>'.$rowlr[2].' </td><td> '.sprintf("%01.2f",$rowlr[3]).' грн.</td></tr>';
                }}
              $printS =  $printS .' </tbody>
                  </table>
               </div>
            </div>
          </div> ';
          echo $printS;
        }}
      ?>

      <p><p></p>по відділам ...</p>

      </div>  
    </div>
<!--  -->
  </div>



<!-- зарплата -->
  <div class="row slideanim"> 
    <div class="col-sm-12">
     <i class="fa fa-cube fa-3x" aria-hidden="true"></i>
     <h4>зарплата ...</h4>
      <div class="row slideanim"> 
        <div class="col-sm-6"> <!-- що підлягая відрахуванню-->
          <i class="fa fa-cube fa-2x" aria-hidden="true"></i> 
                <?php 
                    $val = 0.00;

                    $SUM_FOSTAF = 0;

                    $sumQrec = "SELECT sum(_sum) FROM linerecord WHERE _param_T1 = '1' and _department <> 
                    'Продукты' and _record in (SELECT _number FROM record where _typer = 1  and _paymentt = 1 and _stage >= 6  ".$and_shift.");";
                    $results = $dblite->query( $sumQrec);
                    if ($results <> false ){
                    while ($row = $results->fetchArray()) {
                        echo '<h4> '.sprintf("%01.2f",$row[0]).' грн.</h4>';
                        $SUM_FOSTAF = $row[0];
                    }}
                ?>

          <p>сума що підлягає відрахуванню...</p>

          <?php 
            $val = 0.00;
            $sumQrec = "SELECT users FROM shift WHERE  _number = '".$SHIFT."';";
            //echo $sumQrec;
            $results = $dblite->query( $sumQrec);
            $summ = 0;
            if ($results <> false ){
            while ($row = $results->fetchArray()) {
            //  echo "<p>" .$row[0]. "</p>";
              $aUsers = explode('|',$row[0]);
              foreach($aUsers as $e){
                if ($e <> '' ){
                  if ($e <> 'False'){
              //  echo "<p>" .$e. "</p>";
                $usQ = "SELECT _name,rate FROM staff WHERE id = ".$e.";"; 
              //  echo $usQ;
                
                  $resultsU = $dblite->query( $usQ);
                  if ($resultsU <> false ){
                    while ($rows = $resultsU->fetchArray()) {

                      $sumUser = $SUM_FOSTAF/100*$rows['rate'];

                      echo "<p> ". $rows['_name'] ."    ". $rows['rate'] ."%  из ".$SUM_FOSTAF."  =  ". sprintf("%01.2f", $sumUser)."</p>";
                      $summ = $summ + $sumUser;
                    }
                  }
                }else{ 
                echo "<p> !НЕ ВІРНО ВІДКРИТА ЗМІНА... </p>";
              }}
              }
              unset($e);
            }}

            echo "<p><h4>Всього заробітня плата становить " .sprintf("%01.2f", $summ) . " грн.</h4></p>";
          ?>

        </div>
                <div class="col-sm-6"> <!-- що підлягая відрахуванню-->
          <i class="fa fa-cube fa-2x" aria-hidden="true"></i> 
                <?php 
                    $val = 0.00;
                    $sumQrec = "SELECT sum(_sum) FROM linerecord WHERE _param_T1 = '0' and _department <> 'Продукты' and _record in (SELECT _number FROM record where _typer = 1  and _stage >= 6   ".$and_shift.");";
                    $results = $dblite->query( $sumQrec);
                    if ($results <> false ){
                    while ($row = $results->fetchArray()) {
                        echo '<h4> '.sprintf("%01.2f",$row[0]).' грн.</h4>';
                    }}
                ?>

          <p>сума що не підлягає відрахуванню...</p>
        </div>
      </div>



      <div class="row slideanim"> 

        <div class="col-sm-6"> <!-- що підлягая відрахуванню-->
          <i class="fa fa-cube fa-2x" aria-hidden="true"></i>
         <div class="panel-group" id="accordionISZR">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordionISZR" href="#collapseISZR">
                          підлягає відрахуванню... <p>всі продані за зміну...</p>
                        </a>
                      </h4>
              </div>
              <div id="collapseISZR" class="panel-collapse collapse">
                <div class="panel-body">
                    <table class="table table-condensed">
                    <tbody>
                    <tr>
                  <?php 
                      $val = 0.00;
                      $sumQrec = "SELECT _name, _price, sum(_quantity), sum(_sum) FROM linerecord WHERE _param_T1 = '1' 
                          and _department <> 'Продукты' and _record in (SELECT _number FROM record where _typer = 1  and _stage >= 6   ".$and_shift.") GROUP BY _name  ORDER BY _group;";
                      $results = $dblite->query( $sumQrec);
                      if ($results <> false ){
                      while ($row = $results->fetchArray()) {
                          echo '<tr> <td> '.$row['_name']." </td> <td>".$row[2].' </td><td> ц.'.sprintf("%01.2f",$row['_price']).' грн. </td><td>'.sprintf("%01.2f",$row[3]).' грн.</td></tr> ';
                      }}
                  ?>
                  </tbody>
                  </table>
                  </div>
             </div>
           </div>
        </div>
      </div>  

      <div class="col-sm-6"> <!-- що НЕ підлягая відрахуванню-->
        <i class="fa fa-cube fa-2x" aria-hidden="true"></i>
       <div class="panel-group" id="accordionNOTISZR">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordionNOTISZR" href="#collapseNOTISZ1R">
                        НЕ підлягає відрахуванню... <p>всі продані за зміну...</p>
                      </a>
                    </h4>
            </div>
            <div id="collapseNOTISZ1R" class="panel-collapse collapse">
              <div class="panel-body">
                    <table class="table table-condensed">
                    <tbody>
                    <tr>
                <?php 
                    $val = 0.00;
                    $sumQrec = "SELECT _name, _price, sum(_quantity), sum(_sum) FROM linerecord WHERE _param_T1 = '0' 
                    and _department <> 'Продукты' and _record in (SELECT _number FROM record where _typer = 1   and _stage >= 6   ".$and_shift.") GROUP BY _name ORDER BY _group;";
                    $results = $dblite->query( $sumQrec);
                    if ($results <> false ){
                    while ($row = $results->fetchArray()) {
                        echo '<tr> <td> '.$row['_name']." </td> <td>".$row[2].' </td><td> ц.'.sprintf("%01.2f",$row['_price']).' грн. </td><td>'.sprintf("%01.2f",$row[3]).' грн.</td></tr> ';
                    }}
                ?>
                  </tbody>
                  </table>
                </div>
           </div>
         </div>
      </div>
    </div>
    </div>


      <div class="row slideanim"> 

        <div class="col-sm-6"> <!-- що підлягая відрахуванню-->
          <i class="fa fa-cube fa-2x" aria-hidden="true"></i>
         <div class="panel-group" id="accordionISZ">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordionISZ" href="#collapseISZ">
                          підлягає відрахуванню... <p>всі присутні в номенклатурі</p>
                        </a>
                      </h4>
              </div>
              <div id="collapseISZ" class="panel-collapse collapse">
                <div class="panel-body">
                    <table class="table table-condensed">
                    <tbody>
                    <tr>
                  <?php 
                      $val = 0.00;
                      $sumQrec = "SELECT _cod, _name, _price FROM goods WHERE _param_T1 = '1' and _department <> 'Продукты';";
                      $results = $dblite->query( $sumQrec);
                      if ($results <> false ){
                      while ($row = $results->fetchArray()) {
                          echo '<tr> <td>'.$row['_cod']."</td><td> ".$row['_name'].' </td><td> ц.'.sprintf("%01.2f",$row['_price']).' грн.</td> </tr>';
                      }}
                  ?>
                  </tbody>
                  </table>
                  </div>
             </div>
           </div>
        </div>
      </div>  

      <div class="col-sm-6"> <!-- що НЕ підлягая відрахуванню-->
        <i class="fa fa-cube fa-2x" aria-hidden="true"></i>
       <div class="panel-group" id="accordionNOTISZ">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordionNOTISZ" href="#collapseNOTISZ1">
                        НЕ підлягає відрахуванню... <p>всі присутні в номенклатурі</p>
                      </a>
                    </h4>
            </div>
            <div id="collapseNOTISZ1" class="panel-collapse collapse">
              <div class="panel-body">
                    <table class="table table-condensed">
                    <tbody>
                    <tr>
                <?php 
                    $val = 0.00;
                    $sumQrec = "SELECT _cod, _name, _price FROM goods WHERE _param_T1 = '0' and _department <> 'Продукты';";
                    $results = $dblite->query( $sumQrec);
                    if ($results <> false ){
                    while ($row = $results->fetchArray()) {
                        echo '<tr> <td>'.$row['_cod']."</td><td> ".$row['_name'].' </td><td> ц.'.sprintf("%01.2f",$row['_price']).' грн.</td> </tr>';
                    }}
                ?>
                  </tbody>
                  </table>
                </div>
           </div>
         </div>
      </div>
    </div>
    </div>
  </div>
<!--  -->

  <div class="row slideanim">  
    <div class="col-sm-12">
      <i class="fa fa-cube  fa-3x" aria-hidden="true"></i>
      <h4>замовлення...</h4>
      <div class="panel-group" id="accordion">
      <?php 
       /*_typer, _data, _time, _number, _stage, _status, _table, _visitor, _user, _paymentt, _transactiont, _client, _received,
       _delivery, _sum, _discontt, _sumdiscont, _percentdisc, _totalgdiscont, _premiumt, _sumpremium, _percentpremium, 
       _totalgpremium, _prepaymentt, _sumprepayment, _percentprepay, _ekka, _msg, _admin, _service, _overhead, _sent, 
       _host, _write_, _param_T1, _param_T2, _param_DT1, _param_R1, _shift, _param_I1, _param_I2 */
       $recQrec = "SELECT * FROM record where _typer = 1   ".$and_shift." ;";
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
                      <td>обслу-ння <p>'.$rowR['_sumpremium'].'</p></tb>
                      <td>знижка <p>'.$rowR['_sumdiscont'].'</p></tb>
                      <td>передоплата <p>'.$rowR['_sumprepayment'].'</p></tb>
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
                    <td>назва</td><td>кількість</td><td>ціна</td><td>сума</td>
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


<a class="fixs-afix" id="shiftfilter" title="" data-spy="affix" data-offset-top="205">
  <h4 class="text-right"><i class="fa fa-filter fa-2x" aria-hidden="true"></i></h4>
</a>


<!--Во всех тонах и разной цветовой палитре
Разум отчаяно рисует мисли оборачивает их в слова -->






