<?php

$current_page = isset($_GET['no']) ? intval($_GET['no']) : 1;
$records_per_page = 10;
$offset = ($current_page - 1) * $records_per_page;

$indexSelect="";
$indexQuery="";
if(isset($exam)){
    $examID = $exam['exam_id'];
    $indexSelect=", exam_stud_index.indexNo ";
    $indexQuery = " LEFT JOIN exam_stud_index ON exam_stud_index.regNo = student_check.regNo AND exam_stud_index.exam_id = $examID";
}

$sql = "SELECT student.*, student_check.*".$indexSelect." FROM student INNER JOIN student_check ON student.regNo = student_check.regNo".$indexQuery;
$limit = " LIMIT $offset, $records_per_page";
$order =" ORDER BY CAST(SUBSTRING_INDEX(student.regNo, '/', 1) AS UNSIGNED) DESC,
  SUBSTRING_INDEX(SUBSTRING_INDEX(student.regNo, '/', 2), '/', -1),
  CAST(SUBSTRING_INDEX(student.regNo, '/', -1) AS UNSIGNED)";


$year = "";
$dept = "";
$status = "";
$student_regNo = "";
$filterOp = "";

if (isset($_POST['filter'])) {
    $year = $_POST['year'];
    $dept = (isset($_POST['dept']))?$_POST['dept']:"none";
    $status = (isset($_POST['status']))?$_POST['status']:"none";
    if ($year != "none")
        $filterOp .= " student.regNo LIKE '$year%'";
    if ($dept != "none") {
        if ($filterOp != "")
            $filterOp .= " And ";
        $filterOp .= " student.regNo LIKE '%$dept%'";

    }
    if ($status != "none") {
        if ($filterOp != "")
            $filterOp .= " And ";
        $filterOp .= " student_check.status = '$status'";
    }
}

if ($filterOp != "") {
    $sql .= " Where " . $filterOp;
}

$searchOp = "";
if (isset($_POST['search'])) {
    $searchkey = $_POST['searchkey'];
    $searchOp = " student.regNo LIKE '%$searchkey%' or student.nameWithInitial LIKE '%$searchkey%'";
    if(isset($exam))
        $searchOp .=  " or exam_stud_index.indexNo LIKE '%$searchkey%'";
    if ($searchOp != "") {
        $sql .= " Where " . $searchOp;
    }
}

$forcount = $sql;
$sql .=$order;
$sql .= $limit;
$stdlist = mysqli_query($con, $sql);

?>




<div class="flex flex-col items-center justify-around gap-5">
    <h1 class="title">Student Management</h1>

    <form  id="searchform" action="index.php?page=stud" method="post" class="flex items-center gap-5">
        <div class="search-bar w-96 h-10 border-2 border-gray-500 rounded-full flex items-center gap-5 px-5">
            <i class="bi bi-search"></i>
            <input type="text" name="searchkey" placeholder="Search Here" value="<?php echo (isset($searchkey)) ? $searchkey : "" ?>" class="outline-none h-full w-full" required>
        </div>
        <button class="btn fill-btn" type="submit" name="search">Search</button>
    </form>


    <div class="filter">
        <form id="filterform" method="post" action="index.php?page=stud"  class="flex gap-5 items-center">

            <select name="year" id="year"  class="p-2 border-2 border-gray-500 rounded-lg outline-none">
                <option value="none">Select Year</option>
                <?php
                    $distinctYear = "SELECT DISTINCT SUBSTRING(regNo, 1, 4) AS starting_year FROM student";
                    $result = $con->query($distinctYear);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row["starting_year"] . "' ";
                            echo ($year == $row["starting_year"]) ? "selected" : "";
                            echo ">" . $row["starting_year"] . "</option>";
                        }
                    }
                ?>
            </select>


            <?php
                $distinctDept = "SELECT DISTINCT SUBSTRING(SUBSTRING_INDEX(regNo, '/', 2), 6) AS code FROM student";
                $result = $con->query($distinctDept);
                if ($result->num_rows > 1) { ?>

                    <select for="dept" name="dept" class="p-2 border-2 border-gray-500 rounded-lg outline-none">
                        <option value="none">Select Department</option>
                        <?php
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row["code"] . "' ";
                            echo ($dept == $row["code"]) ? "selected" : "";
                            echo ">" . $row["code"] . "</option>";
                        }
                        ?>
                    </select>
                <?php
                }
            ?>


            <?php

                $distinctStatus = "SELECT DISTINCT status FROM student_check";
                $result = $con->query($distinctStatus);

                if ($result->num_rows > 1) { ?>

                    <select for="status" name="status" class="p-2 border-2 border-gray-500 rounded-lg outline-none">
                        <option value="none">Select Status</option>
                        <?php
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row["status"] . "' ";
                            echo ($status == $row["status"]) ? "selected" : "";
                            echo ">" . $row["status"] . "</option>";
                        }
                        ?>
                    </select>
                    <?php
                }
            ?>

            <div class="flex items-center gap-5">
                <button type="submit" name="filter" value="Filter" class="btn fill-btn">Filter</button>
                <a href="index.php?page=stud">
                    <button id="add" class="btn outline-btn">Reset</button>
                </a>
            </div>

        </form>

    </div>

    <a href="index.php?page=addStud">
        <button id="add" class="btn outline-btn">Add Student</button>
    </a>

    <table class="w-10/12 mt-5 text-center">
        <tr class="h-12 bg-blue-100 font-semibold">
            <th>Reg No</th>
            <th>Name</th>
            <?php if(isset($exam)){?>
                <th>Index No</th>
            <?php }?>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php
        if (mysqli_num_rows($stdlist) > 0) {
        while ($row = mysqli_fetch_assoc($stdlist)) {
            ?>
            <tr class="h-12 odd:bg-blue-50">
                <td><?php echo $row['regNo']; ?></td>
                <td><?php echo ($row['title'] != "") ? $row['title'] . ". " : "";
                    echo $row['nameWithInitial']; ?></td>
                <?php if(isset($exam)){?>
                    <td><?php echo $row['indexNo']; ?></td>
                <?php }?>
                <td class="<?php echo ($row['status'] == 'active') ? 'text-green-600' : 'text-red-400'; ?>"><?php echo $row['status']; ?></td>
                <td>
                    <button onclick="view('<?php echo $row['regNo']; ?>')" class="btn outline-btn !py-1">View</button>
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
    function view(regNo) {
        var myform = document.createElement("form");
        myform.action = "index.php?page=viewStud";
        myform.method = "post";
        myform.style.display = "none"; // Hide the form
        var inp = document.createElement('input');
        inp.name = "regNo";
        inp.value = regNo;
        inp.type = "hidden";
        myform.appendChild(inp);
        document.body.appendChild(myform);
        console.log(myform);
        myform.submit();
    }
    formid ="";
    var subName = document.createElement('input');

    <?php
    if (isset($_POST['filter'])) {
        echo "formid = 'filterform';\n";
        echo "subName.name = 'filter';";
    }
    else if (isset($_POST['search']))
        echo "formid = 'searchform';\nsubName.name = 'search';";
    ?>
    

    const parentElement = document.getElementById(formid);
    function pagechange(no) {
        var myform = document.createElement("form");
        myform.action = "index.php?page=stud&no="+no;
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
</script>
<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>