<?php
session_start();

if ( $_SESSION[ "umail" ] == "" || $_SESSION[ "umail" ] == NULL ) {
	header( 'Location:AdminLogin.php' );
}

$userid = $_SESSION[ "umail" ];
?>
<?php include('adminhead.php'); ?>

<div class="container">
	<div class="row">
		<div class="col-md-4"></div>

		<div class="col-md-4">
			<h3 class="page-header">Welcome <a href="welcomeadmin">Admin</a> </h3>
			<h4 class="page-header">Add New Faculty </h4>
			<?php
			include( "database.php" );
			?>
			<form action="" method="POST" name="update">


				<div class="form-group">
					<label for="Faculty Name">Faculty Name : <span style="color: #ff0000;">*</span></label>
					<input type="text" name="fname" class="form-control" id="fname" required>
				</div>

				<div class="form-group">
					<label for="Father Name">Father Name :<span style="color: #ff0000;">*</span></label>
					<input type="text" class="form-control" id="faname" name="faname" required>
				</div>

				<div class="form-group">
					<label for="Address">Address : <span style="color: #ff0000;">*</span></label>
					<input type="text" class="form-control" name="addrs" required id="addrs">
				</div>

				<div class="form-group">
					<label for="Gender">Gender : &nbsp;</label>
					<input type="radio" name="gender" value="Male" id="Gender_0" checked> Male
					<input type="radio" name="gender" value="Female" id="Gender_1"> Female
				</div>

				<div class="form-group">
					<label for="PhoneNumber">Contact : <span style="color: #ff0000;">*</span></label>
					<input type="text" class="form-control" id="phno" name="phno" maxlength="10" required>
				</div>

				<div class="form-group">
					<label for="Joining Date">Joining Date : <span style="color: #ff0000;">*</span></label>
					<input type="date" class="form-control" id="jdate" name="jdate" placeholder="YYYY-MM-DD" required>
				</div>

				<div class="form-group">
					<label for="City">City : <span style="color: #ff0000;">*</span></label>
					<input type="text" class="form-control" id="city" name="city" required>
				</div>

				<div class="form-group">
					<label for="Password">Password :<span style="color: #ff0000;">*</span></label>
					<input type="password" class="form-control" name="pass" required id="pass">
				</div>

				<div class="form-group">
					<input type="submit" value="Add New Faculty" name="addnewfaculty" style="border-radius:0%" class="btn btn-success">
				</div>

			</form>
			<?php
			//}
			?>

			<?php
			if ( isset( $_POST[ 'addnewfaculty' ] ) ) {
				$tempfname = mysqli_real_escape_string($connect, $_POST['fname']);
                $tempfaname = mysqli_real_escape_string($connect, $_POST['faname']);
                $tempaddrs = mysqli_real_escape_string($connect, $_POST['addrs']);
                $tempgender = mysqli_real_escape_string($connect, $_POST['gender']);
                $tempphno = mysqli_real_escape_string($connect, $_POST['phno']);
                $tempjdate = mysqli_real_escape_string($connect, $_POST['jdate']);
                $tempcity = mysqli_real_escape_string($connect, $_POST['city']);
				$temppass = hash('sha256', $_POST['pass']);
				// adding new faculty
				$stmt = $connect->prepare("INSERT INTO facutlytable (FName, FaName, Addrs, Gender, JDate, City, Pass, PhNo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssss", $tempfname, $tempfaname, $tempaddrs, $tempgender, $tempjdate, $tempcity, $temppass, $tempphno);

				if ($stmt->execute()) {

					echo "<br>
					<br><br>
					<div class='alert alert-success fade in'>
					<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
					<strong>Success!</strong> New Faculty Addded Faculty ID is : <strong>" . mysqli_insert_id( $connect ) . "</strong></div>";

				} else {
					//error message if SQL query Fails
					echo "<br><Strong>New Faculty Adding Faliure. Try Again</strong><br> Error Details: " . $sql . "<br>" . mysqli_error( $connect );
				}
				
				mysqli_close( $connect );

			}


			?>
		</div>

		<div class="col-md-4"></div>
	</div>

	<?php include('allfoot.php'); ?>