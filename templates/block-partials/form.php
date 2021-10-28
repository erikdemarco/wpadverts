<?php 
/*
 *
 * 
 * @var $redirect_to
 * @var $atts
 * @var $form
 * @var $buttons_position
 */ 

$redirect_to = "";
$atts = array();
$buttons_position = "atw-flex-row";

?>

<div class="wpadverts-block wpadverts-partial">
    <form action="<?php echo esc_attr( $redirect_to ) ?>" method="get" class="wpadverts-form <?php echo wpadverts_block_form_styles( $atts ) ?>  atw-block atw-py-0">
        
        <?php foreach($form->get_fields( array( "type" => array( "adverts_field_hidden" ) ) ) as $field): ?>
        <?php call_user_func( adverts_field_get_renderer($field), $field, $form ) ?>
        <?php endforeach; ?>
        
        
        <div class="atw-flex atw-flex-col md:<?php echo $buttons_position ?>">
            
            <div class="md:atw-flex-grow md:atw--mx-1">
                <div class="atw-flex atw-flex-wrap atw-items-end atw-justify-between atw-py-0 atw-px-0">
                    <?php foreach($form->get_fields() as $field): ?>
                    <?php $width = $this->_get_field_width( $field ) ?>
                    <?php $pr = $pl = ""; ?>
                    <div class="atw-relative atw-items-end atw-box-border atw-pb-3 atw-px-1 <?php echo esc_attr( $width ) ?>">
                        <?php if( isset( $field["label"] ) && ! empty( $field["label"] ) ): ?>
                        <span class="atw-block atw-w-full atw-box-border atw-px-2 atw-py-0 atw-pb-1 atw-text-base atw-text-gray-600 adverts-search-input-label"><?php echo esc_html( $field["label"] ) ?></span>
                        <?php endif; ?>
                        <?php $field["class"] = isset( $field["class"] ) ?  $field["class"] : ""; ?>
                        <?php $field["class"] .= " atw-text-base atw-w-full atw-max-w-full"; ?>
                        <div class="atw-block atw-w-full">
                            <?php $r = adverts_field_get_renderer($field); ?>
                            <?php $r = function_exists( $r . "_block" ) ? $r . "_block" : $r; ?>
                            <?php call_user_func( $r, $field, $form ) ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

            </div>
            
            <div class="atw-flex atw-pb-3 md:atw-flex-none <?php echo $buttons_position == "atw-flex-row" ? "md:atw-ml-2" : "" ?>">
        
                
                <div class="atw-flex-auto">
                    <?php echo wpadverts_block_button( array( "text" => __("Search", "wpadverts"), "icon" => "fa-search", "type" => "primary" ), $atts["primary_button"] ) ?>
                </div>

            </div>
            
        </div>
        
        
    </form>
</div>