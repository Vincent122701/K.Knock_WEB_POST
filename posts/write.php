//게시글 작성 화면 만들기 write.php
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>새 글 작성</title>
</head>
<body>
    <h2>📝 새 글 작성</h2>
    //form 태그의 의미
    //입력 묶음 상자
    //action~ : 사용자가 버튼을 누르면 상자 안의 내용들을 처리할 백엔드
    //파일 주소를 지정
    //method="POST" : POST형식으로 전송
    <form action="write_process.php" method="POST">
        <p>
            <label>제목: </label><br>
            <input type="text" name="title" style="width: 500px;" required>
        </p>
        <p>
            <label>본문: </label><br>
            <textarea name="content" rows="10" style="width: 500px;" required></textarea>
        </p>
        <button type="submit">저장하기</button>
        <a href="../index.php">취소</a>
    </form>
</body>
</html>

//1. 아파치가 리눅스 서버에 저장된 write.php 파일을 연다
//2. php 코드가 없으니 apache는 그대로 브라우저에게 넘김
//3. 내 컴퓨터의 웹 브라우저가 html설정에 따라 렌더링
//4. 사용자가 글을 쓰고 올리면 title과 content의 값들을 write_process.php로
//넘김
