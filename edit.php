<?php
require_once "pdo.php";
session_start();

if ( isset($_POST['model']) && isset($_POST['year'])
     && isset($_POST['mileage']) && isset($_POST['make']) ) {

    // Data validation
    if ( strlen($_POST['model']) < 1 || strlen($_POST['mileage']) < 1) {
        $_SESSION['error'] = 'Missing data';
        header("Location: edit.php?make=".$_POST['make']);
        return;
    }

    
    $sql = "UPDATE autos SET make = :make,
            model = :model,
            year = :year, mileage = :mileage
            WHERE make = :make";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':model' => $_POST['model'],
        ':year' => $_POST['year'],
        ':mileage' => $_POST['mileage'],
        ':make' => $_POST['make']));
    $_SESSION['success'] = 'Record updated';
    header( 'Location: view.php' ) ;
    return;
}

// Guardian: Make sure that user_id is present
if ( ! isset($_GET['make']) ) {
  $_SESSION['error'] = "Missing user_id";
  header('Location: view.php');
  return;
}

$stmt = $pdo->prepare("SELECT * FROM autos where make = :xyz");
$stmt->execute(array(":xyz" => $_GET['make']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for user_id';
    header( 'Location: view.php' ) ;
    return;
}

// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
$m = htmlentities($row['make']);
$n = htmlentities($row['model']);
$e = htmlentities($row['year']);
$p = htmlentities($row['mileage']);
$make = $row['make'];
?>
<p>Edit Info</p>
<form method="post">
    <p>Make:
<input type="text" name="model" value="<?= $m ?>"></p>
<p>Model:
<input type="text" name="model" value="<?= $n ?>"></p>
<p>Year:
<input type="text" name="year" value="<?= $e ?>"></p>
<p>Mileage:
<input type="text" name="mileage" value="<?= $p ?>"></p>

<input type="hidden" name="make" value="<?= $make ?>">
<p><input type="submit" value="Update"/>
<a href="index.php">Cancel</a></p>
</form>
