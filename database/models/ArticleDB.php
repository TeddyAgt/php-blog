<?php
class ArticleDB
{
    private PDOStatement $statementFetchOne;
    private PDOStatement $statementFetchAll;
    private PDOStatement $statementFetchUserAll;
    private PDOStatement $statementCreateOne;
    private PDOStatement $statementUpdateOne;
    private PDOStatement $statementDeleteOne;

    function __construct(private PDO $pdo)
    {

        $this->statementFetchOne = $pdo->prepare("SELECT article.*, user.user_firstname, user.user_lastname FROM article LEFT JOIN user ON article.article_author=user.user_id WHERE article.article_id=:articleId");

        $this->statementFetchAll = $pdo->prepare("SELECT article.*, user.user_firstname, user.user_lastname FROM article LEFT JOIN user ON article.article_author=user.user_id");

        $this->statementFetchUserAll = $pdo->prepare("SELECT * FROM article WHERE article_author=:userId");

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

    public function fetchOne(int $articleId): array
    {
        $this->statementFetchOne->bindValue(":articleId", $articleId);
        $this->statementFetchOne->execute();
        return $this->statementFetchOne->fetch();
    }

    public function fetchAll(): array
    {
        $this->statementFetchAll->execute();
        return $this->statementFetchAll->fetchAll();
    }

    public function fetchUserArticles($userId): array
    {
        $this->statementFetchUserAll->bindValue(":userId", $userId);
        $this->statementFetchUserAll->execute();
        return $this->statementFetchUserAll->fetchAll();
    }

    public function createOne($article): array
    {
        $this->statementCreateOne->bindvalue(":title", $article["article_title"]);
        $this->statementCreateOne->bindvalue(":image", $article["article_image"]);
        $this->statementCreateOne->bindvalue(":category", $article["article_category"]);
        $this->statementCreateOne->bindvalue(":content", $article["article_content"]);
        $this->statementCreateOne->bindvalue(":userId", $article["article_author"]);
        $this->statementCreateOne->execute();
        return $this->fetchOne($this->pdo->lastInsertId());
    }

    public function updateOne($article): array
    {
        $this->statementUpdateOne->bindvalue(":title", $article["article_title"]);
        $this->statementUpdateOne->bindvalue(":image", $article["article_image"]);
        $this->statementUpdateOne->bindvalue(":category", $article["article_category"]);
        $this->statementUpdateOne->bindvalue(":content", $article["article_content"]);
        $this->statementUpdateOne->bindvalue(":articleId", $article["article_id"]);
        $this->statementUpdateOne->execute();
        return $article;
    }

    public function deleteOne(int $articleId): string
    {
        $this->statementDeleteOne->bindvalue(":articleId", $articleId);
        $this->statementDeleteOne->execute();
        return $articleId;
    }
}

return new ArticleDB($pdo);
