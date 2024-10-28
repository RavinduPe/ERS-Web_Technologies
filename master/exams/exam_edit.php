<?php
ob_start();
if(!isset($_SESSION)){session_start();}
require_once ('../../config/connect.php');
$edit =false;
if(isset($_POST['exedid'])) {
    $edit =true;
    $exam_id= $_POST['exedid'];
    $cur_ex_detail ="SELECT *
        FROM exam_reg
        WHERE exam_id = $exam_id";
    $cur_ex = $con->query($cur_ex_detail)->fetch_assoc();
    $ed_year =$cur_ex ['academic_year'];
    $ed_sem =$cur_ex ['semester'];
    $status =$cur_ex ['status'];
    $closing_date =$cur_ex ['closing_date'];



    $sql_previous_year = "SELECT MAX(academic_year) as max_year, COUNT(*) as year_count
        FROM exam_reg
        WHERE academic_year = (SELECT MAX(academic_year) FROM exam_reg where academic_year < '$ed_year')";

    $fetch = $con->query($sql_previous_year)->fetch_assoc();

    $counyear = $fetch['year_count'];
    $maxPreviousYear = ($counyear == 2) ? $fetch['max_year'] + 1 : $fetch['max_year'];
    $year = $ed_year;

}
else if( isset($_GET['page']) AND ($_GET['page'] == "add")){
        $sql_previous_year = "SELECT MAX(academic_year) as max_year, COUNT(*) as year_count
        FROM exam_reg
        WHERE academic_year = (SELECT MAX(academic_year) FROM exam_reg);";
    $fetch = $con->query($sql_previous_year)->fetch_assoc();
    $counyear = $fetch['year_count'];
    $maxPreviousYear = ($counyear == 2) ? $fetch['max_year'] + 1 : $fetch['max_year'];
    $year = $maxPreviousYear;
}
else{
    header("Location:index.php");
}
?>


<form action="index.php" method="POST" onsubmit="return validateForm()" class="w-[500px] mx-auto flex flex-col items-center gap-4">
    <?php echo ($edit == false) ? "<h1 class='title mb-5'>Add Exam</h1>" : "<h1 class='title mb-5'>Edit Exam</h1>";?>

    <?php if($edit) echo "<input type='hidden' name='exam_id' value='$exam_id'>";?>

    <div class="w-full grid grid-cols-3 items-center h-10">
        <label for="academic_year">Academic Year:</label>
        <input type="number" name="academic_year" id="academic_year" min="<?php echo $maxPreviousYear?>" max="2099" step="1" value="<?php echo $year?>" <?php if($edit) echo "disabled"?>  class="col-span-2 w-full h-full border-2 border-gray-400 rounded-full px-5 outline-none focus:border-blue-500" required>
    </div>

    <input type="hidden" id="max_previous_year" value="<?php echo $maxPreviousYear; ?>">

    <div class="w-full grid grid-cols-3 items-center h-10">
        <label for="semester">Semester:</label>
        <select name="semester" required <?php if($edit) echo "disabled"?> id="semester" class="col-span-2 w-full h-full border-2 border-gray-400 rounded-full px-5 outline-none focus:border-blue-500">
            <option value="1">1</option>
            <option value="2" <?php if(($edit and $ed_sem ==2) or ($counyear == 1)) echo "selected"?>>2</option>
        </select>
    </div>
    <div class="w-full grid grid-cols-3 items-center h-10">
        <label for="status">Status:</label>
        <select name="status" id="status" class="col-span-2 w-full h-full border-2 border-gray-400 rounded-full px-5 outline-none focus:border-blue-500" required>
            <option value="draft">Draft</option>
            <option value="registration" <?php if($edit and $status =="registration") echo "selected"?>>Registration</option>
            <option value="closed" <?php if($edit and $status =="closed") echo "selected"?>>Closed</option>
            <option value="hidden" <?php if($edit and $status =="hidden") echo "selected"?>>Hidden</option>
        </select>
    </div>
    <div class="w-full grid grid-cols-3 items-center h-10">
        <label for="close_date">closing date:</label>
        <input name="close_date" id="close_date" type="date" min="<?php echo ($edit)?"$closing_date":date('Y-m-d'); ?>" <?php if($edit) echo "value='$closing_date'"?> class="col-span-2 w-full h-full border-2 border-gray-400 rounded-full px-5 outline-none focus:border-blue-500" required>
    </div>

    <div class="w-full grid grid-cols-3 items-center h-10 gap-5 mt-5 mb-10">
        <input type="button" value="< Back" onclick="history.back()" class="btn outline-btn">
        <input type="submit" name="<?php echo ($edit)? 'ed_exm':'add_exm';?>" value="<?php echo ($edit)? 'Save':'add_exm';?>" class="col-span-2 w-full btn fill-btn" required>
    </div>
</form>

<?php if($edit){ ?>

<!-- New section for managing allowed students -->
<div class="w-[500px] mx-auto mt-5">
    <h1 class="title mb-2">Manage Allowed Students</h1>
    <div id="dropdowns" class="grid grid-cols-3 gap-5 mt-5 mb-10">
        <div>
            <label for="level">Level:</label>
            <select name="level" id="level"
                    class="w-full border-2 h-8 border-gray-400 rounded-full px-5 outline-none focus-border-blue-500">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select>
        </div>
        <div>
            <label for="type">Type:</label>
            <select name="type" id="type"
                    class="w-full border-2 h-8 border-gray-400 rounded-full px-5 outline-none focus-border-blue-500">
                <option value="proper">Proper</option>
                <option value="repeat">Repeat</option>
            </select>
        </div>
        <button id="manageButton" class="btn fill-btn mt-2" onclick="manageStudents()">Manage</button>
    </div>
    <div id="studentsSection" style="display: none;">
        <div class="grid grid-cols-3 gap-5 mt-5 mb-10">
            <input type="text" id="studentInput"
                   class="w-full col-span-2 border-2 border-gray-400 rounded-full px-5 outline-none focus-border-blue-500 mt-2"
                   placeholder="Enter student registration number">
            <button id="addButton" class="col-span-1 btn fill-btn mt-2" onclick="addStudent()">Add</button>
        </div>
        <table id="studentTable" class="w-full text-center">
            <thead>
                <tr class="h-12 bg-blue-100 font-semibold">
                    <th>Registration Number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Students will be displayed here -->
            </tbody>
        </table>
    </div>
</div>

<?php } ?>

<script>
    function validateForm() {
        var academicYearInput = document.getElementById("academic_year");
        var academicYear = parseInt(academicYearInput.value);

        var maxPreviousYear = parseInt(document.getElementById("max_previous_year").value);

        var closeDateInput = document.getElementsByName("close_date")[0];
        var closeDate = new Date(closeDateInput.value);

        var today = closeDate.min;
        console.log(today);

        if (isNaN(academicYear) || academicYear < maxPreviousYear) {
            alert("Please enter a valid academic year greater than or equal to the previous year.");
            academicYearInput.focus();
            return false;
        }

        if (closeDate <= today) {
            alert("Closing date must be greater than today's date.");
            closeDateInput.focus();
            return false;
        }
    }
    <?php if($edit){ ?>
    function manageStudents() {
        var dropdowns = document.getElementById('dropdowns');
        var manageButton = document.getElementById('manageButton');
        var studentsSection = document.getElementById('studentsSection');
        var levelDropdown = document.getElementById('level');
        var typeDropdown = document.getElementById('type');

        if (studentsSection.style.display === 'none') {
            getStudents();
            dropdowns.style.display = 'grid';
            levelDropdown.disabled = true;
            typeDropdown.disabled = true;
            manageButton.textContent = 'Change';
            studentsSection.style.display = 'block';
        } else {
            dropdowns.style.display = 'grid';
            levelDropdown.disabled = false;
            typeDropdown.disabled = false;
            manageButton.textContent = 'Manage';
            studentsSection.style.display = 'none';
        }
    }

    // Define the getStudents function to fetch student registrations
    function getStudents() {
        const examId = <?php echo $exam_id; ?>;
        var level = document.getElementById('level').value;
        var type = document.getElementById('type').value;

        var loadtbody = document.getElementById("studentTable").getElementsByTagName('tbody')[0];
        loadtbody.innerHTML = '';

        var loadingRecordsRow = loadtbody.insertRow(0);
        loadingRecordsRow.className = 'h-12 odd:bg-blue-50'; // Add the class to the row
        var loadingRecordsCell = loadingRecordsRow.insertCell(0);
        loadingRecordsCell.colSpan = 2; // Update the colspan to include the "Actions" column
        loadingRecordsCell.textContent = "Loading...";

        // Make an AJAX request to get student registration numbers
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "get_students.php?exam_id=" + examId + "&level=" + level + "&type=" + type, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Parse the JSON response and update the table
                var students = JSON.parse(xhr.responseText);
                var tbody = document.getElementById("studentTable").getElementsByTagName('tbody')[0];
                tbody.innerHTML = '';

                if (students.length === 0) {
                    var noRecordsRow = tbody.insertRow(0);
                    noRecordsRow.className = 'h-12 odd:bg-blue-50'; // Add the class to the row
                    var noRecordsCell = noRecordsRow.insertCell(0);
                    noRecordsCell.colSpan = 2; // Update the colspan to include the "Actions" column
                    noRecordsCell.textContent = "No records";
                } else {
                    for (let i = 0; i < students.length; i++) {
                        var row = tbody.insertRow(i);
                        row.className = 'h-12 odd:bg-blue-50'; // Add the class to the row
                        var cell = row.insertCell(0);
                        cell.innerHTML = students[i].regNo;
                        // Add a "Remove" button for each student
                        var removeCell = row.insertCell(1); // Create a new cell for the "Remove" button

                        var removeButton = document.createElement("button");
                        removeButton.className = "ml-2 py-1 px-2 border-2 border-red-500 rounded-md bg-white text-red-500 hover:bg-red-500 hover:text-white transition";
                        removeButton.innerHTML = "<i class=\"fa-solid fa-trash-can\"></i>";

                        removeButton.onclick = function () {
                            // Handle the removal of the student when the "Remove" button is clicked
                            removeStudent(students[i].regNo);
                        };

                        removeCell.appendChild(removeButton);

                    }
                }
            }
        };
        xhr.send();
    }


    // Define the addStudent function to add a student to the database
    function addStudent() {
        var studentInput = document.getElementById("studentInput");
        var registrationNumber = studentInput.value.trim();
        var registrationPattern = /^\d{4}\/[A-Za-z]+\/\d{3}$/;

        if (!registrationPattern.test(registrationNumber)) {
            alert("Please enter a valid registration number matching the pattern: 'XXX/XX/XXX'.");
            return;
        }
        registrationNumber = registrationNumber.toUpperCase()

        // Rest of your code to add the student
        var examId = <?php echo $exam_id; ?>;
        var level = document.getElementById('level').value;
        var type = document.getElementById('type').value;

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "add_student.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                if (xhr.responseText === "Success") {
                    studentInput.value = "";
                    getStudents();
                } else if (xhr.responseText === "AlreadyAdded") {
                    alert("Student is already added.");
                } else {
                    alert("Error adding student: " + xhr.responseText);
                }
            }
        };

        xhr.send("exam_id=" + examId + "&level=" + level + "&type=" + type + "&regNo=" + registrationNumber);
    }




    function removeStudent(registrationNumber) {
        var examId = <?php echo $exam_id; ?>;
        var level = document.getElementById('level').value;
        var type = document.getElementById('type').value;

        // Make an AJAX request to remove the student registration
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "remove_student.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Handle the response (e.g., refresh the student list)
                if (xhr.responseText === "Success") {
                    // If the removal was successful, refresh the student list
                    getStudents();
                } else {
                    alert("Error removing student.");
                }
            }
        };

        // Send the data to the server
        xhr.send("exam_id=" + examId + "&level=" + level + "&type=" + type + "&regNo=" + registrationNumber);
    }

    // Update the table when the page loads
    document.addEventListener("DOMContentLoaded", getStudents);

    <?php } ?>
</script>
