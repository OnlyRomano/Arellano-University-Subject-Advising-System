function updateDescriptionAndUnit() {
    var selectedCode = document.getElementById("code").value;
    var description = subjectInfo[selectedCode] ? subjectInfo[selectedCode].description : '';
    var unit = subjectInfo[selectedCode] ? subjectInfo[selectedCode].unit : '';
    document.getElementById("description").textContent = description;
    document.getElementById("unit").textContent = unit;
}


function showMessage() {
    alert("Grade created");
    window.location.href = "grade-management.php";
}

function showMessage() {
    alert("Grade updated");
    window.location.href = "grade-management.php";
}