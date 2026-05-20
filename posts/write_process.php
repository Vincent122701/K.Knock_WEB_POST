//글쓰기 처리 백엔드
<?php
require_once '../config/db.php'; //DB 연결 통로(pdo) 가져오기

//사용자가 브라우저에서 '저장하기' 버튼을 눌러 정상적으로 데이터를
//전송(POST)했는지 판별합니다.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    //사용자가 입력한 제목과 본문, 그리고 강제로 꽂아둔 작성자 번호
    //(1번 김경훈)를 PHP 변수에 담습니다.
    $title = trim($_POST['title']);
    $content = $_POST['content'];
    $author_id = 1;

    try {
        /*
           SQL 인젝션 공격을 막기 위한 준비 단계
           
           - 만약 사용자가 제목 칸에 악성 SQL 해킹 코드를 적어서 보낸다면,
             DB가 통째로 날아갈 수 있음
           - 그래서 쿼리문에 직접 값을 넣지 않고, ':title', ':content' 같은
             '플레이스홀더'를 뚫어놓은 껍데기 명령문을 만듦
           - $pdo->prepare(...) : 이 안전한 껍데기 명령문을 MySQL에 
              먼저 보내서 안심하고 대기시키라고 지시합니다.
           - 이 대기 중인 명령서의 이름이 stmt
        */
        $sql = "INSERT INTO users (id, username, password) VALUES (:username, :password)"; 
	//posts 테이블 형태도 동일 구조
        $sql = "INSERT INTO posts (title, content, author_id) VALUES (:title, :content, :author_id)";
        $stmt = $pdo->prepare($sql);

        /*
           실행 플레이스 홀더에 실제 데이터를 매칭해서 발사하는 단계입니다.
           
           - $stmt->execute([...]) : 대기 중인 명령서($stmt)의 빵꾸 자리에 
             실제 사용자가 입력한 값($title, $content)을 안전하게 끼워 넣고
             실행하라는 명령
           - 이렇게 하면 MySQL은 전달받은 값을 '명령어'가 아니라 단순한 
             '글자(텍스트)'로만 인식하기 때문에 해킹 위험이 사라집니다.
        */
        $stmt->execute([
            ':title' => $title,
            ':content' => $content,
            ':author_id' => $author_id
        ]);

        //성공하면 브라우저에 팝업창을 띄우고 메인 화면(index.php)으로
        //강제 이동
        echo "<script>alert('글이 정상적으로 등록되었습니다.'); location.href='../index.php';</script>";
        
    } catch (PDOException $e) {
        die("DB 에러 발생: " . $e->getMessage());
    }
}
?>
