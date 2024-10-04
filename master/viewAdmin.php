<?php
if (isset($_POST['adminId'])) {
    $adminId = $_POST['adminId'];
    $query = "SELECT *
FROM `admin` 
    LEFT JOIN `admin_details` ON `admin_details`.`email` = `admin`.`email` WHERE `admin`.`email` = '" . $adminId . "'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
}

?>


<div class="w-[500px] mx-auto flex flex-col items-center gap-4">
    <h1 class="title">Admin Profile</h1>

    <div class="">
        <div class="w-full grid grid-cols-3 items-center h-10">
            <h4>Name:</h4>
            <p class="text-gray-600"> <?php echo $row['name']; ?> </p>
        </div>
        <div class="w-full grid grid-cols-3 items-center h-10">
            <h4>Email:</h4>
            <p class="text-gray-600"> <?php echo $row['email']; ?> </p>
        </div>
        <div class="w-full grid grid-cols-3 items-center h-10">
            <h4>Role:</h4>
            <p class="text-gray-600"> <?php echo $row['role']; ?> </p>
        </div>
        <div class="w-full grid grid-cols-3 items-center h-10">
            <h4>department:</h4>
            <p class="text-gray-600"> <?php echo $row['department']; ?> </p>
        </div>
        <div class="w-full grid grid-cols-3 items-center h-10">
            <h4>status:</h4>
           <p class="text-gray-600"> <?php echo $row['status']; ?> </p>
        </div>
        <div class="w-full grid grid-cols-3 items-center h-10">
            <h4>fullName:</h4>
            <p class="text-gray-600"> <?php echo $row['fullName']; ?> </p>
        </div>
        <div class="w-full grid grid-cols-3 items-center h-10">
            <h4>Mobile No:</h4>
            <p class="text-gray-600"> <?php echo $row['mobileNo']; ?> </p>
        </div>
    </div>

    <div class="w-full grid grid-cols-3 items-center h-10 gap-5 mt-5 mb-10">
        <a href="index.php?page=listAdmins" class="btn outline-btn">< Back</a>
        <button onclick="edit('<?php echo $row['email']; ?>')" class="col-span-2 w-full btn fill-btn">Edit</button>
    </div>
</div>


<script>
    function edit(editAdminId) {
        var myform = document.createElement("form");
        myform.action = "index.php?page=editAdmin";
        myform.method = "post";
        myform.style.display = "none"; // Hide the form
        var inp = document.createElement('input');
        inp.name = "editAdminId";
        inp.value = editAdminId;
        inp.type = "hidden";
        myform.appendChild(inp);
        document.body.appendChild(myform);
        console.log(myform);
        myform.submit()
    }
</script>

