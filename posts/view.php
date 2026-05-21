<?php
//view.php
require_once '../config/db.php';

//주소창에서 글 번호(id) 가져오기
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id === 0) {
    echo "<script>alert('잘못된 접근입니다.'); location.href='list.php';</script>";
    exit;
}

try {
    //게시글 내용 가져오기
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->execute([$id]);
    $post = $stmt->fetch();

    if (!$post) {
        echo "<script>alert('존재하지 않는 게시글입니다.'); location.href='list.php';</script>";
        exit;
    }

    //사용자가 확인하고 있는  게시글에 달린 댓글들만 
    //작성된 시간 순서대로 불러오기
    $stmt_comments = $pdo->prepare("SELECT * FROM comments WHERE post_id = ? ORDER BY created_at ASC");
    $stmt_comments->execute([$id]);
    $comments = $stmt_comments->fetchAll();

} catch (PDOException $e) {
    die("오류 발생: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($post['title']); ?></title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #f8f9fa;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: #333;
            padding: 40px 20px;
        }

        /* 메인 게시글 카드 레이아웃 */
        .view-container {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
            padding: 40px;
        }

        h1 {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 15px;
            color: #1a202c;
        }

        .meta-info {
            display: flex;
            gap: 20px;
            font-size: 14px;
            color: #718096;
            border-bottom: 1px solid #edf2f7;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .content-box {
            font-size: 16px;
            line-height: 1.8;
            color: #2d3748;
            min-height: 250px;
            white-space: pre-wrap;
            margin-bottom: 30px;
        }

        /* 하단 버튼 그룹 디자인 */
        .btn-group {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            border-top: 1px solid #edf2f7;
            padding-top: 20px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 18px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 6px;
            text-decoration: none;
            transition: all 0.15s ease;
            cursor: pointer;
        }

        .btn-secondary { background-color: #e2e8f0; color: #4a5568; }
        .btn-secondary:hover { background-color: #cbd5e0; }
        
        .btn-edit { background-color: #edf2f7; color: #2b6cb0; border: 1px solid #e2e8f0; }
        .btn-edit:hover { background-color: #ebf8ff; }

        .btn-delete { background-color: #edf2f7; color: #c53030; border: 1px solid #e2e8f0; }
        .btn-delete:hover { background-color: #fff5f5; }

        .footer-text {
            display: block;
            margin-top: 20px;
            color: #cbd5e0;
            font-size: 13px;
        }

        .comment-wrapper {
            max-width: 800px;
            margin: 25px auto 0 auto; 
	/* 본문 카드 바로 아래 자연스럽게 밀착 배치 */
        }

        /* 댓글 블록 공통 카드 디자인 */
        .comment-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
            padding: 30px;
            margin-bottom: 20px;
        }

        .comment-title {
            font-size: 18px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 18px;
        }

        /* 입력 폼 필드 스타일링 */
        .comment-meta-inputs {
            display: flex;
            gap: 10px;
            margin-bottom: 12px;
        }

        .comment-input {
            padding: 10px 14px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            width: 160px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.15s ease;
        }

        .comment-input:focus, .comment-textarea:focus {
            border-color: #4abc73;
        }

        .comment-textarea-group {
            display: flex;
            gap: 10px;
        }

        .comment-textarea {
            flex: 1;
            padding: 12px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            height: 80px;
            resize: none;
            font-size: 14px;
            font-family: inherit;
            outline: none;
            line-height: 1.5;
            transition: border-color 0.15s ease;
        }

        .comment-submit-btn {
            padding: 0 24px;
            background-color: #4abc73;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 700;
            font-size: 14px;
            transition: background 0.2s;
        }

        .comment-submit-btn:hover {
            background-color: #3ca962;
        }

        /* 댓글 리스트 출력 아이템 스타일링 */
        .comment-item {
            padding: 16px 0;
            border-bottom: 1px solid #edf2f7;
        }

        .comment-item:last-child {
            border-bottom: none; /* 마지막 댓글은 밑줄 제거 */
        }

        .comment-item-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
        }

        .comment-author {
            font-weight: 700;
            color: #4a5568;
            font-size: 14px;
        }

        .comment-date {
            font-size: 12px;
            color: #a0aec0;
        }

        .comment-body {
            font-size: 14px;
            color: #2d3748;
            white-space: pre-wrap;
            line-height: 1.6;
        }

        .no-comment {
            color: #a0aec0;
            font-size: 14px;
            text-align: center;
            padding: 30px 0;
        }
    </style>
</head>
<body>

<div class="view-container">
    <h1><?php echo htmlspecialchars($post['title']); ?></h1>
    
    <div class="meta-info">
        <span>글 번호: <?php echo $post['id']; ?></span>
        <span>작성일시: <?php echo $post['created_at']; ?></span>
    </div>

    <div class="content-box"><?php echo htmlspecialchars($post['content']); ?></div>

    <div class="btn-group">
        <a href="list.php" class="btn btn-secondary">📋 목록으로</a>
        <a href="pass_check.php?action=edit&id=<?php echo $id; ?>" class="btn btn-edit">수정</a>
        <a href="pass_check.php?action=delete&id=<?php echo $id; ?>" class="btn btn-delete">삭제</a>
    </div>

    <span class="footer-text">게시판 구현</span>
</div>


<div class="comment-wrapper">

    <div class="comment-card">
        <div class="comment-title">💬 댓글 작성</div>
        <form action="comment_process.php" method="POST">
            <input type="hidden" name="post_id" value="<?php echo $id; ?>">
            
            <div class="comment-meta-inputs">
                <input type="text" name="username" class="comment-input" placeholder="작성자" required>
                <input type="password" name="password" class="comment-input" placeholder="비밀번호" required>
            </div>
            
            <div class="comment-textarea-group">
                <textarea name="content" class="comment-textarea" placeholder="댓글을 입력해 주세요." required></textarea>
                <button type="submit" class="comment-submit-btn">등록</button>
            </div>
        </form>
    </div>

<div class="comment-card">
        <div class="comment-title">댓글 목록</div>
        
        <?php
        if (count($comments) === 0) {
            echo "<p class='no-comment'>등록된 댓글이 없습니다. 첫 댓글을 남겨보세요!</p>";
        } else {
            foreach ($comments as $comment) {
                ?>
                <div class="comment-item">
                    <div class="comment-item-header">
                        <span class="comment-author"><?php echo htmlspecialchars($comment['username']); ?></span>
                        
                        <div style="display: flex; gap: 10px; align-items: center;">
                            <span class="comment-date"><?php echo $comment['created_at']; ?></span>
                            <a href="comment_pass.php?action=edit&post_id=<?php echo $id; ?>&comment_id=<?php echo $comment['id']; ?>" style="font-size: 12px; color: #4abc73; text-decoration: none; font-weight: 600;">수정</a>
                            <a href="comment_pass.php?action=delete&post_id=<?php echo $id; ?>&comment_id=<?php echo $comment['id']; ?>" style="font-size: 12px; color: #e53e3e; text-decoration: none; font-weight: 600;">삭제</a>
                        </div>
                    </div>
                    <p class="comment-body"><?php echo htmlspecialchars($comment['content']); ?></p>
                </div>
                <?php
            }
        }
        ?>
    </div>
