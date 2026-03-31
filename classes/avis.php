<?php
require_once __DIR__ . '/../connection/connect.php';

class Avis
{
    private $pdo;

    public function __construct()
    {
        $db = new DBconnect();
        $this->pdo = $db->connectpdo();
    }

    public function addAvis($commentaire, $userId)
    {
        $sql = "INSERT INTO avis (commentaire, date_creation, user_id) VALUES (?, NOW(), ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$commentaire, $userId]);
    }

    public function getAvisByUserId($userId)
    {
        $sql = "SELECT * FROM avis WHERE user_id = ? AND deleted_at IS NULL";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function softDeleteAvis($avisId)
    {
        $sql = "UPDATE avis SET deleted_at = NOW() WHERE avis_id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$avisId]);
    }

    public function restoreAvis($avisId)
    {
        $sql = "UPDATE avis SET deleted_at = NULL WHERE avis_id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$avisId]);
    }

    public function deleteAvis($avisId)
    {
        $sql = "DELETE FROM avis WHERE avis_id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$avisId]);
    }
}
?>