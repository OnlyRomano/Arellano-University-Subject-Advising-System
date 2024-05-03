<?php
session_start();
require_once "config.php";
// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['usertype']);
}

// Function to check if user is an administrator
function isAdmin() {
    return isset($_SESSION['usertype']) && $_SESSION['usertype'] == 'ADMINISTRATOR';
}

// Function to check if user is a registrar/staff
function isRegistrarOrStaff() {
    return isset($_SESSION['usertype']) && ($_SESSION['usertype'] == 'REGISTRAR' || $_SESSION['usertype'] == 'STAFF');
}

// Redirect users to login page if not logged in
if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$usertype = $_SESSION['usertype'];

// Set visibility flags based on user type
$show_accounts = $show_students = $show_subjects = $show_grades = $show_logs = $show_about = false;

if ($usertype == "ADMINISTRATOR") {
    $show_accounts = $show_students = $show_subjects = $show_grades = $show_logs = $show_about = true;
} elseif ($usertype == "REGISTRAR") {
    $show_students = $show_subjects = $show_grades = $show_about = true;
}

$availableSubjects = [];

if ($usertype == "STUDENT") {
    $student_id = $_SESSION['username']; // Assuming the username is the student ID

    // Fetch subjects where prerequisites are met but the subject itself hasn't been graded
    $sql = "SELECT s.code, s.description FROM tblsubjects s 
            WHERE NOT EXISTS (
                SELECT 1 FROM tblgrades g WHERE g.code = s.code AND g.studentnumber = ? AND g.grade IS NOT NULL
            ) AND NOT EXISTS (
                SELECT 1 FROM tblsubjects pr
                LEFT JOIN tblgrades g ON g.code = pr.code AND g.studentnumber = ?
                WHERE pr.code IN (s.prerequisite1, s.prerequisite2, s.prerequisite3) AND (g.grade IS NULL OR g.grade = '')
            )";
    
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ss", $student_id, $student_id);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            while ($row = mysqli_fetch_assoc($result)) {
                $availableSubjects[] = $row;
            }
        } else {
            echo "Error retrieving available subjects.";
        }
    } else {
        echo "Error preparing the SQL statement.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arellano University Subject Advancing System - AUSAS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" ></script>
    <style>
        body {
            margin: 0;
            padding-top: 70px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, Fira Sans, "Droid Sans", "Helvetica Neue", sans-serif;
        }

        header {
            background-color: #0020C2;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            width: 60px;
            height: auto;
            margin-right: 10px;
        }

        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
        }

        nav li {
            margin-right: 20px;
            background-color: #021a96;
            padding: 10px;
            border-radius: 4px;
            box-shadow: 0px 2px 5px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }

        nav a {
            color: #fff;
            text-decoration: none;
        }

        nav li:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.5);
        }

        footer {
            background-color: #0020C2;
            padding: 10px;
            border-top: 1px solid #ddd;
        }

        .footer-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #fff;
        }

        .footer-right a {
            color: #fff;
            text-decoration: none;
            margin-right: 10px;
        }

        .footer-right a:hover{
            text-decoration: underline;
        }

        .logo-text {
            margin-top: 15px;
            margin-right: auto; 
        }

        .label {
            background-color: #0020C2;
            padding: 10px;
            margin-bottom: 0;
            color: white;
            font-size: 18px;
            text-align: center;
        }

        .wel {
            text-align: center;
            font-style: italic;
            margin: 20px;
        }

        .card {
            display: inline-block;
            margin: 5px;
            padding: 10px;
        }

        .carousel {
            
            padding: 5px;
        }

        .programs h3{
            text-align: center;
            color: navy;
            font-style: oblique;
        }

        .programs p {
            text-align: center;
            color: gray;
        }

        .programs {
            padding-top: 70px;
        }

        .fixed-header {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        /* Dropdown Button */
        .dropbtn {
            background-color: #021a96;
            color: white;
            padding: 10px;
            font-size: 16px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            box-shadow: 0px 2px 5px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }

        /* Dropdown button on hover & focus */
        .dropbtn:hover, .dropbtn:focus {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.5);
        }

        /* The container <div> - needed to position the dropdown content */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        /* Dropdown Content (Hidden by Default) */
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f1f1f1;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            left: -25px; /* Adjust this value as needed */
        }

        /* Links inside the dropdown */
        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        /* Change color of dropdown links on hover */
        .dropdown-content a:hover {
            background-color: #ddd;
        }

        /* Show the dropdown menu (use JS to add this class to the .dropdown-content container when the user clicks on the dropdown button) */
        .show {
            display:block;
        }
</style>

</head>
<body>
<header class="fixed-header">
    <img src="au-logo.png" alt="Logo" class="logo">
    <p class="logo-text">Arellano University Subject Advancing System - AUSAS</p>
    <nav>
        <ul>
            <?php if (isAdmin()): ?>
                <li><a href="accounts-management.php">Accounts</a></li>
                <li><a href="students-management.php">Students</a></li>
                <li><a href="subjects-management.php">Subjects</a></li>
                <li><a href="grade-management.php">Grades</a></li>
                <li><a href="advising-subject.php">Advising</a></li>
                <div class="dropdown">
                <button onclick="myFunction()" class="dropbtn">
                    <?php echo "USER: ". $_SESSION['username']; ?>
                </button>
                <div id="myDropdown" class="dropdown-content">
                    <a href="logout.php">Logout</a> 
                </div>
            </div>
            <?php elseif (isRegistrarOrStaff()): ?>
                <li><a href="students-management.php">Students</a></li>
                <li><a href="subjects-management.php">Subjects</a></li>
                <li><a href="grade-management.php">Grades</a></li>
                <div class="dropdown">
                <button onclick="myFunction()" class="dropbtn">
                    <?php echo "USER: ". $_SESSION['username']; ?>
                </button>
                <div id="myDropdown" class="dropdown-content">
                    <a href="logout.php">Logout</a> 
                    <a href="change-password.php">Change Password</a>
                </div>
            </div>
            <?php else: ?>
                <li><a href="Grade.php">View Grades</a></li>
                <li><a href="subjectstobetaken.php">Advising Subjects</a></li>
                <div class="dropdown">
                <button onclick="myFunction()" class="dropbtn">
                    <?php echo "USER: ". $_SESSION['username']; ?>
                </button>
                <div id="myDropdown" class="dropdown-content">
                    <a href="logout.php">Logout</a> 
                    <a href="change-password.php">Change Password</a>
                </div>
            </div>
            <?php endif; ?>
        </ul>
    </nav>
</header><br><br>
 <div class="wel" >
    <h2>Welcome to Arellano University Subject Advancing System (AUSAS)</h2>
    <blockquote>
        The function of education is to teach one to think intensively and to think critically. Intelligence plus character - that is the goal of true education." - Martin Luther King Jr.
    </blockquote>
 </div><br><br>
<div class="carousel">
    <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="campus/juan-sumulong.jpg" class="d-block w-100" alt="..." style="height: 700px;">
                <p class="label">Juan Sumulong Campus (AU Legarda/Main)</p>
            </div>
            <div class="carousel-item">
                <img src="campus/abad-santos.jpg" class="d-block w-100" alt="..." style="height: 700px;">
                <p class="label">Jose Abad Santos Campus (AU Pasay)</p>
            </div>
            <div class="carousel-item">
                <img src="campus/andres-bonifacio.jpg" class="d-block w-100" alt="..." style="height: 700px;">
                <p class="label">Andres Bonifacio Campus (AU Pasig)</p>
            </div>
            <div class="carousel-item">
                <img src="campus/jose-rizal.jpg" class="d-block w-100" alt="..." style="height: 700px;">
                <p class="label">Jose Rizal Campus (AU Malabon)</p>
            </div>
            <div class="carousel-item">
                <img src="campus/plaridel-campus.jpg" class="d-block w-100" alt="..." style="height: 700px;">
                <p class="label">Plaridel Campus (AU Mandaluyong)</p>
            </div>
            <div class="carousel-item">
                <img src="campus/apolinario-mabini.jpg" class="d-block w-100" alt="..." style="height: 700px;">
                <p class="label">Apolinario Mabini Campus (AU Pasay)</p>
            </div>
            <div class="carousel-item">
                <img src="campus/elisa-campus.jpg" class="d-block w-100" alt="..." style="height: 700px;">
                <p class="label">Elisa Esguerra Campus (AU Malabon)</p>
            </div>
        </div>
    </div>
</div><br><br>

<section>
    <div class="programs">
        <h3>Undergraduate Programs</h3>
        <p>Equitable access to learning through relevant, innovative, industry-sensitive and environment-conscious academic programs and services.</p>
    </div>
    <div class="card" style="width: 18rem;">
        <img src="img/arts-an-acience.jpg" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title">College of Arts and Sciences</h5>
            <p class="card-text">The College of Arts and Sciences offers a challenging Liberal Arts education with a vibrant and diverse community of students from all walks of life.</p>
            <a href="#" class="btn btn-primary">Know More</a>
        </div>
     </div>
     <div class="card" style="width: 18rem;">
        <img src="img/crim.jpg" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title">College of Criminal Justice Education</h5>
            <p class="card-text">The College of Criminal Justice Education is a dynamic and significant institution in Arellano University that provides relevant and responsive education in the rapidly changing fields of criminology and criminal justice.</p>
            <a href="#" class="btn btn-primary">Know More</a>
        </div>
     </div>
     <div class="card" style="width: 18rem;">
        <img src="img/med-tech.jpg" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title">College of Medical Laboratory Science</h5>
            <p class="card-text">Bachelor of Science in Medical Technology/Bachelor in Medical Laboratory Science is a four year program consisting of general education subjects and professional subjects.</p>
            <a href="#" class="btn btn-primary">Know More</a>
        </div>
     </div>
     <div class="card" style="width: 18rem;">
        <img src="img/nursing.jpg" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title">College of Nursing</h5>
            <p class="card-text">The Bachelor of Science in Nursing program is a four-year program designed to prepare future professional nurses to provide optimum nursing care to individuals, families, and groups in any setting at any stage of the health-illness continuum.</p>
            <a href="#" class="btn btn-primary">Know More</a>
        </div>
     </div>
     <div class="card" style="width: 18rem;">
        <img src="img/pharmacy.jpg" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title">College of Pharmacy</h5>
            <p class="card-text">The College of Pharmacy offered a multi-faceted four–year program, Bachelor of Science in Pharmacy, as mandated by the Pharmacy Law, is composed of general education courses, core courses, and professional pharmacy courses.</p>
            <a href="#" class="btn btn-primary">Know More</a>
        </div>
     </div>
</section><br><br>

<section>
    <div class="card" style="width: 18rem;">
        <img src="img/PT.jpg" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title">College of Physical Therapy</h5>
            <p class="card-text">The Bachelor of Science in Physical Therapy (BSPT) program is a four-year degree program based on CHED Memorandum Order (CMO) No. 55 series of 2017 rationalizing the Physical Therapy Education in the country</p>
            <a href="#" class="btn btn-primary">Know More</a>
        </div>
     </div>
     <div class="card" style="width: 18rem;">
        <img src="img/radtech.jpg" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title">College of Radiologic Technology</h5>
            <p class="card-text">Radiologic Technology is an auxiliary branch of Radiology that deals with the technical application of radiation in the diagnosis and treatment of diseases.</p>
            <a href="#" class="btn btn-primary">Know More</a>
        </div>
     </div>
     <div class="card" style="width: 18rem;">
        <img src="img/BSIT.jpg" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title">Information Technology Education (ITE) Cluster</h5>
            <p class="card-text">ITE offers four-year programs that include the study of computing concepts and theories, algorithmic foundations</p>
            <a href="#" class="btn btn-primary">Know More</a>
        </div>
     </div>
     <div class="card" style="width: 18rem;">
        <img src="img/accountancy.jpg" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title">Institute of Accountancy</h5>
            <p class="card-text">The Institute of Accountancy prepare students for a career in the fields of accounting and to equipped them with the necessary tools to compete in the outside world and to deal effectively with the problems they will face as professional accountants and responsible citizens.</p>
            <a href="#" class="btn btn-primary">Know More</a>
        </div>
     </div>
     <div class="card" style="width: 18rem;">
        <img src="img/business-ad.jpg" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title">School of Business Administration</h5>
            <p class="card-text">The School of Business Administration gives the student a choice among four(4) majors leading to a Bachelor of Science in Business Administration degree.</p>
            <a href="#" class="btn btn-primary">Know More</a>
        </div>
     </div>
</section><br><br>

<section>
    <div class="card" style="width: 18rem;">
        <img src="img/business-commerce_0.jpg" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title">School of Business and Commerce</h5>
            <p class="card-text">The School of Business and Commerce of Arellano University offers you a challenging Business Education Program that will prepare its students to become a well-rounded effective and efficient manager in the corporate world, a competent leader in all walks of life as well as entrepreneurs in their chosen field of endeavor.</p>
            <a href="#" class="btn btn-primary">Know More</a>
        </div>
     </div>
     <div class="card" style="width: 18rem;">
        <img src="img/acct-tech.jpg" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title">School of Business Technology</h5>
            <p class="card-text">The Accounting Information System offers a four-year program that complies with the latest competency framework of professional accountants issued by the International Federation of Accountants through their International Education Standards.</p>
            <a href="#" class="btn btn-primary">Know More</a>
        </div>
     </div>
     <div class="card" style="width: 18rem;">
        <img src="img/com-sci_0.jpg" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title">School of Computer Studies</h5>
            <p class="card-text">In order to meet the new challenges of the 21st century Information Technology Education, as well as to achieve the ambition that the industrialization be promoted by modern and sophisticated devices, Arellano University is committed to train talents of new generation on Information Technology.</p>
            <a href="#" class="btn btn-primary">Know More</a>
        </div>
     </div>
     <div class="card" style="width: 18rem;">
        <img src="img/education.jpg" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title">School of Education</h5>
            <p class="card-text">The School of Education offers quality teacher education programs and provides excellent pre-service training for aspiring and prospective basic education.</p>
            <a href="#" class="btn btn-primary">Know More</a>
        </div>
     </div>
     <div class="card" style="width: 18rem;">
        <img src="img/hrm-tourism.jpg" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title">School of Hospitality and Tourism Management</h5>
            <p class="card-text">Arellano University’s School of Hospitality and Tourism Management (AU SHTM) has been delivering Hospitality and Tourism Management courses for aspiring students who wish to work in the hospitality and tourism business since 2006.</p>
            <a href="#" class="btn btn-primary">Know More</a>
        </div>
     </div>
</section><br><br>

<section>
    <div class="card" style="width: 18rem;">
        <img src="img/midwifery.jpg" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title">School of Midwifery</h5>
            <p class="card-text">The Midwifery is a health care profession in which providers offer care to childbearing women during pregnancy.</p>
            <a href="#" class="btn btn-primary">Know More</a>
        </div>
     </div>
     <div class="card" style="width: 18rem;">
        <img src="img/bs-psych.jpg" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title">School of Psychology</h5>
            <p class="card-text">Psychology is the scientific study of behavior and mental processes. In general, the emphasis is on the individual person and how the person’s mental processes and behavior are affected by internal, relational and social factors.</p>
            <a href="#" class="btn btn-primary">Know More</a>
        </div>
     </div>
</section><br><br><br>

<footer>
  <div class="footer-inner">
    <div class="footer-left">
      <p>ARELLANO UNIVERSITY</p>
      <p>2600 Legarda St. , Sampaloc , Manila 1008 Metro Manila , Philippines</p>
      <p>8-734-7371 to 79</p>
    </div>
    <div class="footer-right">
      <p><a href="about.html">About</a> | <a href="administration.html">Administration</a> | <a href="contact.html">Contact</a> | <a href="community-development.html">Community Development</a> | <a href="dataprivacy.html">Data Privacy</a> | <a href="trademark-policy.html">Trade Mark Policy</a> | <a href="dpo-dps.html">DPO/DPS</a></p>
      <p>NPC Registration No. PIC-002-370-2023</p>
    </div>
  </div>
</footer>
<script>
    /* When the user clicks on the button,
toggle between hiding and showing the dropdown content */
function myFunction() {
  document.getElementById("myDropdown").classList.toggle("show");
}

// Close the dropdown menu if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}
</script>
</body>
</html>