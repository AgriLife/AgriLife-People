<?php

class ALS_Error {

	public static function no_metabox_plugin() {

		echo '<div class="updated">';
      __(
        printf( '<p>You must install/activate the <a href="%s">Meta Box</a> plugin to use the Staff custom post type.</p>',
          'http://wordpress.org/extend/plugins/meta-box/' ),
        'agrilife'
      );
    echo '</div>';

	}

}