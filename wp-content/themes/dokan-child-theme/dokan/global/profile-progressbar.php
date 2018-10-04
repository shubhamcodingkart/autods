<?php
/**
 *  Profile Progressbar template
 *
 *  @since 2.4
 *
 *  @package dokan
 */
?>
<div class="dokan-panel dokan-panel-default dokan-profile-completeness">
    <div class="dokan-panel-body">
    <div class="dokan-progress">
        <div class="dokan-progress-bar dokan-progress-bar-info dokan-progress-bar-striped" role="progressbar"
             aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $progress ?>%">
            <?php echo $progress . __( '% Profile complete', 'dokan' ) ?>
        </div>
    </div>
    <?php if( $progress != 100 ){ ?>
    	<div class="dokan-alert dokan-alert-info dokan-panel-alert">
    		<?php if( $next_todo == 'Add a Payment method to gain 30% progress'){
    			echo "<a href='".dokan_get_navigation_url( 'settings/payment' )."'>Add paypal to get 30% progress</a>";
    		}else{ ?>
    			<?php //echo $next_todo;
    			echo "<a href='".dokan_get_navigation_url( 'settings/store' )."'>".$next_todo."</a>"; 
    		}?>
    	</div>
	<?php } ?>
   </div>
</div>
