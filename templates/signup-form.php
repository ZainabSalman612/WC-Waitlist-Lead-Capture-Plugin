<div class="waitlist-form-container">
    <h3>Join the Waitlist</h3>
    <form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post" id="waitlist-signup-form">
        <input type="hidden" name="action" value="waitlist_signup">
        <?php wp_nonce_field('waitlist_signup', 'waitlist_signup_nonce'); ?>
        <p>
            <label>Name (Required)</label>
            <input type="text" name="name" required>
        </p>
        <p>
            <label>Email (Required)</label>
            <input type="email" name="email" required>
        </p>
        <p>
            <label>Phone (Optional)</label>
            <input type="tel" name="phone">
        </p>
        <p>
            <label>Password (Required)</label>
            <input type="password" name="password" required>
        </p>
        <p>
            <label>Why are you interested? (Optional)</label>
            <textarea name="interest" rows="3"></textarea>
        </p>
        <p>
            <button type="submit" class="waitlist-btn">Join Waitlist & Create Account</button>
        </p>
    </form>
    <?php if (isset($_GET['signup_error'])): ?>
        <p class="waitlist-error">Error: <?php echo esc_html($_GET['signup_error']); ?></p>
    <?php endif; ?>
</div>
