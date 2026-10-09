<?php
/** Audited page overrides for WPCode Lite 2.3.9 and 2.4.0. Inherit all saving logic. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/** Suppress premium promotion while inheriting the upstream settings workflow. */
class DDW_PWL_Settings_Page extends WPCode_Admin_Page_Settings {
    /** Return the neutral Lite license explanation.
     * @return string Escaped informational HTML.
     */
    public function get_license_key_input() {
        return '<div class="wpcode-metabox-form"><p>' . esc_html__( 'WPCode Lite does not require a license.', 'purify-wpcode-lite' ) . '</p></div>';
    }
    /** Omit the premium-only php load as file setting upsell output.
     * @return void Outputs no promotional markup.
     */
    public function php_load_as_file_setting_upsell() {}
    /** Output usable free error logging without premium email promotion.
     * @return void Outputs the inherited logging field.
     */
    public function error_view_fields() { $this->error_logging_field(); }
}

/** Suppress premium promotion while inheriting the upstream snippet workflow. */
class DDW_PWL_Snippet_Page extends WPCode_Admin_Page_Snippet_Manager {
    /**
     * Retain free static snippet cards and omit the unavailable AI card.
     * @param string $default_category Default upstream category slug.
     * @return array Filtered static snippet card definitions.
     */
    public function get_static_snippet_items( $default_category ) {
        return array_values( array_filter( parent::get_static_snippet_items( $default_category ), /**
         * Identify cards that remain useful in Lite.
         * @param array $item Upstream static card definition.
         * @return bool Whether the card is retained.
         */
        static function ( $item ) {
            return ! in_array( 'wpcode-library-item-ai-not-available', isset( $item['extra_classes'] ) ? $item['extra_classes'] : array(), true );
        } ) );
    }
    /** Omit the unavailable AI button.
     * @param string $class Upstream optional button class.
     * @return void Outputs no premium control.
     */
    public function ai_generate_button( $class = 'wpcode-button-ai-not-available' ) {}
    /** Omit the premium-only save to library button output.
     * @return void Outputs no promotional markup.
     */
    public function save_to_library_button() {}
    /** Omit the premium-only field device type output.
     * @return void Outputs no promotional markup.
     */
    public function field_device_type() {}
    /** Omit the premium-only field code revisions output.
     * @return void Outputs no promotional markup.
     */
    public function field_code_revisions() {}
    /** Omit the premium-only get input row schedule output.
     * @return void Outputs no promotional markup.
     */
    public function get_input_row_schedule() {}
    /** Omit the premium-only get input row as file output.
     * @return void Outputs no promotional markup.
     */
    public function get_input_row_as_file() {}
    /** Omit the premium-only get input row compress output output.
     * @return void Outputs no promotional markup.
     */
    public function get_input_row_compress_output() {}
    /** Omit the premium-only get input row custom shortcode output.
     * @return void Outputs no promotional markup.
     */
    public function get_input_row_custom_shortcode() {}
}

/** Suppress premium promotion while inheriting the upstream headers workflow. */
class DDW_PWL_Headers_Page extends WPCode_Admin_Page_Headers_Footers {
    /** Omit the premium-only notice wpconsent output.
     * @return void Outputs no promotional markup.
     */
    public function notice_wpconsent() {}
    /** Omit the premium-only revisions box output.
     * @return void Outputs no promotional markup.
     */
    public function revisions_box() {}
}
