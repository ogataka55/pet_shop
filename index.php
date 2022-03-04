<?php

// 関数ファイルを読み込む
require_once __DIR__ . '/functions.php';

// データベースに接続
$dbh = connect_db();

// SQL文の組み立て
// PDO処理でのあいまい検索
$keyword = filter_input(INPUT_GET, 'keyword');
$sql = 'SELECT * FROM animals';

if (!empty($keyword)) {
    $keyword_param = '%' . $keyword . '%'; // %ではさむ
    $sql .= ' WHERE description LIKE :keyword_param';
}

// プリペアドステートメントの準備
// $dbh->query($sql) でも良い
$stmt = $dbh->prepare($sql);

// プリペアドステートメントの実行
// bindParamによる変数の設定
if ($keyword) {
    $stmt->bindParam(':keyword_param', $keyword_param, PDO::PARAM_STR);
}
$stmt->execute();

// 結果の受け取り
$animals = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDO - SELECT</title>
</head>

<body>
    <h2>本日のご紹介ペット！</h2>
    <form action="" method="get">
        <label for="search">キーワード:</label>
        <input type="search" name="keyword" placeholder="キーワードの入力">
        <input type="submit" name="submit" value="検索">
    </form><br>
    <?php foreach ($animals as $animal) : ?>
        <?= h($animal['type']) . 'の' . h($animal['classification']) . 'ちゃん' ?><br>
        <?= h($animal['description']) ?><br>
        <?= h($animal['birthday']) . ' 生まれ' ?><br>
        <?= '出身地 ' . h($animal['birthplace']) ?>
        <hr>
    <?php endforeach; ?>
</body>