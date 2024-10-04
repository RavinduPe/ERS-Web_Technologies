<?php
if (!isset($_SESSION['userid']) && !isset($_SESSION['role'])) {
    header("location: login.php");
    exit();
}

$regID = $_SESSION['userid'];
$courseColumns = array();


function setSelected($fieldName, $fieldValue)
{
    if (isset($_POST[$fieldName]) && $_POST[$fieldName] == $fieldValue) {
        echo "selected='selected'";
    }
}

if ($form == "select") {
    $examID = $aExamID;

    $sql = "SELECT * FROM `exam_reg` WHERE exam_id = '$examID'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);

    displayForm($row);
}
if ($form == "DisplayList" || isset($_POST['DisplayList'])) {
    if (isset($_POST['DisplayList'])) {
        $level = $_POST['level'];
        $type = $_POST['type'];
        $examID = $_POST['exam_id'];
    }
    //echo $examID;
    $userDataSQL = "SELECT ser.combId,ser.stud_regNo as regNo, ser.regId, esi.indexNo, s.title, s.nameWithInitial, c.combinationName
            FROM `stud_exam_reg` ser
            INNER JOIN `student` s ON ser.stud_regNo = s.regNo
            INNER JOIN `exam_stud_index` esi ON esi.regNo = s.regNo AND ser.exam_id = esi.exam_id
            INNER JOIN `combination` c ON ser.combId = c.combinationID
            WHERE ser.level = $level AND ser.type = '$type' AND ser.exam_id = $examID
            ORDER BY c.combinationID ASC;";

    $userDataResult = mysqli_query($con, $userDataSQL);
    //$userData = mysqli_fetch_assoc($userDataResult);
    //print_r($userData);

    $examID = mysqli_real_escape_string($con, $examID);
    $type = mysqli_real_escape_string($con, $type);
    $level = (int)$level; // Assuming level is an integer

    $coursesSQL = "SELECT DISTINCT usem.unitId, u.unitCode
                        FROM unit_sub_exam usem
                        INNER JOIN unit u ON u.unitId = usem.unitId
                        INNER JOIN combination_subjects cs ON cs.subject = u.subject
                        WHERE usem.exam_id = $examID
                          AND usem.type = '$type'
                          AND u.level = $level
                        ORDER BY cs.combinationID, u.unitCode";


    $coursesListResult = mysqli_query($con, $coursesSQL);

    // Initialize an array to store course columns
    $courseColumns = array();

    // Fetch all rows and store the unitCode values in the $courseColumns array
    while ($row = mysqli_fetch_assoc($coursesListResult)) {
        $courseColumns[] = array($row['unitId'], $row['unitCode']);
    }

    displayList($userDataResult, $courseColumns);
}
?>



<?php function displayForm($row)
{
    global $examID;
    ?>
    <div class="w-11/12 mb-10 mx-auto flex flex-col gap-y-5 text-center">
        <h1 class="title text-2xl">View Registration</h1>
        <form action="" method="post" class="w-full grid grid-cols-3 gap-x-5 items-center content-center">
            <input type="hidden" name="exam_id" value="<?php echo $examID ?>">
            <select class="!w-11/12 border-2 rounded-full py-2 px-5" id="type" name="type" required>
                <option value="select" <?php setSelected('type', 'select') ?> disabled selected>Select Type <span
                            class="!text-red-500">*</span></option>
                <option value="proper" <?php setSelected('type', 'proper') ?>>Proper</option>
                <option value="repeat" <?php setSelected('type', 'repeat') ?>>Repeat</option>
            </select>
            <select class="!w-11/12 border-2 rounded-full py-2 px-5" id="level" name="level" required>
                <option value="select" <?php setSelected('level', 'select') ?> disabled selected>Select Level <span
                            class="!text-red-500">*</span></option>
                <option value="1" <?php setSelected('level', 1) ?>>Level 1</option>
                <option value="2" <?php setSelected('level', 2) ?>>Level 2</option>
                <option value="3" <?php setSelected('level', 3) ?>>Level 3</option>
                <option value="4" <?php setSelected('level', 4) ?>>Level 4</option>
            </select>

            <input class="w-10/12 btn fill-btn" type="submit" name="DisplayList" value="Display"/>
            <!-- <input class="btn outline-btn ml-5 " type="submit" name="DownloadList" value="Download as a Excel File" /> -->
        </form>
    </div>
<?php } ?>

<?php function displayList($userDataResult, $courseColumns)
{
    global $con, $type, $level, $examID; ?>

    <div class="flex flex-col gap-y-1 items-center">
        <?php if (isset($_SESSION['role'])) { ?>
            <a href="<?php echo "../registrationList/downloadExcel.php?level=$level&type=$type&examID=$examID" ?>"
               target="_blank"
               class="btn outline-btn !text-green-600 !border-green-500 !bg-white hover:!bg-green-500 hover:!text-white">Download
                as a Excel sheet</a>
        <?php } elseif (false && isset($_SESSION['userid'])) { ?>
            <a href="#" class="btn fill-btn!bg-green-500">Download as a PDF</a>
        <?php } ?>

        <table class="w-11/12 mx-auto my-5 ml-10 rounded-lg text-xs lg:text-base">
            <thead class="bg-blue-100 h-20 lg:h-32">
            <th class="font-semibold px-2">No.</th>
            <th class="font-semibold border-gray-100 border-x-2 px-8">Reg No</th>
            <th class="font-semibold border-gray-100 border-x-2 px-8">Index No</th>
            <th class="font-semibold border-gray-100 border-x-2 px-8">Title</th>
            <th class="font-semibold border-gray-100 border-x-2 px-8">Name with initials</th>
            <th class="font-semibold border-gray-100 border-x-2 px-8">Combination</th>
            <?php
            foreach ($courseColumns as $course) {
                echo "<th class=\"font-semibold border-gray-100 border-x-2 -rotate-90 \">$course[1]</th>";
            }
            ?>

            </thead>
            <tbody class="text-center ">
            <?php
            $counter = 1;
            while ($user = mysqli_fetch_assoc($userDataResult)) {
                //print_r($user);
                echo "<tr class='h-12 even:bg-blue-50'>";
                echo "<td>$counter</td>";
                echo "<td>$user[regNo]</td>";
                echo "<td>$user[indexNo]</td>";
                echo "<td>$user[title]</td>";
                echo "<td>$user[nameWithInitial]</td>";
                echo "<td>$user[combinationName]</td>";
                $regId = $user['regId'];
                $examUnitIds = array();
                $sql = "SELECT exam_unit_id FROM reg_units WHERE regId = $regId";
                $result = $con->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $examUnitIds[] = $row['exam_unit_id'];
                    }
                }
                //print_r($examUnitIds);
                //print_r($courseColumns);
                foreach ($courseColumns as $course) {
                    echo "<td>";
                    echo (in_array($course[0], $examUnitIds)) ? 'P' : '-';
                    echo "</td>";
                }
                echo "</tr>";
                $counter++;
            }

            ?>
        </table>

    </div>
    <?php
} ?>


<script>
    const userMenu = document.getElementById('user-menu');

    function openMenu() {
        userMenu.classList.toggle('hidden');
        userMenu.classList.toggle('absolute');
        userMenu.classList.toggle('-translate-y-full');
        userMenu.classList.toggle('lg:translate-x-full');
    }
</script>
