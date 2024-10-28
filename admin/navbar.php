<nav
        id="navBar"
        class="fixed top-0 bottom-0 lg:left-0 py-5 px-7 w-[300px] bg-white drop-shadow-sm transition-all z-50">
    <div class="flex items-center justify-between">
        <img
                src="../assets/img/logo/ERS_logo.gif"
                alt="Logo"
                class="w-28 transition-all"
                id="nav-logo"/>
        <i
                onclick="toggleNavBar()"
                id="open-close-btn"
                class="bi bi-arrow-bar-left text-xl py-1 px-2 rounded-lg bg-blue-200 text-black cursor-pointer transition-all"></i>
    </div>

    <hr class="bg-gray-700 my-5"/>

    <div class="flex flex-col gap-5 " id="navLinks">
        <a
                id="dashboardLink"
                href="../admin"
                class="flex items-center gap-4 w-full bg-gray-100 rounded-xl py-2.5 px-4 cursor-pointer transition-all hover:bg-gray-200 hover:text-black font-semibold text-gray-700">
            <i class="bi bi-boxes text-xl"></i>
            <span class="transition-all">Dashboard</span>
        </a>

        <a
                id="dashboardLink"
                href="index.php?page=profile"
                class="flex items-center gap-4 w-full bg-gray-100 rounded-xl py-2.5 px-4 cursor-pointer transition-all hover:bg-gray-200 hover:text-black font-semibold text-gray-700">
            <i class="bi bi-person-lines-fill text-xl"></i>
            <span class="transition-all">Profile</span>
        </a>

        <?php if ($_SESSION['role'] == "Admin_Student") { ?>
            <a
                    id="dashboardLink"
                    href="index.php?page=stud"
                    class="flex items-center gap-4 w-full bg-gray-100 rounded-xl py-2.5 px-4 cursor-pointer transition-all hover:bg-gray-200 hover:text-black font-semibold text-gray-700">
                <i class="bi bi-people-fill text-xl"></i>
                <span class="transition-all">Students</span>
            </a>
            <a
                    id="dashboardLink"
                    href="index.php?page=attendence"
                    class="flex items-center gap-4 w-full bg-gray-100 rounded-xl py-2.5 px-4 cursor-pointer transition-all hover:bg-gray-200 hover:text-black font-semibold text-gray-700">
                <i class="bi bi-people-fill text-xl"></i>
                <span class="transition-all">Attendence</span>
            </a>
            <?php
            $getCurrentExam = "SELECT * FROM exam_reg WHERE status = 'draft'";
            $result = mysqli_query($con, $getCurrentExam);

            if ($result->num_rows > 0) {
                $curExam = mysqli_fetch_assoc($result);
            }
            ?>
            <?php if (isset($curExam)) { ?>
                <a
                        id="dashboardLink"
                        href="index.php?page=addIndex"
                        class="flex items-center gap-4 w-full bg-gray-100 rounded-xl py-2.5 px-4 cursor-pointer transition-all hover:bg-gray-200 hover:text-black font-semibold text-gray-700">
                    <i class="bi bi-pencil-square text-xl"></i>
                    <span class="transition-all">Add Index Numbers</span>
                </a>
            <?php }

            $getCurrentExam = "SELECT * FROM exam_reg WHERE status = 'registration' or status = 'closed'";
            $result = mysqli_query($con, $getCurrentExam);

            if ($result->num_rows > 0) {
                $onGoingExam = mysqli_fetch_assoc($result);
            }
            ?>
            <?php if (isset($onGoingExam)) { ?>
                <a
                        id="dashboardLink"
                        href="index.php?page=slips"
                        class="flex items-center gap-4 w-full bg-gray-100 rounded-xl py-2.5 px-4 cursor-pointer transition-all hover:bg-gray-200 hover:text-black font-semibold text-gray-700">
                    <i class="bi bi-credit-card-2-front text-xl"></i>
                    <span class="transition-all">Repeat Payment Slips</span>
                </a>
            <?php }
            if (isset($onGoingExam)) { ?>
                <a
                        id="dashboardLink"
                        href="index.php?page=senate"
                        class="flex items-center gap-4 w-full bg-gray-100 rounded-xl py-2.5 px-4 cursor-pointer transition-all hover:bg-gray-200 hover:text-black font-semibold text-gray-700">
                    <i class="bi bi-file-text text-xl"></i>
                    <span class="transition-all">Senate approval letter</span>
                </a>
            <?php } ?>
        <?php } ?>

        <?php if ($_SESSION['role'] == "Admin_Subject") { ?>
            <a
                    id="dashboardLink"
                    href="index.php?page=subj"
                    class="flex items-center gap-4 w-full bg-gray-100 rounded-xl py-2.5 px-4 cursor-pointer transition-all hover:bg-gray-200 hover:text-black font-semibold text-gray-700">
                <i class="bi bi-list-nested text-xl"></i>
                <span class="transition-all">Subjects</span>
            </a>

            <a
                    id="dashboardLink"
                    href="index.php?page=subComb"
                    class="flex items-center gap-4 w-full bg-gray-100 rounded-xl py-2.5 px-4 cursor-pointer transition-all hover:bg-gray-200 hover:text-black font-semibold text-gray-700">
                <i class="bi bi-intersect text-xl"></i>
                <span class="transition-all">Subject combination</span>
            </a>
            <a
                    id="dashboardLink"
                    href="index.php?page=units"
                    class="flex items-center gap-4 w-full bg-gray-100 rounded-xl py-2.5 px-4 cursor-pointer transition-all hover:bg-gray-200 hover:text-black font-semibold text-gray-700">
                <i class="bi bi-journal-plus text-xl"></i>
                <span class="transition-all">Units</span>
            </a>
            <?php if (isset($curExam)) { ?>
                <a
                        id="dashboardLink"
                        href="index.php?page=asignUnits"
                        class="flex items-center gap-4 w-full bg-gray-100 rounded-xl py-2.5 px-4 cursor-pointer transition-all hover:bg-gray-200 hover:text-black font-semibold text-gray-700">
                    <i class="bi bi-pencil-square text-xl"></i>
                    <span class="transition-all">Exam Units</span>
                </a>
            <?php } ?>
        <?php } ?>


        <a
                id="dashboardLink"
                href="../logout.php"
                class="flex items-center gap-4 w-full bg-gray-100 rounded-xl py-2.5 px-4 cursor-pointer transition-all hover:bg-gray-200 hover:text-black font-semibold text-gray-700">
            <i class="bi bi-box-arrow-left text-xl"></i>
            <span class="transition-all">Logout</span>
        </a>
    </div>

    <hr class="bg-gray-700 my-10"/>

    <a
            id="profile-sec"
            href="index.php?page=profile"
            class="fixed bottom-2 w-10/12 mx-auto bg-blue-200 rounded-xl flex items-center gap-3 py-1 px-4 cursor-pointer overflow-x-hidden">
        <i
                class="user-icon bi bi-person-fill text-2xl py-0.5 px-2 rounded-lg bg-white text-blue-600"></i>
        <div class="name-email leading-tight">
            <h4 class="text-black font-bold text-sm"><?php echo $userprofname ?></h4>
            <p class="text-xs font-semibold text-gray-700"><?php echo $_SESSION['userid'] ?></p>
        </div>
    </a>
</nav>

<script>
    function toggleNavBar() {
        var navBarLogo = document.getElementById("nav-logo");
        var navBar = document.getElementById("navBar");
        var navBarSibling = document.getElementById("nextSibling");
        var dashLinks = document.querySelectorAll("[id='dashboardLink']");

        navBar.classList.toggle("open");

        if (navBarSibling) {
            navBarSibling.classList.toggle("remove-ml-300");
        }

        if (
            navBarLogo.src === "../assets/img/logo/ERS_logo.gif"
        ) {
            setTimeout(function () {
                navBarLogo.setAttribute(
                    "src",
                    "<?php echo $rpath ?>../assets/img/logo/ERS_logo_icon.ico"
                );
            }, 75);
        } else {
            navBarLogo.setAttribute("src", "<?php echo $rpath ?>../assets/img/logo/ERS_logo.gif");
        }
    }


</script>

