<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
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

        h1, h2, h3 {
            color: #333;
            margin-bottom: 20px;
        }

        /* Navigation Links */
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

        /* Forms */
        form {
            margin-bottom: 20px;
            text-align: center;
        }

        input, select {
            padding: 8px 12px;
            margin: 2px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        /* Buttons */
        button {
            margin: 2px;
            padding: 8px 16px;
            font-size: 14px;
            cursor: pointer;
            border: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .add-button {
            background-color: #212529;
            color: white;
        }

        .add-button:hover {
            background-color: #343a40;
        }

        .update-button {
            background-color: #28a745;
            color: white;
        }

        .update-button:hover {
            background-color: #218838;
        }

        .delete-button {
            background-color: #dc3545;
            color: white;
        }

        .delete-button:hover {
            background-color: #c82333;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #dee2e6;
        }

        th {
            background-color: #007bff;
            color: white;
            font-weight: 500;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        td form {
            display: inline-block;
            margin: 0 2px;
            vertical-align: middle;
        }

        .action-column {
            text-align: center;
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <h1>Admin Dashboard</h1>

    <div class="nav-links">
        <a href="/admin/archive">Archive</a>
        <a href="/admin/audit-trails">Audit Trails</a>
        <a href="/logout" onclick="return confirm('Are you sure you want to logout?')" style="color:#dc3545">Logout</a>
    </div>

    <div class="nav-links">
        <a href="#" onclick="showSection('clusters'); return false;">Manage Clusters</a>
        <a href="#" onclick="showSection('events'); return false;">Manage Events</a>
        <a href="#" onclick="showSection('participants'); return false;">Manage Participants</a>
        <a href="#" onclick="showSection('results'); return false;">Manage Event Results</a>
    </div>

    <!-- START OF CLUSTER SECTION -->
        <div id="clusters" class="section active">
        <h2>Cluster Management</h2>
        <form action="/admin/create-cluster" method="post">
            <?= csrf_field() ?>
            <input type="text" name="name" placeholder="Enter Cluster Name" required>
            <button type="submit" class="add-button">Add Cluster</button>
        </form>

        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($clusters as $cluster): ?>
            <tr>
                <td><?= $cluster['id']; ?></td>
                <td><?= $cluster['name']; ?></td>
                <td class="action-column">
                    <form action="/admin/update-cluster/<?= $cluster['id']; ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="text" name="name" value="<?= $cluster['name']; ?>" required>
                        <button type="submit" class="update-button">Update</button>
                    </form>
                    <form action="/admin/delete-cluster/<?= $cluster['id']; ?>" method="post">
                        <?= csrf_field() ?>
                        <button type="submit" class="delete-button" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
        <!-- END OF CLUSTER SECTION -->


    <!-- Events Section -->
    <div id="events" class="section">
        <h2>Event Management</h2>
        <form action="/admin/create-event" method="post">
            <?= csrf_field() ?>
            <input type="text" name="name" placeholder="Enter Event Name" required>
            <button type="submit" class="add-button">Add Event</button>
        </form>

        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($events as $event): ?>
            <tr>
                <td><?= $event['id']; ?></td>
                <td><?= $event['name']; ?></td>
                <td class="action-column">
                    <form action="/admin/update-event/<?= $event['id']; ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="text" name="name" value="<?= $event['name']; ?>" required>
                        <button type="submit" class="update-button">Update</button>
                    </form>
                    <form action="/admin/delete-event/<?= $event['id']; ?>" method="post">
                        <?= csrf_field() ?>
                        <button type="submit" class="delete-button" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
        <!-- End Events Section -->


    <!-- Participants Section -->
    <div id="participants" class="section">
        <h2>Participant Management</h2>
        <form action="/admin/create-participant" method="post">
            <?= csrf_field() ?>
            <input type="text" name="name" placeholder="Enter Participant Name" required>
            <select name="cluster_id" required>
                <option value="">Select Cluster</option>
                <?php foreach ($clusters as $cluster): ?>
                    <option value="<?= $cluster['id']; ?>"><?= $cluster['name']; ?></option>
                <?php endforeach; ?>
            </select>
            <select name="event_id" required>
                <option value="">Select Event</option>
                <?php foreach ($events as $event): ?>
                    <option value="<?= $event['id']; ?>"><?= $event['name']; ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="add-button">Add Participant</button>
        </form>

        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Cluster</th>
                <th>Event</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($participants as $participant): ?>
            <tr>
                <td><?= $participant['id']; ?></td>
                <td><?= $participant['name']; ?></td>
                <td><?= $participant['cluster_name']; ?></td>
                <td><?= $participant['event_name']; ?></td>
                <td class="action-column">
                    <form action="/admin/update-participant/<?= $participant['id']; ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="text" name="name" value="<?= $participant['name']; ?>" required>
                        <select name="cluster_id" required>
                            <?php foreach ($clusters as $cluster): ?>
                                <option value="<?= $cluster['id']; ?>" <?= ($cluster['id'] == $participant['cluster_id']) ? 'selected' : ''; ?>>
                                    <?= $cluster['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <select name="event_id" required>
                            <?php foreach ($events as $event): ?>
                                <option value="<?= $event['id']; ?>" <?= ($event['id'] == $participant['event_id']) ? 'selected' : ''; ?>>
                                    <?= $event['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="update-button">Update</button>
                    </form>
                    <form action="/admin/delete-participant/<?= $participant['id']; ?>" method="post">
                        <?= csrf_field() ?>
                        <button type="submit" class="delete-button" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
        <!-- End Participants Section -->


    <!-- Event Results Section -->
    <div id="results" class="section">
        <h2>Event Results Management</h2>
        <form action="/admin/create-event-result" method="post">
            <?= csrf_field() ?>
            <select name="event_id" required>
                <option value="">Select Event</option>
                <?php foreach ($events as $event): ?>
                    <option value="<?= $event['id']; ?>"><?= $event['name']; ?></option>
                <?php endforeach; ?>
            </select>
            <select name="cluster_id" id="clusterSelect" onchange="loadParticipants(this.value)" required>
                <option value="">Select Cluster</option>
                <?php foreach ($clusters as $cluster): ?>
                    <option value="<?= $cluster['id']; ?>"><?= $cluster['name']; ?></option>
                <?php endforeach; ?>
            </select>
            <select name="participant_id" id="participantSelect" required>
                <option value="">Select Participant</option>
            </select>
            <input type="number" name="points" placeholder="Enter Points" required>
            <button type="submit" class="add-button">Add Event Result</button>
        </form>

        <h3>Event Rankings</h3>
        <table>
            <tr>
                <th>Rank</th>
                <th>Participant (Cluster)</th>
                <th>Event</th>
                <th>Total Points</th>
                <th>Actions</th>
            </tr>
            <?php $rank = 1; foreach ($event_results as $result): ?>
            <tr>
                <td><?= $rank++; ?></td>
                <td><?= isset($result['participant_name']) ? esc($result['participant_name']) : 'Unknown'; ?> 
                    (<?= isset($result['cluster_name']) ? esc($result['cluster_name']) : 'No Cluster'; ?>)</td>
                <td><?= isset($result['event_name']) ? esc($result['event_name']) : 'Unknown Event'; ?></td>
                <td><?= isset($result['total_points']) ? (int)$result['total_points'] : 0; ?></td>
                <td class="action-column">
                    <form action="/admin/update-event-result/<?= $result['participant_id']; ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="number" name="points" value="<?= $result['total_points']; ?>" required>
                        <button type="submit" class="update-button">Update</button>
                    </form>
                    <form action="/admin/delete-event-result/<?= $result['participant_id']; ?>" method="post">
                        <?= csrf_field() ?>
                        <button type="submit" class="delete-button" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
     <!-- End Event Results Section -->

    <script>
        function showSection(sectionId) {
            document.querySelectorAll('.section').forEach(section => {
                section.classList.remove('active');
            });
            document.getElementById(sectionId).classList.add('active');
        }

        function loadParticipants(clusterId) {
            let participantSelect = document.getElementById("participantSelect");
            participantSelect.innerHTML = "<option value=''>Loading...</option>";

            fetch(`/admin/get-participants/${clusterId}`)
                .then(response => response.json())
                .then(data => {
                    participantSelect.innerHTML = "<option value=''>Select Participant</option>";
                    if (data.length === 0) {
                        participantSelect.innerHTML = "<option value=''>No participants available</option>";
                    } else {
                        data.forEach(participant => {
                            let option = document.createElement("option");
                            option.value = participant.id;
                            option.textContent = participant.name;
                            participantSelect.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    participantSelect.innerHTML = "<option value=''>Error loading participants</option>";
                });
        }
    </script>
</body>
</html>