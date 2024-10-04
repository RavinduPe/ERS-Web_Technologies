<?php
ob_start();
session_start();
if (!isset($_SESSION['userid'])) {
    header("location:../index.php");
    exit();
}
elseif (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == "Admin_Master") {
        header("Location:../master");
        exit;
    } else {
        header("Location:../admin");
        exit;
    }
}

include("../config/connect.php");
$errors = array();
$regNo = $_SESSION['userid'];
$selectSQL = "SELECT * FROM student WHERE regNo = '$regNo';";
$selectQuery = mysqli_query($con, $selectSQL);
$user = mysqli_fetch_assoc($selectQuery);

$title = isset($user["title"]) ? $user["title"] : "";
$fullName = isset($user["fullName"]) ? $user["fullName"] : "";
$nameWithInitial = isset($user["nameWithInitial"]) ? $user["nameWithInitial"] : "";
$email = isset($user["email"]) ? $user["email"] : "";
// $index = isset($user["indexNumber"]) ? $user["indexNumber"] : "";
$userDistrict = isset($user["district"]) ? $user["district"] : "";
$mobileNo = isset($user["mobileNo"]) ? $user["mobileNo"] : "";
$landlineNo = isset($user["landlineNo"]) ? $user["landlineNo"] : "";
$home_address = isset($user["homeAddress"]) ? $user["homeAddress"] : "";
$jaffna_address = isset($user["addressInJaffna"]) ? $user["addressInJaffna"] : "";
$profile_img = isset($user['profile_img']) ? $user['profile_img'] : "blankProfile.png";

if (isset($_POST["submit"]))  {
    $title= $_POST["title"];
    $fname= $_POST["fname"];
    $nameWithInitial= $_POST["nameWithInitial"];
    $userDistrict= $_POST["userDistrict"];
    $mobileNo= $_POST["mobileNo"];
    $landlineNo= $_POST["landlineNo"];
    $home_address= $_POST["home_address"];
    $jaffna_address= $_POST["jaffna_address"];


    $imageName = $profile_img;
    if(isset($_FILES["fileImg"]["name"]) and $_FILES["fileImg"]["name"] != Null){
        if($profile_img!="blankProfile.png"){
            echo unlink("../assets/uploads/".$profile_img);
        }
        $src = $_FILES["fileImg"]["tmp_name"];
        $path = $_FILES['fileImg']['name'];
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $imageName = str_replace("/","",$regNo).".".$ext;
        $target = "../assets/uploads/" . $imageName;
        move_uploaded_file($src, $target);
    }

    $sql = "UPDATE student SET title = '$title', fullName = '$fname', nameWithInitial = '$nameWithInitial', district = '$userDistrict', mobileNo = '$mobileNo', landlineNo = '$landlineNo', homeAddress = '$home_address',addressInJaffna = '$jaffna_address', profile_img = '$imageName' WHERE regNo = '$regNo'";
    if ($con->query($sql) === FALSE) {
        $errors['update-error'] = "Error updating record: " . $con->error;
    }
    else{
        header("Location: index.php");
        exit;
    }

}


$districts = ['Select District', 'Colombo', 'Kandy', 'Galle', 'Ampara', 'Anuradhapura', 'Badulla', 'Batticaloa', 'Gampaha', 'Hambantota', 'Jaffna', 'Kalutara', 'Kegalle', 'Kilinochchi', 'Kurunegala', 'Mannar', 'Matale', 'Matara', 'Moneragala', 'Mullativu', 'Nuwara Eliya', 'Polonnaruwa', 'Puttalam', 'Ratnapura', 'Trincomalee', 'Vavuniya'];

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
            content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link
        rel="shortcut icon"
        href="../assets/img/logo/ERS_logo_icon.ico"
        type="image/x-icon" />
    <title>ERS | Student</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" />
    <script
    src="https://kit.fontawesome.com/5ce4b972fd.js"
    crossorigin="anonymous"></script>
</head>
<body class=" bg-gray-50 sm:text-xs xl:text-sm 2xl:text-base" id="student">
    <!-- //Navbar -->
    <nav class="w-full h-[15vh] min-h-fit drop-shadow-md bg-white fixed top-0 left-0">
        <div class="w-10/12 h-full m-auto flex items-center justify-between">
            <a href="index.php">
                <img src="../assets/img/logo/ERS_logo.gif" alt="logo" class="w-28 align-middle">
            </a>    
            <ul class="flex items-center justify-around gap-10">
                <li><a href="exam_reg.php" class="hidden btn outline-btn md:block">Exam Registration</a></li>

                <?php if (!isset($profile_img)) { ?>
                    <li onclick="openMenu()" class="py-2 px-3 bg-[var(--primary)] rounded-full drop-shadow-md cursor-pointer lh:relative">
                        <i class="fa-solid fa-user text-2xl text-[#dfeaff]"></i>
                    </li>
                <?php } else { ?>
                    <li onclick="openMenu()" class="w-10 h-10 lg:w-12 lg:h-12 rounded-full drop-shadow-md cursor-pointer ring-4">
                        <img src="../assets/uploads/<?php echo $profile_img; ?>" alt="user img" class="w-full h-full rounded-full">
                    </div>
                <?php } ?>
                
            </ul>
                       
        </div>
        <div class="hidden top-[14.8vh] right-0 h-56 w-full bg-white -translate-y-full z-20 transition-transform lg:top-[16vh] lg:drop-shadow-2xl lg:right-24 lg:w-56 lg:translate-x-full lg:h-72 lg:rounded-tl-3xl lg:rounded-br-3xl lg:text-gray-800" id="user-menu">
            <ul class="w-11/12 h-full m-auto flex flex-col items-center justify-around text-center">
                <li class="mt-3 "><a class="py-4 hover:text-blue-600 hover:font-bold hover:tracking-wide transition-all" href="exam_reg.php">Exam Registration</a></li>
                <div class="h-px w-3/4 bg-gray-300"></div>
                <li class=""><a class="py-4 hover:text-blue-600 hover:font-bold hover:tracking-wide transition-all" href="index.php">Dashboard</a></li>
                <div class="h-px w-3/4 bg-gray-300"></div>
                <li class=""><a class="py-4 hover:text-blue-600 hover:font-bold hover:tracking-wide transition-all" href="contact.php">Contact</a></li>
                <div class="h-px w-3/4 bg-gray-300"></div>
                <li class="mb-3 "><a class="py-4 hover:text-blue-600 hover:font-bold hover:tracking-wide transition-all" href="../logout.php">Logout</a></li>
            </ul>   
        </div>   
    </nav>

    <!-- Displaying Notification -->
    <?php if (isset($_GET['error'])) { ?>
        <div class="exam-false fixed inset-0 bg-black bg-opacity-30 backdrop-blur-sm flex justify-center items-center">
            <form class="card h-40 w-10/12 lg:w-1/2 flex flex-col items-center justify-around gap-7" action="index.php" method="POST">
                <p class="text-center"><?php echo $_GET['error'] ?></p>
                <input class="btn fill-btn" type="submit" value="OK" name="ok">
            </form>
        </div>
    <?php } elseif (isset($_GET['success'])) { ?>
        <div class="exam-false fixed inset-0 bg-black bg-opacity-30 backdrop-blur-sm flex justify-center items-center">
            <form class="card h-40 w-10/12 lg:w-1/2 flex flex-col items-center justify-around gap-7" action="index.php" method="POST">
                <p class="text-center text-green-700"><?php echo $_GET['success'] ?></p>
                <input class="btn fill-btn !bg-green-700" type="submit" value="OK" name="ok">
            </form>
        </div>
    <?php } ?>

    <!-- Body section -->
    <div class="body-sec my-[20vh]">
        <div class="container m-auto">
            <div class="card w-11/12 m-auto grid grid-rows-[30%_70%] lg:grid-cols-[30%_1%_69%] ">
                <div class="profile text-center flex flex-col items-center justify-around lg:justify-center lg:h-[430px]">
                <?php if (!isset($_GET['update'])) { ?>
                    <img class="mx-auto mb-5 w-[125px] h-[125px] rounded-full ring-4 ring-offset-4" src="../assets/uploads/<?php echo $profile_img; ?>" alt="user img">
                    <h3 class="font-semibold text-lg"><?php echo "$title. $nameWithInitial"; ?></h3>
                    <p class="text-sm"><?php echo $email; ?></p>
                    <h4 class="text-sm"><?php echo $regNo; ?></h4>
                    
                <?php } else { ?>
                    <form action="index.php" method="POST" enctype="multipart/form-data">
                        <img class="mx-auto mb-5 w-[125px] h-[125px] rounded-full ring-4 ring-offset-4" src="../assets/uploads/<?php echo $profile_img; ?>" alt="user img">
                        <input type="file" name="fileImg" id="fileImg" accept=".jpg, .jpeg, .png" class="w-10/12 mt-5 file:cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-[#5465ff] hover:file:bg-violet-100">
                <?php } ?>
                </div>
                <div class="line hidden lg:block lg:w-px lg:h-[430px]"></div>
                <div class ="student-details mt-5 !w-full lg:w-10/12 lg:mt-0 lg:h-fit text-sm lg:text-base">
                    <?php if (isset($_GET['update'])) { ?>
                        <div class="mt-4 w-full h-full flex flex-col items-center justify-around lg:mt-0 lg:h-[750px]">
                            <div class="detail-row my-1 !block lg:!grid">
                                <label class="hidden lg:block"  for="title">Title: <span class="text-red-500">*</span></label>
                                <select for="title" name="title" id="title" class="inputs w-full  border-2 border-gray-400 rounded-full px-5 outline-none focus:border-blue-500" >
                                    <option value="" selected disabled>Select Title</option>
                                    <option value="Mr" <?php echo ("Mr" == $title) ? "selected" : ""; ?>>Mr</option>
                                    <option value="Mrs" <?php echo ("Mrs" == $title) ? "selected" : ""; ?>>Mrs</option>
                                    <option value="Ms" <?php echo ("Ms" == $title) ? "selected" : ""; ?>>Ms</option>
                                </select>
                            </div>

                            <div class="detail-row my-1 !block lg:!grid">
                                <label class="hidden lg:block" for="fname">Full Name: <span class="text-red-500">*</span></label>
                                <input class="inputs lg:placeholder:text-transparent  w-full lg:w-1/2" type="text" id="fname" name="fname" value="<?php echo $fullName; ?>" placeholder="Full Name" required >
                            </div>

                            <div class="detail-row my-1 !block lg:!grid">
                                <label class="hidden lg:block" for="nameWithInitial">Name With Initials: <span class="text-red-500">*</span></label>
                                <input class="inputs lg:placeholder:text-transparent  w-full lg:w-1/2" type="text" id="nameWithInitial" name="nameWithInitial" value="<?php echo $nameWithInitial; ?>" placeholder="Name wih Initial" required>
                            </div>

                            <div class="detail-row my-1 !block lg:!grid">
                                <label class="hidden lg:block" for="regNo">Registration Number:</label>
                                <input class="inputs lg:placeholder:text-transparent  w-full lg:w-1/2" type="text" id="regNo" name="regNo" value="<?php echo $regNo; ?>" placeholder="Registration Number" required disabled>
                            </div>

                            <div class="detail-row my-1 !block lg:!grid">
                                <label class="hidden lg:block" for="district">District: <span class="text-red-500">*</span></label>
                                <select class="inputs lg:placeholder:text-transparent " name="userDistrict" id="district"  required>
                                    <?php foreach ($districts as $district) {?>
                                        <option value="<?php echo $district;?>" <?php if ($district == $userDistrict) { echo "selected"; }?>><?php echo $district;?></option>
                                    <?php }?>
                                </select>
                            </div>

                            <div class="detail-row my-1 !block lg:!grid">
                                <label class="hidden lg:block" for="mobileNo">Mobile: <span class="text-red-500">*</span></label>
                                <input class="inputs lg:placeholder:text-transparent  w-full lg:w-1/2" type="tel" id="mobileNo" name="mobileNo" value="<?php echo $mobileNo; ?>" placeholder="Mobile Number" required>
                            </div>

                            <div class="detail-row my-1 !block lg:!grid">
                                <label class="hidden lg:block" for="landlineNo">Landline: <span class="text-red-500">*</span></label>
                                <input class="inputs lg:placeholder:text-transparent  w-full lg:w-1/2" type="text" id="landlineNo" name="landlineNo" value="<?php echo $landlineNo; ?>" placeholder="Landline Number" required>
                            </div>

                            <div class="detail-row my-1 !block lg:!grid">
                                <label class="hidden lg:block" for="home_address">Home Address: <span class="text-red-500">*</span></label>
                                <textarea class="inputs lg:placeholder:text-transparent " id="home_address" name="home_address" rows="3" placeholder="Home Address" required><?php echo $home_address; ?></textarea>
                            </div>
                                            
                            <div class="detail-row my-1 !block lg:!grid">
                                <label class="hidden lg:block" for="home_address">Current Address: <span class="text-red-500">*</span></label>
                                <textarea class="inputs lg:placeholder:text-transparent " id="jaffna_address" name="jaffna_address" rows="3" placeholder="Cuurent Address" required><?php echo $jaffna_address; ?></textarea>
                            </div>
                                    
                            <input class="inputs w-11/12 lg:w-1/2 btn fill-btn !my-4 lg:my-0" type="submit"  name ="submit" value="Update" class="btn fill-btn">
                                
                        </div>    
                    </form>
                    <?php } else { 
                        if (isset($errors['update-error'])) { ?>
                            <p class="error-text"><?php echo $errors['update-error'] ?></p>
                        <?php } ?>
                        <div class="mt-4 w-full h-full flex flex-col items-center justify-around lg:mt-0 lg:h-[430px]">
                            <div class="detail-row">
                                <h5>Full Name:</h5>
                                <p><?php echo "$title. $fullName"; ?></p>
                            </div>
                            <div class="detail-row">
                                <h5>District:</h5>
                                <p><?php echo $userDistrict; ?></p>
                            </div>
                            <div class="detail-row">
                                <h5>Mobile:</h5>
                                <p><?php echo $mobileNo; ?></p>
                            </div>
                            <div class="detail-row">
                                <h5>Landline:</h5>
                                <p><?php echo $landlineNo; ?></p>
                            </div>
                            <div class="detail-row">
                                <h5>Home Address:</h5>
                                <p><?php echo $home_address; ?></p>
                            </div>
                            <div class="detail-row">
                                <h5>Address in Jaffna:</h5>
                                <p><?php echo $jaffna_address; ?></p>
                            </div>
                            <a href="index.php?update=true" class="btn fill-btn mx-auto mt-5">Update</a>
                            
                        </div>
                    <?php } ?>
                </div>

            </div>

            <?php if (!isset($_GET['update'])) {
                $examSQL = "SELECT ser.*, er.status AS exam_state
            FROM `stud_exam_reg` AS ser
            INNER JOIN `exam_reg` AS er ON ser.exam_id = er.exam_id
            WHERE ser.stud_regNo = '$regNo' 
            AND er.status IN ('registration', 'closed') ORDER BY ser.exam_id DESC LIMIT 7;";

                $examQuery = mysqli_query($con, $examSQL);

                $examDetailsSQL = "SELECT * FROM `exam_reg` WHERE status = 'registration';";
                $examDetails = mysqli_query($con, $examDetailsSQL);
                $exam = mysqli_fetch_assoc($examDetails);
                $exreg = (mysqli_num_rows($examDetails) != 0);

                ?>
                <div class="card mt-32 w-11/12 mx-auto flex flex-col items-center overflow-hidden">
                    <h2 class="font-extrabold text-center underline text-xl">Exam History</h2>
                    <div class="w-full mx-auto mt-8 overflow-x-hidden">
                        <div class="w-full overflow-x-scroll lg:overflow-x-visible">
                            <table class="w-full lg:w-11/12 mx-auto rounded-lg text-xs lg:text-base">
                                <thead class="bg-blue-100 h-6 lg:h-12">
                                    <th class="font-semibold px-3 w-36">Date</th>
                                    <th class="font-semibold px-3 border-gray-100 border-x-2">Type</th>
                                    <th class="font-semibold px-3 border-gray-100 border-x-2">Level</th>
                                    <th class="font-semibold px-3 border-gray-100 border-x-2">Semester</th>
                                    <th class="font-semibold px-3 border-gray-100 border-x-2 ">Subject<br>Combination</th>
                                    <th <?php if($exreg) echo "colspan=2"?> class="font-semibold">Action</th>
                                </thead>
                                <tbody class="text-center ">
                                    <?php
                                        if (mysqli_num_rows($examQuery) > 0) {
                                            while ($exam = $examQuery->fetch_assoc()) {
                                                $regId = $exam['regId'];
                                                $examID = $exam['exam_id'];
                                                $date = $exam['reg_date'];
                                                $type = strtoupper($exam['type']);
                                                $level = $exam['level'];
                                                $combID = $exam['combId'];
                                                $sem = mysqli_fetch_assoc(mysqli_query($con, "SELECT semester FROM `exam_reg` WHERE exam_id = $examID"));
                                                $semester = $sem['semester'];
                                                $comb = mysqli_fetch_assoc(mysqli_query($con, "SELECT combinationName FROM `combination` WHERE combinationID = $combID"));
                                                $combination = $comb['combinationName'];
                                                $eState = $exam['exam_state'];
                                                $btnName = ($eState=="closed")?"View":"Edit";
                                                echo "
                                                <tr class='h-12 even:bg-blue-50'>
                                                    <td class=\"\">$date</td>
                                                    <td class=\"\">$type</td>
                                                    <td class=\"\">$level</td>
                                                    <td class=\"\">$semester</td>
                                                    <td class=\"\">$combination</td>";
                                                echo
                                                        ($exreg)?"

                                                    <td>
                                                        <button onclick=\"openReg('$regId','$eState')\" class=\"py-1 px-2 border-2 border-blue-500 rounded-md bg-white text-blue-500 hover:bg-blue-500 hover:text-white transition \">
                                                        <i class=\"fa-solid fa-pencil\"></i>
                                                        </button>
                                                    </td>

                                                    <td>
                                                        <button onclick=\"openReg('$regId','delete')\" class=\"ml-2 py-1 px-2 border-2 border-red-500 rounded-md bg-white text-red-500 hover:bg-red-500 hover:text-white transition \">
                                                            <i class=\"fa-solid fa-trash-can\"></i>
                                                        </button>
                                                    </td>":"<td>
                                                        <button onclick=\"openReg('$regId','$eState')\" class=\"py-1 px-2 border-2 border-blue-500 rounded-md bg-white text-blue-500 hover:bg-blue-500 hover:text-white transition \">
                                                        <i class=\"fa-solid fa-list\"></i>
                                                        </button>
                                                    </td>";
                                                echo "</tr>
                                                ";
                                                }
                                        } else {

                                            echo "
                                                <tr class='h-10 even:bg-blue-50'>
                                                    <td colspan='7'>No record found</td>
                                                </tr>
                                            ";
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php
                    if($exreg){?>
                        <a href="exam_reg.php" class="btn outline-btn w-1/2 mt-7 text-xs lg:text-base">Register for a new Exam</a>
                    <?php }/*

                    $examDetailsSQL = "SELECT *
                        FROM `exam_reg`
                        WHERE `status` = 'closed'
                        AND (`academic_year`, `semester`) = (
                            SELECT MAX(`academic_year`), MAX(`semester`)
                            FROM `exam_reg`
                        )";
                    $examDetails = mysqli_query($con, $examDetailsSQL);
                    $exam = mysqli_fetch_assoc($examDetails);

                    if ($examDetails) {
                        if (mysqli_num_rows($examDetails) != 0) {
                            $examID = $exam['exam_id']; ?>
                            <a href='../reg_list.php?exam_id=<?php echo $examID ?>' class='btn outline-btn w-1/2 mt-7 text-xs lg:text-base'>View registered list</a>;
                        <?php }
                    } */?>

                </div>
            <?php } ?>
        </div>
    </div>

</body>
</html>

<script>
    const userMenu = document.getElementById('user-menu');

    function openMenu() {
        userMenu.classList.toggle('hidden');
        userMenu.classList.toggle('absolute');
        userMenu.classList.toggle('-translate-y-full');
        userMenu.classList.toggle('lg:translate-x-full');
    }

    function openReg(regId,eState) {
        var myform = document.createElement("form");
        if(eState==="closed")
            myform.action = "view_reg.php"
        else if(eState==="registration")
            myform.action = "exam_reg.php?edit=true";
        else if(eState==="delete")
            myform.action = "deleteReg.php";
        myform.method = "post";
        myform.style.display = "none"; // Hide the form
        var inp = document.createElement('input');
        inp.name = "regId";
        inp.value = regId;
        inp.type = "hidden";
        myform.appendChild(inp);
        document.body.appendChild(myform);
        console.log(myform);
        myform.submit();
    }

    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>
