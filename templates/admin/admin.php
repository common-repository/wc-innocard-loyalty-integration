<?php if ( ! current_user_can( 'manage_options' ) ) {
    return;
} ?>

<nav class="nav-tab-wrapper">
    <a href="?page=<?php echo esc_attr($this->plugin_slug) ?>&tab=" class="nav-tab <?php echo $this->tab == null ? 'nav-tab-active' : '' ?>">
        <?php echo __('Integration Settings', 'wc-innocard-integration') ?>
    </a>

    <a href="?page=<?php echo esc_attr($this->plugin_slug) ?>&tab=account" class="nav-tab <?php echo $this->tab == 'account' ? 'nav-tab-active' : '' ?>">
        <?php echo __('Account Settings', 'wc-innocard-integration') ?>
    </a>

    <a href="?page=<?php echo esc_attr($this->plugin_slug) ?>&tab=labels" class="nav-tab <?php echo $this->tab == 'labels' ? 'nav-tab-active' : '' ?>">
        <?php echo __('Checkout Labels', 'wc-innocard-integration') ?>
    </a>

</nav>

<div class="tab-content">


    <?php if ( false == empty($this->error_message) ) : ?>
        <p><div class="error"><p><?php echo esc_html($this->error_message) ?></p></div></p>
    <?php endif ?>

    <?php if ( false == empty($this->success_message) ) : ?>
        <p><div class="updated"><p><?php echo esc_html($this->success_message) ?></p></div></p>
    <?php endif ?>

    <?php 
    // replace relative paths to prevent unauthorized access to files
    $view = __DIR__ . '/views/' . ( empty($this->tab) ? 'settings' : str_replace(['.', '..', './', '../'], '', $this->tab ) ) . '.php';

    if ( file_exists($view)) require_once $view; 
    ?>
    
</div>