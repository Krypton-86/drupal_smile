<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles/style-main.css">
    <title>My personal page</title>
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
            <input type="text" class="fields" value="John" readonly><br><br>
            <label class="fields_label">Last name:</label>
            <input type="text" class="fields" value="John" readonly><br><br>
            <label class="fields_label">Email:</label>
            <input type="text" class="fields" value="John" readonly><br><br>
            <label class="fields_label">Password:</label>
            <input type="text" class="fields" value="John" readonly><br><br>
            <label class="fields_label">Birthday:</label>
            <input type="text" class="fields" value="1991-10-12" readonly><br><br>
            <label class="fields_label">Interested in:</label>
            <input type="text" class="fields" value="array[]" readonly><br><br>
        </div>
    </form>

</div>

</body>
</html>