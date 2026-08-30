<!DOCTYPE html>
<html>
<head>
    <title>Report Generation System</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>
<body>
    <h2>Generate Student Report</h2>

    <!-- Report Form -->
    <form action="" method="POST">
        <label for="studentId">Student ID:</label>
        <input type="number" id="studentId" name="studentId" required><br><br>

        <label for="reportType">Report Type:</label>
        <select id="reportType" name="reportType" required>
            <option value="attendance">Attendance Report</option>
            <option value="exam_results">Exam Results</option>
            <option value="fees">Fees Report</option>
            <option value="full">Full Academic Report</option>
        </select><br><br>

        <button type="submit">Generate Report</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $conn = new mysqli("localhost", "root", "", "school_management_system");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $studentId  = $_POST['studentId'];
        $reportType = $_POST['reportType'];

        // Validate student exists
        $studentCheck = $conn->query("SELECT FullName FROM students WHERE StudentID='$studentId'");
        if ($studentCheck->num_rows == 0) {
            echo "<h3>No student found with ID: $studentId</h3>";
            exit;
        }
        $studentName = $studentCheck->fetch_assoc()['FullName'];

        echo "<h3>Generated Report</h3>";
        echo "<p><strong>Student:</strong> $studentName</p>";
        echo "<p><strong>Report Type:</strong> $reportType</p>";

        switch ($reportType) {
            case "attendance":
                $sql = "SELECT Date, Status FROM attendance WHERE StudentID='$studentId'";
                $result = $conn->query($sql);
                echo "<h4>Attendance Report</h4>";
                if ($result->num_rows > 0) {
                    echo "<table><tr><th>Date</th><th>Status</th></tr>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr><td>{$row['Date']}</td><td>{$row['Status']}</td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>No attendance records found.</p>";
                }
                break;

            case "exam_results":
                $sql = "SELECT subjects.SubjectName, users.FullName AS TeacherName,
                               results.Marks, results.Grade
                        FROM results
                        JOIN exams ON results.ExamID = exams.ExamID
                        JOIN subjects ON exams.SubjectID = subjects.SubjectID
                        JOIN users ON subjects.TeacherID = users.UserID
                        WHERE results.StudentID='$studentId'";
                $result = $conn->query($sql);
                echo "<h4>Exam Results</h4>";
                if ($result->num_rows > 0) {
                    echo "<table><tr><th>Subject</th><th>Teacher</th><th>Marks</th><th>Grade</th></tr>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr><td>{$row['SubjectName']}</td><td>{$row['TeacherName']}</td><td>{$row['Marks']}</td><td>{$row['Grade']}</td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>No exam results found.</p>";
                }
                break;

            case "fees":
                $sql = "SELECT Amount, Status, Date FROM fees WHERE StudentID='$studentId'";
                $result = $conn->query($sql);
                echo "<h4>Fees Report</h4>";
                if ($result->num_rows > 0) {
                    echo "<table><tr><th>Amount</th><th>Status</th><th>Date</th></tr>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr><td>{$row['Amount']}</td><td>{$row['Status']}</td><td>{$row['Date']}</td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>No fees records found.</p>";
                }
                break;

            case "full":
                echo "<h4>Full Academic Report</h4>";

                // Attendance
                $sql = "SELECT Date, Status FROM attendance WHERE StudentID='$studentId'";
                $result = $conn->query($sql);
                echo "<h5>Attendance</h5>";
                if ($result->num_rows > 0) {
                    echo "<table><tr><th>Date</th><th>Status</th></tr>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr><td>{$row['Date']}</td><td>{$row['Status']}</td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>No attendance records.</p>";
                }

                // Exam Results
                $sql = "SELECT subjects.SubjectName, users.FullName AS TeacherName,
                               results.Marks, results.Grade
                        FROM results
                        JOIN exams ON results.ExamID = exams.ExamID
                        JOIN subjects ON exams.SubjectID = subjects.SubjectID
                        JOIN users ON subjects.TeacherID = users.UserID
                        WHERE results.StudentID='$studentId'";
                $result = $conn->query($sql);
                echo "<h5>Exam Results</h5>";
                if ($result->num_rows > 0) {
                    echo "<table><tr><th>Subject</th><th>Teacher</th><th>Marks</th><th>Grade</th></tr>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr><td>{$row['SubjectName']}</td><td>{$row['TeacherName']}</td><td>{$row['Marks']}</td><td>{$row['Grade']}</td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>No exam results.</p>";
                }

                // Fees
                $sql = "SELECT Amount, Status, Date FROM fees WHERE StudentID='$studentId'";
                $result = $conn->query($sql);
                echo "<h5>Fees</h5>";
                if ($result->num_rows > 0) {
                    echo "<table><tr><th>Amount</th><th>Status</th><th>Date</th></tr>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr><td>{$row['Amount']}</td><td>{$row['Status']}</td><td>{$row['Date']}</td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>No fees records.</p>";
                }
                break;
        }

        $conn->close();
    }
    ?>
</body>
</html>
