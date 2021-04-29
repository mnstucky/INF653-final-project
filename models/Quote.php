<?php
class Quote
{
    private $conn;
    private $table = 'quotes';

    public $id;
    public $quote;
    public $authorId;
    public $authorName;
    public $categoryId;
    public $categoryName;
    public $limit;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get all quotes
    public function read()
    {
        $query = 'SELECT 
                c.category as category, 
                a.author as author,
                q.id,
                q.quote
            FROM ' . $this->table . ' q 
            LEFT JOIN
                categories c ON q.categoryId = c.id
            LEFT JOIN
                authors a ON q.authorId = a.id';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readByAuthor()
    {
        $query = 'SELECT 
                c.category as category, 
                a.author as author,
                q.id,
                q.quote
            FROM ' . $this->table . ' q 
            LEFT JOIN
                categories c ON q.categoryId = c.id
            LEFT JOIN
                authors a ON q.authorId = a.id
            WHERE authorId = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->authorId);
        $stmt->execute();
        return $stmt;
    }

    public function readByCategory()
    {
        $query = 'SELECT 
                c.category as category, 
                a.author as author,
                q.id,
                q.quote
            FROM ' . $this->table . ' q 
            LEFT JOIN
                categories c ON q.categoryId = c.id
            LEFT JOIN
                authors a ON q.authorId = a.id
            WHERE categoryId = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->categoryId);
        $stmt->execute();
        return $stmt;
    }

    public function readByAuthorAndCategory()
    {
        $query = 'SELECT 
                c.category as category, 
                a.author as author,
                q.id,
                q.quote
            FROM ' . $this->table . ' q 
            LEFT JOIN
                categories c ON q.categoryId = c.id
            LEFT JOIN
                authors a ON q.authorId = a.id
            WHERE categoryId = :categoryId AND authorId = :authorId';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':categoryId', $this->categoryId);
        $stmt->bindParam(':authorId', $this->authorId);
        $stmt->execute();
        return $stmt;
    }

    public function readWithLimit()
    {
        $query = 'SELECT 
                c.category as category, 
                a.author as author,
                q.id,
                q.quote
            FROM ' . $this->table . ' q 
            LEFT JOIN
                categories c ON q.categoryId = c.id
            LEFT JOIN
                authors a ON q.authorId = a.id
            LIMIT ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    // Get single quote
    public function readOne()
    {
        $query = 'SELECT 
                c.category as category, 
                a.author as author,
                q.id,
                q.quote
            FROM ' . $this->table . ' q 
            LEFT JOIN
                categories c ON q.categoryId = c.id
            LEFT JOIN
                authors a ON q.authorId = a.id
            WHERE 
                q.id = ?
            LIMIT 0,1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->category = $row['category'];
        $this->author = $row['author'];
        $this->id = $row['id'];
        $this->quote = $row['quote'];
    }

    public function create()
    {
        $query = 'INSERT INTO ' . $this->table . '
            SET
                categoryId = :categoryId,
                authorId = :authorId,
                quote = :quote';
        $stmt = $this->conn->prepare($query);
        $this->categoryId = htmlspecialchars(strip_tags($this->categoryId));
        $this->authorId = htmlspecialchars(strip_tags($this->authorId));
        $this->quote = htmlspecialchars(strip_tags($this->quote));
        $stmt->bindParam(':categoryId', $this->categoryId);
        $stmt->bindParam(':authorId', $this->authorId);
        $stmt->bindParam(':quote', $this->quote);
        if ($stmt->execute()) {
            return true;
        }
        printf('Error: %s.\n', $stmt->error);
        return false;
    }

    public function update()
    {
        $query = 'UPDATE ' . $this->table . '
            SET
                categoryId = :categoryId,
                authorId = :authorId,
                quote = :quote
            WHERE
                id = :id';
        $stmt = $this->conn->prepare($query);
        $this->categoryId = htmlspecialchars(strip_tags($this->categoryId));
        $this->authorId = htmlspecialchars(strip_tags($this->authorId));
        $this->quote = htmlspecialchars(strip_tags($this->quote));
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':categoryId', $this->categoryId);
        $stmt->bindParam(':authorId', $this->authorId);
        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':id', $this->id);
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        }
        printf('Error: %s.\n', $stmt->error);
        return false;
    }

    public function delete()
    {
        $query = 'DELETE FROM ' . $this->table . ' 
            WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        }
        printf('Error: %s.\n', $stmt->error);
        return false;
    }
}
