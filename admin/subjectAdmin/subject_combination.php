<?php
require_once("../config/connect.php");
$errorMsg = "";

// Handling form submission
if (isset($_POST['sub_comb'])) {
    $subjectCount = isset($_POST['subjectCount']) ? intval($_POST['subjectCount']) : 0;
    $selectedSubjects = [];

    for ($i = 1; $i <= $subjectCount; $i++) {
        $selectedSubjects[] = $_POST['subject' . $i];
    }

    // Check for duplicate subjects and validate subject count
    if (count($selectedSubjects) !== count(array_unique($selectedSubjects))) {
        $errorMsg = "Duplicate subjects detected in the combination.";
    } elseif (count($selectedSubjects) !== $subjectCount) {
        $errorMsg = "Subject count and dropdown count do not match.";
    } else {
        // Check if the combination already exists
        $combinationName = implode(', ', $selectedSubjects);
        $checkCombinationQuery = "SELECT combinationID FROM combination_subjects WHERE subject IN ('" . implode("', '", $selectedSubjects) . "') GROUP BY combinationID HAVING COUNT(DISTINCT subject) = " . count($selectedSubjects);
        $result = mysqli_query($con, $checkCombinationQuery);

        if (mysqli_num_rows($result) > 0) {
            $errorMsg = "Combination already exists with the same subjects.";
        } else {
            // Insert the combination into 'combination' table
            $insertCombinationQuery = "INSERT INTO combination (combinationName) VALUES ('$combinationName')";
            mysqli_query($con, $insertCombinationQuery);

            // Get the combinationID for the inserted combination
            $combinationID = mysqli_insert_id($con);

            // Insert subjects into 'combination_subjects' table
            foreach ($selectedSubjects as $subject) {
                $insertSubjectsQuery = "INSERT INTO combination_subjects (combinationID, subject) VALUES ($combinationID, '$subject')";
                mysqli_query($con, $insertSubjectsQuery);
                // Set a success message
                $successMsg = "Combination added successfully!";
            }
        }
    }
}

$get_subjects = "SELECT subject FROM subject";
$subjects = mysqli_query($con, $get_subjects);

$subarr = "const subjects = [";
$fst = false;

while ($sub = $subjects->fetch_assoc()) {
    if ($fst)
        $subarr .= ", ";
    else
        $fst = true;

    $subarr .= "'" . $sub['subject'] . "'";
}

$subarr .= "];";
?>



<div class="flex flex-col items-center justify-around gap-5">
    <h1 class="title">Subject Combinations</h1>
    <div class="form-container">
        <!-- <h2 class="text-center text-primary">Add New Combination</h2> -->
        <div class="text-center">
            <?php
            if (isset($successMsg)) {
                echo "<p class='text-green-500'>$successMsg</p>";
            } elseif (isset($errorMsg)) {
                echo "<p class='text-red-500'>$errorMsg</p>";
            }
            ?>
        </div>
        <form action="" method="post" id="combinationForm" class="flex flex-col justify-center items-center gap-5 mt-8">
            <div class="w-full flex items-center gap-5">
                <input type="number" id="subjectCount" name="subjectCount" min="1" max="4"
                       placeholder="Number of Subjects" class="w-56 border border-gray-400 rounded-full py-2 px-5 outline-none focus:border-blue-500" required>
                <button type="button" id="generateButton" class="btn fill-btn">Generate</button>
            </div>

            <div id="dropdownContainer" class="mt-2 flex flex-col gap-2">
                <!-- Dropdowns will be generated here -->
            </div>
            <div id="addCombinationBtnContainer" class="mt-3" style="display: none;">
                <input type="submit" name="sub_comb" value="Add Combination" class="btn fill-btn">
            </div>
        </form>
    </div>

    <table class="w-96 mt-5 text-center">
        <tr class="h-10 bg-blue-100 font-semibold">
            <th>Combination ID</th>
            <th>Combination Name</th>
        </tr>
        <?php
        $getCombinationsQuery = "SELECT * FROM combination";
        $combinations = mysqli_query($con, $getCombinationsQuery);

        while ($combination = mysqli_fetch_assoc($combinations)) {
            echo "<tr class='h-10 odd:bg-blue-50'>";
            echo "<td>" . $combination['combinationID'] . "</td>";
            echo "<td>" . $combination['combinationName'] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>

</div>
<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>

<script>
    <?php echo $subarr . "\n"?>
    const subjectCountInput = document.getElementById('subjectCount');
    const generateButton = document.getElementById('generateButton');
    const combinationForm = document.getElementById('combinationForm');
    const dropdownContainer = document.getElementById('dropdownContainer');
    const addCombinationBtnContainer = document.getElementById('addCombinationBtnContainer');
    let generatedSubjects = 0; // Track the number of generated subjects
    let maxSubjects = subjectCountInput.getAttribute('max');
    generateButton.addEventListener('click', generateDropdowns);
    subjectCountInput.addEventListener('input', updateSubjectCount);

    function updateSubjectCount() {
        if (subjectCountInput.value > maxSubjects) {
            subjectCountInput.value = maxSubjects;
        }
    }

    function generateDropdowns() {
        generatedSubjects = parseInt(subjectCountInput.value); // Update the generated subjects count
        dropdownContainer.innerHTML = ''; // Clear existing dropdowns

        for (let i = 1; i <= generatedSubjects; i++) {
            const div = document.createElement('div');
            div.className = "flex items-center gap-4";
            const label = document.createElement('label');
            label.textContent = `Subject ${i}: `;

            const dropdown = document.createElement('select');
            dropdown.name = `subject${i}`;
            dropdown.className = "w-56 border border-gray-400 rounded-full py-2 px-5 outline-none focus:border-blue-500";

            for (let j = 0; j < subjects.length; j++) {
                const option = document.createElement('option');
                option.value = subjects[j];
                option.textContent = subjects[j];
                dropdown.appendChild(option);
            }

            div.appendChild(label);
            div.appendChild(dropdown);
            dropdownContainer.appendChild(div);
            // Show the "Add Combination" button after generating dropdowns
            addCombinationBtnContainer.style.display = 'block';
        }
    }

    combinationForm.addEventListener('submit', function (event) {
        const dropdowns = dropdownContainer.getElementsByTagName('select');
        const inpValue = parseInt(subjectCountInput.value);
        let selectedCount = 0;

        for (let i = 0; i < dropdowns.length; i++) {
            if (dropdowns[i].value) {
                selectedCount++;
            }
        }

        if (selectedCount === 0) {
            event.preventDefault(); // Prevent form submission
            alert("Please select at least one subject.");
        }
        if (selectedCount !== inpValue) {
            event.preventDefault(); // Prevent form submission
            alert("Subject count and dropdown count do not match.");
        }
    });
</script>

