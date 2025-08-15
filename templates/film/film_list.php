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

    /* Top bar styles */
    .top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap; /* Allows wrapping on small screens */
        background-color: #f0f0f0;
        padding: 10px 20px;
        gap: 10px;
    }

    .top-bar .left,
    .top-bar .center,
    .top-bar .right {
        display: flex;
        align-items: center;
    }

    .top-bar a,
    .top-bar button {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        border: 1px solid #ccc;
        background-color: #eee;
        text-decoration: none;
        color: black;
        cursor: pointer;
        height: 34px;
        white-space: nowrap;
    }

    .top-bar a:hover,
    .top-bar button:hover {
        background-color: #ddd;
    }

    .top-bar input[type="file"] {
        border: 1px solid #ccc;
        padding: 5px;
        height: 34px;
        box-sizing: border-box;
    }

    .top-bar form {
        display: inline-flex;
        align-items: stretch;
        gap: 5px;
    }

    /* Search box */
    .top-bar .center form {
        display: inline-flex;
        align-items: stretch;
    }

    .top-bar input[type="text"] {
        padding: 5px;
        width: 250px;
        border: 1px solid #ccc;
        border-right: none;
        box-sizing: border-box;
    }

    .top-bar .center button {
        padding: 0 10px;
        border: 1px solid #ccc;
        background-color: #eee;
        cursor: pointer;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .top-bar {
            flex-direction: column;
            align-items: stretch;
        }
        .top-bar .center {
            order: -1; /* Move search to top on mobile */
            justify-content: center;
        }
        .top-bar .left,
        .top-bar .right {
            justify-content: center;
        }
    }
</style>

<div class="top-bar">
    <div class="left">
        <a href="film/create">Add New Film</a>
    </div>

    <div class="center">
        <form method="get" action="/search">
            <input type="text" name="q"
                   value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>"
                   placeholder="Search by film or actor">
            <button type="submit">Search</button>
        </form>
    </div>

    <div class="right">
        <form action="/import" method="post" enctype="multipart/form-data">
            <input type="file" name="films_file" accept=".txt" required>
            <button type="submit">Import Films</button>
        </form>
    </div>
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
