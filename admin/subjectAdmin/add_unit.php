<?php

    if (isset($_POST['submit'])) {
        $unitCode = $_POST["unitCode"];
        $unitName = $_POST["unitName"];
        $subject = $_POST["subject"];
        $level = $_POST["level"];
        $acYear = $_POST["acYear"];

            $query = "SELECT unitCode from unit where unitCode ='$unitCode' and acYearAdded = '$acYear'";

            if (mysqli_num_rows(mysqli_query($con, $query))) {

                $msg[0] = "Unit already added!";
                $msg[1] = "text-red-500";
            } else {
                $query = "INSERT INTO unit (unitCode, name, subject, level, acYearAdded) values('$unitCode', '$unitName', '$subject', '$level', '$acYear')";
                if (!mysqli_query($con, $query)) {

                    $msg[0] = "error!";
                    $msg[1] = "text-red-500";
                } else {
                    $msg[0] = "Successfully added!";
                    $msg[1] = "text-green-500";
                }
            }
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
        <div class="w-full grid grid-cols-3 items-center h-10">
            <label for="unitCode">Unit Code: </label>
            <input type="text" name="unitCode" class="col-span-2 w-full h-full border border-gray-400 rounded-full px-5 outline-none focus:border-blue-500" required>
        </div>

        <div class="w-full grid grid-cols-3 items-center h-10">
            <label for="unitName">Unit Name: </label>
            <input type="text" name="unitName" class="col-span-2 w-full h-full border border-gray-400 rounded-full px-5 outline-none focus:border-blue-500" required>
        </div>

        <div class="w-full grid grid-cols-3 items-center h-10">
            <label for="subject">Subject: </label>
            <select name = "subject" class="col-span-2 w-full h-full border border-gray-400 rounded-full px-5 outline-none focus:border-blue-500" required>
            <?php
                // Fetch distinct exam names from the database
                $Subject = "SELECT subject FROM subject";
                $result = $con->query($Subject);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row["subject"] ."' >" . $row["subject"] . "</option>";
                    }
                }
            ?>
            </select>
        </div>

        <div class="w-full grid grid-cols-3 items-center h-10">
            <label for="level">Level: </label>
            <select name = "level" class="col-span-2 w-full h-full border border-gray-400 rounded-full px-5 outline-none focus:border-blue-500" required>
                <option value = "1">1</option>
                <option value = "2">2</option>
                <option value = "3">3</option>
                <option value = "4">4</option>
            </select>
        </div>

        <div class="w-full grid grid-cols-3 items-center h-10">
            <label for="acYear">Academic Year: </label>
            <input type="text" name="acYear" class="col-span-2 w-full h-full border border-gray-400 rounded-full px-5 outline-none focus:border-blue-500" required>
        </div>

        <div class="w-full grid grid-cols-2 items-center h-10 gap-10 mt-5">
            <input type="button" value="< Back" onclick="history.back()" class="btn outline-btn">
            <input type="submit" name="submit" value="Add" class="btn fill-btn">
        </div>
    </form>
</div>
