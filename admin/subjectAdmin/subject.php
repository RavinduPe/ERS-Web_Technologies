<?php

    if (isset($_POST['submit'])) {
        $subject = strtoupper($_POST['subject']);


            $query = "SELECT * from subject where subject ='$subject' ";

            if (mysqli_num_rows(mysqli_query($con, $query))) {

                $msg[0] = "Subject already added!";
                $msg[1] = "!text-red-500";
            } else {
                $query = "INSERT INTO subject (subject) values('$subject')";
                if (!mysqli_query($con, $query)) {

                    $msg[0] = "error!";
                    $msg[1] = "!text-red-500";
                } else {
                    $query = "INSERT INTO subject (subject) values('$subject')";
                    mysqli_query($con, $query);
                    $msg[0] = "Successfully added!";
                    $msg[1] = "!text-green-500";
                }
            }
        }
        
    $sql = "SELECT * FROM subject";
    $sublist = mysqli_query($con, $sql);


    ?>


<div class="flex flex-col items-center justify-around gap-5">
    <h1 class="title">Subjects</h1>
    <form action="" method="post" class="flex flex-col justify-center items-center gap-5 mt-8">
        <?php
        if (isset($msg)) {
            echo "<b class='" . $msg[1] . "'>" . $msg[0] . "</b>";
        }
        ?>
        <div class="w-full flex items-center gap-5">
            <input type="subject" name="subject" placeholder="Enter New Subject" class="w-56 border border-gray-400 rounded-full py-2 px-5 outline-none focus:border-blue-500" required>
            <input type="submit" name="submit" class="btn fill-btn" value="Register">
        </div>
    </form>

    <table class="w-96 mt-5 text-center">
        <tr class="h-10 bg-blue-100 font-semibold">
            <th>Subject</th>
        </tr>
        <?php
            if (mysqli_num_rows($sublist) > 0) {
            while ($row = mysqli_fetch_assoc($sublist)) {
                ?>
                <tr class='h-10 odd:bg-blue-50'>
                    <td><?php echo $row['subject']; ?></td>
                </tr>
                <?php
            }
            } else {
                echo "<tr class='h-10 odd:bg-blue-50'>
                        <td>No record found</td>
                    </tr>
                                            ";
            }
        ?>
    </table>

</div>


<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>