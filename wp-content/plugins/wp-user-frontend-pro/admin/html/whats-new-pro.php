<?php
$changelog = array(
    array(
        'version'  => 'Version 2.8.1',
        'released' => '2018-04-15',
        'changes' => array(
            array(
                'title'       => __( 'Added Tax for payments', 'wpuf-pro' ),
                'type'        => 'New',
                'description' => __( 'Now you can setup Tax rates on WPUF payments like Pay Per Post payments and Subscription Pack payments. Check the setup guideline <a href="https://wedevs.com/docs/wp-user-frontend-pro/settings/tax/" target="_blank">here</a>.', 'wpuf-pro' ),
            ),
            array(
                'title'       => __( 'Avatar image size on registration', 'wpuf-pro' ),
                'type'        => 'New',
                'description' => __( 'You can now set avatar size from User Frontend > Settings > Login/Registration > Avatar Size.', 'wpuf-pro' ),
            ),
            array(
                'title'       => __( 'Updated Stripe SDK', 'wpuf-pro' ),
                'type'        => 'Improvement',
                'description' => __( 'Updated Stripe SDK to 6.4.1. <br> Stripe module is now fully compatible with the latest Stripe API. If you are still using old API you should upgrade to latest API version from your  <a href="https://dashboard.stripe.com/developers" target="_blank">Stripe Dashboard</a>. Older API should work fine as well but it\'s recommended that you upgrade soon.', 'wpuf-pro' ),
            ),
            array(
                'title'       => __( 'Registration confirmation URL wasn\'t redirecting to login page', 'wpuf-pro' ),
                'type'        => 'Fix',
                'description' => __( "Registration confirmation link now redirects users to Login page set in settings.", 'wpuf-pro' ),
            ),
            array(
                'title'       => __( 'Date format in coupon', 'wpuf-pro' ),
                'type'        => 'Fix',
                'description' => __( 'Coupon date format was not compatible with WordPress date format. Now it works with WordPress date format.', 'wpuf-pro' ),
            ),
            array(
                'title'       => __( 'User directory search query', 'wpuf-pro' ),
                'type'        => 'Fix',
                'description' => __( 'User directory search was not working for custom fields is fixed now.', 'wpuf-pro' ),
            ),
            array(
                'title'       => __( 'Showing country code on the frontend instead of country name', 'wpuf-pro' ),
                'type'        => 'Fix',
                'description' => __( 'Country field was showing country code which is irrelevant, now it will show country name on the frontend.', 'wpuf-pro' ),  
            ),
            array(
                'title'       => __( 'Fixed google callback in social login', 'wpuf-pro' ),
                'type'        => 'Fix',
                'description' => __( "Social login with google was not working in some cases.", 'wpuf-pro' ),
            )
        )
    ),
    array(
        'version'  => 'Version 2.8.0',
        'released' => '2018-01-02',
        'changes' => array(
            array(
                'title'       => __( 'Introducing New Modules for better Integration and Workflow of your Forms', 'wpuf-pro' ),
                'type'        => 'New',
                'description' => '<ul> 
                                    <li style="margin-bottom: 5px"><b><i style="color: #1794CE;">Personal Package </i>: MailPoet 2</b></li>
                                    <li style="margin-bottom: 5px"><b><i style="color: #20C5BA;">Professional Package </i>: MailPoet 3 , Campaign Monitor, GetResponse & HTML Email Templates</b></li>
                                    <li style="margin-bottom: 5px"><b><i style="color: #F16E58">Business Package Exclusive </i> : Private Messaging, Zapier, Convert Kit & User Activity</b></li>
                                  </ul>
                                  <br>
                                  <a href="https://wedevs.com/in/wpuf-v2-8" target="_blank"> Click here to read more </a>'
            ),
            array(
                'title'       => __( 'Admin approval for newly Registered users', 'wpuf-pro' ),
                'type'        => 'New',
                'description' => __( 'A new option added on registration form settings to approve user by admin. You can make a user pending before approved by admin.', 'wpuf-pro' ) .
                '<br><br><iframe width="100%" height="372" src="https://www.youtube.com/embed/jJ05767-Ew4" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen></iframe>'
            ),
            array(
                'title'       => __( 'Subscription expire notification', 'wpuf-pro' ),
                'type'        => 'New',
                'description' => __( 'Add new notification for subscription expiration. User will get custom email after subscription expiration.', 'wpuf-pro' ) .
                '<br><br><iframe width="100%" height="372" src="https://www.youtube.com/embed/jotTY4FCHsk" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen></iframe>'
            ),
            array(
                'title'       => __( 'Form submission with Captcha field', 'wpuf-pro' ),
                'type'        => 'Improvement',
                'description' => __( 'Form field validation process updated if form submits with captcha field.', 'wpuf-pro' ),
            ),
            array(
                'title'       => __( 'Confirmation email not sent while email module is deactivated', 'wpuf-pro' ),
                'type'        => 'Fix',
                'description' => __( 'Users were not receiving confirmation email if the email module is deactivated, this issue is fixed now', 'wpuf-pro' ),
            ),
            array(
                'title'       => __( 'Various other bug fixed and improvements are done', 'wpuf-pro' ),
                'type'        => 'Fix',
                'description' => __( 'For more details see the Changelog.', 'wpuf-pro' ),
            ),
        )
    )
);

if ( ! function_exists( '_wpuf_changelog_content' ) ) {
    function _wpuf_changelog_content( $content ) {
        $content = wpautop( $content, true );

        return $content;
    }
}

?>

<div class="wrap wpuf-whats-new">
    <h1><?php _e( 'What\'s New in WPUF Pro?', 'wpuf' ); ?></h1>

    <div class="wedevs-changelog-wrapper">

        <?php foreach ( $changelog as $release ) { ?>
            <div class="wedevs-changelog">
                <div class="wedevs-changelog-version">
                    <h3><?php echo esc_html( $release['version'] ); ?></h3>
                    <p class="released">
                        (<?php echo human_time_diff( time(), strtotime( $release['released'] ) ); ?> ago)
                    </p>
                </div>
                <div class="wedevs-changelog-history">
                    <ul>
                        <?php foreach ( $release['changes'] as $change ) { ?>
                            <li>
                                <h4>
                                    <span class="title"><?php echo esc_html( $change['title'] ); ?></span>
                                    <span class="label <?php echo strtolower( $change['type'] ); ?>"><?php echo esc_html( $change['type'] ); ?></span>
                                </h4>

                                <div class="description">
                                    <?php echo _wpuf_changelog_content( $change['description'] ); ?>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        <?php } ?>
    </div>

</div>
