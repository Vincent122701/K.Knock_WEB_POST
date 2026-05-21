<?php
//comment_edit.php
require_once '../config/db.php';

//GET으로 전달받은 글 id와 댓글 id를 받아 int형으로변환
$post_id = isset($_GET['post_id']) ? (int)$_GET['post_id'] : 0;
$comment_id = isset($_GET['comment_id']) ? (int)$_GET['comment_id'] : 0;

//둘 중 하나라도 정상적인 값이 안넘어오면 메시지
if ($post_id === 0 || $comment_id === 0) {
    echo "<script>alert('잘못된 접근입니다.'); location.href='list.php';</script>";
    exit;
}

try { //comment_edit.php 파일도 sql 인젝션 방지하기
      //수정 화면에 기존에 작성한 댓글 내용 띄우기(원본DB에서 가져옴)
    $stmt = $pdo->prepare("SELECT * FROM comments WHERE id = ?");
    $stmt->execute([$comment_id]);
    $comment = $stmt->fetch();
    
    //입력받은 comment_id에 매칭되는 댓글이 없는 경우
    if (!$comment) {
        echo "<script>alert('존재하지 않는 댓글입니다.'); location.href='view.php?id=" . $post_id . "';</script>";
        exit;
    }
} catch (PDOException $e) {
    die("오류: " . $e->getMessage());
}

//댓글 수정 후 버튼 눌렀을 때 전송
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //텍스트 영역에 입력된 값 가져오고 좌우 공백 삭제
    $content = isset($_POST['content']) ? trim($_POST['content']) : '';
    //댓글 내용이 비어있거나 공백만 있으면 메시지
    if (empty($content)) {
        echo "<script>alert('댓글 내용을 입력해주세요.'); history.back();</script>";
        exit;
    }

    try { //이것도 sql 인젝션 방지
          //UPDATE sql문 사용해서 댓글 본문 수
        $stmt_update = $pdo->prepare("UPDATE comments SET content = ? WHERE id = ?");
        $stmt_update->execute([$content, $comment_id]);

        echo "<script>alert('댓글이 수정되었습니다.'); location.href='view.php?id=" . $post_id . "';</script>";
        exit;
    } catch (PDOException $e) {
        die("수정 실패: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>📝 댓글 수정하기</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #f8f9fa;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .edit-container {
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
            width: 100%;
            max-width: 550px;정
        }

        h3 {
            font-size: 22px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 20px;
        }

        .author-info {
            font-size: 14px;
            color: #4a5568;
            margin-bottom: 12px;
            background: #edf2f7;
            padding: 10px 14px;
            border-radius: 6px;
            display: inline-block;
        }

        .author-info strong {
            color: #2b6cb0;
        }

        .textarea-field {
            width: 100%;
            height: 120px;
            padding: 14px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            resize: none;
            font-size: 15px;
            font-family: inherit;
            line-height: 1.6;
            outline: none;
            margin-bottom: 25px;
            transition: border-color 0.15s ease;
        }

        .textarea-field:focus {
            border-color: #4abc73;
        }

        .btn-group {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }

        .btn {
            padding: 11px 22px;
            font-size: 14px;
            font-weight: 700;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-cancel {
            background-color: #e2e8f0;
            color: #4a5568;
        }

        .btn-cancel:hover {
            background-color: #cbd5e0;
        }

        .btn-submit {
            background-color: #4abc73;
            color: white;
        }

        .btn-submit:hover {
            background-color: #3ca962;
        }
    </style>
</head>
<body>

<div class="edit-container">
    <h3>📝 댓글 수정하기</h3>
    
    <div class="author-info">
        작성자 👤 <strong><?php echo htmlspecialchars($comment['username']); ?></strong>
    </div>
    
    <form action="" method="POST">
        <textarea name="content" class="textarea-field" placeholder="수정할 내용을 입력해 주세요." required><?php echo htmlspecialchars($comment['content']); ?></textarea>
        
        <div class="btn-group">
            <button type="button" class="btn btn-cancel" onclick="location.href='view.php?id=<?php echo $post_id; ?>';">취소</button>
            <button type="submit" class="btn btn-submit">수정 완료</button>
        </div>
    </form>
</div>

</body>
</html>
