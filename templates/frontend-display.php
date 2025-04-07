<?php
$today = date('m-d');
$days = wit_get_special_days();
$matches = array_filter($days, fn($day) => $day['date'] === $today);
?>

<div class="wit-container">
    <?php if (!empty($matches)) : ?>
        <?php foreach ($matches as $day) : ?>
            <div class="wit-day-card">
                <h2><?php echo esc_html($day['title']); ?></h2>
                <p><?php echo esc_html($day['description']); ?></p>
                <span class="wit-category"><?php echo esc_html($day['category']); ?></span>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <p>No special days today!</p>
    <?php endif; ?>
</div>
