<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internship Request / Comment</title>
    <link rel="stylesheet" href="style.css">
    <style>
        form {
            width: 900px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 2px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        input, select, textarea {
            width: 97%;
            padding: 12px;
            margin-bottom: 4px;
            border: 2px solid #e1e1e1;
            border-radius: 6px;
            transition: border-color 0.3s ease;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #007BFF;
            box-shadow: 0 0 5px rgba(0,123,255,0.2);
        }

        textarea {
            resize: vertical;
            min-height: 120px;
        }

        button {
            background-color: #007BFF;
            color: #fff;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            cursor: pointer;
            width: 200px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: background-color 0.3s ease;
            display: block;
            margin: 5px auto 0;
        }

        button:hover {
            background-color: #0056b3;
            transform: translateY(-1px);
        }

        .radio-group {
            display: flex;
            gap: 20px;
            margin-top: 10px;
        }

        .radio-option {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .radio-option input[type="radio"] {
            width: auto;
            margin: 0;
        }

        .error-message {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <header class='header'>
        <a href="home" class="logo">Ametal <span>Tech Sdn. Bhd</span></a>
        <h1>Internship Request / Comment</h1>
    </header>
    <?php include 'navigation.php'; ?>

    <main>
        <section id="profile-details">
            <?php
            if (isset($_GET['status']) && $_GET['status'] === 'success') {
                echo '<div class="success-message">Your request has been submitted successfully!</div>';
            }
            ?>
            
            <form action="process_request.php" method="post" onsubmit="return validateForm()">
                <div class="form-group">
                <label for="id" style="display: block; width: 97%; margin-bottom: 5px;">Intern ID:</label>
                <input type="text" id="id" name="id" placeholder="Enter your ID" required style="width: 97%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 5px;">
                <span class="error-message" id="id-error" style="color: red; font-size: 0.9em;"></span>

                </div>

                <div class="form-group">
                    <label for="email">Your Email:</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email address" required>
                    <span class="error-message" id="email-error"></span>
                </div>

                <div class="form-group">
                    <label for="admin">To (Supervisor Email):</label>
                    <input type="email" id="admin" name="admin" placeholder="Enter the recipient's email address" required>
                    <span class="error-message" id="admin-error"></span>
                </div>

                <div class="form-group">
                    <label>Request Type:</label>
                    <div class="radio-group">
                        <label class="radio-option">
                            <input type="radio" name="type" value="request" required>
                            Request
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="type" value="comment" required>
                            Comment
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="subject" style="display: block; width: 97%; margin-bottom: 5px;">Subject:</label>
                    <input type="text" id="subject" name="subject" placeholder="Enter the subject of your request/comment" required style="width: 97%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 5px;">
                    <span class="error-message" id="subject-error" style="color: red; font-size: 0.9em;"></span>
                </div>

                <div class="form-group">
                    <label for="details">Details:</label>
                    <textarea id="details" name="details" placeholder="Enter detailed information about your request/comment" required></textarea>
                    <span class="error-message" id="details-error"></span>
                </div>

                <button type="submit">Submit Request</button>
            </form>
        </section>
    </main>

    <?php include 'footer.php'; ?>

    <script>
    function validateForm() {
        let isValid = true;
        const id = document.getElementById('id').value.trim();
        const email = document.getElementById('email').value.trim();
        const admin = document.getElementById('admin').value.trim();
        const subject = document.getElementById('subject').value.trim();
        const details = document.getElementById('details').value.trim();

        document.querySelectorAll('.error-message').forEach(elem => elem.textContent = '');

        if (id.length < 3) {
            document.getElementById('id-error').textContent = 'ID must be at least 3 characters long';
            isValid = false;
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            document.getElementById('email-error').textContent = 'Please enter a valid email address';
            isValid = false;
        }

        if (!emailRegex.test(admin)) {
            document.getElementById('admin-error').textContent = 'Please enter a valid admin email address';
            isValid = false;
        }

        if (subject.length < 5) {
            document.getElementById('subject-error').textContent = 'Subject must be at least 5 characters long';
            isValid = false;
        }

        if (details.length < 20) {
            document.getElementById('details-error').textContent = 'Please provide more detailed information (at least 20 characters)';
            isValid = false;
        }

        return isValid;
    }
    </script>
</body>
</html>