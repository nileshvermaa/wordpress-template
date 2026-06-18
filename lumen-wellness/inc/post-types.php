<?php
/**
 * Custom post types so end-clients can edit Services, Programs and Testimonials
 * from wp-admin — no code, no ACF dependency. The front page uses these posts
 * when they exist and falls back to the demo arrays in template-data.php when
 * they don't, so a fresh site always looks complete.
 *
 * @package Lumen_Wellness
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the three content types.
 */
function lumen_register_cpts() {
	register_post_type(
		'lumen_service',
		array(
			'labels'       => lumen_cpt_labels( __( 'Service', 'lumen-wellness' ), __( 'Services', 'lumen-wellness' ) ),
			'public'       => true,
			'has_archive'  => true,
			'menu_icon'    => 'dashicons-heart',
			'rewrite'      => array( 'slug' => 'services' ),
			'supports'     => array( 'title', 'editor', 'thumbnail', 'page-attributes', 'excerpt' ),
			'show_in_rest' => true,
		)
	);

	register_post_type(
		'lumen_program',
		array(
			'labels'       => lumen_cpt_labels( __( 'Program', 'lumen-wellness' ), __( 'Programs', 'lumen-wellness' ) ),
			'public'       => true,
			'has_archive'  => true,
			'menu_icon'    => 'dashicons-clipboard',
			'rewrite'      => array( 'slug' => 'programs' ),
			'supports'     => array( 'title', 'editor', 'thumbnail', 'page-attributes', 'excerpt' ),
			'show_in_rest' => true,
		)
	);

	register_post_type(
		'lumen_testimonial',
		array(
			'labels'       => lumen_cpt_labels( __( 'Testimonial', 'lumen-wellness' ), __( 'Testimonials', 'lumen-wellness' ) ),
			'public'       => false,
			'show_ui'      => true,
			'menu_icon'    => 'dashicons-format-quote',
			'supports'     => array( 'title', 'editor', 'page-attributes' ),
			'show_in_rest' => true,
		)
	);

	// Flush rewrite rules once after activation so the new archives resolve.
	if ( get_option( 'lumen_flush_needed' ) ) {
		flush_rewrite_rules();
		delete_option( 'lumen_flush_needed' );
	}
}
add_action( 'init', 'lumen_register_cpts' );

/**
 * Build a standard labels array for a CPT.
 *
 * @param string $singular Singular label.
 * @param string $plural   Plural label.
 * @return array
 */
function lumen_cpt_labels( $singular, $plural ) {
	return array(
		'name'               => $plural,
		'singular_name'      => $singular,
		'add_new_item'       => sprintf( /* translators: %s: singular name */ __( 'Add New %s', 'lumen-wellness' ), $singular ),
		'edit_item'          => sprintf( /* translators: %s: singular name */ __( 'Edit %s', 'lumen-wellness' ), $singular ),
		'new_item'           => sprintf( /* translators: %s: singular name */ __( 'New %s', 'lumen-wellness' ), $singular ),
		'view_item'          => sprintf( /* translators: %s: singular name */ __( 'View %s', 'lumen-wellness' ), $singular ),
		'search_items'       => sprintf( /* translators: %s: plural name */ __( 'Search %s', 'lumen-wellness' ), $plural ),
		'not_found'          => sprintf( /* translators: %s: plural name */ __( 'No %s found', 'lumen-wellness' ), $plural ),
		'all_items'          => $plural,
		'menu_name'          => $plural,
	);
}

/* ============================================================
   META BOXES — the extra fields each type needs.
   ============================================================ */

/**
 * Field definitions per post type: meta key => label.
 *
 * @return array
 */
function lumen_meta_fields() {
	return array(
		'lumen_service'     => array(
			'_lumen_period' => __( 'Duration / label (e.g. “12-week program”)', 'lumen-wellness' ),
			'_lumen_place'  => __( 'Format (e.g. “Online · Worldwide”)', 'lumen-wellness' ),
			'_lumen_tags'   => __( 'Tags (comma-separated)', 'lumen-wellness' ),
		),
		'lumen_program'     => array(
			'_lumen_platform' => __( 'Label (e.g. “Signature Program”)', 'lumen-wellness' ),
			'_lumen_cta'      => __( 'Button text (e.g. “Explore the method”)', 'lumen-wellness' ),
			'_lumen_cta_url'  => __( 'Button link (URL or #contact)', 'lumen-wellness' ),
		),
		'lumen_testimonial' => array(
			'_lumen_meta' => __( 'Result / sub-line (e.g. “Lost 9kg · kept it off”)', 'lumen-wellness' ),
		),
	);
}

/**
 * Register meta boxes.
 */
function lumen_add_meta_boxes() {
	foreach ( lumen_meta_fields() as $cpt => $fields ) {
		add_meta_box(
			'lumen_details',
			__( 'Details', 'lumen-wellness' ),
			'lumen_render_meta_box',
			$cpt,
			'normal',
			'high'
		);
	}
}
add_action( 'add_meta_boxes', 'lumen_add_meta_boxes' );

/**
 * Render the meta box.
 *
 * @param WP_Post $post Current post.
 */
function lumen_render_meta_box( $post ) {
	$fields = lumen_meta_fields();
	if ( empty( $fields[ $post->post_type ] ) ) {
		return;
	}
	wp_nonce_field( 'lumen_save_meta', 'lumen_meta_nonce' );

	echo '<div style="display:grid;gap:1rem;padding-top:0.5rem;">';
	foreach ( $fields[ $post->post_type ] as $key => $label ) {
		$value = get_post_meta( $post->ID, $key, true );
		printf(
			'<p style="margin:0;"><label for="%1$s" style="display:block;font-weight:600;margin-bottom:0.25rem;">%2$s</label>' .
			'<input type="text" id="%1$s" name="%1$s" value="%3$s" class="widefat" /></p>',
			esc_attr( $key ),
			esc_html( $label ),
			esc_attr( $value )
		);
	}
	echo '</div>';
}

/**
 * Save meta box values.
 *
 * @param int $post_id Post ID.
 */
function lumen_save_meta( $post_id ) {
	if ( ! isset( $_POST['lumen_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['lumen_meta_nonce'] ) ), 'lumen_save_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$fields = lumen_meta_fields();
	$cpt    = get_post_type( $post_id );
	if ( empty( $fields[ $cpt ] ) ) {
		return;
	}

	foreach ( array_keys( $fields[ $cpt ] ) as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			$val = sanitize_text_field( wp_unslash( $_POST[ $key ] ) );
			if ( '_lumen_cta_url' === $key ) {
				$val = esc_url_raw( $val );
			}
			update_post_meta( $post_id, $key, $val );
		}
	}
}
add_action( 'save_post', 'lumen_save_meta' );

/* ============================================================
   GETTERS — CPT posts mapped to the template shape, with the
   demo arrays as a fallback when no posts exist yet.
   ============================================================ */

/**
 * @return array Services in template shape.
 */
function lumen_get_services() {
	$q = new WP_Query( array(
		'post_type'      => 'lumen_service',
		'posts_per_page' => 12,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	) );
	if ( ! $q->have_posts() ) {
		return lumen_services();
	}
	$out = array();
	foreach ( $q->posts as $p ) {
		$tags = get_post_meta( $p->ID, '_lumen_tags', true );
		$out[] = array(
			'period'  => get_post_meta( $p->ID, '_lumen_period', true ),
			'role'    => get_the_title( $p ),
			'place'   => get_post_meta( $p->ID, '_lumen_place', true ),
			'summary' => wp_strip_all_tags( $p->post_excerpt ? $p->post_excerpt : $p->post_content ),
			'tags'    => $tags ? array_map( 'trim', explode( ',', $tags ) ) : array(),
		);
	}
	return $out;
}

/**
 * @return array Programs in template shape.
 */
function lumen_get_programs() {
	$q = new WP_Query( array(
		'post_type'      => 'lumen_program',
		'posts_per_page' => 9,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	) );
	if ( ! $q->have_posts() ) {
		return lumen_programs();
	}
	$out = array();
	$i   = 0;
	foreach ( $q->posts as $p ) {
		$i++;
		$cta_url = get_post_meta( $p->ID, '_lumen_cta_url', true );
		$out[] = array(
			'index'    => str_pad( (string) $i, 2, '0', STR_PAD_LEFT ),
			'platform' => get_post_meta( $p->ID, '_lumen_platform', true ),
			'title'    => get_the_title( $p ),
			'desc'     => wp_strip_all_tags( $p->post_excerpt ? $p->post_excerpt : $p->post_content ),
			'cta'      => get_post_meta( $p->ID, '_lumen_cta', true ) ? get_post_meta( $p->ID, '_lumen_cta', true ) : __( 'Learn more', 'lumen-wellness' ),
			'href'     => $cta_url ? $cta_url : get_permalink( $p ),
		);
	}
	return $out;
}

/**
 * @return array Testimonials in template shape.
 */
function lumen_get_testimonials() {
	$q = new WP_Query( array(
		'post_type'      => 'lumen_testimonial',
		'posts_per_page' => 9,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	) );
	if ( ! $q->have_posts() ) {
		return lumen_testimonials();
	}
	$out = array();
	foreach ( $q->posts as $p ) {
		$out[] = array(
			'quote' => wp_strip_all_tags( $p->post_content ),
			'name'  => get_the_title( $p ),
			'meta'  => get_post_meta( $p->ID, '_lumen_meta', true ),
		);
	}
	return $out;
}

/**
 * Seed editable example posts once, so clients have a starting point in wp-admin.
 * Mirrors the demo arrays. Runs from the activation hook.
 */
function lumen_seed_cpt_content() {
	if ( get_option( 'lumen_cpt_seeded' ) ) {
		return;
	}

	$order = 0;
	foreach ( lumen_services() as $s ) {
		$order += 10;
		$id = wp_insert_post( array(
			'post_type'    => 'lumen_service',
			'post_status'  => 'publish',
			'post_title'   => $s['role'],
			'post_content' => $s['summary'],
			'menu_order'   => $order,
		) );
		if ( $id && ! is_wp_error( $id ) ) {
			update_post_meta( $id, '_lumen_period', $s['period'] );
			update_post_meta( $id, '_lumen_place', $s['place'] );
			update_post_meta( $id, '_lumen_tags', implode( ', ', $s['tags'] ) );
		}
	}

	$order = 0;
	foreach ( lumen_programs() as $p ) {
		$order += 10;
		$id = wp_insert_post( array(
			'post_type'    => 'lumen_program',
			'post_status'  => 'publish',
			'post_title'   => $p['title'],
			'post_content' => $p['desc'],
			'menu_order'   => $order,
		) );
		if ( $id && ! is_wp_error( $id ) ) {
			update_post_meta( $id, '_lumen_platform', $p['platform'] );
			update_post_meta( $id, '_lumen_cta', $p['cta'] );
			update_post_meta( $id, '_lumen_cta_url', $p['href'] );
		}
	}

	$order = 0;
	foreach ( lumen_testimonials() as $t ) {
		$order += 10;
		$id = wp_insert_post( array(
			'post_type'    => 'lumen_testimonial',
			'post_status'  => 'publish',
			'post_title'   => $t['name'],
			'post_content' => $t['quote'],
			'menu_order'   => $order,
		) );
		if ( $id && ! is_wp_error( $id ) ) {
			update_post_meta( $id, '_lumen_meta', $t['meta'] );
		}
	}

	update_option( 'lumen_cpt_seeded', 1 );
}
