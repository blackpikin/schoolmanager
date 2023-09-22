<div class="row" style="margin-top: 10px;">
    <div class="col-xs-4">

    </div>
    <div class="col-xs-4">
        <p>
            <label id="label1">Revenue sources</label>
        </p>
    </div>
    <div class="col-xs-4">

    </div>
</div>
<div class="row">
    <div class="col-xs-2">

    </div>
    <div class="col-xs-8">
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
                                    <button onclick="GotoPage('modifyRev&ref=<?= $res['id'] ?>')" title="Edit" class="btn btn-primary glyphicon glyphicon-edit"></button>
                                </td>
                            </tr>

                        <?php
                        }
                    }

                ?>
        </table>
    </div>
    <div class="col-xs-2">

    </div>
</div>