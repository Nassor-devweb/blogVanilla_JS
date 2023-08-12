<?php


class Auth
{
    public $isConnect = null;
    static function verifyIsConnect()
    {
        $pdo = require_once('./class_ConnexionDb.php');
        if ($_SESSION['session']) {
            $idSession = $_SESSION['session'];
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
                $stmtDataUser = $pdo->prepare('SELECT * FROM user WHERE id_user = :id_user');
                $stmtDataUser->bindValue(':id_user', $sessionExist['id_user']);
                $stmtDataUser->execute();
                $isConnect = $stmtDataUser->fetch();
            }
        }
        return $isConnect;
    }
}
