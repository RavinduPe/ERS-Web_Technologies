<?php

require($_SERVER['DOCUMENT_ROOT'] . '/ERS-Web_Technologies/vendor/autoload.php'); // Include PHPMailer autoloader

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$examID = $exam['exam_id'];

if (isset($_POST['regID'])) {
    $regID = $_POST['regID'];
    $regNo = $_POST['regNo'];
    $action = (isset($_POST['accept'])) ? "accepted" : "rejected";

    $sql = "UPDATE `repeat_slips` SET `senate_approval_letter_status`='$action' WHERE `regId`='$regID'";
    $result = mysqli_query($con, $sql);

    if (!$result) {
        header("Location: index.php?page=senate&error=Something went wrong!");
    } else {
        if ($action === 'rejected') {
            $sql = "SELECT `email` FROM `student_check` WHERE `regNo`='$regNo'";
            $result = mysqli_query($con, $sql);

            if (mysqli_num_rows($result) == 1) {
                $mail = new PHPMailer(true);
                $student = mysqli_fetch_assoc($result);
                $email = $student['email'];
    
                if ($student) {
                    $subject = "ERS - Repeat Senate approval letter Rejected";
                    $message = "Your Senate approval letter for the repeat exam/s have been Rejected. Please contact the dean office for further details.";
                    $sender_name = "Exam Registration System | Faculty of Science";
                    $sender_mail = "ers.fos.csc@gmail.com";
                    $htmlBody = '
                        <!DOCTYPE html>
                        <html>
                        <head>
                            <meta charset="UTF-8">
                            <title>OTP Email</title>
                        </head>
                        <body style="font-family: Arial, sans-serif; background-color: #f0f0f0; padding: 20px;">
                            <div style="background-color: #ffffff; border-radius: 10px; padding: 20px; max-width: 400px; margin: 0 auto;">
                                <h2 style="color: #333; text-align: center;">Exam Registration System</h2>
                                <p>Your Senate approval letter for the repeate exam/s have been Rejected. Please contact the dean office for further details.</p>
                                <p>Thank you!</p>
                            </div>
                        </body>
                        </html>
                        ';
        
                    try {
                        // SMTP configuration
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'ers.fos.csc@gmail.com';
                        $mail->Password = 'izvixydstkhxvpsf';
                        $mail->SMTPSecure = 'tls'; // Use TLS
                        $mail->Port = 587;
        
                        // Recipients and content
                        $mail->setFrom($sender_mail, $sender_name);
                        $mail->addAddress($email, $regNo);
                        $mail->Subject = $subject;
                        //$mail->Body = $message;
                        $mail->msgHTML($htmlBody);
        
                        // Send email
                        $mail->send();
                    } catch (Exception $e) {
                        header("Location: index.php?page=senate&error=Failed while sending code!");
                    }
                } else {
                    header("Location: index.php?page=senate&error=Failed while inserting data into database!");
                }
            }
        }
    }
}

$current_page = isset($_GET['no']) ? intval($_GET['no']) : 1;
$records_per_page = 10;
$offset = ($current_page - 1) * $records_per_page;
$order =" ORDER BY CAST(SUBSTRING_INDEX(s.regNo, '/', 1) AS UNSIGNED) DESC,
  SUBSTRING_INDEX(SUBSTRING_INDEX(s.regNo, '/', 2), '/', -1),
  CAST(SUBSTRING_INDEX(s.regNo, '/', -1) AS UNSIGNED)";
$sql = "SELECT rs.*, s.regNo, s.title, s.fullName, s.nameWithInitial, i.indexNo FROM `repeat_slips` rs 
        INNER JOIN `stud_exam_reg` ser ON ser.regId  = rs.regId
        INNER JOIN `student` s ON ser.stud_regNo  = s.regNo
        INNER JOIN `exam_stud_index` i ON ser.stud_regNo = i.regNo
        WHERE ser.exam_id = $examID AND rs.senate_approval_letter != 'None'";

$limit = " LIMIT $offset, $records_per_page";

$year = "";
$status = "";
$filterOp = "";
if (isset($_POST['filter'])) {
    $year = $_POST['year'];
    $status = (isset($_POST['status']))?$_POST['status']:"none";
    if ($year != "none")
        $filterOp .= " s.regNo LIKE '$year%'";

    if ($status != "none") {
        if ($filterOp != "")
            $filterOp .= " And ";
        $filterOp .= " rs.senate_approval_letter_status = '$status'";
    }
    if ($filterOp != "") {
        $sql .= " And " . $filterOp;
    }
}



$searchOp = "";

if (isset($_POST['search'])) {
    $searchkey = $_POST['searchkey'];
    $searchOp = " s.regNo LIKE '%$searchkey%' or s.nameWithInitial LIKE '%$searchkey%' or i.indexNo LIKE '%$searchkey%' or rs.senate_approval_letter_status LIKE '%$searchkey%'";
    if ($searchOp != "") $sql .= " AND " . $searchOp;
}

$forcount = $sql;
$sql .=$order;
$sql .= $limit;
$stdlist = mysqli_query($con, $sql);

?>

<div class="static flex flex-col items-center justify-around gap-5">
    <h1 class="title !mb-5">Senate Approval Letter</h1>

    <form  id="searchform" action="index.php?page=senate" method="post" class="flex items-center gap-5">
        <div class="search-bar w-96 h-10 border-2 border-gray-500 rounded-full flex items-center gap-5 px-5">
            <i class="bi bi-search"></i>
            <input type="text" name="searchkey" placeholder="Search Here" value="<?php echo (isset($searchkey)) ? $searchkey : "" ?>" class="outline-none h-full w-full" required>
        </div>
        <button class="btn fill-btn" type="submit" name="search">Search</button>
    </form>

    <div class="filter">
        <form id="filterform" method="post" action="index.php?page=senate"  class="flex gap-5 items-center">

            <select name="year" id="year"  class="p-2 border-2 border-gray-500 rounded-lg outline-none">
                <option value="none">Select Year</option>
                <?php
                $distinctYear = "SELECT DISTINCT SUBSTRING(ser.stud_regNo, 1, 4) AS starting_year FROM `repeat_slips` rs 
                INNER JOIN `stud_exam_reg` ser ON ser.regId  = rs.regId";
                $result = $con->query($distinctYear);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row["starting_year"] . "' ";
                        echo ($year == $row["starting_year"]) ? "selected" : "";
                        echo ">" . ucfirst($row["starting_year"]) . "</option>";
                    }
                }
                ?>
            </select>

            <?php

            $distinctStatus = "SELECT DISTINCT rs.senate_approval_letter_status  FROM `repeat_slips` rs 
                INNER JOIN `stud_exam_reg` ser ON ser.regId  = rs.regId";
            $result = $con->query($distinctStatus);

            if ($result->num_rows > 1) { ?>

                <select for="status" name="status" class="p-2 border-2 border-gray-500 rounded-lg outline-none">
                    <option value="none">Select Status</option>
                    <?php
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row["senate_approval_letter_status"] . "' ";
                        echo ($status == $row["senate_approval_letter_status"]) ? "selected" : "";
                        echo ">" . ucfirst($row["senate_approval_letter_status"]) . "</option>";
                    }
                    ?>
                </select>
                <?php
            }
            ?>

            <div class="flex items-center gap-5">
                <button type="submit" name="filter" value="Filter" class="btn fill-btn">Filter</button>
                <a href="index.php?page=senate">
                    <button id="add" class="btn outline-btn">Reset</button>
                </a>
            </div>

        </form>

    </div>


    <table class="w-10/12 text-center">
        <tr class="h-12 bg-blue-100 font-semibold">
            <th>Reg No</th>
            <th>Name</th>
            <th>Index No</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php
        if (mysqli_num_rows($stdlist) > 0) {
        while ($row = mysqli_fetch_assoc($stdlist)) {
            $regNo = $row['regNo'];
            $indexNo = $row['indexNo'];
            $name = ($row['title'] != "") ? $row['title'] . ". " . $row['nameWithInitial'] : $row['nameWithInitial'];
            $fullName = $row['fullName'];
            $status = ucfirst($row['senate_approval_letter_status']);
            $regID = ucfirst($row['regId']);
            ?>
            <tr class="h-12 odd:bg-blue-50">
                <td><?php echo $regNo; ?></td>
                <td><?php echo $name; ?></td>
                <td><?php echo $indexNo; ?></td>
                <td><?php echo $status; ?></td>
                <td>
                    <button onclick="view(<?php echo '\''.$regNo.'\', \''.$regID.'\', \''.$fullName.'\', \''.$indexNo.'\', \''.$row['senate_approval_letter'].'\'' ?>)" class="btn outline-btn !py-1">View</button>
                </td>
            </tr>
            
            <?php
        }
        } else {
            echo "<tr class='h-12 odd:bg-blue-50'>
                     <td colspan='4'>No record found</td>
                  </tr>
                ";
        }
        ?>
    </table>

    <div id="slips-modal" class="hidden fixed !-left-[12.5%] !-top-[20%] z-40 w-[calc(100vw-300px)] p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-screen backdrop-blur-md bg-zinc-900/50">
        <div class="w-[87.5%] max-h-full mx-auto py-16">
            <!-- Modal content -->
            <div class="bg-zinc-700 rounded-2xl shadow px-5 py-3">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 border-b rounded-t text-white">
                    <h3 class="text-xl font-semibold text-white">
                        <span id="regIDSpan" class="mr-1 text-lg"></span>
                        Senate approval letter
                    </h3>
                    <form method="post" action="index.php?page=senate" class="flex items-center gap-5">
                        <input type="hidden" name="regID" id="regIDInput">
                        <input type="hidden" name="regNo" id="regNoInput">
                        <input type="submit" value="Accept" name="accept" class="btn fill-btn !bg-green-500">
                        <input type="submit" value="Reject" name="reject" class="btn fill-btn !bg-red-500">
                        <button id="close-btn" type="button" class="text-zinc-100 bg-transparent hover:bg-white hover:text-zinc-800 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </form>
                </div>
                <!-- Modal body -->
                <div class="p-6 space-y-6" id="objectElementParentElement">
                    <div class="w-2/3 grid grid-cols-3 items-center text-white">
                        <p>Index Number</p>
                        <span class="col-span-2" id="indexNoSpan"></span>
                    </div>
                    <div class="w-2/3 grid grid-cols-3 items-center text-white">
                        <p>Registration Number</p>
                        <span class="col-span-2" id="regNoSpan"></span>
                    </div>
                    <div class="w-2/3 grid grid-cols-3 items-center text-white mb-10">
                        <p>Full Name</p>
                        <span class="col-span-2" id="fullName"></span>
                    </div>
                </div>
                <!-- Modal footer -->
                <form method="post" action="index.php?page=senate" class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b">
                    
                </form>
            </div>
        </div>
    </div>


    <div class="w-1/2 flex items-center justify-around mt-10">
        <?php
        $prev_page = $current_page - 1;
        $next_page = $current_page + 1;

        if ($prev_page > 0) {
            echo "<button onclick='pagechange($prev_page)' class='btn outline-btn'>< Previous</button>";
        }


        $count_result = mysqli_query($con, $forcount);
        $total_records = $count_result->num_rows;

        $total_pages = ceil($total_records / $records_per_page);

        if ($next_page <= $total_pages) {
            echo "<button onclick='pagechange($next_page)' class='btn outline-btn'>Next ></button>";
        }
        ?>
    </div>

</div>


<script>
    function view(regNo, regID, fullName, indexNo, $fileName) {
        const modal = document.getElementById('slips-modal');
        const modalCloseBtn = document.getElementById('close-btn');
        const regIDSpan = document.getElementById('regIDSpan');
        const regIDInput = document.getElementById('regIDInput');
        const regNoInput = document.getElementById('regNoInput');
        const indexNoSpan = document.getElementById('indexNoSpan');
        const regNoSpan = document.getElementById('regNoSpan');
        const fName = document.getElementById('fullName');
        const fileName = document.getElementById('fileName');
        const objectElementParentElement = document.getElementById('objectElementParentElement');

        const objectElement = document.createElement('object');
        const objectElementFailText = document.createElement('p');
        const objectElementFailLink = document.createElement('a');

        modal.classList.remove('hidden');
        modalCloseBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
            objectElementParentElement.removeChild(objectElement);
        })
        regIDSpan.innerHTML = regNo;
        regIDInput.value = regID;
        regNoInput.value = regNo;
        indexNoSpan.innerHTML = ": " + indexNo;
        regNoSpan.innerHTML = ": " + regNo;
        fName.innerHTML = ": " + fullName;

        objectElement.data = "../assets/uploads/repeat_slips/senate_approval_letter/" + $fileName;
        objectElement.type = "application/pdf";
        objectElement.width = "100%";
        objectElement.height = "600px";

        objectElementFailLink.href = "../assets/uploads/repeat_slips/senate_approval_letter/" + $fileName;
        objectElementFailLink.classList.add('text-blue-500', 'underline');
        objectElementFailLink.innerHTML = "Download";
        objectElementFailLink.target = "_blank";

        objectElementFailText.innerHTML = "Unable to display PDF file. ";
        objectElementFailText.appendChild(objectElementFailLink);
        objectElementFailText.innerHTML += " instead.";
        objectElementFailText.classList.add('mt-24', 'text-center');

        objectElement.appendChild(objectElementFailText);
        objectElementParentElement.appendChild(objectElement);
    }


    formid ="";
    var subName = document.createElement('input');

    <?php
    if (isset($_POST['search']))
        echo "formid = 'searchform';\nsubName.name = 'search';";
    ?>
    

    const parentElement = document.getElementById(formid);
    function pagechange(no) {
        var myform = document.createElement("form");
        myform.action = "index.php?page=senate&no="+no;
        myform.method = "post";
        myform.style.display = "none"; // Hide the form
        if(formid!="") {
            const childElements = parentElement.children;
            for (const child of childElements) {
                myform.appendChild(child.cloneNode(true));
            }
            myform.appendChild(subName);
        }
        document.body.appendChild(myform);
        console.log(myform);
        myform.submit()
    }

    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>