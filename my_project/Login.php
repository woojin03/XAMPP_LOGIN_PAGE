<?php
// 데이터베이스 연결
require 'db.conn.php';

// POST 요청 확인
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        echo "<script>alert('아이디와 비밀번호를 입력해주세요.'); history.back();</script>";
        exit();
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // 디버깅: 사용자 정보 확인
            echo "<script>console.log('사용자 데이터: " . json_encode($user) . "');</script>";

            if (password_verify($password, $user['password'])) {
                // 로그인 성공
                session_start();
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                echo "<script>alert('반갑습니다, {$user['username']} 회원님!'); window.location.href='hello.html';</script>";
            } else {
                echo "<script>alert('비밀번호가 일치하지 않습니다.'); history.back();</script>";
            }
        } else {
            echo "<script>alert('가입된 회원 정보가 없습니다.'); history.back();</script>";
        }
    } catch (PDOException $e) {
        echo "데이터베이스 오류: " . $e->getMessage();
    }
} else {
    header("Location: login_page.html");
    exit();
}
?>
