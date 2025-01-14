<?php
/**
 * Created by PhpStorm.
 * User: Halsey
 * Date: 26/05/2020
 * Time: 18:37
 */
?>
<div>
<div class="row">
    <div class="col-md-4 col-sm-4 col-xs-4">

    </div>
    <div class="col-md-4 col-sm-4 col-xs-4">
        <h1 id="label1"><?= $lang[$_SESSION['lang']]["Welcome"] ?>,  <?= $_SESSION['username']; ?></h1>  
    </div>
    <div class="col-md-4 col-sm-4 col-xs-4">

    </div>
 </div>   
<div class="row">
        <div class="col-md-7 col-sm-7 col-xs-7 curved-box" style="background-color:white;">
         <?php
            $events = $Model->GetEvents(date('Y-m'));
            if(!empty($events)){
                foreach($events as $ev){
                    $calendar->add_event($ev['event'], $ev['dateof'], $ev['duration'], $ev['colored']);
                }
            }
            
         echo $calendar; 
         ?>
        </div>
        <div class="col-md-4 col-sm-4 col-xs-4 curved-box">
            <h3 class=""><?= $lang[$_SESSION['lang']]["UpcomingEvents"] ?></h3>
          <?php
            $year = date('Y');
            $m = date('m');
            $month = (int)$m + 1;
            if(strlen($month) == 1){
                $month_year = $year.'-0'.$month;
            }else{
                $month_year = $year.'-'.$month;
            }
            
            $events = $Model->GetEvents($month_year); 
            if(!empty($events)){
                ?>
                <table class="table table-responsive table-bordered" style="font-size:10pt;">
                    <tr class="table-header">
                            <td class="text-white"><?= $lang[$_SESSION['lang']]["Event"] ?></td>
                            <td class="text-white">Date</td>
                    </tr>
                <?php
                foreach($events as $ev){
                    ?>
                    <tr class="normal-tr">
                        <td class="text-white"><?= $ev['event'] ?></td>
                        <td class="text-white">
                        <?php
                            $evDate = new DateTime($ev['dateof']);
                            echo date_format($evDate, "d F Y")
                        ?>
                        </td>
                    </tr>
                <?php
                }
            }else{
                echo '<h5 class="">'.$lang[$_SESSION['lang']]["NoUpcomingEvents"]."</h5>";
            }    
          ?>
          </table>
        </div>
    </div>
</div>