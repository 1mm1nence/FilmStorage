<?php
/** @var Film $film */

use App\Entity\Film;

?>

<style>
    .edit-container {
        max-width: 600px;
        margin: 30px auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        background-color: #fafafa;
        font-family: Arial, sans-serif;
        line-height: 1.6;
    }

    .edit-container h1 {
        text-align: center;
        margin-bottom: 10px;
        font-size: 28px;
        color: #333;
    }

    .edit-container h3 {
        text-align: center;
        font-weight: normal;
        margin-bottom: 20px;
    }

    .edit-container h3 a {
        text-decoration: none;
        color: #007bff;
    }

    .edit-container h3 a:hover {
        text-decoration: underline;
    }

    .edit-container p {
        text-align: center;
        color: #555;
        margin: 5px 0 20px;
    }

    .actor-form {
        display: flex;
        gap: 5px;
        margin-bottom: 10px;
    }

    .actor-form input[type="text"] {
        padding: 5px;
        width: 45%;
        box-sizing: border-box;
        border: 1px solid #ccc;
        border-radius: 4px;
        background-color: #f1f1f2;
    }

    .actor-form button {
        padding: 5px 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        background-color: #eee;
        cursor: pointer;
    }

    .actor-form button:hover {
        background-color: #ddd;
    }

    .add-actor-form {
        display: flex;
        gap: 5px;
        margin-top: 20px;
    }

    .add-actor-form input[type="text"] {
        padding: 5px;
        width: 45%;
        box-sizing: border-box;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .add-actor-form button {
        padding: 5px 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        background-color: #eee;
        cursor: pointer;
    }

    .add-actor-form button:hover {
        background-color: #ddd;
    }
</style>

<div class="edit-container">
    <h3>Actors list edit page. <a href="/film/detail?id=<?= $film->getId() ?>">Back to detail view</a></h3>
    <h1><?= htmlspecialchars($film->getName()) ?></h1>
    <p>Year: <?= $film->getYear() ?> | Format: <?= $film->getFormatName() ?></p>

    <!-- List of actors with ability to remove them -->
    <?php foreach ($film->getActors() as $actor): ?>
        <form action="/film/remove-actor" method="post" class="actor-form">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
            <input type="hidden" name="film_id" value="<?= (int)$film->getId() ?>">
            <input type="hidden" name="actor_id" value="<?= (int)$actor->getId() ?>">
            <input type="text" value="<?= htmlspecialchars($actor->getName()) ?>" readonly>
            <input type="text" value="<?= htmlspecialchars($actor->getSurname()) ?>" readonly>
            <button type="submit" onclick="return confirm('Remove this actor?')">Remove</button>
        </form>
    <?php endforeach; ?>

    <!-- Add actor -->
    <form action="/film/add-actor" method="post" class="add-actor-form">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
        <input type="hidden" name="film_id" value="<?= (int)$film->getId() ?>">
        <input type="text" name="name" required placeholder="Name">
        <input type="text" name="surname" required placeholder="Surname">
        <button type="submit">Add</button>
    </form>

</div>
