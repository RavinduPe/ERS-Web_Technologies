<?php

$errors = array();
$userID = $_SESSION['userid'];

$selectSQL = "SELECT admin.name, admin_details.* FROM admin_details INNER JOIN admin ON admin.email= admin_details.email WHERE admin.email = '$userID';";
$selectQuery = mysqli_query($con, $selectSQL);
$admin = mysqli_fetch_assoc($selectQuery);
$title = (isset($admin["title"]) && $admin["title"]!="") ? $admin["title"]."." : "";
$name = isset($admin["name"]) ? $admin["name"] : "";
$fullName = isset($admin["fullName"]) ? $admin["fullName"] : "";
$email = isset($admin["email"]) ? $admin["email"] : "";
$department = isset($admin["department"]) ? $admin["department"] : "";
$mobileNo = isset($admin["mobileNo"]) ? $admin["mobileNo"] : "";
$profile_img = isset($admin['profile_img']) ? $admin['profile_img'] : "blankProfile.png";

?>



<div class="w-11/12 m-auto grid grid-rows-[30%_70%] lg:grid-cols-[30%_1%_69%] ">
    <div class="profile text-center flex flex-col items-center justify-around lg:justify-center lg:h-[430px]">
        <img class="mx-auto mb-5 w-[125px] h-[125px] rounded-full ring-4 ring-offset-4" src="../assets/uploads/<?php echo $profile_img; ?>" alt="user img">
        <h3 class="font-semibold text-lg"><?php echo "$title $name"; ?></h3>
        <p class="text-sm"><?php echo $email; ?></p>
    </div>
    <div class="line hidden lg:block lg:w-px lg:h-[430px]"></div>
    <div class ="student-details mt-5 lg:w-10/12 lg:mt-0 lg:h-fit text-sm lg:text-base">
            <div class="mt-4 w-full h-full flex flex-col items-center justify-around lg:mt-0 lg:h-[430px]">
                <div class="detail-row">
                    <h5>Name:</h5>
                    <p><?php echo "$title $name"; ?></p>
                </div>
                <div class="detail-row">
                    <h5>Full Name:</h5>
                    <p><?php echo "$fullName"; ?></p>
                </div>
                <div class="detail-row">
                    <h5>Department:</h5>
                    <p><?php echo $department; ?></p>
                </div>
                <div class="detail-row">
                    <h5>Mobile:</h5>
                    <p><?php echo $mobileNo; ?></p>
                </div>
                
                <div class="w-full flex items-center justify-around">
                    <a href="index.php?page=updateProfile" class="btn fill-btn mx-auto mt-5">Update Details</a>
                    <a href="index.php?page=pwdChg" class="btn fill-btn mx-auto mt-5">Change Password</a>
                </div>
                
            </div>
    </div>

</div>

<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>


