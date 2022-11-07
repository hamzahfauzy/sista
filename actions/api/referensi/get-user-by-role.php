<?php

$role = strtolower($_GET['role']);
$conn = conn();
$db   = new Database($conn);

$role = $db->single('roles',['name' => $role]);

$db->query = "SELECT id, name FROM users WHERE id IN (SELECT user_id FROM user_roles WHERE role_id=$role->id)";
$users = $db->exec('all');

echo json_encode([
    'status' => 'success',
    'data' => $users,
    'message' => 'list data lingkungan',
]);
die();