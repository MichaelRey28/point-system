<!DOCTYPE html>
<html lang="en">
<head>
    <title>Welcome | Scoring System</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center; 
    }

    .container {
        text-align: center; 
    }

        button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            background: #007bff;
            color: white;
            border-radius: 5px;
        }
        button:hover {
            background: #0056b3;
        }

        /* Modal Styling */
        .modal {
            display: none;
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            padding: 20px;
            width: 90%;
            max-width: 350px; 
            box-shadow: 0px 0px 10px gray;
            border-radius: 8px;
            text-align: left;
            position: relative;
            animation: fadeIn 0.3s ease-in-out;
            display: flex;
            flex-direction: column;
            gap: 10px;
            box-sizing: border-box; 
            overflow: hidden; 
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .close {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 20px;
            cursor: pointer;
            color: #555;
        }
        .close:hover {
            color: black;
        }

        .modal-content input, .modal-content button {
            width: 100%;
            padding: 12px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .modal-content button {
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        .modal-content button:hover {
            background: #218838;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>MINI PROJECT USING CODEIGNITE</h1>
    <p>CRUD Point System</p>
    <button onclick="showModal('loginModal')">Login</button>
    <button onclick="showModal('registerModal')">Register</button>
</div>

<!-- Login Modal / POP UP LOGIN -->
<div id="loginModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="hideModal('loginModal')">&times;</span>
        <h2>Login</h2>
        <form action="/login" method="post">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</div>

<!-- Register Modal POP UP REGISTER-->
<div id="registerModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="hideModal('registerModal')">&times;</span>
        <h2>Register</h2>
        <form action="/register" method="post">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
        </form>
    </div>
</div>

<script>
    function showModal(id) {
        document.getElementById(id).style.display = 'flex';
    }

    function hideModal(id) {
        document.getElementById(id).style.display = 'none';
    }

    window.onclick = function(event) {
        let loginModal = document.getElementById('loginModal');
        let registerModal = document.getElementById('registerModal');
        
        if (event.target === loginModal) loginModal.style.display = "none";
        if (event.target === registerModal) registerModal.style.display = "none";
    }
</script>

</body>
</html>
