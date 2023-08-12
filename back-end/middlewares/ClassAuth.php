<?php
require_once('../middlewares/class_ConnexionDb.php');

class Auth
{
    static $isConnect = null;
    static function verifyIsConnect()
    {

        $pdo = ConnexionDb::connectDb();
        if (isset($_COOKIE['session'])) {
            $idSession = $_COOKIE['session'];
            $stmt = $pdo->prepare('SELECT * FROM session where id_session = :id_session');
            $stmt->bindValue(':id_session', $idSession);
            $sessionExist = null;
            try {
                $stmt->execute();
                $sessionExist = $stmt->fetch();
            } catch (PDOException $err) {
                echo $err->getMessage();
            }
            if (!is_null($sessionExist)) {
                $stmtDataUser = $pdo->prepare('SELECT * FROM user WHERE user_id = :user_id');
                $stmtDataUser->bindValue(':user_id', $sessionExist['id_user']);
                $stmtDataUser->execute();
                self::$isConnect = $stmtDataUser->fetch();
            }
        }
        return self::$isConnect;
    }
}
