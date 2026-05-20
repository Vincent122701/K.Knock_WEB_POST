<?php
//게시글 목록 페이지 만들기 index.php
//데이터베이스 연결 통로($pdo)가 담긴 파일을 이 자리에 통째로 가져오기
require_once 'config/db.php';

try {
    //최신 글이 맨 위로 오도록 DESC(내림차순) 정렬
    //stmt(Statement 줄임말 명령문 대기)
    //pdo(PHP가 MYSQL 서버와 연결되어있는 통로 config/db.php 연결)
    $stmt = $pdo->query("SELECT * FROM posts ORDER BY id DESC");
    //stmt에게 결과를 배열로 한번에 전부 반환하게함
    $posts = $stmt->fetchAll();
} catch (PDOException $e) {
    //위 과정에서 에러 발생하면 프로세서 중단 및 에러 메세지 출력
    die("오류 발생: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>게시판</title>
</head>
<body>
    <h2>📋 게시글 목록</h2>
    <a href="posts/write.php"><button>📝 새 글 쓰기</button></a>
    <hr>

    <?php if (empty($posts)): ?>
        <p>아직 등록된 게시글이 없습니다.</p>
    <?php else: ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th width="50">번호</th>
                    <th width="300">제목</th>
                    <th width="150">작성일</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($posts as $post): ?>
                    <tr>
                        <td><?= $post['id'] ?></td>
                        <td>
                            <a href="posts/view.php?id=<?= $post['id'] ?>">
                                <?= htmlspecialchars($post['title']) ?>
                            </a>
                        </td>
                        <td><?= $post['created_at'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
