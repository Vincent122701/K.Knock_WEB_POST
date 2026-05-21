<?php
// pass_check.php
require_once '../config/db.php';

//주소창에서 글 번호(id)와 어떤 action을 할지 가져오기
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$action = isset($_GET['action']) ? $_GET['action'] : ''; 
//'edit' 또는 'delete'

//올바른 접근이 아니면 차단
if ($id === 0 || !in_html_array($action, ['edit', 'delete'])) {
    echo "<script>alert('잘못된 접근입니다.'); location.href='list.php';</script>";
    exit;
}

//사용자가 비밀번호를 입력하고 '확인'을 눌렀을 때
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_password = isset($_POST['password']) ? $_POST['password'] : '';

    try {
        //DB에서 해당 글의 실제 비밀번호 가져오기
        $stmt = $pdo->prepare("SELECT password FROM posts WHERE id = ?");
        $stmt->execute([$id]);
        $post = $stmt->fetch();

        if ($post) {
            //입력한 비밀번호와 DB의 비밀번호 비교 (일치할 때)
            if ($input_password === $post['password']) {
                if ($action === 'edit') {
                    //비밀번호가 맞으면 수정 페이지로 이동
                    header("Location: edit.php?id=" . $id);
                } else if ($action === 'delete') {
                    //비밀번호가 맞으면 즉시 삭제 처리 페이지로 이동
                    header("Location: delete_action.php?id=" . $id);
                }
                exit;
            } else {
                echo "<script>alert('비밀번호가 일치하지 않습니다.'); history.back();</script>";
                exit;
            }
        } else {
            echo "<script>alert('존재하지 않는 게시글입니다.'); location.href='list.php';</script>";
            exit;
        }
    } catch (PDOException $e) {
        die("오류 발생: " . $e->getMessage());
    }
}

//단순 배열 검사를 위한 함수 (상단 if문용)
function in_html_array($needle, $haystack) {
    return in_array($needle, $haystack);
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>비밀번호 확인</title>
</head>
<body>
    <div style="margin: 50px auto; max-width: 400px; text-align: center; border: 1px solid #ddd; padding: 20px;">
        <h2>비밀번호 확인</h2>
        <p>글을 <?php echo ($action === 'edit') ? '수정' : '삭제'; ?>하시려면 비밀번호를 입력하세요.</p>
        
        <form method="POST">
            <input type="password" name="password" required placeholder="비밀번호 입력" style="padding: 10px; width: 80%; margin-bottom: 10px;"><br>
            <button type="submit" style="padding: 10px 20px;">확인</button>
            <button type="button" onclick="history.back();" style="padding: 10px 20px;">취소</button>
        </form>
    </div>
</body>
</html>
