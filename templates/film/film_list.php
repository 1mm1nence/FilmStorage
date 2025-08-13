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
    .actors {
        font-size: 0.9em;
        color: #444;
        margin-top: 8px;
    }
    .actors strong {
        color: #222;
    }
</style>

<div class="film-list">
    <?php if (!empty($films)): ?>
        <?php foreach ($films as $film): ?>
            <div class="film-card">
                <h2><?= htmlspecialchars($film['name']) ?></h2>
                <div class="film-meta">Year: <?= htmlspecialchars($film['year']) ?></div>
                <div class="film-meta">Format: <?= htmlspecialchars($film['format']) ?></div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No films found.</p>
    <?php endif; ?>
</div>
