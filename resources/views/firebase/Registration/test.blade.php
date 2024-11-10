<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collapsible Inline Form</title>
    <style>
        /* Button style */
        .toggle-btn {
            cursor: pointer;
            padding: 8px 12px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            margin-bottom: 10px;
        }

        /* Form container style for hiding */
        .form-container {
            display: none; /* Hidden by default */
            margin-top: 10px;
        }

        /* Inline form styling */
        .inline-form {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .inline-form input,
        .inline-form button {
            padding: 8px;
            font-size: 16px;
        }
    </style>
</head>
<body>

    <!-- Button to toggle form visibility -->
    <button class="toggle-btn" onclick="toggleForm()">Toggle Form</button>

    <!-- Collapsible form container -->
    <div class="form-container" id="formContainer">
        <form class="inline-form">
            <input type="text" placeholder="Username">
            <input type="password" placeholder="Password">
            <button type="submit">Sign In</button>
        </form>
    </div>

    <script>
        // JavaScript function to toggle form visibility
        function toggleForm() {
            const formContainer = document.getElementById("formContainer");
            if (formContainer.style.display === "none" || formContainer.style.display === "") {
                formContainer.style.display = "flex";
            } else {
                formContainer.style.display = "none";
            }
        }
    </script>
</body>
</html>
