# ERS-Web_Technologies

<h3>Team Details</h3>
<ul>
<li></li>

</ul>

Then run the apache server and mysql. And then create a database called `ers_fos_db` and import the `ers_fos_db.sql` file.
then go to the `http://localhost/ERS-Web_Technologies/` to access the ERS website</br></br>

Once you clone this repository you need to install the comsoper software. <a href="https://getcomposer.org/download/">Click here to download</a>
</br></br>

if you dont download `vendor` folder, then folow the bellow step. otherwise skip this step and do the next one:</br>
Then open the project file in composer teminal. (open the composer terminal and then `cd C:/xampp/htdocs/ERS-Web_Technologies` and then enter).</br>
then type `composer install` to install the neccesary libraries and packages. </br>

Then enable these extensions in your `php.ini` file (`C:\xampp\php`).</br>

<ol><li>extension=gd</li><li>extension=fileinfo</li><li>extension=zip</li></ol></br>

To enable this, you have to do the following:</br>

<ol>
<li>located the file `c:/xampp/php/php`</li>
<li>open the file</li>
<li>search for `extension=fileinfo` and `extension=gd` and `extension=zip`</li>
<li>if your see `;extension=fileinfo`, `;extension=gd`, `;extension=zip`. Remove the semicolon so it will be like this: `extension=fileinfo`, `extension=gd`, `extension=zip`</li>
<li>if you don't find any result then simply write `extension=fileinfo` `extension=gd` `extension=zip` among the `extensions`</li>
<li>Close the file and run the restart the apache server again</li>
</ol>


<h2>Exam Registration steps : How to use?</h2>
<ol>
    <li>
        <ol>
            <li>Login using master admin account
            <ul>
            <li>User Name:admin_master@nexus.com</li>
            <li>Password:Abcd1234@</li>
            </ul>
            <li>Create exam or edit exams => change exam state</li>
            <li>Add specific Students to particular exam  registration (repeat / medical)</li>
        </ol>
    </li>
    <li>
        <ol>
            <li>Login using student admin account
            <ul>
            <li>User Name:stud_admin1@nexus.com</li>
            <li>Password:stud_admin1@nexus</li>
            </ul>
            <li>Can able to add students => one by one or bulk upload using <b>Excel sheet</b></li>
            <li>Can able to assign index no to students => bulk upload using <b>Excel sheet</b></li>
            <li>Can able view repeat students payment slips and senate letter then verify them</li>
        </ol>
    </li>
    <li>
        <ol>
            <li>Login using subject admin account
            <ul>
            <li>User Name:subj_admin1@nexus.com</li>
            <li>Password:subj_admin1@nexus</li>
            </ul>
            <li>Can able to add subjects</li>
            <li>Can able to add combinations</li>
            <li>Can able to add units for subjects</li>
            <li>Can able to assign units for exam</li>
        </ol>
    </li>
    <li>
        <ol>
            <li>Login using student account / register</li>
            <li>Students can only register for exams if they are able (proper / repeat)</li>
            <li>Can able to view final registration list after the form closed</li>
        </ol>
    </li>
</ol>
