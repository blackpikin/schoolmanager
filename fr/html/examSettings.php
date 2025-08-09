<?php
    $lng = $_SESSION['lang'];
    $section = 0;
    if($lng == 'fr'){
        $section = 1;
    }
    if(isset($Model->GetCurrentYear()[0]['id'])){
        $exams = $Model->GetAllExams($Model->GetCurrentYear()[0]['id']);
    }else{
        $exams = [];
    }
    
?>
<div class="row" style="margin-top: 50px;">
    <div class="col-md-4 col-sm-4 col-xs-4">

    </div>
    <div class="col-md-4 col-sm-4 col-xs-4">
        <p>
            <label id="label1"><?= $lang[$_SESSION['lang']]['ExamSettings'] ?></label>
        </p>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-4">

    </div>
</div>
<div class="row">
    <div class="col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-md-8 col-sm-8 col-xs-8">
        <button class="btn btn-primary button-width" onclick="GotoPage('exam')"><?= $lang[$_SESSION['lang']]['NewExam'] ?></button>   
    </div>
    <div class="col-md-2 col-sm-2 col-xs-2">

    </div>
</div>
<br>
<div class="row">
    <div class="col-md-1 col-sm-1 col-xs-1">

    </div>
    <div class="col-md-10 col-sm-10 col-xs-10">
       <table class="table table-responsive table-bordered">
           <tr class="table-header">
               <td><?= $lang[$_SESSION['lang']]['TERM'] ?></td>
               <td>Sequence</td>
               <td><?= $lang[$_SESSION['lang']]['AcademicYear'] ?></td>
               <td><?= $lang[$_SESSION['lang']]['Weighted'] ?></td>
               <td><?= $lang[$_SESSION['lang']]['Percentage'] ?></td>
               <td><?= $lang[$_SESSION['lang']]['Status'] ?></td>
           </tr>
           <?php 
                if (!empty($exams)){
                    foreach ($exams as $exam){
                        ?>
                        <tr class="normal-tr">
                            <td>
                                <?php
                                 if($exam['term'] == 'First'){
                                    echo $lang[$_SESSION['lang']]['FIRST'];
                                 }elseif($exam['term'] == 'Second'){
                                    echo $lang[$_SESSION['lang']]['SECOND'];
                                 }else{
                                    echo $lang[$_SESSION['lang']]['THIRD'];
                                 }
                                  ?>
                            </td>
                            <td>
                            <?php
                                 if($exam['sequence'] == 'ONE'){
                                    echo $lang[$_SESSION['lang']]['ONE'];
                                 }elseif($exam['sequence'] == 'TWO'){
                                    echo $lang[$_SESSION['lang']]['TWO'];
                                 }elseif($exam['sequence'] == 'PRE-MOCK'){
                                    echo $lang[$_SESSION['lang']]['SEQUENCE PRE-MOCK'];
                                 }else{
                                    echo $lang[$_SESSION['lang']]['SEQUENCE MOCK'];
                                 }
                                  ?>
                            </td>
                            <td><?= $Model->GetCurrentYear()[0]['start'] ?>/<?= $Model->GetCurrentYear()[0]['end'] ?></td>
                            <td><?= $exam['weighted'] == 0 ? $lang[$_SESSION['lang']]['No'] : $lang[$_SESSION['lang']]['Yes'] ?></td>
                            <td><?= $exam['weighted'] == 0 ? "N/A" : $exam['percentage'] ?></td>
                            <td><?= $exam['status'] == 0 ? $lang[$_SESSION['lang']]['StateClose'] : $lang[$_SESSION['lang']]['StateOpen'] ?></td>
                        </tr>
                        <?php
                    }
                }
           ?>
       </table>
    </div>
    <div class="col-md-1 col-sm-1 col-xs-1">

    </div>
</div>