<?php
require_once '../config/db.php';

//POST로 넘어온 4가지 데이터를 모두 수집
$username = isset($_POST['username']) ? trim($_POST['username']) : '익명';
$password = isset($_POST['password']) ? trim($_POST['password']) : ''; // <-- 추가
$title = isset($_POST['title']) ? trim($_POST['title']) : '';
$content = isset($_POST['content']) ? trim($_POST['content']) : '';

//필수값 검증 (password가 비어있는지도 함께 체크)
if (empty($password) || empty($title) || empty($content)) {
    echo "<script>alert('비밀번호, 제목, 내용을 모두 입력해주세요.'); history.back();</script>";
    exit;
}

try {
    //sql문에 password 컬럼과 플레이스홀더를 추가합니다.
    $stmt = $pdo->prepare("INSERT INTO posts (username, password, title, content) VALUES (?, ?, ?, ?)");
    
    //execute 배열에도 password의 위치를 매칭
    $stmt->execute([$username, $password, $title, $content]);

    echo "<script>alert('글이 등록되었습니다.'); location.href='list.php';</script>";
} catch (PDOException $e) {
    die("등록 실패: " . $e->getMessage());
}
?>
