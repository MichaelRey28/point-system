<!DOCTYPE html>
<html lang="en">
<head>
    <title>Scoring System - Rankings</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        /* Buttons Container */
        .buttons {
            margin-bottom: 20px;
        }

        /* Button Styles */
        button {
            margin: 10px;
            padding: 12px 18px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            transition: 0.3s;
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

        .nav-links a:last-child {
            color: #dc3545;
        }

        .nav-links a:last-child:hover {
            color: #b02a37;
        }

        .nav-links a.active {
            color: #007bff;
        }

        .section {
            display: none;
            margin-top: 20px;
            width: 80%;
        }

        .active {
            display: block;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table, th, td {
            border: 1px solid black;
            padding: 10px;
        }

        th {
            background-color: #007bff;
            color: white;
        }
        select {
            padding: 8px;
            font-size: 14px;
            margin-top: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>

    <h1>USER</h1>
    <!-- NAV BAR -->
    <div class="nav-links">
    <a href="#" onclick="showSection('overall'); return false;">Overall Rankings</a>
    <a href="#" onclick="showSection('winners'); return false;">Event Winners</a>
    <a href="#" onclick="showSection('events'); return false;">Event Rankings</a>
    <a href="#" onclick="confirmLogout(); return false;">Logout</a>
</div>



    <!-- Overall Rankings Section -->
    <div id="overall" class="section active">
        <h2>Overall Rankings by Cluster</h2>
        <table>
            <tr>
                <th>Rank</th>
                <th>Cluster</th>
                <th>Total Points</th>
            </tr>
            <?php $rank = 1; foreach ($rankings as $ranking): ?>
                <tr>
                    <td><?= $rank++; ?></td>
                    <td><?= $ranking['cluster_name']; ?></td>
                    <td><?= $ranking['total_points']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <!-- Event Winners Section -->
    <div id="winners" class="section">
        <h2>All Events - Winners</h2>

        <!-- Event Filter Dropdown -->
        <label for="eventFilter">Filter by Event:</label>
        <select id="eventFilter" onchange="filterWinners()">
            <option value="all">All Events</option>
            <?php foreach ($events as $event): ?>
                <option value="<?= htmlspecialchars($event['name']); ?>"><?= htmlspecialchars($event['name']); ?></option>
            <?php endforeach; ?>
        </select>

        <table id="winnersTable">
            <tr>
                <th>Event</th>
                <th>Participant</th>
                <th>Rank</th>
                <th>Points</th>
            </tr>
            <?php foreach ($event_results as $result): ?>
                <tr class="winnerRow" data-event="<?= htmlspecialchars($result['event_name']); ?>">
                    <td><?= htmlspecialchars($result['event_name']); ?></td>
                    <td><?= htmlspecialchars($result['participant_name']); ?></td>
                    <td><?= isset($result['rank']) ? $result['rank'] : 'N/A'; ?></td>
                    <td><?= isset($result['total_points']) ? (int)$result['total_points'] : 0; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <!-- Event Rankings Section -->
    <div id="events" class="section">
        <h2>Event Rankings</h2>
        <table>
            <tr>
                <th>Rank</th>
                <th>Participant (Cluster)</th>
                <th>Total Points</th>
            </tr>
            <?php $rank = 1; foreach ($rankings as $ranking): ?>
                <tr>
                    <td><?= $rank++; ?></td>
                    <td><?= $ranking['participant_name']; ?> (<?= $ranking['cluster_name']; ?>)</td>
                    <td><?= $ranking['total_points']; ?></td>
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

        // FILTER FOR DROPDOWN
        function filterWinners() {
            let selectedEvent = document.getElementById("eventFilter").value;
            let rows = document.querySelectorAll(".winnerRow");

            rows.forEach(row => {
                if (selectedEvent === "all" || row.getAttribute("data-event") === selectedEvent) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }

        function confirmLogout() {
        if (confirm("Are you sure you want to logout?")) {
            window.location.href = "/logout";
        }
    }
    </script>

</body>
</html>
