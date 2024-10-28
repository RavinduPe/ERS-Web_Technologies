<?php
    require($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php');
    require($_SERVER['DOCUMENT_ROOT'] . '/config/connect.php');

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    $examID = mysqli_real_escape_string($con, $_GET['examID']);
    $type = mysqli_real_escape_string($con, $_GET['type']);
    $level = (int)$_GET['level']; // Assuming level is an integer
    $fileName = "LEVEL ".$level."G-".$level."S ". strtoupper($type);
    if($level == 3)
        $fileName = "LEVEL ".$level."G-".$level."M-".$level."S ". strtoupper($type);
    if($level == 4)
        $fileName = "LEVEL ".$level."M-".$level."S ". strtoupper($type);

    header('Content-Type: application/vnd.ms-excel');
    header("Content-Disposition: attachment;filename=$fileName.xlsx");

    //echo $examID;
    $userDataSQL = "SELECT ser.combId,ser.stud_regNo as regNo, ser.regId, esi.indexNo, s.title, s.nameWithInitial, c.combinationName
        FROM `stud_exam_reg` ser
        INNER JOIN `student` s ON ser.stud_regNo = s.regNo
        INNER JOIN `exam_stud_index` esi ON esi.regNo = s.regNo AND ser.exam_id = esi.exam_id
        INNER JOIN `combination` c ON ser.combId = c.combinationID
        WHERE ser.level = $level AND ser.type = '$type' AND ser.exam_id = $examID
        ORDER BY c.combinationID ASC;";

    $userDataResult = mysqli_query($con, $userDataSQL);

    $coursesSQL = "SELECT DISTINCT usem.unitId, u.unitCode
        FROM unit_sub_exam usem
        INNER JOIN unit u ON u.unitId = usem.unitId
        INNER JOIN combination_subjects cs ON cs.subject = u.subject
        WHERE usem.exam_id = $examID
        AND usem.type = '$type'
        AND u.level = $level
        ORDER BY cs.combinationID, u.unitCode";

    $coursesListResult = mysqli_query($con, $coursesSQL);

    // Initialize an array to store columns
    $columnArray = array();
    $courseColumns = array();
    $columnArray = ['No.','Reg No','Index No','Title','Name with initials','Combination']; // Default columns

    // Fetch all rows and store the unitCode values in the $columnArray array
    while ($row = mysqli_fetch_assoc($coursesListResult)) {
        array_push($columnArray, $row['unitCode']);
        $courseColumns[] = array($row['unitId'],$row['unitCode']);
    }

    // Creating the excel file part
    // creating an array for column letters in excel sheet [A to CZ]
    $startColumn = 'A';
    $endColumn = 'DZ';
    ++$endColumn;

    $ColumnIndexArray = [];
    for($column = $startColumn; $column != $endColumn; ++$column) {
        $ColumnIndexArray[] = $column;
    }

    // Definening a new spreadsheet file
    $spreadsheet = new Spreadsheet();
    $activeWorksheet = $spreadsheet->getActiveSheet();

    // Setting the Properties of the spreadsheet
    $spreadsheet->getProperties()
        ->setCreator("Dean Office, Faculty of Science")
        ->setTitle("Exam Registered Student List - $type, level $level")
        ->setSubject("$type, level $level")
        ->setDescription("Students who registered for the $type, level $level exam");


    for ($i=0; $i < count($columnArray); $i++) {
        $activeWorksheet->setCellValue($ColumnIndexArray[$i].'1', $columnArray[$i]);
    }

    $rowNum = 2;
    $counter =1;
    while ($user = mysqli_fetch_assoc($userDataResult)) {
        $activeWorksheet->setCellValue('A'.$rowNum, $counter);
        $activeWorksheet->setCellValue('B'.$rowNum, $user['regNo']);
        $activeWorksheet->setCellValue('C'.$rowNum, $user['indexNo']);
        $activeWorksheet->setCellValue('D'.$rowNum, $user['title']);
        $activeWorksheet->setCellValue('E'.$rowNum, $user['nameWithInitial']);
        $activeWorksheet->setCellValue('F'.$rowNum, $user['combinationName']);

        $regId = $user['regId'];
        $examUnitIds = array();
        $sql = "SELECT exam_unit_id FROM reg_units WHERE regId = $regId";
        $result = $con->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $examUnitIds[] = $row['exam_unit_id'];
            }
        }

        $index=6;
        foreach ($courseColumns as $course) {
            $matchResult = (in_array($course[0], $examUnitIds)) ? 'P' : '-';
            $activeWorksheet->setCellValue($ColumnIndexArray[$index].$rowNum, $matchResult);
            $index++;
        }
        $counter++;
        $rowNum++;
    }


    $writer = new Xlsx($spreadsheet);
    $writer->save("php://output");

?>