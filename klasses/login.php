<?php 
/*
Versie          : 1.0
Datum           : 28 januari 2026
Omschrijving    : Login class voor authenticatie
*/

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class login {
    private $conn;
    private $table = "klant";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function requestPasswordReset($email) {
        $token = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", strtotime("+1 hour"));

        $stmt = $this->conn->prepare("
            UPDATE {$this->table}
            SET reset_token = :token,
                reset_expires = :expires
            WHERE email = :email
        ");
        $stmt->execute([
            ":token" => $token,
            ":expires" => $expires,
            ":email" => $email
        ]);

        if ($stmt->rowCount() > 0) {
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
            $host = $_SERVER['HTTP_HOST'];
            $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
            $link = "$protocol://$host$scriptDir/reset_password.php?token=$token";
            return $this->sendResetEmail($email, $link);
        }

        return true; 
    }

   
    private function sendResetEmail($email, $link) {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username = "6035835.mbo@gmail.com";
            $mail->Password = "xhyh ahrz bndm qaxf";
            $mail->SMTPSecure = "tls";
            $mail->Port = 587;

            $mail->setFrom("6035835.mbo@gmail.com", "kringloopwinkel");
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = "Reset je wachtwoord";
            $mail->Body = "
                <p>Geachte heer/mevrouw, </p>
                <p>U heeft verzocht uw wachtwoord te wijzigen, dit kan via de volgende url:</p>
                <a href='$link'>$link</a>
                <p>Deze link verloopt over 1 uur.</p>
                <p>Met vriendelijke groet, </p> 
                <p>kringloopwinkel</p>";

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    
    public function resetPassword($token, $newPassword) {
        $stmt = $this->conn->prepare("
            SELECT * FROM {$this->table}
            WHERE reset_token = :token
            AND reset_expires > NOW()
        ");
        $stmt->execute([":token" => $token]);
        $user = $stmt->fetch();

        if (!$user) return false;

        $hash = password_hash($newPassword, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare("
            UPDATE {$this->table}
            SET password = :pass,
                reset_token = NULL,
                reset_expires = NULL
            WHERE id = :id
        ");
        $stmt->execute([
            ":pass" => $hash,
            ":id" => $user['id']
        ]);

        return true;
    }

    public function register($naam, $adres, $plaats, $telefoon, $email, $password) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO {$this->table} (naam, adres, plaats, telefoon, email, password)
                VALUES (:naam, :adres, :plaats, :telefoon, :email, :password)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":naam" => $naam,
            ":adres" => $adres,
            ":plaats" => $plaats,
            ":telefoon" => $telefoon,
            ":email" => $email,
            ":password" => $hashed
        ]);
    }

    public function login($email, $password) {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':email' => $email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }
}
