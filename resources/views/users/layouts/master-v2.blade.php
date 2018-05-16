@extends('layouts.master')

@section('header')
    @include('users.includes.header.master')
@endsection

@section('nav-bar-horizontal')

@endsection

@section('content')
    @yield('contents')
@endsection

@section('footer')
    @include('layouts.footer')
@endsection
@section('footer-imports')
<noscript>&lt;iframe src="//www.googletagmanager.com/ns.html?id=GTM-KM44RQ8"
    height="0" width="0" style="display:none;visibility:hidden"&gt;&lt;/iframe&gt;
</noscript>
<link rel="stylesheet" id="intergeo-frontend-css"
      href="https://demot-vertigostudio.netdna-ssl.com/parallax-one/wp-content/plugins/intergeo-maps/css/frontend.css?ver=2.2.2"
      type="text/css" media="all">
<script type="text/javascript">/* <![CDATA[ */
    var wpcf7 = {
        "apiSettings": {
            "root": "https:\/\/demo.themeisle.com\/parallax-one\/wp-json\/contact-form-7\/v1",
            "namespace": "contact-form-7\/v1"
        }, "recaptcha": {"messages": {"empty": "Please verify that you are not a robot."}}, "cached": "1"
    };
    /* ]]> */</script>
<script type="text/javascript"
        src="https://demot-vertigostudio.netdna-ssl.com/parallax-one/wp-content/plugins/contact-form-7/includes/js/scripts.js?ver=5.0"></script>
<script type="text/javascript">/* <![CDATA[ */
    var edd_scripts = {
        "ajaxurl": "https:\/\/demo.themeisle.com\/parallax-one\/wp-admin\/admin-ajax.php",
        "position_in_cart": "",
        "has_purchase_links": "",
        "already_in_cart_message": "You have already added this item to your cart",
        "empty_cart_message": "Your cart is empty",
        "loading": "Loading",
        "select_option": "Please select an option",
        "is_checkout": "0",
        "default_gateway": "",
        "redirect_to_checkout": "0",
        "checkout_page": "",
        "permalinks": "1",
        "quantities_enabled": "",
        "taxes_enabled": "0"
    };
    /* ]]> */</script>
<script type="text/javascript"
        src="https://demot-vertigostudio.netdna-ssl.com/parallax-one/wp-content/plugins/easy-digital-downloads/assets/js/edd-ajax.min.js?ver=2.8.18"></script>
<script type="text/javascript">/* <![CDATA[ */
    var wc_add_to_cart_params = {
        "ajax_url": "\/parallax-one\/wp-admin\/admin-ajax.php",
        "wc_ajax_url": "https:\/\/demo.themeisle.com\/parallax-one\/?wc-ajax=%%endpoint%%",
        "i18n_view_cart": "View cart",
        "cart_url": "https:\/\/demo.themeisle.com\/parallax-one\/cart\/",
        "is_cart": "",
        "cart_redirect_after_add": "no"
    };
    /* ]]> */</script>
<script type="text/javascript"
        src="https://demot-vertigostudio.netdna-ssl.com/parallax-one/wp-content/plugins/woocommerce/assets/js/frontend/add-to-cart.min.js?ver=3.3.3"></script>
<script type="text/javascript"
        src="https://demot-vertigostudio.netdna-ssl.com/parallax-one/wp-content/plugins/woocommerce/assets/js/jquery-blockui/jquery.blockUI.min.js?ver=2.70"></script>
<script type="text/javascript"
        src="https://demot-vertigostudio.netdna-ssl.com/parallax-one/wp-content/plugins/woocommerce/assets/js/js-cookie/js.cookie.min.js?ver=2.1.4"></script>
<script type="text/javascript">/* <![CDATA[ */
    var woocommerce_params = {
        "ajax_url": "\/parallax-one\/wp-admin\/admin-ajax.php",
        "wc_ajax_url": "https:\/\/demo.themeisle.com\/parallax-one\/?wc-ajax=%%endpoint%%"
    };
    /* ]]> */</script>
<script type="text/javascript"
        src="https://demot-vertigostudio.netdna-ssl.com/parallax-one/wp-content/plugins/woocommerce/assets/js/frontend/woocommerce.min.js?ver=3.3.3"></script>
<script type="text/javascript">/* <![CDATA[ */
    var wc_cart_fragments_params = {
        "ajax_url": "\/parallax-one\/wp-admin\/admin-ajax.php",
        "wc_ajax_url": "https:\/\/demo.themeisle.com\/parallax-one\/?wc-ajax=%%endpoint%%",
        "cart_hash_key": "wc_cart_hash_b6deab142aee3c9fdd4b1be44f1cc82a",
        "fragment_name": "wc_fragments_b6deab142aee3c9fdd4b1be44f1cc82a"
    };
    /* ]]> */</script>
<script type="text/javascript"
        src="https://demot-vertigostudio.netdna-ssl.com/parallax-one/wp-content/plugins/woocommerce/assets/js/frontend/cart-fragments.min.js?ver=3.3.3"></script>
<script type="text/javascript"
        src="https://demot-vertigostudio.netdna-ssl.com/parallax-one/wp-content/themes/Parallax-One/js/bootstrap.min.js?ver=3.3.5"></script>
<script type="text/javascript">/* <![CDATA[ */
    var screenReaderText = {
        "expand": "<span class=\"screen-reader-text\">expand child menu<\/span>",
        "collapse": "<span class=\"screen-reader-text\">collapse child menu<\/span>"
    };
    /* ]]> */</script>
<script type="text/javascript"
        src="https://demot-vertigostudio.netdna-ssl.com/parallax-one/wp-content/themes/Parallax-One/js/custom.all.js?ver=2.0.2"></script>
<script type="text/javascript"
        src="https://demot-vertigostudio.netdna-ssl.com/parallax-one/wp-content/themes/Parallax-One/js/plugin.home.js?ver=1.0.1"></script>
<script type="text/javascript"
        src="https://demot-vertigostudio.netdna-ssl.com/parallax-one/wp-content/themes/Parallax-One/js/custom.home.js?ver=1.0.0"></script>
<script type="text/javascript"
        src="https://demot-vertigostudio.netdna-ssl.com/parallax-one/wp-content/themes/Parallax-One/js/skip-link-focus-fix.js?ver=1.0.0"></script>
<script type="text/javascript"
        src="https://demot-vertigostudio.netdna-ssl.com/parallax-one/wp-includes/js/hoverIntent.min.js?ver=1.8.1"></script>
<script type="text/javascript">/* <![CDATA[ */
    var megamenu = {"timeout": "300", "interval": "100"};
    /* ]]> */</script>
<script type="text/javascript"
        src="https://demot-vertigostudio.netdna-ssl.com/parallax-one/wp-content/plugins/megamenu/js/maxmegamenu.js?ver=2.4.1.2"></script>
<script type="text/javascript"
        src="https://demot-vertigostudio.netdna-ssl.com/parallax-one/wp-includes/js/wp-embed.min.js?ver=4.9.5"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?region=US&amp;language=en"></script>
<script type="text/javascript">/* <![CDATA[ */
    var intergeo_options = {"adsense": {"publisher_id": false}};
    /* ]]> */</script>
<script type="text/javascript"
        src="https://demot-vertigostudio.netdna-ssl.com/parallax-one/wp-content/plugins/intergeo-maps/js/rendering.js?ver=2.2.2"></script>
<style type="text/css">.overlay-layer-wrap {
        background: rgba(0, 0, 0, .7)
    }</style>
<script>(function (w, d) {
        var b = d.getElementsByTagName("body")[0];
        var s = d.createElement("script");
        s.async = true;
        var v = !("IntersectionObserver" in w) ? "8.5.2" : "10.3.5";
        s.src = "https://demot-vertigostudio.netdna-ssl.com/parallax-one/wp-content/plugins/wp-rocket/inc/front/js/lazyload-" + v + ".min.js";
        w.lazyLoadOptions = {
            elements_selector: "img, iframe",
            data_src: "lazy-src",
            data_srcset: "lazy-srcset",
            skip_invisible: false,
            class_loading: "lazyloading",
            class_loaded: "lazyloaded",
            threshold: 300,
            callback_load: function (element) {
                if (element.tagName === "IFRAME" && element.dataset.rocketLazyload == "fitvidscompatible") {
                    if (element.classList.contains("lazyloaded")) {
                        if (typeof window.jQuery != "undefined") {
                            if (jQuery.fn.fitVids) {
                                jQuery(element).parent().fitVids();
                            }
                        }
                    }
                }
            }
        }; // Your options here. See "recipes" for more information about async.
        b.appendChild(s);
    }(window, document));

    // Listen to the Initialized event
    window.addEventListener('LazyLoad::Initialized', function (e) {
        // Get the instance and puts it in the lazyLoadInstance variable
        var lazyLoadInstance = e.detail.instance;

        var observer = new MutationObserver(function (mutations) {
            mutations.forEach(function (mutation) {
                lazyLoadInstance.update();
            });
        });

        var b = document.getElementsByTagName("body")[0];
        var config = {childList: true, subtree: true};

        observer.observe(b, config);
    }, false);</script>
<script async=""
        src="https://demot-vertigostudio.netdna-ssl.com/parallax-one/wp-content/plugins/wp-rocket/inc/front/js/lazyload-10.3.5.min.js"></script>
<!-- This website is like a Rocket, isn't it? Performance optimized by WP Rocket. Learn more: https://wp-rocket.me - Debug: cached@1525545101 -->
<script type="text/javascript" id="">!function (b, e, f, g, a, c, d) {
        b.fbq || (a = b.fbq = function () {
            a.callMethod ? a.callMethod.apply(a, arguments) : a.queue.push(arguments)
        }, b._fbq || (b._fbq = a), a.push = a, a.loaded = !0, a.version = "2.0", a.queue = [], c = e.createElement(f), c.async = !0, c.src = g, d = e.getElementsByTagName(f)[0], d.parentNode.insertBefore(c, d))
    }(window, document, "script", "https://connect.facebook.net/en_US/fbevents.js");
    fbq("init", "704894032915584", {em: "shirimas@gmail.com"});
    fbq("track", "PageView");</script>
<noscript>&lt;img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=704894032915584&amp;amp;ev=PageView&amp;amp;noscript=1"&gt;</noscript>

<iframe name="_hjRemoteVarsFrame" title="_hjRemoteVarsFrame" id="_hjRemoteVarsFrame"
        style="display: none !important; width: 1px !important; height: 1px !important; opacity: 0 !important; pointer-events: none !important;"
        src="https://vars.hotjar.com/rcj-99d43ead6bdf30da8ed5ffcb4f17100c.html"></iframe>
@endsection