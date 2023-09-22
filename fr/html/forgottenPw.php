<div class="row" style="margin-top:50px;">
        <div class="col-xs-1">

        </div>
        <div class="col-xs-8">
        <h4 id="label1" style="text-align:center;"><?= $lang[$_SESSION['lang']]["RecoverPw"] ?></h4>
        </div>
        <div class="col-xs-3">

        </div>
</div>
<div class="row">
    <div class="col-xs-1">

    </div>
    <div class="col-xs-8 curved-box">
        <p style="font-weight:bold;font-size:13pt;"><?= $lang[$_SESSION['lang']]["IfYouHave"] ?></p>
        <ul>
        <?php
            $admins = $Model->GetAdmins();
            foreach($admins as $admin){
                ?>
                <li style="font-size:13pt;">+237 <?= $admin['phone'] ?></li>
                <?php
            }
        ?>
        </ul>
        <p style="font-weight:bold;font-size:13pt;"><?= $lang[$_SESSION['lang']]["OrSendEmail"] ?></p>
        <ul>
        <?php
            $admins = $Model->GetAdmins();
            foreach($admins as $admin){
                ?>
                <li style="font-size:13pt;"><?= $admin['email'] ?></li>
                <?php
            }
        ?>
        </ul>
    </div>
    <div class="col-xs-3">

    </div>
</div>
<br>
<br>
