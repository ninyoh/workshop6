<?php
require_once "pdo.php";
session_start();

if ( isset($_POST['delete']) && isset($_POST['make']) ) {
    $sql = "DELETE FROM autos WHERE make = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_POST['make']));
    $_SESSION['success'] = 'Record deleted';
    header( 'Location: view.php' ) ;
    return;
}

// Guardian: Make sure that user_id is present
if ( ! isset($_GET['make']) ) {
  $_SESSION['error'] = "Missing user_id";
  header('Location: view.php');
  return;
}

$stmt = $pdo->prepare("SELECT make, model FROM autos where make = :xyz");
$stmt->execute(array(":xyz" => $_GET['make']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for user_id';
    header( 'Location:view.php' ) ;
    return;
}

?>
<p>Confirm: Deleting <?= htmlentities($row['make']) ?></p>

<form method="post">
<input type="hidden" name="make" value="<?= $row['make'] ?>">
<input type="submit" value="Delete" name="delete">
<a href="view.php">Cancel</a>
</form>
