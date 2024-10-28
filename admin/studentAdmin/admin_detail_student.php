<?php
if (isset($_POST['regNo'])) {
    $regNo = $_POST['regNo'];

    $indexQuery = "";
    if (isset($exam)) {
        $examID = $exam['exam_id'];
        $indexSelect=", exam_stud_index.indexNo ";
        $indexQuery = " LEFT JOIN `exam_stud_index` ON exam_stud_index.regNo = student.regNo AND `exam_id` = $examID";

    }

    $query = "SELECT student.*, student_check.*".$indexSelect." FROM student INNER JOIN student_check ON student.regNo = student_check.regNo " . $indexQuery . " WHERE student.regNo = '" . $regNo . "'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row['indexNo'] != "") {
        $indexNo = $row['indexNo'];
    } else {
        $indexNo = null;
    }

}

?>


<div class="w-[600px] mx-auto flex flex-col items-center gap-4">
    <h1 class="title">View Student Profile</h1>

    <div>
        <div class="w-full grid grid-cols-3 items-center h-10">
            <h4>Registration No:</h4>
            <p class="text-gray-600"> <?php echo $row['regNo']; ?> </p>
        </div>
        <div class="w-full grid grid-cols-3 items-center h-10">
            <h4>Index No:</h4>
            <p class="text-gray-600"> <?php echo ($indexNo) ? $indexNo : "---"; ?> </p>
        </div>
        <div class="w-full grid grid-cols-3 items-center h-10">
            <h4>Status:</h4>
            <p class="text-gray-600"> <?php echo $row['status']; ?> </p>
        </div>
        <div class="w-full grid grid-cols-3 items-center h-10">
            <h4>Email:</h4>
            <p class="text-gray-600"> <?php echo $row['email']; ?> </p>
        </div>
        <div class="w-full grid grid-cols-3 items-center h-10">
            <h4>Full Name:</h4>
            <p class="text-gray-600"> <?php echo $row['fullName']; ?> </p>
        </div>
        <div class="w-full grid grid-cols-3 items-center h-10">
            <h4>Name with Initials:</h4>
            <p class="text-gray-600"> <?php echo $row['nameWithInitial']; ?> </p>
        </div>
        <div class="w-full grid grid-cols-3 items-center h-10">
            <h4>District:</h4>
            <p class="text-gray-600"> <?php echo $row['district']; ?> </p>
        </div>
        <div class="w-full grid grid-cols-3 items-center h-10">
            <h4>Mobile No:</h4>
            <p class="text-gray-600"> <?php echo $row['mobileNo']; ?> </p>
        </div>
        <div class="w-full grid grid-cols-3 items-center h-10">
            <h4>Home Tp No:</h4>
            <p class="text-gray-600"> <?php echo $row['landlineNo']; ?> </p>
        </div>
        <div class="w-full grid grid-cols-3 items-center h-10">
            <h4>Home Address:</h4>
            <p class="text-gray-600"> <?php echo $row['homeAddress']; ?> </p>
        </div>
        <div class="w-full grid grid-cols-3 items-center h-10">
            <h4>Address in Jaffna:</h4>
            <p class="text-gray-600"> <?php echo $row['addressInJaffna']; ?> </p>
        </div>

    </div>

    <div class="w-full grid grid-cols-3 items-center h-10 gap-5 mt-5 mb-10">
        <a href="index.php?page=stud" class="btn outline-btn">< Back</a>
        <button onclick="edit('<?php echo $row['regNo']; ?>')" class="col-span-2 w-full btn fill-btn">Edit</button>
    </div>
</div>


<script>
    function edit(regNo) {
        var myform = document.createElement("form");
        myform.action = "index.php?page=editStud";
        myform.method = "post";
        myform.style.display = "none"; // Hide the form
        var inp = document.createElement('input');
        inp.name = "regNo";
        inp.value = regNo;
        inp.type = "hidden";
        myform.appendChild(inp);
        document.body.appendChild(myform);
        console.log(myform);
        myform.submit()
    }
</script>

