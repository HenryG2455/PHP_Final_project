<?php

require_once('dbconnect.php');
require_once(__DIR__ . '/../entities/Tag.php');


class TagAccessor
{
    public function getAllTags(): array
    {
        $result = [];
        $stmt = null;
        try {
            $conn = connect_db();
            $stmt = $conn->prepare("select * from Tag");
            $stmt->execute();
            $allTags = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($allTags as $tag) {
                $tagID = $tag['tagID'];
                $tagName = $tag['tagName'];
                $tagCategoryName = $tag['tagCategoryName'];
                $tagObj = new Tag($tagID, $tagName, $tagCategoryName);
                $result[] = $tagObj;
            }
        } catch (Exception $e) {
            $result = [];
        } finally {
            if (!is_null($stmt)) {
                $stmt->closeCursor();
            }
        }

        return $result;
    }

    public function getTagsForQuestion($questionID): array
    {
        $results = [];
        try {
            $conn = connect_db();
            $stmt = $conn->prepare("select * from QuestionTag join Tag using(tagID) where questionID = :questionID");
            $stmt->bindParam(":questionID", $questionID);
            $stmt->execute();
            $dbresults = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($dbresults as $result) {
                $obj = new Tag($result["tagID"], $result["tagName"], $result["tagCategoryName"]);
                $results[] = $obj;
            }
        } catch (Exception $e) {
            $results = [];
        } finally {
            if (!is_null($stmt)) {
                $stmt->closeCursor();
            }
        }
        return $results;
    }
}