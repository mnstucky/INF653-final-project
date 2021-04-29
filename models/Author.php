<?php
class Author
{
    private $conn;
    private $table = 'authors';

    public $id;
    public $category;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get all authors
    public function read()
    {
        $query = 'SELECT id, author
            FROM ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Get single author
    public function readOne()
    {
        $query = 'SELECT id, author
            FROM ' . $this->table . '
            WHERE id = ?
            LIMIT 0,1'; 
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->author = $row['author'];
        $this->id = $row['id'];
    }

    public function create() {
        $query = 'INSERT INTO ' . $this->table . '
            SET
                author = :author';
        $stmt = $this->conn->prepare($query);
        $this->author = htmlspecialchars(strip_tags($this->author));
        $stmt->bindParam(':author', $this->author);
        if ($stmt->execute()) {
            return true;
        }
        printf('Error: %s.\n', $stmt->error);
        return false;
    }

    public function update() {
        $query = 'UPDATE ' . $this->table . '
            SET
                author = :author
            WHERE
                id = :id';
        $stmt = $this->conn->prepare($query);
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':id', $this->id);
        if ($stmt->execute()) {
            return true;
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
