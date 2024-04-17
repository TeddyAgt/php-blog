<?php
class ArticleDB
{
    private $statementFetchOne;
    private $statementFetchAll;
    private $statementCreateOne;
    private $statementUpdateOne;
    private $statementDeleteOne;

    function __construct(private PDO $pdo)
    {

        $this->statementFetchOne = $pdo->prepare("SELECT * FROM article WHERE article_id=:articleId");

        $this->statementFetchAll = $pdo->prepare("SELECT * FROM article");

        $this->statementCreateOne = $pdo->prepare("INSERT INTO article (
            article_title,
            article_image,
            article_category,
            article_content,
            article_author
        ) VALUES (
            :title,
            :image,
            :category,
            :content,
            :userId
        )");

        $this->statementUpdateOne = $pdo->prepare("UPDATE article SET 
            article_title=:title,
            article_image=:image,
            article_category=:category,
            article_content=:content
            WHERE article_id=:articleId
            ");

        $this->statementDeleteOne = $pdo->prepare("DELETE FROM article WHERE article_id=:articleId");
    }

    public function fetchOne(int $articleId)
    {
        $this->statementFetchOne->bindValue(":articleId", $articleId);
        $this->statementFetchOne->execute();
        return $this->statementFetchOne->fetch();
    }

    public function fetchAll()
    {
        $this->statementFetchAll->execute();
        return $this->statementFetchAll->fetchAll();
    }

    public function createOne($article)
    {
        $this->statementCreateOne->bindvalue(":title", $article["article_title"]);
        $this->statementCreateOne->bindvalue(":image", $article["article_image"]);
        $this->statementCreateOne->bindvalue(":category", $article["article_category"]);
        $this->statementCreateOne->bindvalue(":content", $article["article_content"]);
        $this->statementCreateOne->bindvalue(":userId", $article["article_author"]);
        $this->statementCreateOne->execute();
        return $this->fetchOne($this->pdo->lastInsertId());
    }

    public function updateOne($article)
    {
        $this->statementUpdateOne->bindvalue(":title", $article["article_title"]);
        $this->statementUpdateOne->bindvalue(":image", $article["article_image"]);
        $this->statementUpdateOne->bindvalue(":category", $article["article_category"]);
        $this->statementUpdateOne->bindvalue(":content", $article["article_content"]);
        $this->statementUpdateOne->bindvalue(":articleId", $article["article_id"]);
        $this->statementUpdateOne->execute();
        return $article;
    }

    public function deleteOne(int $articleId)
    {
        $this->statementDeleteOne->bindvalue(":articleId", $articleId);
        $this->statementDeleteOne->execute();
        return $articleId;
    }
}

return new ArticleDB($pdo);
