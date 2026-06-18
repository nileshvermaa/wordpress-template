<?php
/**
 * Lumen Wellness — content & default data.
 *
 * Brand-level fields (name, colours, hero text, contact, socials) are editable
 * from Appearance → Customize. The repeatable sections below (services,
 * programs, testimonials, specialties, marquee) are edited here — one clearly
 * labelled block per client. Everything is escaped at output time.
 *
 * @package Lumen_Wellness
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Read a Customizer value with a sensible default.
 *
 * @param string $key     theme_mod key.
 * @param mixed  $default Fallback when unset.
 * @return mixed
 */
function lumen_opt( $key, $default = '' ) {
	return get_theme_mod( $key, $default );
}

/**
 * Brand defaults — used by the Customizer and as fallbacks.
 *
 * Filterable via `lumen_defaults` so a child theme can rebrand the whole site
 * for a different niche (yoga, therapy, dental, etc.) in a few lines, without
 * duplicating the codebase. See lumen-wellness-yoga/ for an example.
 *
 * @return array
 */
function lumen_defaults() {
	$defaults = array(
		'brand_name'    => 'Shivani Kamra',
		'brand_accent'  => 'Wellness',
		'hero_word'     => 'WELLNESS',
		'role'          => 'Integrative Health & Nutrition Coach',
		'tagline'       => 'Real food, gentle habits, lasting energy — health that finally fits your life.',
		'location'      => 'Bengaluru, India',
		'email'         => 'hello@shivanikamra.com',
		'phone'         => '+91 90000 00000',
		'availability'  => 'Now accepting new 1:1 clients',
		'booking_url'   => '#contact',
		'primary_cta'   => 'Book a free call',
		'secondary_cta' => 'See programs',
		'color_accent'  => '#16a06b',
		'color_deep'    => '#0c6e48',
		'color_ink'     => '#122019',
		'color_paper'   => '#f6faf6',
		'color_blush'   => '#d6f2e3',
		'about_intro'   => "I'm a certified nutrition and lifestyle coach who believes wellbeing shouldn't feel like punishment. Over the last decade I've helped people heal their relationship with food, steady their energy, and build routines that actually hold — without crash diets, guilt or guesswork.",
		'about_phil'    => 'Health isn\'t a 30-day challenge. It\'s a hundred small, kind decisions — and my job is to make the next one obvious.',
		'stat1_value'   => '10+',
		'stat1_label'   => 'Years guiding clients to better health',
		'stat2_value'   => '600+',
		'stat2_label'   => 'One-to-one coaching journeys',
		'stat3_value'   => '4',
		'stat3_label'   => 'Evidence-based certifications',
		'copyright'     => 'Shivani Kamra Wellness',
	);

	/**
	 * Filter the brand defaults. Child themes / niche variants can override any
	 * key here (hero word, role, tagline, copy, default colour preset, etc.).
	 *
	 * @param array $defaults Default brand values.
	 */
	return apply_filters( 'lumen_defaults', $defaults );
}

/**
 * Ready-made colour presets. Selectable in the Customizer; each is a complete,
 * contrast-checked palette so a site can be re-skinned for a different niche in
 * one click. "custom" falls back to the individual colour pickers.
 *
 * @return array
 */
function lumen_color_presets() {
	return array(
		'sage' => array(
			'label'  => 'Sage Green (default)',
			'accent' => '#16a06b', 'deep' => '#0c6e48', 'ink' => '#122019',
			'paper'  => '#f6faf6', 'blush' => '#d6f2e3',
		),
		'ocean' => array(
			'label'  => 'Ocean Calm (blue)',
			'accent' => '#1390b8', 'deep' => '#0a607f', 'ink' => '#0e1f29',
			'paper'  => '#f4fafc', 'blush' => '#d2eef6',
		),
		'terracotta' => array(
			'label'  => 'Warm Terracotta',
			'accent' => '#cf6638', 'deep' => '#9c451d', 'ink' => '#2a1a12',
			'paper'  => '#fbf6f2', 'blush' => '#f6e0d2',
		),
		'lavender' => array(
			'label'  => 'Lavender Calm (therapy)',
			'accent' => '#7c5fc4', 'deep' => '#553b97', 'ink' => '#1d1726',
			'paper'  => '#f9f7fd', 'blush' => '#e7defa',
		),
		'rosewood' => array(
			'label'  => 'Rosewood (beauty / spa)',
			'accent' => '#c14a72', 'deep' => '#8f2a4f', 'ink' => '#241318',
			'paper'  => '#fdf6f8', 'blush' => '#f7dde7',
		),
	);
}

/**
 * Socials — label, handle, URL. Leave URL blank to hide a row.
 *
 * @return array
 */
function lumen_socials() {
	$rows = array(
		array( 'label' => 'Instagram', 'mod' => 'social_instagram', 'default' => 'https://instagram.com/' ),
		array( 'label' => 'YouTube',   'mod' => 'social_youtube',   'default' => '' ),
		array( 'label' => 'LinkedIn',  'mod' => 'social_linkedin',  'default' => '' ),
		array( 'label' => 'WhatsApp',  'mod' => 'social_whatsapp',  'default' => '' ),
	);

	$out = array();
	foreach ( $rows as $row ) {
		$url = lumen_opt( $row['mod'], $row['default'] );
		if ( $url ) {
			$out[] = array(
				'label' => $row['label'],
				'href'  => $url,
			);
		}
	}
	return $out;
}

/**
 * Marquee strip words.
 *
 * @return string[]
 */
function lumen_marquee_words() {
	return apply_filters( 'lumen_marquee_words', array(
		'Nutrition Coaching',
		'Gut Health',
		'Sustainable Weight',
		'Hormone Balance',
		'Mindful Eating',
		'Energy & Sleep',
		'Habit Building',
		'Whole Foods',
	) );
}

/**
 * Services / what I help with. Edit per client.
 *
 * @return array
 */
function lumen_services() {
	return array(
		array(
			'period'  => '12-week program',
			'role'    => '1:1 Nutrition Coaching',
			'place'   => 'Online · Worldwide',
			'summary' => 'A fully personalised plan built around your body, schedule and goals — weekly check-ins, adjustable meal frameworks, and the accountability to make it stick.',
			'tags'    => array( 'Personalised Plan', 'Weekly Check-ins', 'Habit Tracking' ),
		),
		array(
			'period'  => 'Targeted protocol',
			'role'    => 'Gut Health Reset',
			'place'   => 'Online · 6 weeks',
			'summary' => 'A gentle, structured approach to bloating, irregularity and food sensitivities — calming inflammation and rebuilding a diverse, resilient microbiome.',
			'tags'    => array( 'Digestion', 'Anti-inflammatory', 'Food Mapping' ),
		),
		array(
			'period'  => 'Lifestyle coaching',
			'role'    => 'Hormone & Energy Balance',
			'place'   => 'Online · Ongoing',
			'summary' => 'For the constantly-tired and stress-wired. We steady blood sugar, improve sleep and rebuild energy through food, movement and recovery — not stimulants.',
			'tags'    => array( 'Blood Sugar', 'Sleep', 'Stress' ),
		),
		array(
			'period'  => 'For teams',
			'role'    => 'Corporate Wellness Workshops',
			'place'   => 'On-site or virtual',
			'summary' => 'Practical, science-backed sessions that help teams eat better, focus longer and burn out less — talks, Q&As and follow-along resources.',
			'tags'    => array( 'Workshops', 'Talks', 'Resources' ),
		),
	);
}

/**
 * Featured programs (cards).
 *
 * @return array
 */
function lumen_programs() {
	return array(
		array(
			'index'    => '01',
			'platform' => 'Signature Program',
			'title'    => 'The Reset Method',
			'desc'     => 'My flagship 12-week journey — from confusion and crash diets to calm, confident eating you never have to think hard about again.',
			'cta'      => 'Explore the method',
			'href'     => '#contact',
		),
		array(
			'index'    => '02',
			'platform' => 'Self-paced',
			'title'    => 'Everyday Nourish',
			'desc'     => 'A library of seasonal meal frameworks, swaps and 20-minute recipes — for people who want structure without a strict plan.',
			'cta'      => 'Get the toolkit',
			'href'     => '#contact',
		),
		array(
			'index'    => '03',
			'platform' => 'Free resource',
			'title'    => 'The 7-Day Energy Guide',
			'desc'     => 'A no-cost starter guide with the seven changes that move the needle first. A gentle, doable place to begin.',
			'cta'      => 'Download free',
			'href'     => '#contact',
		),
	);
}

/**
 * "How it works" steps.
 *
 * @return array
 */
function lumen_steps() {
	return array(
		array( 'num' => '01', 'title' => 'Listen', 'desc' => 'We start with your story, your labs and your real day — no judgement, no one-size template.' ),
		array( 'num' => '02', 'title' => 'Build', 'desc' => 'Together we design a plan that fits your life: food you enjoy, habits that hold, goals that move.' ),
		array( 'num' => '03', 'title' => 'Sustain', 'desc' => 'Weekly support and small course-corrections until the routine runs without me — for good.' ),
	);
}

/**
 * Testimonials.
 *
 * @return array
 */
function lumen_testimonials() {
	return array(
		array(
			'quote' => '"For the first time food doesn\'t run my life. I have energy at 4pm, my bloating is gone, and I didn\'t give up a single thing I love."',
			'name'  => 'Meera S.',
			'meta'  => 'Lost 9kg · kept it off 1 year',
		),
		array(
			'quote' => '"Shivani is the first coach who treated me like a person, not a meal plan. The check-ins kept me honest and the science kept me confident."',
			'name'  => 'Rahul T.',
			'meta'  => 'Reversed pre-diabetes markers',
		),
		array(
			'quote' => '"Calm, practical, zero shame. Six weeks in and my gut feels human again. I recommend her to everyone who\'ll listen."',
			'name'  => 'Priya N.',
			'meta'  => 'Gut Health Reset client',
		),
	);
}

/**
 * Specialties (skills) grouped.
 *
 * @return array
 */
function lumen_specialties() {
	return array(
		array(
			'group' => 'Nourish',
			'items' => array( 'Personalised Nutrition', 'Meal Planning', 'Whole-Food Cooking', 'Mindful Eating', 'Supplement Guidance', 'Seasonal Eating' ),
		),
		array(
			'group' => 'Heal',
			'items' => array( 'Gut Health', 'Anti-inflammatory Protocols', 'Blood Sugar Balance', 'Hormone Support', 'Sleep & Recovery', 'Stress Regulation' ),
		),
		array(
			'group' => 'Sustain',
			'items' => array( 'Habit Design', 'Behaviour Change', 'Accountability', 'Movement Routines', 'Relapse Prevention', 'Long-term Coaching' ),
		),
	);
}
