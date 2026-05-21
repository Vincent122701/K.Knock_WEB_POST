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
    $posts = $stmt->fetchAll();} catch (PDOException $e) {
    //위 과정에서 에러 발생하면 프로세서 중단 및 에러 메세지 출력
    die("오류 발생: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>게시판 구현하기</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .welcome-container {
            text-align: center;
            background: white;
            padding: 50px 80px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        h1 {
            color: #4a5568;
            margin-bottom: 10px;
        }
        p {
            color: #718096;
            margin-bottom: 30px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #3182ce;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            transition: background 0.2s;
        }
        .btn:hover {
            background-color: #2b6cb0;
        }
    </style>
</head>
<body>

    <div class="welcome-container">
        <h1>K.Knock first Website</h1>
        <a href="posts/list.php" class="btn">📋 게시판 들어가기</a>
    </div>

</body>
</html>
