<?php

require('./config/Database.php');
require('./models/Author.php');
require('./models/Category.php');
require('./models/Quote.php');

$database = new Database();
$db = $database->connect();

$authorDB = new Author($db);
$authors = $authorDB->read();
$categoryDB = new Category($db);
$categories = $categoryDB->read();

if (!isset($categoryId)) {
    $categoryId = filter_input(
        INPUT_GET,
        'categoryId',
        FILTER_VALIDATE_INT
    );
}

if (!isset($authorId)) {
    $authorId = filter_input(
        INPUT_GET,
        'authorId',
        FILTER_VALIDATE_INT
    );
}

$quotesDB = new Quote($db);

if ($categoryId == NULL || $categoryId == FALSE) {
    if ($authorId == NULL || $authorId == FALSE) {
        $quotes = $quotesDB->read();
    } else {
        $quotesDB->authorId = $authorId;
        $quotes = $quotesDB->readByAuthor();
    }
} else {
    if ($authorId == NULL || $authorId == FALSE) {
        $quotesDB->categoryId = $categoryId;
        $quotes = $quotesDB->readByCategory();
    } else {
        $quotesDB->categoryId = $categoryId;
        $quotesDB->authorId = $authorId;
        $quotes = $quotesDB->readByAuthorAndCategory();
    }
}

$numQuotes = $quotes->rowCount();
$quotesArray = $quotes->fetchAll(PDO::FETCH_ASSOC);
shuffle($quotesArray);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matt's Quote API</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.2/css/bulma.min.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orelega+One&display=swap" rel="stylesheet">
</head>

<body style="font-family: 'Noto Sans JP', sans-serif">

    <header>
        <h1 class="is-size-1 has-text-centered" style="font-family: 'Orelega One', serif">Matt's Quotation API</h1>
    </header>

    <main class="section">
        <div class="container">
            <form class="is-flex is-justify-content-center" action="./" method="GET" >
                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label">Author</label>
                    </div>
                    <div class="field-body">
                        <div class="select">
                            <select id="authorId" name="authorId">
                                <option value="all">All</option>
                                <?php foreach ($authors as $author) { ?>
                                    <option value="<?php echo $author['id'] ?>"><?php echo $author['author'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="field is-horizontal">
                    <div class="field-label is-normal pl-4">
                        <label class="label">Category</label>
                    </div>
                    <div class="field-body">
                        <div class="select">
                            <select id="categoryId" name="categoryId">
                                <option value="all">All</option>
                                <?php foreach ($categories as $category) { ?>
                                    <option value="<?php echo $category['id'] ?>"><?php echo $category['category'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="field is-horizontal">
                    <div class="field-label">
                        <!-- Left empty for spacing -->
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <button class="button is-primary">
                                    Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="section is-flex is-justify-content-space-around is-flex-wrap-wrap">
                <?php if ($numQuotes == 0) { ?>
                    <p class="is-size-4">Sorry, no quotes match your chosen filters.</p>
                    <?php } else {
                    foreach ($quotesArray as $quote) { ?>
                        <div class="card mx-4 my-4" style="width: max(300px, 35vw);">
                            <div class="card-content">
                                <p class="title">
                                    <?php echo $quote['quote'] ?>
                                </p>
                                <p class="subtitle">
                                    <?php echo $quote['author'] ?>
                                </p>
                            </div>
                            <footer class="card-footer">
                                <p class="card-footer-item">
                                    <?php echo 'Category: ' . $quote['category'] ?>
                                </p>
                            </footer>
                        </div>
                <?php }
                } ?>
            </div>

        </div>
    </main>

    <footer>
        <p class="has-text-centered">&copy; <?php echo date("Y"); ?> Matt Stucky</p>
    </footer>
</body>

</html>