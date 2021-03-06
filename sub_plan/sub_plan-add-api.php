<?php require __DIR__ . '\..\parts\__connect_db.php' ?>
<?php
if (! $_SESSION['admin']) {
    header("Location: " . "../login/login.php");
    exit;
}
$output = [
    'success' => false,
    'code' => 0,
    'error' => ''
];

$sub_plan = $_POST["sub_plan"] ?? '';
$sub_products = $_POST["sub_products"] ?? '';
$sub_price = $_POST["sub_price"] ?? '';

if (empty($sub_plan)) {
    $output['code'] = 401;
    $output['error'] = '請輸入完整的方案名稱';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}
if (empty($sub_products)) {
    $output['code'] = 403;
    $output['error'] = '請輸入完整的產品名稱';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}
if (empty($sub_price)) {
    $output['code'] = 405;
    $output['error'] = '請輸入完整的方案價格';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

$sql = "INSERT INTO `sub_plan`(`sub_plan`, `sub_products`, `sub_price`) VALUES (?,?,?)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $sub_plan,
    $sub_products,
    $sub_price,
]);
$output['success'] = $stmt->rowCount() == 1;
$output['rowcount'] = $stmt->rowCount();

echo json_encode($output);
