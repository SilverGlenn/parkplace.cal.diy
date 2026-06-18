<?php
/**
 * Plugin Name:       Cal.diy Booking
 * Plugin URI:        https://your-cal-diy-domain.com
 * Description:       Embed your Cal.diy booking calendar into any WordPress page/post with a simple shortcode.
 * Version:           1.0
 * Author:            Your Chiropractic Clinic
 * License:           MIT
 * Text Domain:       caldiy-embed
 */

function caldiy_shortcode($atts, $content = null) {
    // Parse shortcode attributes
    $atts = shortcode_atts(array(
        'url' => '',
        'type' => '1',
        'text' => 'Book an appointment',
        'domain' => 'https://your-cal-diy-domain.com', // ← CHANGE THIS to your Coolify domain
    ), $atts);

    // Validate URL
    if (empty($atts['url'])) {
        return '<p style="color:red;">Please specify a url, e.g. [cal url="/dr-smith/initial-consult"]</p>';
    }

    $domain = rtrim($atts['domain'], '/');
    $cal_link = ltrim($atts['url'], '/');
    $domain_url = $domain . '/' . $cal_link;

    if ($atts['type'] === '2') {
        // Popup embed
        $button_text = esc_html($atts['text']);
        return '
<div id="cal-booking-btn">
    <button onclick="Cal(\'modal\', {calLink: \'' . esc_js($cal_link) . '\'});">
        ' . $button_text . '
    </button>
</div>
<script>
(function (C, A, L) {
    let p = function (a, ar) { a.q.push(ar); };
    let d = C.document;
    C.Cal = C.Cal || function () {
        let cal = C.Cal;
        let ar = arguments;
        if (!cal.loaded) {
            cal.ns = {};
            cal.q = cal.q || [];
            d.head.appendChild(d.createElement("script")).src = A;
            cal.loaded = true;
        }
        if (ar[0] === L) {
            const api = function () { p(api, arguments); };
            const namespace = ar[1];
            api.q = api.q || [];
            typeof namespace === "string" ? (cal.ns[namespace] = api) && p(api, ar) : p(cal, ar);
            return;
        }
        p(cal, ar);
    };
})(window, "' . $domain . '/embed.js", "init");
Cal("init");
</script>';
    } else {
        // Inline embed (default)
        return '
<div id="cal-inline-embed"></div>
<script>
(function (C, A, L) {
    let p = function (a, ar) { a.q.push(ar); };
    let d = C.document;
    C.Cal = C.Cal || function () {
        let cal = C.Cal;
        let ar = arguments;
        if (!cal.loaded) {
            cal.ns = {};
            cal.q = cal.q || [];
            d.head.appendChild(d.createElement("script")).src = A;
            cal.loaded = true;
        }
        if (ar[0] === L) {
            const api = function () { p(api, arguments); };
            const namespace = ar[1];
            api.q = api.q || [];
            typeof namespace === "string" ? (cal.ns[namespace] = api) && p(api, ar) : p(cal, ar);
            return;
        }
        p(cal, ar);
    };
})(window, "' . $domain . '/embed.js", "init");
Cal("init");
Cal("inline", {calLink: "' . esc_js($cal_link) . '", elementOrSelector: "#cal-inline-embed"});
</script>';
    }
}
add_shortcode('cal', 'caldiy_shortcode');
