<?php
/** @var Film $film */

use App\Entity\Film;

?>

<style>
    .detail-container {
        max-width: 600px;
        margin: 30px auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        background-color: #fafafa;
        font-family: Arial, sans-serif;
        line-height: 1.6;
    }

    .detail-container h1 {
        text-align: center;
        margin-bottom: 10px;
        font-size: 28px;
        color: #333;
    }

    .detail-container p {
        text-align: center;
        color: #555;
        margin: 5px 0;
    }

    .detail-container h2 {
        margin-top: 20px;
        font-size: 22px;
        color: #444;
        border-bottom: 1px solid #ccc;
        padding-bottom: 5px;
    }

    .detail-container ul {
        list-style: disc;
        padding-left: 20px;
        margin-top: 10px;
    }

    .detail-container ul li {
        margin-bottom: 5px;
    }

    .detail-container a {
        display: inline-block;
        margin-top: 20px;
        padding: 6px 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        background-color: #eee;
        text-decoration: none;
        color: #333;
    }

    .detail-container a:hover {
        background-color: #ddd;
    }

    .delete-link {
        display: inline-block;
        margin-top: 20px;
        padding: 6px 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        background-color: #eee;
        text-decoration: none;
        color: #cc0000 !important;;
    }

    .delete-link:hover {
        background-color: #ffcccc !important;;
    }
</style>

<div class="detail-container">
    <h1><?= htmlspecialchars($film->getName()) ?></h1>
    <p>Year: <?= $film->getYear() ?> | Format: <?= $film->getFormatName() ?></p>

    <h2>Actors</h2>
    <?php if (!empty($film->getActors())): ?>
        <ul>
            <?php foreach ($film->getActors() as $actor): ?>
                <li><?= htmlspecialchars($actor->getName() . ' ' . $actor->getSurname()) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No actors listed for this film.</p>
    <?php endif; ?>

    <p>
        <a href="/film/edit?id=<?= urlencode($film->getId()) ?>">Edit actor list</a>
    </p>
    <p>
        <a href="/film/delete?id=<?= $film->getId() ?>" class="delete-link" onclick="return confirm('Delete this film?')">Delete Film</a>
    </p>
</div>
