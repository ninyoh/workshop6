<?php
$msg='';
$errmsg='';
// Demand a GET parameter

// If the user requested logout go back to index.php
if ( isset($_POST['view']) ) {
    header('Location: view.php');
    return;
}
if(isset($_POST['submit'])){
if (!is_numeric($_POST['year']) || !is_numeric($_POST['mileage']))
{
    $errmsg='Mileage and year must be numeric';
}
elseif ($_POST['make'] == '') {
    $errmsg='Make is required';
}
else{
include('pdo.php');
try {
$dbh = new PDO("mysql:host=sql12.freesqldatabase.com;dbname=sql12256794",'sql12256794','r5d6pZ8NWG');
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // <== add this line
/*$sql = "INSERT INTO users (make, year, mileage)
VALUES ('".$_POST["make"]."','".$_POST["year"]."','".$_POST["mileage"]."')";*/
$stmt = $dbh->prepare('INSERT INTO autos (make, model, year, mileage) VALUES ( :mk, :md, :yr, :mi)');
    $stmt->execute(array(
        ':mk' => $_POST['make'],
        ':md' => $_POST['model'],
        ':yr' => $_POST['year'],
        ':mi' => $_POST['mileage'])
    );

    $msg = 'Record Inserted';

$dbh = null;
}
catch(PDOException $e)
{
echo $e->getMessage();
}
}
}
?>

<!DOCTYPE html>
<html>
<head>
<title>NinyohAutos Database</title>
<?php require_once "bootstrap.php"; ?>
</head>
<style type="text/css">
    .green{
        color: green;
    }
    .red{
        color: red;
    }
</style>
<body>
<div class="container">
<h1>Tracking Autos for NINYOH </h1>
<?php
if ( isset($_REQUEST['name']) ) {
    echo "<p>Welcome: ";
    echo htmlentities($_REQUEST['name']);
    echo "</p>\n";
}
?>
<br>
<div><p class="green"><?php echo $msg; ?></p><p class="red"><?php echo $errmsg; ?></p></div>
<form action="" method="post">
Make: <input type="text" name="make"><br>
Model: <input type="text" name="model"><br>
Year: <input type="text" name="year"><br>
Mileage: <input type="text" name="mileage"><br>
<input type="submit" name="submit" value="Submit">
<input type="submit" name="view" value="View">
</form>
<div>
</div>
</div>
</body>
</html>
