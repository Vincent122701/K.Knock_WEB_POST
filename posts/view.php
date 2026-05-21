<?php
require_once '../config/db.php';

//주소창에서 넘겨준  글 번호(id)를 가져오기
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

try {
    //해당 id를 가진 게시글 하나만 DB에서 쿼리
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->execute([$id]);
    $post = $stmt->fetch();

    //만약 존재하지 않는 글 번호라면 목록으로
    if (!$post) {
        echo "<script>alert('존재하지 않는 게시글입니다.'); location.href='list.php';</script>";
        exit;
    }
} catch (PDOException $e) {
    die("오류 발생: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($post['title']) ?></title>
    <style>
        body { font-family: sans-serif; background: #f5f7fa; padding: 40px; margin: 0; }
        .view-container { max-width: 700px; margin: 0 auto; background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        h1 { color: #2d3748; font-size: 24px; margin-top: 0; border-bottom: 2px solid #edf2f7; padding-bottom: 15px; }
        .meta-info { color: #718096; font-size: 13px; margin-bottom: 30px; display: flex; justify-content: space-between; }
        .content-box { font-size: 16px; line-height: 1.8; color: #4a5568; min-height: 200px; white-space: pre-wrap; }
        .btn-group { margin-top: 40px; border-top: 1px solid #edf2f7; padding-top: 20px; display: flex; justify-content: space-between; }
        .btn { padding: 10px 20px; background: #3182ce; color: white; text-decoration: none; border-radius: 4px; font-size: 14px; font-weight: bold; }
        .btn:hover { background: #2b6cb0; }
        .btn-secondary { background: #e2e8f0; color: #4a5568; }
        .btn-secondary:hover { background: #cbd5e0; }
    </style>
</head>
<body>

<div class="view-container">
    <h1><?= htmlspecialchars($post['title']) ?></h1>

    <div class="meta-info">
        <span>글 번호: <?= $post['id'] ?></span>
        <span>작성일시: <?= $post['created_at'] ?></span>
    </div>

    <div class="content-box"><?= htmlspecialchars($post['content']) ?></div>

    <div class="btn-group">
        <a href="list.php" class="btn btn-secondary">📋 목록으로</a>
        <span style="color: #cbd5e0; line-height: 40px;">개발 공부 중 🚀</span>
    </div>
</div>

</body>
</html>
