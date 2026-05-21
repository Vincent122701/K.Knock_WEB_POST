<?php
// comment_delete.php
require_once '../config/db.php';

//GET 방식으로 받은 부모  글 ID와 삭제할 댓글 ID를 수집
$post_id    = isset($_GET['post_id']) ? (int)$_GET['post_id'] : 0;
// 복귀할 view.php?id=번호 주소에 쓸 변수
$comment_id = isset($_GET['comment_id']) ? (int)$_GET['comment_id'] : 0;
// 실제로 DELETE 구문에 집어넣을 댓글 고유 번호

//필수 ID 값이 하나라도 없으면 메시지
if ($post_id === 0 || $comment_id === 0) {
    echo "<script>alert('잘못된 접근입니다.'); location.href='list.php';</script>";
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM comments WHERE id = ?");
    $stmt->execute([$comment_id]);

    //삭제가 완료되었음을 사용자에게 보여줌
    //알림창 확인 버튼을 누르면, 사용자가 방금 전까지 머물렀던 게시글 
    //상세 페이지(view.php?id=글번호)로 즉시 화면을 돌려보냄
    echo "<script>
            alert('댓글이 성공적으로 삭제되었습니다.'); 
            location.href='view.php?id=" . $post_id . "';
          </script>";
    exit;

} catch (PDOException $e) {
    //오류 생기면 메시지
    die("댓글 삭제 실패: " . $e->getMessage());
}
?>
