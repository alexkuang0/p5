<?php

$mysqli = new mysqli('127.0.0.1', 'root', '', 'cs329e');

if ($mysqli->connect_errno) {
    die("Failed to connect to MySQL ($mysqli->connect_errno) $mysqli->connect_error");
}

function add_project($title, $content, $image, int $leader_id): int {
    global $mysqli;

    // user inputs
    $title = htmlspecialchars($title);
    $content = htmlspecialchars($content);
    $image = htmlspecialchars($image);

    // create project
    $stmt1 = $mysqli->prepare("INSERT INTO `teamup_projects` (`title`, `content`, `created`, `image`) VALUES (?, ?, NOW(), ?);");
    $stmt1->bind_param('sss', $title, $content, $image);
    if (!$stmt1->execute()) return -1;

    // create user-project relation
    $project_id = $mysqli->insert_id;
    $stmt2 = $mysqli->prepare("INSERT INTO `teamup_user_projects` (`user_id`, `project_id`, `is_leader`) VALUES (?, ?, TRUE)");
    $stmt2->bind_param('ii', $leader_id, $project_id);
    if (!$stmt2->execute()) return -1;

    return $project_id;
}

function add_user($first_name, $last_name, $email, $password): bool {
    global $mysqli;

    // user inputs
    $first_name = htmlspecialchars($first_name);
    $last_name = htmlspecialchars($last_name);
    $email = htmlspecialchars($email);
    $password = password_hash(htmlspecialchars($password), PASSWORD_BCRYPT);

    // create user
    $stmt = $mysqli->prepare("INSERT INTO `teamup_users` (`first_name`, `last_name`, `email`, `password`) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('ssss', $first_name, $last_name, $email, $password);
    return $stmt->execute();
}

function add_member_to_project($member_id, $project_id): bool {
    global $mysqli;

    $leader_id = get_project_leader($project_id)['id'];
    if ($leader_id === $member_id) return false;

    // create user-project relation
    $stmt = $mysqli->prepare("INSERT INTO `teamup_user_projects` (user_id, project_id) VALUES (?, ?)");
    $stmt->bind_param('ii', $member_id, $project_id);
    return $stmt->execute();
}

function get_projects(int $page_size, int $page_num = 1) {
    global $mysqli;
    $data = [];
    $offset = ($page_num - 1) * $page_size;
    $row_count = get_row_count('teamup_projects');

    if ($offset >= $row_count) return false;

    $stmt = $mysqli->prepare("SELECT * FROM `teamup_projects` LIMIT ? OFFSET ?");
    $stmt->bind_param('ii', $page_size, $offset);
    $stmt->execute();
    $res = $stmt->get_result();

    while ($row = $res->fetch_assoc()) {
        array_push($data, $row);
    }

    return ['data' => $data, 'total_pages' => (int)ceil($row_count / $page_size)];
}

function get_project(int $project_id): ?array {
    global $mysqli;

    $stmt = $mysqli->prepare("SELECT * FROM `teamup_projects` WHERE `id` = ?");
    $stmt->bind_param('i', $project_id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) return null;

    return $res->fetch_assoc();
}

function get_project_members(int $project_id): array {
    global $mysqli;

    $stmt = $mysqli->prepare("SELECT `user_id` FROM `teamup_user_projects` WHERE `project_id` = ? AND `is_leader` = FALSE");
    $stmt->bind_param('i', $project_id);
    $stmt->execute();
    $res = $stmt->get_result();

    $data = [];
    while ($row = $res->fetch_row()) {
        array_push($data, get_user_info($row[0]));
    }
    return $data;
}

function get_project_leader(int $project_id): ?array {
    global $mysqli;

    $stmt = $mysqli->prepare('SELECT `user_id` FROM `teamup_user_projects` WHERE project_id = ? AND `is_leader` = TRUE');
    $stmt->bind_param('i', $project_id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) return null;

    $leader_id = $res->fetch_row()[0];

    return get_user_info($leader_id);
}

function verify_user($email, $password): int {
    global $mysqli;

    // user inputs
    $email = htmlspecialchars($email);
    $password = htmlspecialchars($password);

    // get password hash
    $stmt = $mysqli->prepare('SELECT `id`, `password` FROM `teamup_users` WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) return -1;

    [$user_id, $password_hash] = $res->fetch_row();

    return password_verify($password, $password_hash) ? $user_id : -1;
}

function get_user_info($id): ?array {
    global $mysqli;

    $stmt = $mysqli->prepare('SELECT `id`, `first_name`, `last_name` FROM `teamup_users` WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) return null;

    return $res->fetch_assoc();
}

function get_row_count($table_name): int {
    global $mysqli;
    return $mysqli
        ->query("SELECT COUNT(*) FROM $table_name;")
        ->fetch_row()[0];
}
