<?php
/**
 * Legal pages for the health/wellness niche.
 *
 * On theme activation we create three pages if they don't already exist:
 * Medical Disclaimer, Privacy Policy and Terms. They are starting templates —
 * the site owner must review them with their own details / counsel. Each
 * begins with an editor note to that effect.
 *
 * @package Lumen_Wellness
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The legal pages to scaffold: slug => [title, content].
 *
 * @return array
 */
function lumen_legal_pages() {
	$brand = lumen_opt( 'lumen_brand_name', lumen_defaults()['brand_name'] );
	$email = lumen_opt( 'lumen_email', lumen_defaults()['email'] );
	$note  = '<p style="padding:1rem 1.25rem;background:#fff6e5;border-left:4px solid #e0a83d;border-radius:8px;"><strong>Editor note:</strong> This is a starting template. Review it carefully, insert your real business details, and have a professional confirm it fits your jurisdiction before publishing. Then delete this note.</p>';

	return array(
		'medical-disclaimer' => array(
			'title'   => __( 'Medical Disclaimer', 'lumen-wellness' ),
			'content' => $note .
				'<h2>Not medical advice</h2>' .
				'<p>The content on this website, and any coaching, programs or materials provided by ' . esc_html( $brand ) . ', is for general educational and informational purposes only. It is <strong>not</strong> medical advice and is not a substitute for the advice, diagnosis or treatment of a qualified physician, registered dietitian or other licensed health professional.</p>' .
				'<h2>Always consult a professional</h2>' .
				'<p>Always seek the advice of your doctor or a qualified health provider with any questions about a medical condition, before starting any diet, supplement or exercise program, and before making changes to medication or treatment. Never disregard or delay seeking professional medical advice because of something you have read here.</p>' .
				'<h2>Individual results vary</h2>' .
				'<p>Wellness outcomes depend on many factors unique to each person. Any testimonials, examples or results shared on this site are personal experiences and are <strong>not a guarantee</strong> that you will achieve the same or similar results.</p>' .
				'<h2>In an emergency</h2>' .
				'<p>If you think you may have a medical emergency, call your doctor or your local emergency number immediately.</p>' .
				'<p>Questions about this disclaimer? Contact <a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>.</p>',
		),
		'privacy-policy' => array(
			'title'   => __( 'Privacy Policy', 'lumen-wellness' ),
			'content' => $note .
				'<p>This Privacy Policy explains how ' . esc_html( $brand ) . ' ("we", "us") collects, uses and protects information when you use this website.</p>' .
				'<h2>What we collect</h2>' .
				'<p>When you submit the contact form we collect the name, email address and message you provide, so we can reply. Our host and analytics tools may also collect standard technical data such as IP address, browser type and pages visited.</p>' .
				'<h2>How we use it</h2>' .
				'<p>We use your information only to respond to your enquiry, provide the services you request, and improve the website. We do not sell your personal data.</p>' .
				'<h2>Cookies</h2>' .
				'<p>This site may use cookies for essential functionality and, if enabled, analytics. You can control cookies through your browser settings.</p>' .
				'<h2>Your rights</h2>' .
				'<p>You may request access to, correction of, or deletion of your personal data at any time by contacting <a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>. If you are in the EU/UK, you have rights under the GDPR/UK GDPR.</p>' .
				'<h2>Contact</h2>' .
				'<p>For any privacy question, email <a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>.</p>',
		),
		'terms' => array(
			'title'   => __( 'Terms & Conditions', 'lumen-wellness' ),
			'content' => $note .
				'<p>By using this website and any services offered by ' . esc_html( $brand ) . ', you agree to these terms.</p>' .
				'<h2>Use of the site</h2>' .
				'<p>Content is provided for general information and may be updated at any time. You agree not to misuse the site or its content.</p>' .
				'<h2>Services & payment</h2>' .
				'<p>Specific coaching programs, their scope, pricing, scheduling, cancellation and refund terms are agreed separately at the time of booking and form part of these terms.</p>' .
				'<h2>No guarantees</h2>' .
				'<p>Wellness services are provided in good faith but outcomes are not guaranteed and depend on individual circumstances. See our <a href="/medical-disclaimer/">Medical Disclaimer</a>.</p>' .
				'<h2>Limitation of liability</h2>' .
				'<p>To the fullest extent permitted by law, we are not liable for any indirect or consequential loss arising from use of this site or its content.</p>' .
				'<h2>Contact</h2>' .
				'<p>Questions about these terms? Email <a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>.</p>',
		),
	);
}

/**
 * Create the legal pages once, on theme activation, if they don't exist.
 * Also builds a footer menu linking to them.
 */
function lumen_create_legal_pages() {
	if ( get_option( 'lumen_legal_pages_created' ) ) {
		return;
	}

	$created = array();
	foreach ( lumen_legal_pages() as $slug => $page ) {
		$existing = get_page_by_path( $slug );
		if ( $existing ) {
			$created[ $slug ] = $existing->ID;
			continue;
		}
		$id = wp_insert_post(
			array(
				'post_title'   => $page['title'],
				'post_name'    => $slug,
				'post_content' => $page['content'],
				'post_status'  => 'publish',
				'post_type'    => 'page',
			)
		);
		if ( $id && ! is_wp_error( $id ) ) {
			$created[ $slug ] = $id;
		}
	}

	// Build a "Footer" menu with the legal links if one doesn't exist yet.
	$menu_name = 'Footer Legal';
	if ( ! wp_get_nav_menu_object( $menu_name ) ) {
		$menu_id = wp_create_nav_menu( $menu_name );
		if ( ! is_wp_error( $menu_id ) ) {
			foreach ( $created as $page_id ) {
				wp_update_nav_menu_item(
					$menu_id,
					0,
					array(
						'menu-item-object-id' => $page_id,
						'menu-item-object'    => 'page',
						'menu-item-type'      => 'post_type',
						'menu-item-status'    => 'publish',
					)
				);
			}
			$locations          = get_theme_mod( 'nav_menu_locations', array() );
			$locations['footer'] = $menu_id;
			set_theme_mod( 'nav_menu_locations', $locations );
		}
	}

	update_option( 'lumen_legal_pages_created', 1 );
}
