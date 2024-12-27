<?php
// 데이터베이스 연결 설정
$host = 'localhost'; // XAMPP의 기본 호스트
$port = 3306;        // MariaDB의 기본 포트
$dbname = 'db';      // 데이터베이스 이름
$username = 'root';  // MariaDB 기본 사용자 이름
$password = '';      // MariaDB 기본 비밀번호 (기본값은 비워둠)

try {
    // PDO 객체를 사용한 데이터베이스 연결
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8";
    $pdo = new PDO($dsn, $username, $password);

    // PDO 오류 모드 설정 (예외를 던지도록 설정)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 성공 메시지와 데이터베이스 목록 가져오기
    echo "<h2>데이터베이스 연결 성공!</h2>";

    // 현재 데이터베이스의 'users' 테이블 데이터 가져오기
    echo "<h3>'users' 테이블 데이터:</h3>";
    $stmt = $pdo->query("SELECT * FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($users) > 0) {
        // 'users' 테이블 데이터 출력
        echo "<table border='1' cellpadding='10' cellspacing='0'>";
        echo "<tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Created At</th>
                <th>Updated At</th>
              </tr>";
        foreach ($users as $user) {
            echo "<tr>
                    <td>{$user['user_id']}</td>
                    <td>{$user['username']}</td>
                    <td>{$user['email']}</td>
                    <td>{$user['created_at']}</td>
                    <td>{$user['updated_at']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>'users' 테이블에 데이터가 없습니다.</p>";
    }
} catch (PDOException $e) {
    // 연결 실패 시 오류 메시지 출력
    die("데이터베이스 연결 실패: " . $e->getMessage());
}
?>
