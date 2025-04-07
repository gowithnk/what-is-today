<div class="wrap">
    <h1>What is Today - Settings</h1>
    <form method="post" action="options.php">
        <?php settings_fields('wit_settings_group'); ?>
        <?php $settings = get_option('wit_settings', []); ?>
        
        <table class="form-table">
            <tr>
                <th scope="row">Number of Upcoming Days to Show</th>
                <td>
                    <input type="number" name="wit_settings[days_to_show]" value="<?php echo esc_attr($settings['days_to_show'] ?? 0); ?>" min="0" max="10" />
                    <p class="description">Set how many upcoming days (after today) you want to display.</p>
                </td>
            </tr>

            <tr>
                <th scope="row">Select Theme</th>
                <td>
                    <select name="wit_settings[theme]">
                        <option value="default" <?php selected($settings['theme'] ?? '', 'default'); ?>>Default</option>
                        <option value="tricolor" <?php selected($settings['theme'] ?? '', 'tricolor'); ?>>Indian Tricolor</option>
                        <option value="custom" <?php selected($settings['theme'] ?? '', 'custom'); ?>>Custom CSS</option>
                    </select>
                </td>
            </tr>

            <tr>
                <th scope="row">Custom CSS</th>
                <td>
                    <textarea name="wit_settings[custom_css]" rows="5" cols="50"><?php echo esc_textarea($settings['custom_css'] ?? ''); ?></textarea>
                </td>
            </tr>
        </table>

        <?php submit_button(); ?>
    </form>
</div>
