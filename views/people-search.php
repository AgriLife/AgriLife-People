<?php $search_terms = get_search_query(); ?>

<div class="people-search-form">
	<label>
		<h4>Search People Database</h4>
	</label>
	<form role="search" class="people-searchform" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
		<input type="text" class="s" name="s" id="s" placeholder="<?php echo $search_terms; ?>" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;"/><br />
		<input type="hidden" name="post_type" value="people" />
	</form>
</div>
