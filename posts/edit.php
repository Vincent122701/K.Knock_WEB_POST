<?php
//edit.php
require_once '../config/db.php';

//주소창에서 글 번호(id) 가져오기
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id === 0) {
    echo "<script>alert('잘못된 접근입니다.'); location.href='list.php';</script>";
    exit;
}

try {
    //기존에 작성된 글 내용을 가져와서 수정창에 미리 보여줌
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->execute([$id]);
    $post = $stmt->fetch();

    if (!$post) {
        echo "<script>alert('존재하지 않는 게시글입니다.'); location.href='list.php';</script>";
        exit;
    }
} catch (PDOException $e) {
    die("오류 발생: " . $e->getMessage());
}

//사용자가 수정한 내용을 입력하고 수정 버튼을 눌렀을 때
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $content = isset($_POST['content']) ? trim($_POST['content']) : '';

    if (empty($title) || empty($content)) {
        echo "<script>alert('제목과 내용을 모두 입력해주세요.'); history.back();</script>";
        exit;
    }

    try {
        //기존 작성한 글을 수정된 버전으로 업데이트하는 sql문
        $stmt_update = $pdo->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
        $stmt_update->execute([$title, $content, $id]);

        echo "<script>alert('글이 성공적으로 수정되었습니다.'); location.href='view.php?id=" . $id . "';</script>";
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
    <title>📝 글 수정하기</title>
    <style>
        /* 기본 스타일 초기화 */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Noto Sans KR', sans-serif;
        }

        body {
            background-color: #f8f9fa;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        /* 중앙 정렬 카드 레이아웃 (write.php 스타일 반영) */
        .container {
            width: 100%;
            max-width: 650px;
        }

        .edit-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            padding: 35px;
        }

        h2 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 25px;
            color: #222;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* 폼 요소 레이아웃 */
        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #555;
        }

        /* 입력창 디자인 (부드러운 라운딩 및 포커스 효과) */
        .form-control {
            width: 100%;
            padding: 12px 14px;
            font-size: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            transition: all 0.2s ease-in-out;
            outline: none;
        }

        .form-control:focus {
            border-color: #4abc73;
            box-shadow: 0 0 0 3px rgba(74, 188, 115, 0.15);
        }

        /* 작성자창 전용 (수정 불가능한 상태 시각화) */
        .form-control[readonly] {
            background-color: #f1f3f5;
            color: #868e96;
            border-color: #e9ecef;
            cursor: not-allowed;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 200px;
            line-height: 1.6;
        }

        .divider {
            border: 0;
            height: 1px;
            background: #e9ecef;
            margin: 25px 0;
        }

        /* 버튼 그룹 디자인 */
        .btn-group {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 24px;
            font-size: 15px;
            font-weight: 600;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.15s ease-in-out;
        }

        .btn-submit {
            background-color: #4abc73;
            color: white;
        }

        .btn-submit:hover {
            background-color: #3ca962;
        }

        .btn-cancel {
            background-color: #e9ecef;
            color: #495057;
        }

        .btn-cancel:hover {
            background-color: #dee2e6;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="edit-card">
        <h2>📝 글 수정하기</h2>
        
        <form action="" method="POST">
            <div class="form-group">
                <label>작성자</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($post['username']); ?>" readonly>
            </div>

            <div class="form-group">
                <label for="title">제목</label>
                <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($post['title']); ?>" required placeholder="제목을 입력하세요">
            </div>

            <div class="form-group">
                <label for="content">본문</label>
                <textarea id="content" name="content" class="form-control" required placeholder="내용을 입력하세요"><?php echo htmlspecialchars($post['content']); ?></textarea>
            </div>

            <hr class="divider">

            <div class="btn-group">
                <button type="button" onclick="history.back();" class="btn btn-cancel">취소</button>
                <button type="submit" class="btn btn-submit">수정 완료</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
