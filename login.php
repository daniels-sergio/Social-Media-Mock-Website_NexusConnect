<?php
session_start();
include("database.php");

if($_SERVER['REQUEST_METHOD'] == "POST"){
    if(isset($_POST["create_account"])){
        $Fullname = $_POST["full_name"];
        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $confirmedpassword = $_POST["confPassword"];
        $hash = password_hash($password,PASSWORD_DEFAULT); //Hashes password using the password_hash function as per scope requirements 

        $sqlEmail = "SELECT * FROM users WHERE email = '$email' ";
        $sqlUsername = "SELECT * FROM users WHERE username = '$username'";

        $emailResult = mysqli_query($conn,$sqlEmail);
        $usernameResult = mysqli_query($conn,$sqlUsername);

        if(mysqli_num_rows($emailResult) > 0 ){ //Checks if there will be more than one user with that email,username or if the passwords dont match
            echo "<script> window.alert('Account already exists with this email')</script>";
        }elseif(mysqli_num_rows($usernameResult) > 0){
            echo "<script> window.alert('Username already taken') </script>";
        }elseif($password != $confirmedpassword){
            echo "<script> window.alert('Password do not match') </script>";
        }else{
            $sql = "INSERT INTO users (username,email,password,full_name)
                    values ('$username','$email','$hash','$Fullname')";
            try{
                mysqli_query($conn,$sql);
            }catch(mysqli_sql_exception){
                echo "could not register user";
            }
        }
    }
    
    if(isset($_POST["login"])){
        $login_identifier = $_POST["login_identifier"];
        $password = $_POST["password"];

        $sql = "SELECT * from users WHERE username = ? or email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $login_identifier, $login_identifier);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if(mysqli_num_rows($result) > 0 ){
            $row = mysqli_fetch_assoc($result);

            if(password_verify($password, $row['password'])){
                $_SESSION['logged_in'] = true;
                $_SESSION['username'] = $row["username"];
                $_SESSION["full_name"] = $row["full_name"];
                $_SESSION["profile_picture"] = $row["profile_picture"] ?? 'default.jpg';
                $_SESSION["email"] = $row["email"] ?? '';
                
                echo "<script>window.alert('Successful login')</script>";
                header("location: Index.php");
                exit;
            }else{
                echo "<script>window.alert('Incorrect password')</script>";
            }
        }else{
            echo "<script>window.alert('User not found')</script>";
        }
        mysqli_stmt_close($stmt);
    }
}


 
  

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NexusConnect - Join the Future</title>
    <link rel="stylesheet" type="text/css" href="Styles/login_styles.css">
</head>
<body>
    <!-- Background Animation -->
    <div class="background-animation">
        <div class="circuit-line"></div>
        <div class="circuit-line"></div>
        <div class="circuit-line"></div>
        <div class="glow-orb glow-orb-1"></div>
        <div class="glow-orb glow-orb-2"></div>
        <div class="glow-orb glow-orb-3"></div>
    </div>

    <!-- Auth Container -->
    <div class="auth-container">
        <!-- Left Side - Branding -->
        <div class="auth-branding">
            <div class="logo-large">NexusConnect</div>
            <p class="tagline">Connect Beyond Boundaries. Experience the Future of Social Networking.</p>
            
            <ul class="feature-list">
                <li class="feature-item">
                    <div class="feature-icon">
                        <span class="feature-arrow"></span>
                    </div>
                    <span class="feature-text">Connect with millions worldwide</span>
                </li>
                <li class="feature-item">
                    <div class="feature-icon">
                        <span class="feature-arrow"></span>
                    </div>
                    <span class="feature-text">Share moments that matter</span>
                </li>
                <li class="feature-item">
                    <div class="feature-icon">
                        <span class="feature-arrow"></span>
                    </div>
                    <span class="feature-text">Verified & secure platform</span>
                </li>
                <li class="feature-item">
                    <div class="feature-icon">
                        <span class="feature-arrow"></span>
                    </div>
                    <span class="feature-text">Real-time notifications</span>
                </li>
            </ul>
        </div>

        <!-- Right Side - Forms -->
        <div class="auth-forms">
            <div class="form-container">
                <!-- Form Tabs -->
                <div class="form-tabs">
                    <button class="tab-button active" data-tab="login">Sign In</button>
                    <button class="tab-button" data-tab="signup">Sign Up</button>
                </div>

                <!-- Error/Success Messages -->
                <div class="error-message" id="errorMessage"></div>
                <div class="success-message" id="successMessage"></div>

                <!-- Login Form -->
                <form action="" method="post" class="auth-form active" id="loginForm">
                    <div class="form-group">
                        <label class="form-label">Email or Username</label>
                        <input name="login_identifier" type="text" class="form-input" placeholder="Enter your email or username" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="password-input-wrapper">
                            <input name="password" type="password" class="form-input" id="loginPassword" placeholder="Enter your password" required>
                            <button type="button" class="toggle-password" onclick="togglePassword('loginPassword')">
                            </button>
                        </div>
                    </div>
                    <!-- Remember me to mimic real profile login options -->
                    <div class="form-options"> 
                        <div class="checkbox-wrapper">
                            <input type="checkbox" id="rememberMe">
                            <label for="rememberMe">Remember me</label>
                        </div>
                    </div>

                    <button name="login" type="submit" class="submit-button">Sign In</button>
                </form>

                <!-- Signup Form -->
                <form class="auth-form" id="signupForm" method="post" action="">
                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input name="full_name" type="text" class="form-input" placeholder="Enter your full name" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input name="email" type="email" class="form-input" placeholder="Enter your email" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Username</label>
                        <input name="username" type="text" class="form-input" placeholder="Choose a username" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="password-input-wrapper">
                            <input name="password" type="password" class="form-input" id="signupPassword" placeholder="Create a password" required>
                            <button type="button" class="toggle-password" onclick="togglePassword('signupPassword')">
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <div class="password-input-wrapper">
                            <input name="confPassword" type="password" class="form-input" id="confirmPassword" placeholder="Confirm your password" required>
                            <button type="button" class="toggle-password" onclick="togglePassword('confirmPassword')">
                            </button>
                        </div>
                    </div>

                    <div class="form-options">
                        <div class="checkbox-wrapper">
                            <input type="checkbox" id="agreeTerms" required>
                            <label for="agreeTerms">I agree to Terms & Privacy Policy</label>
                        </div>
                    </div>

                    <button name="create_account" type="submit" class="submit-button">Create Account</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Tab switching functionality
        const tabButtons = document.querySelectorAll('.tab-button');
        const authForms = document.querySelectorAll('.auth-form');

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const targetTab = button.getAttribute('data-tab');
                
                // Removes active class from all tabs and forms
                tabButtons.forEach(btn => btn.classList.remove('active'));
                authForms.forEach(form => form.classList.remove('active'));
                
                // Adds active class to tabs and forms
                button.classList.add('active');
                document.getElementById(targetTab + 'Form').classList.add('active');
                
                // Clear messages
                hideMessage('error');
                hideMessage('success');
            });
        });

        // Password visibility toggle
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
        }

        // Show message function
        function showMessage(type, message) {
            const messageElement = document.getElementById(type + 'Message');
            messageElement.textContent = message;
            messageElement.classList.add('show');
            
            setTimeout(() => {
                hideMessage(type);
            }, 5000);
        }

        // Hide message function
        function hideMessage(type) {
            const messageElement = document.getElementById(type + 'Message');
            messageElement.classList.remove('show');
        }

        // Input validation - real-time feedback
        const inputs = document.querySelectorAll('.form-input');
        inputs.forEach(input => {
            input.addEventListener('blur', () => {
                if (input.value && !input.checkValidity()) {
                    input.style.borderColor = 'var(--accent)';
                } else if (input.value) {
                    input.style.borderColor = 'var(--secondary)';
                }
            });
            
            input.addEventListener('focus', () => {
                input.style.borderColor = 'var(--gold)';
            });
        });

        // Email validation
        const emailInputs = document.querySelectorAll('input[type="email"]');
        emailInputs.forEach(input => {
            input.addEventListener('blur', () => {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (input.value && !emailRegex.test(input.value)) {
                    showMessage('error', 'Please enter a valid email address!');
                    input.style.borderColor = 'var(--accent)';
                }
            });
        });

        // Username validation -- interms of length
        const signupUsername = document.querySelector('#signupForm input[name="username"]');
        if(signupUsername) {
            signupUsername.addEventListener('input', (e) => {
                e.target.value = e.target.value.replace(/[^a-zA-Z0-9_]/g, '');
            });
            
            signupUsername.addEventListener('blur', () => {
                if (signupUsername.value && (signupUsername.value.length < 3 || signupUsername.value.length > 20)) {
                    showMessage('error', 'Username must be between 3-20 characters!');
                    signupUsername.style.borderColor = 'var(--accent)';
                }
            });
        }

        // Prevent form resubmission on page refresh
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }

        
    </script>

    
</body>
</html>