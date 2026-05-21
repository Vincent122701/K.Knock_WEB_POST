<?php
//comment_process.php
require_once '../config/db.php';

//POST 데이터 수집 (어떤 글의 댓글인지 식별할 post_id와 작성자 정보, 내용)
$post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';
$content = isset($_POST['content']) ? trim($_POST['content']) : '';

//필수 값 유효성 검사
if ($post_id === 0 || empty($username) || empty($password) || empty($content)) {
    echo "<script>alert('모든 항목을 입력해주세요.'); history.back();</script>";
    exit;
}

try {
    //sql 인젝션 방지를 위해 플레이스홀더를 사용하여 안전하게 INSERT 문 구성
    $sql = "INSERT INTO comments (post_id, username, password, content) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$post_id, $username, $password, $content]);

    //등록 완료 후 사용자가 방금 보던 게시글 상세 페이지(view.php)로
    //즉시 복귀
    echo "<script>
            alert('댓글이 등록되었습니다.'); 
            location.href = 'view.php?id=" . $post_id . "';
          </script>";
    exit;

} catch (PDOException $e) {
    die("댓글 등록 실패: " . $e->getMessage());
}
?>
