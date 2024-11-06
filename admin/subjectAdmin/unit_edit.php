<?php


if (isset($_POST['save'])) {
    $unitId = $_POST['unitId'];
    $unitCode = $_POST["unitCode"];
    $newUnitCode = $_POST["newUnitCode"];
    $unitName = $_POST["unitName"];
    $subject = $_POST["subject"];
    $level = $_POST["level"];
    $acYear = $_POST["acYear"];
    $newAcYear = $_POST["newAcYear"];
    if ($unitCode != $newUnitCode || $acYear != $newAcYear) {
        $query = "SELECT unitCode from unit where unitCode ='$newUnitCode' and acYearAdded = '$newAcYear'";
        $res = mysqli_query($con, $query);
        print_r(mysqli_fetch_assoc($res));
        if (mysqli_num_rows($res)) {

            $msg[0] = "Unit code is already existed in the academic Year!";
            $msg[1] = "text-red-500";
        }
    }
    if (!isset($msg)) {
        $query = "UPDATE unit SET unitCode = '$newUnitCode', name = '$unitName', subject = '$subject', level = '$level', acYearAdded = '$newAcYear' WHERE unitId = '$unitId'";
        if (!mysqli_query($con, $query)) {
            $msg[0] = "error!";
            $msg[1] = "text-red-500";

            $msg[0] = "error!";
            $msg[1] = "text-red-500";
        } else {
            $msg[0] = "Successfully Edited!";
            $msg[1] = "text-green-500";
        }
    }
}

if (isset($_POST['unitId']) || isset($unitId)) {
    $unitId = $_POST['unitId'];
    $query = "SELECT * FROM unit WHERE unitId = '" . $unitId . "'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
}



?>


<div class="flex flex-col items-center justify-around gap-5">
    <h1 class="title">Add Unit</h1>
    <form action="" method="post" class="w-10/12 flex flex-col items-center gap-5 mt-5">
        <?php
        if (isset($msg)) {
            echo "<b class='" . $msg[1] . "'>" . $msg[0] . "</b>";
        }
        ?>

        <input type="hidden" name="unitId" value="<?php echo $row['unitId']; ?>" required>

        <div class="w-full grid grid-cols-3 items-center h-10">
            <label for="unitCode">Unit Code: </label>
            <input type="text" name="newUnitCode" value="<?php echo $row['unitCode']; ?>" class="col-span-2 w-full h-full border border-gray-400 rounded-full px-5 outline-none focus:border-blue-500" required>
            <input type="hidden" name="unitCode" value="<?php echo $row['unitCode']; ?>">
        </div>

        <div class="w-full grid grid-cols-3 items-center h-10">
            <label for="unitName">Unit Name: </label>
            <input type="text" name="unitName" value="<?php echo $row['name']; ?>" class="col-span-2 w-full h-full border border-gray-400 rounded-full px-5 outline-none focus:border-blue-500" required>
        </div>

        <div class="w-full grid grid-cols-3 items-center h-10">
            <label for="subject">Subject: </label>
            <select name="subject" class="col-span-2 w-full h-full border border-gray-400 rounded-full px-5 outline-none focus:border-blue-500" required>
                <?php
                $SubjectValue = "SELECT subject FROM subject";
                $result = $con->query($SubjectValue);

                if ($result->num_rows > 0) {
                    while ($row1 = $result->fetch_assoc()) {
                        echo "<option value='" . $row1["subject"] . "' ";
                        echo ($row1["subject"] == $row["subject"]) ? "selected" : "";
                        echo ">" . $row1["subject"] . "</option>";
                    }
                }
                ?>
            </select>
        </div>

        <div class="w-full grid grid-cols-3 items-center h-10">
            <label for="level">Level: </label>
            <select name="level" class="col-span-2 w-full h-full border border-gray-400 rounded-full px-5 outline-none focus:border-blue-500" required>
                <option value="1" <?php echo ("1" == $row['level']) ? "selected" : ""; ?>>1</option>
                <option value="2" <?php echo ("2" == $row['level']) ? "selected" : ""; ?>>2</option>
                <option value="3" <?php echo ("3" == $row['level']) ? "selected" : ""; ?>>3</option>
                <option value="4" <?php echo ("4" == $row['level']) ? "selected" : ""; ?>>4</option>
            </select>
        </div>

        <div class="w-full grid grid-cols-3 items-center h-10">
            <label for="acYear">Academic Year: </label>
            <input type="text" name="newAcYear" value="<?php echo $row['acYearAdded']; ?>" class="col-span-2 w-full h-full border border-gray-400 rounded-full px-5 outline-none focus:border-blue-500" required>
            <input type="hidden" name="acYear" value="<?php echo $row['acYearAdded']; ?>">
        </div>

        <div class="w-full grid grid-cols-2 items-center h-10 gap-10 mt-5">
            <input type="button" value="< Back" onclick="history.back()" class="btn outline-btn">
            <input type="submit" name="save" value="Save" class="btn fill-btn">
        </div>
    </form>

</div>
