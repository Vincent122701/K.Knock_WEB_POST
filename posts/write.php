<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>새 글 작성</title>
    <style>
        /* 기본 레이아웃 및 폰트 세팅 */
        body { 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; 
            background: #f8f9fa; 
            padding: 40px; 
            color: #212529; 
            margin: 0;
        }
        .container {
            max-width: 650px;
            margin: 0 auto;
        }

        /* 글쓰기 카드 박스 */
        .write-card {
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            border: 1px solid #e9ecef;
            box-shadow: 0 4px 10px rgba(0,0,0,0.02);
        }
        .write-card h2 {
            margin-top: 0;
            margin-bottom: 30px;
            font-size: 24px;
            color: #343a40;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 15px;
        }

        /* 입력 폼 레이아웃 */
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-weight: 600;
            font-size: 14px;
            color: #495057;
            margin-bottom: 8px;
        }
        
        /* 인풋 및 텍스트에어리어 스타일 스타일 */
        .form-control {
            width: 100%;
            padding: 12px 16px;
            font-size: 14px;
            border: 1px solid #ced4da;
            border-radius: 8px;
            background-color: #fff;
            outline: none;
            box-sizing: border-box;
            transition: border-color 0.15s ease-in-out;
        }
        .form-control:focus {
            border-color: #228be6; /* 포커스 시 모던 블루 테두리 */
        }
        textarea.form-control {
            resize: vertical; /* 세로 크기 조절만 허용 */
            font-family: inherit;
        }

        /* 하단 구분선 및 버튼 그룹 */
        .divider {
            margin: 30px 0 20px 0;
            border: 0;
            border-top: 1px solid #e9ecef;
        }
        .btn-group {
            display: flex;
            justify-content: flex-end;
            gap: 12px; /* 버튼 사이 간격 */
            align-items: center;
        }

        /* 버튼 기본 스타일 */
        .btn {
            display: inline-block;
            padding: 12px 24px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            border-radius: 8px;
            cursor: pointer;
            border: none;
            transition: background 0.15s ease-in-out;
        }
        /* 저장하기 (블루) */
        .btn-submit {
            background: #228be6;
            color: white;
        }
        .btn-submit:hover {
            background: #1c7ed6;
        }
        /* 취소 (그레이) */
        .btn-cancel {
            background: #e9ecef;
            color: #495057;
        }
        .btn-cancel:hover {
            background: #dee2e6;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="write-card">
        <h2>📝 새 글 작성</h2>
        
        <form action="write_process.php" method="POST">
            <div class="form-group">
                <label for="username">작성자</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="이름을 입력하세요" required>
            </div>

            <div class="form-group">
                <label for="title">제목</label>
                <input type="text" id="title" name="title" class="form-control" placeholder="제목을 입력하세요" required>
            </div>

            <div class="form-group">
                <label for="content">본문</label>
                <textarea id="content" name="content" class="form-control" rows="10" placeholder="내용을 입력하세요" required></textarea>
            </div>

            <hr class="divider">

            <div class="btn-group">
                <a href="list.php" class="btn btn-cancel">취소</a>
                <button type="submit" class="btn btn-submit">저장하기</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
