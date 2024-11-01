<?php 
/**
 * Get innocard terminals and contracts
 */
try {
    $innocard = get_innocard_api_instance();
    $innocard->auth();

    $terminals = $innocard->terminals();

} catch (Exception $e) {
    
    error_log('');
    error_log( $e->getMessage() );
    error_log('');

    echo '<div class="error"><p>',
        sprintf(
            __( 'We are unable to connect to Innocard service. %s Verify your settings %s or try again later' , 'wc-innocard-integration'),
            '<a href="?page=' . esc_html($this->plugin_slug) . '&tab=">',
            '</a>'
        ),
    '</p></div>';
    return;
}

?>

<form action="?page=<?php echo esc_html($this->plugin_slug) ?>&tab=account" method="post">
    
    <input type="hidden" name="wc_innocard_integration_action" value="account">

    <table class="form-table">
        
        <tr>
            <th>
                <label><?php echo __('Terminal', 'wc-innocard-integration') ?></label>
            </th>
            <td>
                <select name="innocard_terminal" id="">
                    <option value=""> - <?php echo __('Terminal', 'wc-innocard-integration') ?> - </option>
                    <?php foreach ( $terminals->LOINTerminalSelect->Terminals as $terminal ): 
                        if( substr($terminal->Terminal->TrmID, 0, 2) != 'WS' ) continue; ?>
                        <option 
                        value="<?php echo esc_attr($terminal->Terminal->TrmID) ?>"
                        <?php selected( isset($_POST['innocard_terminal'] ) ? esc_attr($_POST['innocard_terminal']) : esc_html($this->getOption('terminal')) , $terminal->Terminal->TrmID, TRUE ); ?>
                        ><?php echo esc_html($terminal->Terminal->CompanyName) ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th>
                <button class="button-primary">
                    <?php echo __('Save Changes', 'wc-innocard-integration') ?>
                </button>
            </th>
        </tr>
    </table>

</form>