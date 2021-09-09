<?php
require 'db_connect.php';
require 'user.php';

$user = new user();
$from_db=$user->info($db_conn);
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles/style-main.css">
    <title><?php echo $from_db['First_name'];?>`s, page</title>
    <meta charset="utf-8">
</head>
<body>
<div class="form_block">
    <div id="edit_LogOut">
        <div class="edit_block">
            <a href="personalize.php" target="_self">edit data</a>
        </div>
        <div class="logout_block">
            <a href="logout.php" target="_self">Log out</a>
        </div>
    </div>
    <form id="mypage_form"
          method="post">
        <h2>What we know about You:</h2>
        <div class="fields_block">
            <label class="fields_label">First name:</label>
            <input type="text" class="fields" value="<?php echo $from_db['First_name']?>" readonly><br><br>
            <label class="fields_label">Last name:</label>
            <input type="text" class="fields" value="<?php echo $from_db['Last_name']?>" readonly><br><br>
            <label class="fields_label">Email:</label>
            <input type="text" class="fields" value="<?php echo $from_db['Email']?>" readonly><br><br>
            <label class="fields_label">Password:</label>
            <input type="text" class="fields" value="<?php echo $from_db['Password']?>" readonly><br><br>
            <label class="fields_label">Birthday:</label>
            <input type="text" class="fields" value="<?php echo $from_db['Birthday']?>" readonly><br><br>
            <label class="fields_label">Interested in:</label>
            <input type="text" class="fields" value="<?php $str=""; foreach ($from_db as $key=>$value){$str = $str . ($value=='1'&&$key!='user_id'? $key . ", " : ""); } echo rtrim($str, ", "); ?>" readonly><br><br>
        </div>
    </form>

</div>

</body>
</html>