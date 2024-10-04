<?php
$get_admins = "SELECT admin.email,`admin`.name,`admin`.`role`, `admin_details`.department, admin.status 
FROM `admin` 
    LEFT JOIN `admin_details` ON `admin_details`.`email` = `admin`.`email` WHERE admin.email != '".$_SESSION['userid']."'";


$role = "";
$status = "";
$dept = "";
$filterOp = "";
$current_page = isset($_GET['no']) ? intval($_GET['no']) : 1;
$records_per_page = 10;
$offset = ($current_page - 1) * $records_per_page;
$limit = " LIMIT $offset, $records_per_page";

if (isset($_POST['filter'])) {

    $role = $_POST['role'];
    $dept = (isset($_POST['dept']))?$_POST['dept']:"none";
    $status =(isset($_POST['status']))?$_POST['status']:"none";
    if ($role != "none")
        $filterOp .= " role LIKE '$role%'";
    if ($dept != "none") {
        if ($filterOp != "") $filterOp .= " AND ";
        $filterOp .= " department LIKE '%$dept%'";
    }
    if ($status != "none") {
        if ($filterOp != "") $filterOp .= " AND ";
        $filterOp .= " status = '$status'";
    }
}
if ($filterOp != "") $get_admins .= " And " . $filterOp;

$searchOp = "";
if (isset($_POST['search'])) {
    $search_key = $_POST['search_key'];
    $searchOp = " admin.email like '%$search_key%' OR name like '%$search_key%' OR department like '%$search_key%'";
    if ($searchOp != "") {
        $get_admins .= " AND (" . $searchOp.")";
    }
}
$forcount =$get_admins;
$get_admins .= $limit;
$adminlist = mysqli_query($con, $get_admins);

?>

<div class="flex flex-col items-center justify-around gap-5">

    <h1 class="title">Admin Management</h1>

    <form  id="searchform" action="index.php?page=listAdmins" method="post" class="flex items-center gap-5">
        <div class="search-bar w-96 h-10 border-2 border-gray-500 rounded-full flex items-center gap-5 px-5">
            <i class="bi bi-search"></i>
            <input type="text" name="search_key" placeholder="Search Here" value="<?php echo (isset($search_key)) ? $search_key : "" ?>" class="outline-none h-full w-full" required>
        </div>
        <button class="btn fill-btn" type="submit" name="search">Search</button>
    </form>

    <div class="filter">
        <form id="filterform" method="post" action="index.php?page=listAdmins" class="flex gap-5 items-center">

          <select name="role" id="role" class="p-2 border-2 border-gray-500 rounded-lg outline-none">
              <option value="none">Select Role</option>
              <?php
              // Fetch distinct roles from the database
              $distinctYear = "SELECT DISTINCT role FROM admin WHERE role !='Admin_Master'";
              $result = $con->query($distinctYear);

              if ($result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                      echo "<option value='" . $row["role"] . "' ";
                      echo ($role == $row["role"]) ? "selected" : "";
                      echo ">" . $row["role"] . "</option>";
                  }
              }
              ?>
          </select>


          
          
          <?php
          // Fetch distinct departments from the database
          $distinctDept = "SELECT DISTINCT department 
                FROM `admin` 
                LEFT JOIN `admin_details` ON `admin_details`.`email` = `admin`.`email` WHERE role !='Admin_Master'";
          $result = $con->query($distinctDept);
          if ($result->num_rows > 1) {?>

          
            <select for="dept" name="dept" class="p-2 border-2 border-gray-500 rounded-lg outline-none">
                <option value="none">Select Department</option>
                <?php
                    while ($row = $result->fetch_assoc()) {
                        if ($row["department"] == "") continue;
                        echo "<option value='" . $row["department"] . "' ";
                        echo ($dept == $row["department"]) ? "selected" : "";
                        echo ">" . $row["department"] . "</option>";
                    }
                ?>
            </select>
          <?php } ?>


          
          
          <?php
            // Fetch distinct status from the database
            $distinctStatus = "SELECT DISTINCT status
                            FROM `admin` 
                LEFT JOIN `admin_details` ON `admin_details`.`email` = `admin`.`email` WHERE role !='Admin_Master'";
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
            <?php } ?>

            <div class="flex items-center gap-5">
                <button type="submit" name="filter" value="Filter" class="btn fill-btn">Filter</button>
                <a href="index.php?page=listAdmins">
                    <button id="add" class="btn outline-btn"> Reset</button>
                </a>
            </div>
        </form>
    </div>

    <a href="index.php?page=addAdmin">
        <button id="add" class="btn outline-btn">Add Admin</button>
    </a>


    <table class="w-10/12 mt-5 text-center">
        <tr class="h-12 bg-blue-100 font-semibold">
            <th>Email</th>
            <th>Name</th>
            <th>Role</th>
            <th>Department</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php
        if (mysqli_num_rows($adminlist) > 0) {
            while ($row = mysqli_fetch_assoc($adminlist)) {
                ?>
                <tr class="h-12 odd:bg-blue-50">
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['role']; ?></td>
                    <td><?php echo $row['department']; ?></td>
                    <td class="<?php echo ($row['status'] == 'active') ? 'text-green-600' : 'text-red-400'; ?>"><?php echo strtoupper($row['status']); ?></td>
                    <td>
                        <button onclick="view('<?php echo $row['email']; ?>')" class="btn outline-btn !py-1">View</button>
                    </td>
                </tr>
                <?php
            }
        } else {
            echo "<tr class='h-12 odd:bg-blue-50'>
                    <td colspan='6'>No record found</td>
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
    function view(adminId) {
        var myform = document.createElement("form");
        myform.action = "index.php?page=viewAdmin";
        myform.method = "post";
        myform.style.display = "none"; // Hide the form
        var inp = document.createElement('input');
        inp.name = "adminId";
        inp.value = adminId;
        inp.type = "hidden";
        myform.appendChild(inp);
        document.body.appendChild(myform);
        console.log(myform);
        myform.submit()
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
        myform.action = "?page=listAdmins&no="+no;
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