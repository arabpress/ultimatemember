<?php

	/***
	***	@Dates
	***/
	add_filter('um_view_label_birth_date', 'um_view_label_birth_date');
	function um_view_label_birth_date( $label ) {
		$label = __('Age','ultimatemember');
		return $label;
	}
	
	/***
	***	@Dates
	***/
	add_filter('um_profile_field_filter_hook__date', 'um_profile_field_filter_hook__date', 99, 2);
	function um_profile_field_filter_hook__date( $value, $data ) {
		global $ultimatemember;

		if ( $data['pretty_format'] == 1 ) {
			$value = $ultimatemember->datetime->get_age( $value );
		} else {
			$value = $ultimatemember->datetime->format( $value, $data['format'] );
		}
		
		return $value;
	}
	
	/***
	***	@Images
	***/
	add_filter('um_profile_field_filter_hook__file', 'um_profile_field_filter_hook__file', 99, 2);
	function um_profile_field_filter_hook__file( $value, $data ) {
		global $ultimatemember;
		
		$uri = um_user_uploads_uri() . $value;
		$extension = pathinfo( $uri, PATHINFO_EXTENSION);

		if ( !file_exists( um_user_uploads_dir() . $value ) ) {
			$value = __('This file has been removed.');
		} else {
			$value = '<div class="um-single-file-preview show">
										<div class="um-single-fileinfo">
											<a href="' . $uri  . '" target="_blank">
												<span class="icon" style="background:'. $ultimatemember->files->get_fonticon_bg_by_ext( $extension ) . '"><i class="'. $ultimatemember->files->get_fonticon_by_ext( $extension ) .'"></i></span>
												<span class="filename">' . $value . '</span>
											</a>
										</div>
							</div>';
		}
		
		return $value;
	}
	
	/***
	***	@Files
	***/
	add_filter('um_profile_field_filter_hook__image', 'um_profile_field_filter_hook__image', 99, 2);
	function um_profile_field_filter_hook__image( $value, $data ) {
	
		$uri = um_user_uploads_uri() . $value;
		$title = ( isset( $data['title'] ) ) ? $data['title'] : __('Untitled photo');
		
		if ( !file_exists( um_user_uploads_dir() . $value ) ) {
			$value = __('Image has been removed.');
		} else {
			$value = '<div class="um-photo"><a href="#"><img src="'. $uri .'" alt="'.$title.'" title="'.$title.'" class="" /></a></div>';
		}
		
		return $value;
	}
	
	/***
	***	@Global
	***/
	add_filter('um_profile_field_filter_hook__', 'um_profile_field_filter_hook__', 99, 2);
	function um_profile_field_filter_hook__( $value, $data ) {
	
		if ( isset( $data['validate'] ) && $data['validate'] != '' && strstr( $data['validate'], 'url' ) ) {
			$alt = ( isset( $data['url_text'] ) ) ? $data['url_text'] : $value;
			$url_rel = ( isset( $data['url_rel'] ) ) ? 'rel="nofollow"' : '';
			if( !strstr( $value, 'http' )
				&& !strstr( $value, '://' )
				&& !strstr( $value, 'www.' ) 
				&& !strstr( $value, '.com' ) 
				&& !strstr( $value, '.net' )
				&& !strstr( $value, '.org' )
			) {
				if ( $data['validate'] == 'facebook_url' ) $value = 'http://facebook.com/' . $value;
				if ( $data['validate'] == 'twitter_url' ) $value = 'http://twitter.com/' . $value;
				if ( $data['validate'] == 'linkedin_url' ) $value = 'http://linkedin.com/' . $value;
				if ( $data['validate'] == 'skype' ) $value = 'http://skype.com/' . $value;
				if ( $data['validate'] == 'googleplus_url' ) $value = 'http://plus.google.com/' . $value;
				if ( $data['validate'] == 'instagram_url' ) $value = 'http://instagram.com/' . $value;	
			}
			if ( strpos($value, 'http://') !== 0 ) {
				$value = 'http://' . $value;
			}
			$value = '<a href="'. $value .'" target="'.$data['url_target'].'" ' . $url_rel . '>'.$alt.'</a>';
		}
			
		if ( !is_array( $value ) ) {
			if ( is_email( $value ) )
				$value = '<a href="mailto:'. $value.'">'.$value.'</a>';
		} else {
			$value = implode(', ', $value);
		}

		return $value;
	}
	
	/***
	***	@get form fields
	***/
	add_filter('um_get_form_fields', 'um_get_form_fields', 99);
	function um_get_form_fields( $array ) {
		
		global $ultimatemember;
		
		$form_id = (isset ( $ultimatemember->fields->set_id ) ) ? $ultimatemember->fields->set_id : null;
		$mode = (isset( $ultimatemember->fields->set_mode ) ) ? $ultimatemember->fields->set_mode : null;
		
		if ( $form_id && $mode ) {
		$array = $ultimatemember->query->get_attr('custom_fields', $form_id );
		} else {
			$array = '';
		}
		
		return $array;
		
	}