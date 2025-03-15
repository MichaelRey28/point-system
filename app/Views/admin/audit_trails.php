<!DOCTYPE html>
<html lang="en">
<head>
    <title>Audit Trails</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }

        h1, h2 {
            color: #333;
            margin-bottom: 20px;
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

        /* Content Section */
        .section {
            width: 95%;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow-x: auto;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 14px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #007bff;
            color: white;
            white-space: nowrap;
        }

        td {
            min-width: 100px;
        }

        td:nth-child(1) { /* Date */
            white-space: nowrap;
        }
        
        td:nth-child(2), /* Action */
        td:nth-child(3), /* Table Name */
        td:nth-child(4) { /* Record ID */
            white-space: nowrap;
        }

        td:nth-child(5), /* Old Value */
        td:nth-child(6) { /* New Value */
            min-width: 200px;
            max-width: 300px;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .json-data {
            white-space: pre-wrap;
            font-family: monospace;
            font-size: 13px;
            word-break: break-word;
            max-width: 300px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <h1>Audit Trails</h1>

    <div class="nav-links">
        <a href="/admin">Dashboard</a>
        <a href="/admin/archive">Archive</a>
        <a href="/logout" onclick="return confirm('Are you sure you want to logout?')">Logout</a>
    </div>

    <div class="section">
        <table>
            <thead>
                <tr>
                    <th>Date Created</th>
                    <th>Action</th>
                    <th>Table Name</th>
                    <th>Record ID</th>
                    <th>Old Value</th>
                    <th>New Value</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($audit_logs as $log): ?>
                    <tr>
                        <td><?= date('Y-m-d H:i:s', strtotime($log['created_at'])); ?></td>
                        <td><?= esc($log['action']); ?></td>
                        <td><?= esc($log['table_name']); ?></td>
                        <td><?= esc($log['record_id']); ?></td>
                        <td class="json-data"><?= nl2br(esc($log['old_value'])); ?></td>
                        <td class="json-data"><?= nl2br(esc($log['new_value'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>