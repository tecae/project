<?php
    session_start();
	if (empty($_SESSION['_user']) or empty($_SESSION['id'])){
		header("Location: ../index.php");		
	}	 

    $selectQ = " SELECT id,_d, _t, _type, _act, _user, _msg, _coment, _l, _I, _shift, _record FROM l WHERE id>0 ";
    $pathToDb = '../db/webdb.db';
    $dblite = new SQLite3($pathToDb);

    if (isset($_POST['intype'])) { $intype=$_POST['intype']; if ($intype =='') { unset($intype);} }
    if (isset($_POST['indata'])) { $indata=$_POST['indata']; if ($indata =='') { unset($indata);} }
    if (isset($_POST['inshift'])) { $inshift=$_POST['inshift']; if ($inshift =='') { unset($inshift);} }
    if (isset($_POST['inrecord'])) { $inrecord=$_POST['inrecord']; if ($inrecord =='') { unset($inrecord);} }

   $intype = trim(htmlspecialchars(stripslashes($intype)));
   $indata = trim(htmlspecialchars(stripslashes($indata)));
   $inshift = trim(htmlspecialchars(stripslashes($inshift)));
   $inrecord = trim(htmlspecialchars(stripslashes($inrecord)));

   if (empty($intype) or $intype == "all") 
    {
    
    }else{
        $selectQ = $selectQ . " and  _type='$intype'";
    }
    if (empty($indata) or $indata == "") 
    {
    
    }else{
        $selectQ = $selectQ . " and  _d='$indata'";
    }
    if (empty($inshift) or $inshift == "all") 
    {
    
    }else{
        $selectQ = $selectQ . " and  _shift='$inshift'";
    }
    if (empty($inrecord) or $inrecord == "" ) 
    {
    
    }else{
        $selectQ = $selectQ . " and  _shift='$inrecord'";
    }

    $selectQ = $selectQ . ' ';


    $shiftQ = "SELECT _value FROM сonstants WHERE _name = 'shift';";
    $SHIFT = '';
    $results = $dblite->query( $shiftQ);
    $row =  $results->fetchArray();
    if (empty($row['_value'])) {

    } else {
      $SHIFT = $row['_value'];
    }





?>


  <!-- Modal -->
  <div class="modal fade" id="mysafilter" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="padding:35px 50px;">

          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h3>відфільтрувати дані</h3>
        </div>
        <div class="modal-body" style="padding:40px 50px;">
          <form role="form" action="index.php?whotsey=log" method="post" >
                <div class="input-group">
                  <span class="input-group-addon span-in-group">тип</span>
                  <select  class="form-control" name="intype">
                        <option value="all">не використовувати</option>
                        <option value="+"> [ + ]  неважливо</option>
                        <option value="-"> [ - ]  звернути увагу</option>
                        <option value="_"> [ _ ]  повідомлення</option>
                        <option value="!"> [ ! ]  увага</option>
                        <option value="add"> [ add ]  додавання даних</option>
                        <option value="edit"> [ edit ]  редагування даних</option>
                        <option value="del" selected> [ del ]  видалення даних</option>
                    
                  </select>
                </div>
                    
                <div class="input-group">
                  <span class="input-group-addon span-in-group">дата</span>
                  <input type="date"  class="form-control" name="indata">
                </div>


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
                            if ($val==$SHIFT){$sel = 'selected';}else{$sel = '';}
                            echo '<option value="'.$val.'"  '.$sel.'>'.$datashift."  ".$val.'</option>';
                          }
                        ?>
                  </select>
                </div>
                
                <div class="input-group">
                  <span class="input-group-addon span-in-group">замовлення</span>
                  <input type="text"  class="form-control" name="inrecord">
                </div>
              <button type="submit" class="btn btn-success btn-block"><i class="fa fa-refresh" aria-hidden="true"></i> оновити</button>
          </form>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger btn-default pull-left" data-dismiss="modal"> ні дякую...</button>
        </div>
      </div>
      
    </div>
  </div> 

<div class="container-fluid">

<?php

$selectQ = $selectQ ."  ORDER BY id DESC LIMIT 500";
//echo "".$selectQ;

 $results = $dblite->query( $selectQ);
 while ($row = $results->fetchArray()) {

    $classBG = '';
    $classBR = '';
    switch ($row['_type']) {
        case '!':
            $classBG = 'panel-heading-light-red';
            $classBR = 'panel-default-light-red';
            break;
        case '+':
            $classBG = 'panel-heading-green';
            $classBR = 'panel-default-green';
            break;
        case '-':
            $classBG = 'panel-heading-orange';
            $classBR = 'panel-default-orange';
            break;
        case '_':
            $classBG = 'panel-heading-def';
            $classBR = 'panel-default-def';
            break;
        case 'add':
            $classBG = 'panel-heading-light-green';
            $classBR = 'panel-default-light-green';
            break;
        case 'del':
            $classBG = 'panel-heading-red';
            $classBR = 'panel-default-red';
            break;
        case 'edit':
            $classBG = 'panel-heading-elo';
            $classBR = 'panel-default-elo';
            break;
        default:
            break;
    }
    $icosize = ' fa-lg ';
    echo '<div class="col-sm-6 col-xs-12">
          <div class="panel panel-default  '.$classBR.'  text-center ">
            <div class="panel-heading   '.$classBG.'  '.$classBR.'">
                <p>
                    <h3 class="panel-title ">
                        <a href="#">
                            <i class="fa fa-calendar'.$icosize.'" aria-hidden="true"></i></a>

                            <small></small><strong>  '. date_format(date_create($row['_d']), 'd.m.Y') .'  </strong>

                        <a href="#">
                        <i class="fa fa-clock-o'.$icosize.'" aria-hidden="true"></i></a>
                            <small></small><strong>  '.str_replace('-' , ':' , $row['_t'] ).'   </strong>
                    </h3>
                </p>
                <p></h3>
                <a href="#">
                <i class="fa fa-tag'.$icosize.'" aria-hidden="true"></i></a>
                <strong>'.$row['_record'].'      </strong>  
                 <a href="#">
                <i class="fa fa-tag'.$icosize.'" aria-hidden="true"></i> </a>           
                <strong>'.$row['_shift'].'</strong></h3></p> 
            </div>
            <div class="panel-body '.$classBR.'">
                <p>
                <h5>
                 <a href="#">
                <i class="fa fa-eercast'.$icosize.'" aria-hidden="true">  </i></a>

                <strong>'.$row['_act'].'</strong></h5></p>

                <p>
                <h5> 
                 <a href="#">
                <i class="fa fa-comments-o'.$icosize.'" aria-hidden="true">  </i> </a>
                <strong>'.$row['_coment'].'</strong></h5></p>
            </div>
            <div class="panel-footer '.$classBR.'">
                <p><h6>
                    <i class="fa fa-ravelry" aria-hidden="true">  </i>
                    <strong>'.$row['id'].'  </strong>
                    <i class="fa fa-user'.$icosize.'" aria-hidden="true">  </i>
                    <strong>'.$row['_user'].'</strong>

                    <i class="fa fa-code-fork'.$icosize.'" aria-hidden="true">  </i>
                    <strong>'.$row['_l'].'</strong>
                    <i class="fa fa-code-fork'.$icosize.'" aria-hidden="true">  </i>
                    <strong>'.$row['_I'].'</strong>
                    <i class="fa fa-code-fork'.$icosize.'" aria-hidden="true">  </i>
                    <strong>'.$row['_type'].'</strong>
                </h6></p>
            </div>
          </div>      
        </div>       
    ';

    } 

?>
</div>
 
<a class="fixs-afix" id="logfilter" title="На початок" data-spy="affix" data-offset-top="205">
  <h4 class="text-right"><i class="fa fa-filter fa-2x" aria-hidden="true"></i></h4>
</a>
    

<?php $dblite->close(); ?>







