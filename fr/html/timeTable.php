<br>
<div class="row" >
    <div class="col-xs-3">

    </div>
    <div class="col-xs-8">
        <p>
            <label id="label1"><?= $lang[$_SESSION['lang']]["Timetable"] ?></label>
        </p>
    </div>
    <div class="col-xs-1">

    </div>
</div>
<div class="row" >
<div class="col-xs-3">

</div>
    <div class="col-xs-4">
        <select id="timetable" class="form-control">
        <option value=""><?= $lang[$_SESSION['lang']]["Choose one"] ?></option>
            <option value="school">School timetable</option>
        </select>
    </div>
    <div class="col-xs-5">
        <button type="button" class="btn btn-primary"><?= $lang[$_SESSION['lang']]["Generate"] ?></button>
    </div>
</div>