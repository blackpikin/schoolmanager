<br>
<div class="row" >
    <div class="col-md-3 col-sm-3 col-xs-3">

    </div>
    <div class="col-md-8 col-sm-8 col-xs-8">
        <p>
            <label id="label1"><?= $lang[$_SESSION['lang']]["Timetable"] ?></label>
        </p>
    </div>
    <div class="col-md-1 col-sm-1 col-xs-1">

    </div>
</div>
<div class="row" >
<div class="col-md-3 col-sm-3 col-xs-3">

</div>
    <div class="col-md-4 col-sm-4 col-xs-4">
        <select id="timetable" class="form-control">
        <option value=""><?= $lang[$_SESSION['lang']]["Choose one"] ?></option>
            <option value="school">School timetable</option>
        </select>
    </div>
    <div class="col-md-5 col-sm-5 col-xs-5">
        <button type="button" onclick="SchoolTimeTable()" class="btn btn-primary"><?= $lang[$_SESSION['lang']]["Generate"] ?></button>
    </div>
</div>