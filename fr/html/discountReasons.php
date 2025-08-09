<div class="row" style="margin-top: 10px;">
    <div class="col-md-4 col-sm-4 col-xs-4">

    </div>
    <div class="col-md-4 col-sm-4 col-xs-4">
        <p>
            <label id="label1">Discount reasons</label>
        </p>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-4">

    </div>
</div>
<div class="row">
    <div class="col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-md-8 col-sm-8 col-xs-8">
        <button class="btn btn-primary" onclick="GotoPage('newReason')">New reason</button>
        <br>
        <br>
        <?php 
            $reasons = $Model->GetDiscountReasons();        
        ?>
        <table class="table table-bordered table-responsive">
            <tr class="table-header">
                <td>Reason</td>
                <td>Percent</td>
                <td>Set by</td>
                <td>Action</td>
            </tr>
            <?php
                    if(!empty($reasons)){
                        foreach($reasons as $res){
                            ?>
                             <tr class="normal-tr">
                                <td><?= $res['reason'] ?></td>
                                <td><?= $res['percent'] ?> %</td>
                                <td><?= $Model->GetUser($res['user_id'])[0]['name'] ?></td>
                                <td>
                                    <button onclick="GotoPage('modifyReason&ref=<?= $res['id'] ?>')" title="Edit" class="btn btn-primary fa fa-edit"></button>
                                </td>
                            </tr>

                        <?php
                        }
                    }

                ?>
        </table>
    </div>
    <div class="col-md-2 col-sm-2 col-xs-2">

    </div>
</div>