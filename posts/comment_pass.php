<?php
// comment_pass.php
require_once '../config/db.php';

// 주소창(GET)으로 넘어오는 액션 유형 및 ID 데이터 수집
$action = isset($_GET['action']) ? $_GET['action'] : ''; // 'edit' 또는 'delete'
$post_id = isset($_GET['post_id']) ? (int)$_GET['post_id'] : 0;
$comment_id = isset($_GET['comment_id']) ? (int)$_GET['comment_id'] : 0;

if (empty($action) || $post_id === 0 || $comment_id === 0) {
    echo "<script>alert('잘못된 접근입니다.'); location.href='list.php';</script>";
    exit;
}

// 사용자가 암호를 입력하고 [확인]을 눌렀을 때 (POST 방식)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_password = isset($_POST['password']) ? trim($_POST['password']) : '';

    try {
        // DB에서 해당 댓글의 비밀번호 조회
        $stmt = $pdo->prepare("SELECT password FROM comments WHERE id = ?");
        $stmt->execute([$comment_id]);
        $comment = $stmt->fetch();

        if ($comment && $input_password === $comment['password']) {
            // 비밀번호가 맞으면 각각의 처리 페이지로 이동
            if ($action === 'edit') {
                header("Location: comment_edit.php?post_id=" . $post_id . "&comment_id=" . $comment_id);
                exit;
            } elseif ($action === 'delete') {
                header("Location: comment_delete.php?post_id=" . $post_id . "&comment_id=" . $comment_id);
                exit;
            }
        } else {
            echo "<script>alert('비밀번호가 일치하지 않습니다.'); history.back();</script>";
            exit;
        }
    } catch (PDOException $e) {
        die("오류 발생: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>🔒 댓글 인증</title>
</head>
<body style="background-color: #f8f9fa; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; font-family: sans-serif;">
    <div style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); text-align: center; width: 350px;">
        <h3 style="margin-bottom: 15px;">🔒 댓글 비밀번호 확인</h3>
        <p style="font-size: 13px; color: #666; margin-bottom: 20px;">댓글 작성 시 입력한 비밀번호를 입력해 주세요.</p>
        
        <form action="" method="POST">
            <input type="password" name="password" placeholder="비밀번호 입력" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; margin-bottom: 15px; box-sizing: border-box; outline: none;">
            <div style="display: flex; gap: 10px;">
                <button type="button" onclick="history.back();" style="flex: 1; padding: 10px; background: #e2e8f0; border: none; border-radius: 4px; cursor: pointer;">취소</button>
                <button type="submit" style="flex: 1; padding: 10px; background: #4abc73; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">확인</button>
            </div>
        </form>
    </div>
</body>
</html>
