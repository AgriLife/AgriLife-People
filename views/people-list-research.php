<?php

if ( $people->have_posts() ) : ?>
	<ul class="people-listing-ul row">
	<?php while ( $people->have_posts() ) : $people->the_post();
		$linkopen = $linkclose = '';
		if($website && !empty(get_field('ag-people-website'))){
			$linkopen = '<a href="' . get_field('ag-people-website') . '" rel="bookmark">';
			$linkclose = '</a>';
		}

		$image = get_field( 'ag-people-photo' );
		if(!$image) $image = array();
		if ( array_key_exists('sizes', $image) && array_key_exists('people_archive', $image['sizes']) ) {
			$image_src = $image['sizes']['people_archive'];
			$image_alt = the_title( '', '', false );
		} else {
			$image_src = PEOPLE_PLUGIN_DIR_URL . 'img/agrilife-default-people-image-single.png';
			$image_alt = 'No photo found';
		}

		$email = '';
		if(!empty(get_field('ag-people-email'))){
			$email = '<p class="people-email email"><a href="mailto:' . get_field( 'ag-people-email' ) . '">' . get_field( 'ag-people-email' ) . '</a></p>';
		}

		$phone = '';
		if(!empty(get_field('ag-people-phone'))){
			$phone = '<p class="people-phone tel">' . get_field( 'ag-people-phone' ) . '</p>';
		}

    // Collect job titles
    $job_titles = array();
    if( get_field( 'ag-people-title' ) ){
        $job_titles[] = get_field( 'ag-people-title' );
    }
    if( get_the_terms(get_the_ID(), 'agency') ){
	    foreach ( get_the_terms(get_the_ID(), 'agency') as $agency => $atts ) {
	        if( $atts->slug == 'extension' ){
	            $job_titles[] = 'Texas A&M AgriLife Extension';
	        } else if( $atts->slug == 'research' ){
	            $job_titles[] = 'Texas A&M AgriLife Research';
	        }
	    }
	  }

		?><li class="people-listing-item column small-12 medium-6 large-6">
			<div class="role people-container">
				<div class="people-image"><?php echo $linkopen; ?><img src="<?php echo $image_src; ?>" alt="<?php echo $image_alt; ?>" title="<?php echo $image_alt; ?>" width="70" height="70" /><?php echo $linkclose; ?></div>
				<div class="people-head">
					<h3 class="people-name" title="<?php the_title(); ?>"><?php
							echo $linkopen;
							if(isset($lastnamefirst) && $lastnamefirst === true){
								the_field( 'ag-people-last-name' );?>, <?php the_field( 'ag-people-first-name' );
							} else {
								the_field( 'ag-people-first-name' ); ?> <?php the_field( 'ag-people-last-name' );
							}
							echo $linkclose;
						?></h3>
					<h4 class="people-title"><?php echo implode( '<br>', $job_titles ); ?></h4>
				</div>
				<div class="people-contact-details"><?php
						echo $email;
						echo $phone;
					?></div>
			</div>
		</li><?php endwhile; ?>
	</ul>
<?php endif;
