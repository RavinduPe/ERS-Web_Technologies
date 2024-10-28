<?php


$current_page = isset($_GET['no']) ? intval($_GET['no']) : 1;
$records_per_page =10;
$offset = ($current_page - 1) * $records_per_page;


$sql = "SELECT * FROM unit";
$limit = " LIMIT $offset, $records_per_page";



$searchOp = "";
if (isset($_POST['search'])) {
    $searchkey = $_POST['searchkey'];
    $searchOp = " unitCode LIKE '%$searchkey%' or name LIKE '%$searchkey%' or subject LIKE '%$searchkey%'";
    if ($searchOp != "") {
        $sql .= " Where " . $searchOp;
    }
}

$forcount = $sql;
$sql .= $limit;
$unitlist = mysqli_query($con, $sql);

?>


<div class="flex flex-col items-center justify-around gap-5">
    <h1 class="title">Unit Management</h1>

    <div class="flex items-center gap-5 mt-5">
        <form  id="searchform" action="index.php?page=units" method="post" class="flex items-center gap-5">
            <div class="search-bar w-96 h-10 border-2 border-gray-500 rounded-full flex items-center gap-5 px-5">
                <i class="bi bi-search"></i>
                <input type="text" name="searchkey" placeholder="Search Here" value="<?php echo (isset($searchkey)) ? $searchkey : "" ?>" class="outline-none h-full w-full" required>
            </div>
            <button class="btn fill-btn" type="submit" name="search">Search</button>
        </form>
        <a href="index.php?page=units">
            <button id="add" class="btn outline-btn">Reset</button>
        </a>

    </div>




    <a href="index.php?page=addUnit">
        <button id="add" class="btn fill-btn">Add New Unit</button>
    </a>


    <table class="w-11/12 mt-5 text-center">
        <tr class="h-12 bg-blue-100 font-semibold">
            <th>Unit Id</th>
            <th>Unit<br>Code</th>
            <th>Name</th>
            <th>Subject</th>
            <th>Level</th>
            <th>Academic<br>Year</th>
            <th>Action</th>
        </tr>
        <?php
        if (mysqli_num_rows($unitlist) > 0) {
        while ($row = mysqli_fetch_assoc($unitlist)) {
            ?>
            <tr class="h-12 odd:bg-blue-50">
                <td><?php echo $row['unitId']; ?></td>
                <td><?php echo $row['unitCode']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['subject']; ?></td>
                <td><?php echo $row['level']; ?></td>
                <td><?php echo $row['acYearAdded']; ?></td>
                <td>
                    <button onclick="edit('<?php echo $row['unitId']; ?>')" class="btn outline-btn !py-1 !px-3">Edit</button>
                </td>
            </tr>
            <?php
        }
        } else {
            echo "<tr class='h-12 odd:bg-blue-50'>
                     <td colspan='7'>No record found</td>
                  </tr>
                                        ";
        }
        ?>
    </table>

    <div class="w-1/2 flex items-center justify-around mt-10">
    <?php
        $prev_page = $current_page - 1;
        $next_page = $current_page + 1;

        echo "<br>";
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
    function edit(unitId) {
        var myform = document.createElement("form");
        myform.action = "index.php?page=editUnit";
        myform.method = "post";
        myform.style.display = "none"; // Hide the form
        var inp = document.createElement('input');
        inp.name = "unitId";
        inp.value = unitId;
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
        myform.action = "index.php?page=units&no="+no;
        myform.method = "post";
        myform.style.display = "none"; // Hide the form
        if(formid!=="") {
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