<?php

$errors = array();
$userID = $_SESSION['userid'];

$selectSQL = "SELECT admin.name, admin_details.* FROM admin_details INNER JOIN admin ON admin.email= admin_details.email WHERE admin.email = '$userID';";
$selectQuery = mysqli_query($con, $selectSQL);
$admin = mysqli_fetch_assoc($selectQuery);
$adminId = isset($admin["adminId"]) ? $admin["adminId"] : "";
$title = isset($admin["title"]) ? $admin["title"] : "";
$name = isset($admin["name"]) ? $admin["name"] : "";
$fullName = isset($admin["fullName"]) ? $admin["fullName"] : "";
$email = isset($admin["email"]) ? $admin["email"] : "";
$department = isset($admin["department"]) ? $admin["department"] : "";
$mobileNo = isset($admin["mobileNo"]) ? $admin["mobileNo"] : "";
$profile_img = isset($admin['profile_img']) ? $admin['profile_img'] : "blankProfile.png";

if (isset($_POST["submit"]))  {
    $title= $_POST["title"];
    $name= $_POST["name"];
    $fullName= $_POST["fullName"];
    $department= $_POST["department"];
    $mobileNo= $_POST["mobileNo"];
    $imageName = $profile_img;
    if(isset($_FILES["fileImg"]["name"]) and $_FILES["fileImg"]["name"] != Null){
        if($profile_img!="blankProfile.png"){
            echo unlink("../assets/uploads/".$profile_img);
        }
        $src = $_FILES["fileImg"]["tmp_name"];
        $path = $_FILES['fileImg']['name'];
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $imageName = str_replace("/","",$adminId).".".$ext;
        $target = "../assets/uploads/" . $imageName;
        move_uploaded_file($src, $target);
    }



    $sql = "UPDATE admin_details INNER JOIN admin ON admin_details.email = admin.email  
    SET admin.name = '$name', admin_details.title='$title', admin_details.fullName = '$fullName', admin_details.profile_img='$imageName', admin_details.mobileNo = $mobileNo , admin_details.department = '$department' 
    WHERE admin.email = '" . $userID . "'";

    $insertResult = mysqli_query($con, $sql);
            
    if (!$insertResult) {
        header("location: index.php?error=Something went wrong! $con->error");
    } else {
        header("Location: index.php?page=profile&success=Successfully data Updated.");
        exit;
    }

}

?>


<div class="w-11/12 m-auto ">
    <form action="index.php?page=updateProfile" method="POST" enctype="multipart/form-data" class="grid grid-rows-[30%_70%] lg:grid-cols-[30%_1%_69%] ">
        <div class="profile text-center flex flex-col items-center justify-around lg:justify-center lg:h-[430px]">
            <img class="mx-auto mb-5 w-[125px] h-[125px] rounded-full ring-4 ring-offset-4" src="../assets/uploads/<?php echo $profile_img; ?>" alt="user img">
            <input class="bg-blue-100 w-10/12 text-sm mt-5" type="file" name="fileImg" id="fileImg" accept=".jpg, .jpeg, .png">
        </div>
        <div class="line hidden lg:block lg:w-px lg:h-[430px]"></div>
        <div class ="student-details mt-5 lg:w-10/12 lg:mt-0 lg:h-fit text-sm lg:text-base">
            <div class="mt-4 w-full h-full flex flex-col items-center justify-around lg:mt-0 lg:h-[430px]">
                <div class="detail-row ">
                    <label class="hidden lg:block"  for="title">Title: <span class="text-red-500">*</span></label>
                    <select for="title" name="title" id="title" class="inputs w-full  border-2 border-gray-400 rounded-full px-5 outline-none focus:border-blue-500" >
                        <option value="" selected disabled>Select Title</option>
                        <option value="Mr" <?php echo ("Mr" == $title) ? "selected" : ""; ?>>Mr</option>
                        <option value="Mrs" <?php echo ("Mrs" == $title) ? "selected" : ""; ?>>Mrs</option>
                        <option value="Ms" <?php echo ("Ms" == $title) ? "selected" : ""; ?>>Ms</option>
                        <option value="Dr" <?php echo ("Dr" == $title) ? "selected" : ""; ?>>Dr</option>
                        <option value="Prof" <?php echo ("Prof" == $title) ? "selected" : ""; ?>>Prof</option>
                    </select>
                </div>

                <div class="detail-row">
                    <label class="hidden lg:block" for="name">Name: <span class="text-red-500">*</span></label>
                    <input class="inputs  w-full lg:w-1/2" type="text" id="name" name="name" value="<?php echo $name; ?>" required >
                </div>

                <div class="detail-row">
                    <label class="hidden lg:block" for="fullName">Full Name: <span class="text-red-500">*</span></label>
                    <input class="inputs  w-full lg:w-1/2" type="text" id="fullName" name="fullName" value="<?php echo $fullName; ?>" required>
                </div>

                <div class="detail-row">
                    <label class="hidden lg:block" for="department">Department: <span class="text-red-500">*</span></label>
                    <input class="inputs  w-full lg:w-1/2" type="text" id="department" name="department" value="<?php echo $department; ?>" required>
                </div>

                <div class="detail-row">
                    <label class="hidden lg:block" for="mobileNo">Mobile: <span class="text-red-500">*</span></label>
                    <input class="inputs  w-full lg:w-1/2" type="tel" id="mobileNo" name="mobileNo" value="<?php echo $mobileNo; ?>" required>
                </div>
                
                <div class="detail-row">
                    <input type="button" value="< Back" onclick="history.back()" class="btn outline-btn mr-3">
                    <input class="inputs w-full lg:w-1/2 btn fill-btn" type="submit"  name ="submit" value="Update" class="btn fill-btn">
                </div>
                    
            </div>    
        </div>
    </form>

</div>


