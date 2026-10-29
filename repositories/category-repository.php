<?php
require_once __DIR__ . "/../config/database.php";

function getCategories()
{
  global $pdo;

  $query = "select c.id, c.name, c.description,
            count(b.id) as total_books
            from categories c
            left join books b on c.id = b.category_id
            group by c.id, c.name, c.description";
  $stmt = $pdo->prepare($query);
  $stmt->execute();

  $categories = $stmt->fetchAll();

  return $categories;
}

function getCategory()
{
  $category = [
    "id"          => 1,
    "name"        => "Fiksi",
    "description" => "Novel dan cerita rekaan",
  ];

  return $category;
}

function isCategoryExists($category_id) 
{
  global $pdo;

  $query = "select count(id) from categories where id = :category_id";

  $stmt = $pdo->prepare($query);
  $stmt->execute([
    'category_id' => $category_id
  ]);

  $isCategoryExists = $stmt->fetchColumn() > 0;

  return $isCategoryExists;
}