<br class="clear" />
<div class="alignleft actions">
	<select name="filter_type" class="postform">
		<option value=""><?php esc_html_e( 'Select filter type', TTC_TEXT_DOMAIN ); ?></option>
		<?php
		foreach (\TCo_Three_Tomatoes\Report_Table::$defaults['filter_type'] as $key => $label ) {
			echo '<option value="'.$key.'" '. ( $_GET['filter_type'] == $key ? ' selected' : '' ). ' >'.__($label, TTC_TEXT_DOMAIN  ).'</option>';	
		}
		?>		
	</select>
	<?php esc_html_e( 'Start Date', TTC_TEXT_DOMAIN ); ?>: <input class="postform datepicker_start" type="text" value="<?php echo $_GET['start_date']; ?>" name="start_date">
	<?php esc_html_e( 'End Date', TTC_TEXT_DOMAIN ); ?>: <input class="postform datepicker_end" type="text" value="<?php echo $_GET['end_date']; ?>" name="end_date">
</div>
<div class="alignright actions">	
	<select name="search_type" class="postform">
		<option value=""><?php esc_html_e( 'Select search type', TTC_TEXT_DOMAIN ); ?></option>
		<?php
		foreach (\TCo_Three_Tomatoes\Report_Table::$defaults['search_type'] as $key => $label ) {
			echo '<option value="'.$key.'" '. ( $_GET['search_type'] == $key ? ' selected' : '' ). ' >'.__($label, TTC_TEXT_DOMAIN  ).'</option>';	
		}
		?>	
	</select>
	<input class="postform" type="text" value="<?php echo $_GET['search']; ?>" name="search" placeholder="<?php esc_html_e( 'Search keyword', TTC_TEXT_DOMAIN ); ?>">	
	<button type="submit" class="button button-secondary"> <?php esc_html_e( 'Apply Filter', TTC_TEXT_DOMAIN ); ?></button>	
</div>
<div class="clear">&nbsp;</div>
