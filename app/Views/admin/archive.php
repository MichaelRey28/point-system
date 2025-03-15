<!DOCTYPE html>
<html lang="en">
<head>
    <title>Archived Records</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1, h2 {
            color: #333;
        }

        .nav-links {
            margin: 20px 0;
        }

        .nav-links a {
            display: inline-block;
            padding: 0 20px;
            font-size: 16px;
            color: #333;
            text-decoration: none;
            position: relative;
        }

        .nav-links a:not(:last-child)::after {
            content: '|';
            position: absolute;
            right: -3px;
            color: #666;
        }

        .nav-links a:hover {
            color: #007bff;
        }

        .buttons {
            margin: 20px 0;
        }

        button {
            margin: 5px;
            padding: 12px 18px;
            font-size: 16px;
            cursor: pointer;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            transition: 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Content Sections */
        .section {
            display: none;
            width: 80%;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .active {
            display: block;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table, th, td {
            border: 1px solid #ddd;
            padding: 12px;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .restore-button {
            background-color: #28a745;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 5px;
        }

        .restore-button:hover {
            background-color: #218838;
        }

        .delete-button {
            background-color: #dc3545;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .delete-button:hover {
            background-color: #b02a37;
        }

        td form {
            display: inline-block;
            margin: 0 2px;
        }
    </style>
</head>
<body>
    <h1>Archived Records</h1>

    <div class="nav-links">
        <a href="/admin">Dashboard</a>
        <a href="/admin/audit-trails">Audit Trails</a>
        <a href="/logout" onclick="return confirm('Are you sure you want to logout?')" style="color:#dc3545">Logout</a>
    </div>

    <div class="nav-links">
    <a href="#" onclick="showSection('participants'); return false;">Archived Participants</a>
    <a href="#" onclick="showSection('events'); return false;">Archived Events</a>
</div>

    <div id="participants" class="section active">
        <h2>Archived Participants</h2>
        <table>
            <tr>
                <th>Name</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($archived_participants as $participant): ?>
                <tr>
                    <td><?= htmlspecialchars($participant['name']); ?></td>
                    <td>
                        <form action="/admin/restore-participant/<?= $participant['id']; ?>" method="post" style="display:inline;">
                            <button type="submit" class="restore-button">Restore</button>
                        </form>
                        <form action="/admin/permanent-delete-participant/<?= $participant['id']; ?>" method="post" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                            <button type="submit" class="delete-button">Delete Permanently</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>


    <div id="events" class="section">
        <h2>Archived Events</h2>
        <table>
            <tr>
                <th>Name</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($archived_events as $event): ?>
                <tr>
                    <td><?= htmlspecialchars($event['name']); ?></td>
                    <td>
                        <form action="/admin/restore-event/<?= $event['id']; ?>" method="post" style="display:inline;">
                            <button type="submit" class="restore-button">Restore</button>
                        </form>
                        <form action="/admin/permanent-delete-event/<?= $event['id']; ?>" method="post" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                            <button type="submit" class="delete-button">Delete Permanently</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <script>
        function showSection(sectionId) {
            document.querySelectorAll('.section').forEach(section => {
                section.classList.remove('active');
            });
            document.getElementById(sectionId).classList.add('active');
        }
    </script>
</body>
</html>