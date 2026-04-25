<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coming Soon - <?php bloginfo( 'name' ); ?></title>
    <link rel="stylesheet" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'assets/css/frontend.css'; ?>">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }
        .maintenance-container {
            background: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            max-width: 500px;
            width: 100%;
            text-align: center;
        }
        .maintenance-container h1 {
            margin-top: 0;
            color: #333;
        }
        .maintenance-container p.desc {
            color: #666;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <h1>Coming Soon</h1>
        <p class="desc">We are currently working on something awesome. Join the waitlist below to be notified and create your account early!</p>

        <?php
        $frontend = new Waitlist_Frontend('waitlist', '1.0');
        echo $frontend->render_signup_form(); 
        ?>
        
        <p style="margin-top:20px; font-size:14px;">Already have an account? <br><br> <?php echo $frontend->render_login_form(); ?></p>
    </div>
</body>
</html>
