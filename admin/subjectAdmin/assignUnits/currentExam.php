<?php
$getCurrentExam = "SELECT * FROM exam_reg WHERE status = 'draft'";
$result = mysqli_query($con, $getCurrentExam);

$curExam = []; // Initialize an empty array to hold all current draft exams
if ($result && $result instanceof mysqli_result) {
    // Check if there are any rows in the result
    if ($result->num_rows > 0) {
        // Fetch all rows into the $curExam array
        while ($row = mysqli_fetch_assoc($result)) {
            $curExam[] = $row;
        }
    }
} else {
    echo "Error: Unable to fetch data.";
}
?>
