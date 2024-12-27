<?php
// 데이터베이스 연결
require 'db.conn.php';

// POST 요청 확인
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 사용자 입력값 가져오기
    $아이디 = trim($_POST['username']);
    $비밀번호 = trim($_POST['password']);
    $비밀번호확인 = trim($_POST['confirm_password']);
    $이메일 = trim($_POST['email']);

    // 유효성 검사
    if (empty($아이디) || empty($비밀번호) || empty($비밀번호확인) || empty($이메일)) {
        echo "<script>alert('모든 필드를 입력해주세요.'); history.back();</script>";
        exit();
    }

    if ($비밀번호 !== $비밀번호확인) {
        echo "<script>alert('비밀번호가 일치하지 않습니다.'); history.back();</script>";
        exit();
    }

    try {
        // 아이디 중복 체크
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $아이디);
        $stmt->execute();

        if ($stmt->fetch()) {
            echo "<script>alert('이미 사용 중인 아이디입니다.'); history.back();</script>";
            exit();
        }

        // 비밀번호 암호화
        $암호화된비밀번호 = password_hash($비밀번호, PASSWORD_DEFAULT);

        // 데이터 삽입
        $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (:username, :password, :email)");
        $stmt->execute([
            ':username' => $아이디,
            ':password' => $암호화된비밀번호,
            ':email' => $이메일
        ]);

        echo "<script>alert('회원가입이 완료되었습니다. 로그인 페이지로 이동합니다.'); window.location.href='login_page.html';</script>";
    } catch (PDOException $e) {
        echo "회원가입 중 오류가 발생했습니다: " . $e->getMessage();
    }
} else {
    // GET 요청 또는 잘못된 접근
    header("Location: Join_page.html");
    exit();
}
?>
