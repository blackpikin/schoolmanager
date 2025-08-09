<div class="row" style="margin-top: 10px;">
    <div class="col-md-4 col-sm-4 col-xs-4">

    </div>
    <div class="col-md-4 col-sm-4 col-xs-4">
        <p>
            <label id="label1">Revenue sources</label>
        </p>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-4">

    </div>
</div>
<div class="row">
    <div class="col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-md-8 col-sm-8 col-xs-8">
        <button class="btn btn-primary" onclick="GotoPage('newRevSource')">New source</button>
        <br>
        <br>
        <?php 
            $sources = $Model->GetRevenueSources();        
        ?>
        <table class="table table-bordered table-responsive">
            <tr class="table-header">
                <td>Source</td>
                <td>Set by</td>
                <td>Action</td>
            </tr>
            <?php
                    if(!empty($sources)){
                        foreach($sources as $res){
                            ?>
                             <tr class="normal-tr">
                                <td><?= $res['source'] ?></td>
                                <td><?= $Model->GetUser($res['user_id'])[0]['name'] ?></td>
                                <td>
                                    <button onclick="GotoPage('modifyRev&ref=<?= $res['id'] ?>')" title="Edit" class="btn btn-primary fa fa-edit"></button>
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