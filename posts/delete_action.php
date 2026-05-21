<?php
// delete_action.php
require_once '../config/db.php';

// 주소창에서 글 번호(id) 가져오기
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id === 0) {
    echo "<script>alert('잘못된 접근입니다.'); location.href='list.php';</script>";
    exit;
}

try {
    // 1. 데이터베이스에서 해당 id를 가진 게시글 완전히 삭제하기
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->execute([$id]);

    // 2. 삭제 완료 메시지를 띄우고 목록 화면으로 튕겨주기
    echo "<script>alert('게시글이 성공적으로 삭제되었습니다.'); location.href='list.php';</script>";
    exit;
} catch (PDOException $e) {
    die("삭제 중 오류 발생: " . $e->getMessage());
}
?>
