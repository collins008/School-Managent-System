// Wait until the page loads
document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const studentIdInput = document.getElementById("studentId");
    const reportTypeSelect = document.getElementById("reportType");

    form.addEventListener("submit", function (event) {
        let errors = [];

        // Validate Student ID
        if (studentIdInput.value.trim() === "" || isNaN(studentIdInput.value)) {
            errors.push("Please enter a valid Student ID.");
        }

        // Validate Report Type
        if (reportTypeSelect.value.trim() === "") {
            errors.push("Please select a report type.");
        }

        // If errors exist, stop form submission
        if (errors.length > 0) {
            event.preventDefault();
            alert(errors.join("\n"));
        }
    });

    // Optional: dynamic feedback
    studentIdInput.addEventListener("input", function () {
        if (isNaN(studentIdInput.value)) {
            studentIdInput.style.borderColor = "red";
        } else {
            studentIdInput.style.borderColor = "green";
        }
    });
});
