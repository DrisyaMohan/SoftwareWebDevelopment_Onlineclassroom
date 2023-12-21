<?php
session_start();

if ($_SESSION["fidx"] == "" || $_SESSION["fidx"] == NULL) {
    header('Location:facultylogin');
}

$userid = $_SESSION["fidx"];
$fname = $_SESSION["fname"];
?>
<?php include('fhead.php');  ?>

<html>

<head>
    <!-- Include the JavaScript code for session timeout -->
    <script>
        var timeoutInMinutes = 3;

        function startSessionTimer() {
            setTimeout(function () {
                window.location.href = 'logoutfaculty.php';
            }, timeoutInMinutes * 60 * 1000);
        }

        document.addEventListener('mousemove', function () {
            clearTimeout(sessionTimer);
            startSessionTimer();
        });

        var sessionTimer = startSessionTimer();
    </script>
</head>

<body>
    
<div class="container">
    <div class="row">
        <div class="col-md-8">

            <h3> Welcome Faculty : <a href="welcomefaculty.php"><span style="color:#FF0004"> <?php echo $fname; ?></span></a>
            </h3>
            <?php
            include("database.php");
            if (isset($_POST['submit'])) {
                // Sanitize input
                $Aname = mysqli_real_escape_string($connect, $_POST['AssessmentName']);
                $q1 = mysqli_real_escape_string($connect, $_POST['Q1']);
                $q2 = mysqli_real_escape_string($connect, $_POST['Q2']);
                $q3 = mysqli_real_escape_string($connect, $_POST['Q3']);
                $q4 = mysqli_real_escape_string($connect, $_POST['Q4']);
                $q5 = mysqli_real_escape_string($connect, $_POST['Q5']);

                $done = "
                    <center>
                    <div class='alert alert-success fade in __web-inspector-hide-shortcut__'' style='margin-top:10px;'>
                    <a href='#' class='close' data-dismiss='alert' aria-label='close' title='close'>&times;</a>
                    <strong><h3 style='margin-top: 10px;
                    margin-bottom: 10px;'> Assessment added Successfully.</h3>
                    </strong>
                    </div>
                    </center>
                    ";

                
                $stmt = $connect->prepare("INSERT INTO `ExamDetails` (`ExamName`, `Q1`, `Q2`, `Q3`, `Q4`, `Q5`) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $Aname, $q1, $q2, $q3, $q4, $q5);
                $stmt->execute();
                $stmt->close();

                echo $done;
            }

            ?>

            <fieldset>
                <legend><a href="addassessment.php">Add Assessment</a></legend>
                <form action="" method="POST" name="AddAssessment">
                    <table class="table table-hover">

                        <tr>
                            <td><strong>Assessment Name </strong>
                            </td>
                            <td>
                                <input type="text" name="AssessmentName">
                            </td>

                        </tr>
                        <tr>
                            <td><strong>Question 1</strong> </td>
                            <td>
                                <textarea name="Q1" rows="5" cols="150"></textarea>
                            </td>
                        </tr>
                        <!-- ... (Repeat for other questions) ... -->
                        <td><button type="submit" name="submit" class="btn btn-success" style="border-radius:0%">Add Assessment</button>
                        </td>

                    </table>
                </form>
            </fieldset>
        </div>
    </div>
    <?php include('allfoot.php');  ?>
