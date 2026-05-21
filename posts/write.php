<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>새 글 작성</title>
</head>
<body>

    <h2>📝 새 글 작성</h2>
    <form action="write_process.php" method="POST">
        <p>
            <label>작성자: </label><br>
            <input type="text" name="username" placeholder="이름을 입력하세요" required>
        </p>
        <p>
            <label>제목: </label><br>
            <input type="text" name="title" style="width: 500px;" placeholder="제목을 입력하세요" required>
        </p>
        <p>
            <label>본문: </label><br>
            <textarea name="content" rows="10" style="width: 500px;" placeholder="내용을 입력하세요" required></textarea>
        </p>
        <hr>
        <button type="submit">저장하기</button>
        <a href="list.php">취소</a>
    </form>

</body>
</html>
