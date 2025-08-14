<style>
    .film-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .film-card {
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 15px;
        background-color: #f9f9f9;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        text-decoration: none;
        color: inherit;
        display: block;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .film-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .film-card h2 {
        margin-top: 0;
        font-size: 1.3em;
        color: #333;
    }

    .film-meta {
        font-size: 0.9em;
        color: #555;
        margin: 5px 0;
    }

    .top-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 20px;
    }

    .top-actions a,
    .top-actions button {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        border: 1px solid #ccc;
        background-color: #eee;
        text-decoration: none;
        color: black;
        cursor: pointer;
        height: 34px; /* same height as inputs for uniformity */
    }

    .top-actions a:hover,
    .top-actions button:hover,
    .search-container button:hover {
        background-color: #ddd;
    }

    .top-actions form {
        display: inline-flex;
        align-items: stretch;
        gap: 5px;
    }

    .top-actions input[type="file"] {
        border: 1px solid #ccc;
        padding: 5px;
        height: 34px; /* match button height */
        box-sizing: border-box;
    }

    .search-container {
        text-align: center;
        margin: 20px 0;
    }

    .search-container form {
        display: inline-flex;
        align-items: stretch; /* Make input and button same height */
    }

    .search-container input[type="text"] {
        padding: 5px;
        width: 250px;
        border: 1px solid #ccc;
        border-right: none;
        box-sizing: border-box;
    }

    .search-container button {
        padding: 0 10px;
        border: 1px solid #ccc;
        background-color: #eee;
        cursor: pointer;
    }

</style>

<div class="top-actions">
    <div>
        <a href="film/create">Add New Film</a>
    </div>
    <div>
        <form action="/import" method="post" enctype="multipart/form-data">
            <input type="file" name="films_file" accept=".txt" required>
            <button type="submit">Import Films</button>
        </form>
    </div>
</div>

<div class="search-container">
    <form method="get" action="/search">
        <input type="text" name="q"
               value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>"
               placeholder="Search by film or actor">
        <button type="submit">Search</button>
    </form>
</div>

<div class="film-list">
    <?php if (!empty($films)): ?>
        <?php foreach ($films as $film): ?>
            <a href="film/detail?id=<?= urlencode($film->getId()) ?>" class="film-card">
                <h2><?= htmlspecialchars($film->getName()) ?></h2>
                <div class="film-meta">Year: <?= htmlspecialchars($film->getYear()) ?></div>
                <div class="film-meta">Format: <?= htmlspecialchars($film->getFormatName()) ?></div>
            </a>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No films found.</p>
    <?php endif; ?>
</div>
