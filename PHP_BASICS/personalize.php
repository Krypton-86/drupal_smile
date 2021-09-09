<?php
require 'db_connect.php';
$db_query = "SELECT * FROM smile.users WHERE user_id='" . $_COOKIE['user_id'] . "';";
$result = mysqli_query($db_conn, $db_query);
// Run query for writing user info
$from_db = [];
if (mysqli_num_rows($result) > 0) {
  $from_db = mysqli_fetch_assoc($result);
} else {
  echo '<style> h1{text-align: center; color: darkred;}</style> <br><br><br><br><br><br><br><br><br><h1>403<h1><br><br><br><br><br><br><br><br><br>';
  header( "refresh:0;url=authorization.html" );
}
mysqli_close($db_conn);
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="styles/style-main.css">
  <title>Editing <?php echo $from_db['First_name'];?>`s, info</title>
  <meta charset="utf-8">
</head>
<body>
<div class="form_block">
  <div id="edit_LogOut">
    <div class="edit_block">
      <a href="mypage.php" target="_self">Cancel</a>
    </div>
    <div class="logout_block">
      <a href="logout.php" target="_self">Log out</a>
    </div>
  </div>
  <form action="/action_edit.php" id="mypage_form"
        method="post">
    <h2>Editing mode</h2>
    <div class="fields_edit_block">
      <label class="fields_label">First name:</label>
      <input type="text" class="fields" name="fname" value="<?php echo $from_db['First_name']?>" minlength="2" maxlength="50"
             size="22" required><br><br>
      <label class="fields_label">Last name:</label>
      <input type="text" class="fields" name="lname" value="<?php echo $from_db['Last_name']?>" minlength="2" maxlength="50"
             size="22" required><br><br>
      <label class="fields_label">Email:</label>
      <input type="email" class="fields" name="email" value="<?php echo $from_db['Email']?>" minlength="5"
             maxlength="50" size="48" required><br><br>
      <label class="fields_label">Password:</label>
      <input type="password" class="fields" name="password" value="" placeholder="Can change your password" minlength="8"
             maxlength="32" size="48"><br><br>
      <label class="fields_label">Birthday:</label>
      <input type="date" class="fields" name="birthday" value="<?php echo $from_db['Birthday']?>" min="1925-12-31" required><br><br>
      <label class="fields_label">Interested in:</label>
      <input type="text" class="fields" value="" placeholder="<?php $str=""; foreach ($from_db as $key=>$value){$str = $str . ($value=='1'&&$key!='user_id'? $key . ", " : ""); } echo rtrim($str, ", "); ?>" readonly><br><br>
      <label id="categories_label" for="categories">Choose a categories:</label><br>
      <select id="categories" name="categories[]" multiple size="5">
        <optgroup label="--Please choose a categories--">
          <?php $start = false;
          foreach ($from_db as $key=>$value)
          { if ($key=="Cars"||$start == true){$start = true;
            $a=($value == "1"?" selected":"");
            echo "<option" . $a . " value='" . $key . "'>" . $key . "</option>";}}  ?>
        </optgroup>
      </select><br><br>

      <input type="checkbox" id="confirm_check" name="confirm_reg_check" value="CHECKED" required>
      <label id="confirm_check_label" for="confirm_check"> I confirm this changes</label><br><br>
      <input id="button_GET_STARTED" type="submit" value="APPLY CHANGES">
    </div>
  </form>

</div>

</body>
</html>