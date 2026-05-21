<?php
require_once '../config/db.php';

//검색어와 검색 기준(제목, 작성자 등)이 넘어왔는지 확인
$search_type = isset($_GET['search_type']) ? $_GET['search_type'] : 'title';
$search_keyword = isset($_GET['search_keyword']) ? $_GET['search_keyword'] : '';

try {
    //검색어가 있으면 조건부 검색, 없으면 전체 조회
    if (!empty($search_keyword)) {
        if ($search_type === 'username') {
            $stmt = $pdo->prepare("SELECT * FROM posts WHERE username LIKE ? ORDER BY id DESC");
        } else {
            $stmt = $pdo->prepare("SELECT * FROM posts WHERE title LIKE ? ORDER BY id DESC");
        }
        $stmt->execute(["%$search_keyword%"]);
    } else {
        $stmt = $pdo->query("SELECT * FROM posts ORDER BY id DESC");
    }
    $posts = $stmt->fetchAll();
} catch (PDOException $e) {
    die("DB 오류: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>게시판 프로젝트</title>
    <style>
        /* 기본 레이아웃 세팅 */
        body { 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; 
            background: #f8f9fa; 
            padding: 40px; 
            color: #212529; 
            margin: 0;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        /* 상단 타이틀 배너 (Login 제거 후 중앙 정렬) */
        .main-header { 
            padding: 20px 0; 
            text-align: center; 
            margin-bottom: 40px; 
            border-bottom: 2px solid #e9ecef; 
        }
        .main-header h1 { 
            margin: 0; 
            color: #343a40; 
            font-size: 32px; 
            letter-spacing: -1px; 
        }
        .board-title { 
            font-size: 22px; 
            font-weight: 700; 
            margin-bottom: 20px; 
            color: #212529; 
        }

        /* 우측 상단 검색창 컴포넌트 */
        .search-container { 
            display: flex; 
            justify-content: flex-end; 
            margin-bottom: 25px; 
        }
        .search-select { 
            padding: 10px 16px; 
            border: 1px solid #ced4da; 
            border-radius: 8px 0 0 8px; 
            font-size: 14px; 
            background-color: #fff; 
            outline: none;
        }
        .search-input { 
            padding: 10px 16px; 
            border: 1px solid #ced4da; 
            border-left: none; 
            font-size: 14px; 
            width: 220px; 
            outline: none; 
        }
        .btn-search { 
            padding: 10px 18px; 
            background: #4dabf7; 
            border: 1px solid #4dabf7; 
            color: white; 
            border-radius: 0 8px 8px 0; 
            cursor: pointer; 
            font-size: 14px;
            transition: background 0.15s ease-in-out;
        }
        .btn-search:hover { 
            background: #339af0; 
        }

        /* 메인 테이블 스타일 */
        .board-table { 
            width: 100%; 
            border-collapse: separate; 
            border-spacing: 0; 
            background: #fff; 
            border: 1px solid #e9ecef; 
            border-radius: 12px; 
            overflow: hidden; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        }
        .board-table th { 
            background: #f8f9fa; 
            color: #495057; 
            padding: 16px; 
            font-weight: 600; 
            border-bottom: 1px solid #e9ecef; 
            font-size: 14px; 
        }
        .board-table td { 
            padding: 16px; 
            text-align: center; 
            border-bottom: 1px solid #e9ecef; 
            color: #495057; 
            font-size: 14px; 
        }
        .board-table tr:last-child td { 
            border-bottom: none; 
        }
        .board-table tr:hover { 
            background-color: #f1f3f5; 
        }
        .text-left { 
            text-align: left !important; 
        }
        
        /* 게시글 링크 스타일 */
        .post-link { 
            color: #212529; 
            text-decoration: none; 
            font-weight: 600; 
        }
        .post-link:hover { 
            color: #228be6; 
        }
        .date-text { 
            color: #868e96; 
        }
        .no-data { 
            padding: 60px 0 !important; 
            color: #868e96; 
        }

        /* 우측 하단 글쓰기 버튼 컴포넌트 */
        .action-container { 
            display: flex; 
            justify-content: flex-end; 
            margin-top: 25px; 
        }
        .btn-write { 
            display: inline-block; 
            padding: 12px 28px; 
            background: #228be6; 
            color: white; 
            text-decoration: none; 
            border-radius: 8px; 
            font-weight: 600; 
            font-size: 14px; 
            transition: background 0.15s ease-in-out; 
        }
        .btn-write:hover { 
            background: #1c7ed6; 
        }
    </style>
</head>
<body>

<div class="container">

    <div class="main-header">
        <h1>🚩 17기 김경훈 게시판 🚩</h1>
    </div>

    <div class="search-container">
        <form action="list.php" method="GET">
            <select name="search_type" class="search-select">
                <option value="title" <?= $search_type === 'title' ? 'selected' : '' ?>>제목</option>
                <option value="username" <?= $search_type === 'username' ? 'selected' : '' ?>>작성자</option>
            </select>
            <input type="text" name="search_keyword" class="search-input" value="<?= htmlspecialchars($search_keyword) ?>" placeholder="검색어 입력">
            <button type="submit" class="btn-search">🔍</button>
        </form>
    </div>

    <div class="board-title">게시판</div>

    <table class="board-table">
        <thead>
            <tr>
                <th width="80">번호</th>
                <th>제목</th>
                <th width="150">작성자</th>
                <th width="180">작성일</th>
                <th width="80">조회</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($posts)): ?>
                <tr>
                    <td colspan="5" class="no-data">등록된 게시글이 없거나 검색 결과가 없습니다.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                    <tr>
                        <td><?= $post['id'] ?></td>
                        <td class="text-left">
                            <a href="view.php?id=<?= $post['id'] ?>" class="post-link">
                                <?= htmlspecialchars($post['title']) ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($post['username']) ?></td>
                        <td><span class="date-text"><?= substr($post['created_at'], 0, 10) ?></span></td>
                        <td><?= $post['views'] ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="action-container">
        <a href="write.php" class="btn-write">글쓰기</a>
    </div>

</div>

</body>
</html>
