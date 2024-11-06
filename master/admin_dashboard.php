

<div class="w-full h-fit flex flex-col items-center justify-around">
    <h1 class="title">Exam Registration System</h1>
    <img alt="Banner" src="../assets/img/panels/Banner.png" class="w-96 my-10">
</div>
    <?php
    $examQuery = "select * from exam_reg where status='registration';";
    $result = mysqli_query($con, $examQuery);
    if(mysqli_num_rows($result) > 0){
    ?>
    <p class="font-bold text-2xl p-5 border rounded-xl bg-white mx-5 mt-4">Summary Of Processing Exams</p>
    <?php

    while($exams = mysqli_fetch_assoc($result)){
    $currentExamId = $exams['exam_id'];

    $eligibleStudentsQuery = "Select * from exam_stud_index where exam_id=$currentExamId;";
    $eligibleStudentResult = mysqli_query($con, $eligibleStudentsQuery);
    $numOfElibleStudents = mysqli_num_rows($eligibleStudentResult);

    $registeredStudents = "select * from stud_exam_reg where exam_id=$currentExamId;";
    $registeredStudentsResult = mysqli_query($con, $registeredStudents);
    $numberOfRegisteredStudent = mysqli_num_rows($registeredStudentsResult);

    $numberOfNotRegStud = ($numOfElibleStudents - $numberOfRegisteredStudent);

    $registeredLevel1StudQuery = "Select * from stud_exam_reg where exam_id=$currentExamId and level=1;";
    $registeredLevel1StudResult = mysqli_query($con, $registeredLevel1StudQuery);
    $numOfRegisteredLevel1Stud = mysqli_num_rows($registeredLevel1StudResult);

    $registeredLevel2StudQuery = "Select * from stud_exam_reg where exam_id=$currentExamId and level=2;";
    $registeredLevel2StudResult = mysqli_query($con, $registeredLevel2StudQuery);
    $numOfRegisteredLevel2Stud = mysqli_num_rows($registeredLevel2StudResult);

    $registeredLevel3StudQuery = "Select * from stud_exam_reg where exam_id=$currentExamId and level=3;";
    $registeredLevel3StudResult = mysqli_query($con, $registeredLevel3StudQuery);
    $numOfRegisteredLevel3Stud = mysqli_num_rows($registeredLevel3StudResult);

    $registeredLevel4StudQuery = "Select * from stud_exam_reg where exam_id=$currentExamId and level=4;";
    $registeredLevel4StudResult = mysqli_query($con, $registeredLevel4StudQuery);
    $numOfRegisteredLevel4Stud = mysqli_num_rows($registeredLevel4StudResult);

    $donutchartId = "donutChartId_$currentExamId";
    $columnchartId = "columnChartId_$currentExamId";

    // var_dump($numOfRegisteredLevel4Stud);
    // var_dump($numOfRegisteredLevel3Stud);
    // var_dump($numOfRegisteredLevel2Stud);
    // var_dump($numOfRegisteredLevel1Stud);

    ?>
    <div class="">
        <?php
        if($numOfElibleStudents != null){
            ?>
    <div class="grid grid-cols-2 gap-2 mx-5">
        <div id="<?php echo $donutchartId ?>" class=" h-56 border rounded-xl  shadow-lg mt-4 overflow-hidden hover:cursor-pointer hover:shadow-2xl"></div>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            google.charts.load("current", {packages:["corechart"]});
            google.charts.setOnLoadCallback(drawDonutChart);
            function drawDonutChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Status', 'No. of students'],
                    ['Registered',     <?php echo $numberOfRegisteredStudent;?>],
                    ['Not Registered',      <?php echo $numberOfNotRegStud;?>],
                ]);

                var options = {
                    title: "Academic year <?php echo $exams['academic_year']?>" ,
                    pieHole: 0.4,
                };

                var chart = new google.visualization.PieChart(document.getElementById('<?php echo $donutchartId ?>'));
                chart.draw(data, options);
            }
        </script>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            google.charts.load("current", {packages:['corechart']});
            google.charts.setOnLoadCallback(drawBarChart);
            function drawBarChart() {
                var data = google.visualization.arrayToDataTable([
                    ["Element", "Density", { role: "style" } ],
                    ["Level 1", <?php echo $numOfRegisteredLevel1Stud ?>, "#b87333"],
                    ["Level 2", <?php echo $numOfRegisteredLevel2Stud ?>, "silver"],
                    ["Level 3", <?php echo $numOfRegisteredLevel3Stud ?>, "gold"],
                    ["Level 4", <?php echo $numOfRegisteredLevel4Stud ?>, "color: #e5e4e2"]
                ]);

                

                var view = new google.visualization.DataView(data);
                view.setColumns([0, 1,
                    { calc: "stringify",
                        sourceColumn: 1,
                        type: "string",
                        role: "annotation" },
                    2]);

                var options = {
                    title: "Academic year <?php echo $exams['academic_year']?> (Registered students by levels)",
                    bar: {groupWidth: "95%"},
                    legend: { position: "none" },
                };
                var chart = new google.visualization.ColumnChart(document.getElementById("<?php echo $columnchartId ?>"));
                chart.draw(view, options);
            }
        </script>
        <div id="<?php echo $columnchartId ?>" class="h-56 border rounded-xl  shadow-lg mt-4 overflow-hidden hover:cursor-pointer hover:shadow-2xl"></div>
        </div>
        <?php
            } else{?>
            <div class="p-10 h-56 border rounded-xl bg-white  shadow-lg mx-5 mt-4 overflow-hidden hover:cursor-pointer hover:shadow-2xl">
                <p class="bold text-xl text-center">Students are not assigned yet!</p>
                <p class="text-center text-gray-300">Academic year:  <?php echo $exams['academic_year'];?></p>
                <p class="text-center text-md text-blue-600 my-5">Inform to the student admin</p>
                <form action="send_email.php" onsubmit="showAlert(event)" method="POST">
                    <button type="submit" id="send_email" name="send_email" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Send mail</button>
                </form>
            </div>
        <?php }}} else{?>
            <h2 class="font-semibold text-xl">Welcome <?php echo "$userproftitle $userprofname" ?> (Master Admin)</h2>
            <?php }?>
    </div>






