<?php
/** @var FilmFormat[] $formats */

use App\Enum\FilmFormat;

?>

<h1 style="text-align:center; margin-bottom:20px;">Create Film</h1>

<div style="display:flex; justify-content:center;">
    <form action="/film/create" method="post" style="display:flex; flex-direction:column; gap:10px; width:300px;">

        <label>
            Name:
            <span style="position:relative; cursor:pointer;" title="The name should contain only letters, spaces, numbers, apostrophes or hyphens, max 255 characters.">ℹ️</span>
            <input type="text" name="name" required style="width:100%; padding:5px; box-sizing:border-box;">
        </label>

        <label>
            Year:
            <span style="position:relative; cursor:pointer;" title="Enter a year between 1901 and the current year plus 20 years.">ℹ️</span>
            <input type="number" name="year" value="2000" required style="width:100%; padding:5px; box-sizing:border-box;">
        </label>

        <label>
            Format:
            <select name="format" style="width:100%; padding:5px; box-sizing:border-box;">
                <?php foreach ($formats as $format): ?>
                    <option value="<?= $format->value ?>"><?= $format->label() ?></option>
                <?php endforeach; ?>
            </select>
        </label>

        <button type="submit" style="width:100%; padding:5px; box-sizing:border-box;">Create</button>
    </form>
</div>
