<?php
$error = "";
$editError = "";
$editSuccess = "";
include '/home/aj4057/verify_iron.php';
include '/home/aj4057/config_iron.php'; #Connect to db.

if(isset($_POST["DESTROY_STUDENT_REAL"])) {
	$stmt = $conn->prepare("SELECT * FROM STUDENT$ WHERE ID = :id AND COACH = :coach");
	$stmt->execute(array('id' => $_POST["DESTROY_STUDENT_REAL"],
						 'coach' => $_SESSION['login_user']));
	$row = $stmt->fetch();
	if(isset($row["NAME"])) {
		$stmt = $conn->prepare("DELETE FROM STUDENT$ WHERE ID = :id AND COACH = :coach");
		$stmt->execute(array('id' => $_POST["DESTROY_STUDENT_REAL"],
							 'coach' => $_SESSION['login_user']));
		$editSuccess = 'Student "' . $row["NAME"] . '" deleted forever.';
	}
}

if(isset($_POST["DELETE"])) {
	$stmt = $conn->prepare("SELECT * FROM STUDENT$ WHERE ID = :id AND COACH = :coach");
	$stmt->execute(array('id' => $_POST["DELETE"],
						 'coach' => $_SESSION['login_user']));
	$row = $stmt->fetch();
	if(isset($row["NAME"])) {
?>
<!DOCTYPE html>
<html>
<head>
	<title>Destroy Student</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../css/style.css">
	
</head>
<body>
<div id="navbar">
	<div id="exit" style="margin: 0;">
		<a href="students.php"><div class="headlink" style="border-radius: 0 0 30px 0;"><div class="textheadlink">No, take me back!</div></div></a>
	</div>
</div>
<div class="pseudobody">
	<h1>Are you sure?</h1>
	<div class="center" style="font-size: 22px; margin-bottom: 20px;">
		<p>You are about to destroy the student "<?php echo($row["NAME"]);?>"!</p>
		<p>Are you REALLY sure you want to do this?</p>
		<form method="post">
			<input type="hidden" name="DESTROY_STUDENT_REAL" value="<?php echo($row["ID"]);?>">
			<div class="padding"><button id="yes" name="submit" type="submit" value="submit">Yes, delete <?php echo($row["NAME"]);?> forever (A long time!)</button></div>
		</form>
	</div>
</div>
</body>
</html>
<?php
		die();
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Edit Students</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>
<div id="navbar">
	<div id="exit" style="margin: 0;">
		<a href="index.php"><div class="headlink" style="border-radius: 0 0 30px 0;"><div class="textheadlink">Exit</div></div></a>
	</div>
	<?php include '/home/aj4057/require_select_iron.php';?>
</div>
<div id="body">
	<h1>Edit Students</h1>
	<?php
	if($error !== "") {echo("<span>$error</span>");}
	if($editError !== "") {echo("<span>$editError</span>");}
	if($editSuccess !== "") {echo("<p style=\"color:green; text-align: center;\">$editSuccess</p>");}
	?>
	<form method="post">
		<table>
			<style>
				button{margin-top:0}
			</style>
			<tr>
				<th>Name</th>
				<th>Student ID</th>
				<th>Bench</th>
				<th>Deadlift</th>
				<th>Backsquat</th>
				<th>Actions</th>
			</tr><tr>
				<td colspan="6">
					<a href="create.php"><div class="headlink" style="height: 52px;"><div class="textheadlink">Add Student</div></div></a>
				</td>
			</tr>
<?php
$stmt = $conn->prepare("SELECT * FROM STUDENT$ WHERE COACH = :coach AND SEMESTER = :semester AND PERIOD = :period");
$stmt->execute(array('coach' => $_SESSION['login_user'],
					 'semester' => $_SESSION["SEMESTER_GLOBAL"],
					 'period' => $_SESSION["PERIOD_GLOBAL"]));
$all = $stmt->fetchAll();
foreach($all as $row) {
?>
			<tr>
				<td><?php echo($row["NAME"]); ?></td>
				<td><?php echo($row["STUDENT_ID"]); ?></td>
				<td><?php echo($row["BASE_BENCH"]); ?></td>
				<td><?php echo($row["BASE_DEADLIFT"]); ?></td>
				<td><?php echo($row["BASE_BACKSQUAT"]); ?></td>
				<td><button name="DELETE" type="submit" value="<?php echo($row["ID"]); ?>">Delete</button></td>
			</tr>
<?php
}
?>
		</table>
	</form>
</div>
</body>