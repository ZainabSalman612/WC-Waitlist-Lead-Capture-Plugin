<div class="waitlist-form-container">
    <h3>Login</h3>
    <form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post" id="waitlist-login-form">
        <input type="hidden" name="action" value="waitlist_login">
        <?php wp_nonce_field('waitlist_login', 'waitlist_login_nonce'); ?>
        <p>
            <label>Email / Username</label>
            <input type="text" name="username" required>
        </p>
        <p>
            <label>Password</label>
            <input type="password" name="password" required>
        </p>
        <p>
            <button type="submit" class="waitlist-btn">Login</button>
        </p>
        <p>
            <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>">Lost your password?</a>
        </p>
    </form>
    <?php if (isset($_GET['login_error'])): ?>
        <p class="waitlist-error">Error: <?php echo esc_html($_GET['login_error']); ?></p>
    <?php endif; ?>
</div>
