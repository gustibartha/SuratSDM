<!DOCTYPE HTML>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="author" content="Benaissa Ghrib" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <title>Crudder : Laravel APP Builder</title>
    <link rel='shortcut icon' type='image/x-icon' href="{{asset('/favicon.png')}}" />
    <!--Stylesheet-->

    <link href="{{asset('vendor/landing')}}/css/font.css" rel="stylesheet" type="text/css">
    <link href="{{asset('vendor/landing')}}/css/fontello.css" rel="stylesheet" type="text/css">
    <link href="{{asset('vendor/landing')}}/css/main.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{asset('vendor/landing')}}/css/ui.totop.css" />
    <link rel="stylesheet" href="{{asset('vendor/landing')}}/css/jquery-ui-1.8.23.custom.css" />
    <link rel="stylesheet" href="{{asset('vendor/landing')}}/css/flexslider.css" />

</head>

<body>

    <!-- Preloader -->
    <div id="loader">
        <div id="loaderInner"></div>
    </div>

    <!--Wrapper-->
    <div id="wrapper">

        <!--Header-->
        <header id="header" class=" default clearfix">

            <!--Holder 960-->
            <div class=" clearfix">

                <!--Logo-->
                <div class="cr-logo">
                    <a href=""><span>[</span>Crud<i>der</i><span>]</span></a>
                </div>
                <!--End logo-->


                <!--Navigation-->
                <nav id="mainNav">
                    @if (Route::has('login'))
                    <ul>
                        @auth
                        <li><a href="#about">about</a></li>
                        <li><a href="#faq">faq</a></li>
                        @else
                        <li><a href="#about">about</a></li>
                        <li><a href="#faq">faq</a></li>
                        <li><a class="buy" href="{{ url('login')}}"><i class="icon-right-big"></i>Login</a></li>
                        @endauth
                        @endif
                    </ul>

                </nav>
                <!--End navigation-->
            </div>
            <!--End Holder 960-->
        </header>
        <!--Header-->

        <!--Teaser-->
        <div id="teaser" class="clearfix">

            <!--Overlay-->
            <div class="overlay">

                <!--Holder 960-->
                <div class="holder960 clearfix">

                    <!--Teaser title-->
                    <div class="teaserTitle">
                        <h1><span>Crud</span>der</h1>
                        <h2>simple easy to build your laravel app</h2>
                    </div>
                    <!--End teaser title-->

                    <!--Down-->
                    <div class="down">
                        <a href="#about"><i class="icon-down-open-big"></i></a>
                    </div>
                    <!--End down-->

                </div>
                <!--End Holder 960-->

            </div>
            <!--End overlay-->
        </div>
        <!--End teaser-->

        <!--About section-->
        <section id="about" class="clearfix section">

            <!--Holder 960-->
            <div class="holder960 clearfix">

                <!--Section title-->
                <div class="secArrow">
                    <div class="arrowHolder"><img src="{{asset('vendor/landing')}}/images/bigArrow-1.png" alt=""></div>
                    <i class="icon-thumbs-up"></i>
                </div>
                <!--End section title-->

                <!--Intro-->
                <div class="aboutIntro">

                    <h1><span class="black">a beautiful</span> &amp; simple easy to use<br>
                        <span class="orange">laravel crud generator</span></h1>

                    <p>Save your time with CRUDDER. Make your laravel apps without coding for the same method.
                        Make your job easier.
                    </p>

                </div>
                <!--End intro-->


                <!--Features-->
                <div class="features">

                    <!--Features holder-->
                    <div class="featureHolder clearfix">
                        <!--Feature single-->
                        <div class="featureSingle column clearfix">
                            <div class="ico column"><i class="icon-mobile"></i></div>
                            <div class="desc column">
                                <h2>Mobile Responsive</h2>
                                <p>Crudder designed with fully responsive for mobile web browser
                                </p>
                            </div>
                        </div>
                        <!--End feature single-->

                        <!--Feature single-->
                        <div class="featureSingle column clearfix">
                            <div class="ico column"><i class="icon-hourglass"></i></div>
                            <div class="desc column">
                                <h2>Easy To Setup</h2>
                                <p>Just Install the project, and make your app schema to .JSON to render.
                                </p>
                            </div>
                        </div>
                        <!--End feature single-->

                        <!--Feature single-->
                        <div class="featureSingle column clearfix">
                            <div class="ico column"><i class="icon-megaphone"></i></div>
                            <div class="desc column">
                                <h2>For Marketing</h2>
                                <p>You can create tons of app more efficient and your app is ready for sale.
                                </p>
                            </div>
                        </div>
                        <!--End feature single-->
                    </div>
                    <!--End features holder-->


                    <!--Features holder-->
                    <div class="featureHolder clearfix">
                        <!--Feature single-->
                        <div class="featureSingle column clearfix">
                            <div class="ico column"><i class="icon-eye"></i></div>
                            <div class="desc column">
                                <h2>Light Design</h2>
                                <p>Simple design for better UX.
                                </p>
                            </div>
                        </div>
                        <!--End feature single-->

                        <!--Feature single-->
                        <div class="featureSingle column clearfix">
                            <div class="ico column"><i class="icon-lifebuoy"></i></div>
                            <div class="desc column">
                                <h2>Fully Support</h2>
                                <p>Crudder comes with full documentations.
                                </p>
                            </div>
                        </div>
                        <!--End feature single-->

                        <!--Feature single-->
                        <div class="featureSingle column clearfix">
                            <div class="ico column"><i class="icon-rocket"></i></div>
                            <div class="desc column">
                                <h2>Sweet Alert</h2>
                                <p>Crudder supported with Sweet Alert for your beautiful UI.
                                </p>
                            </div>
                        </div>
                        <!--End feature single-->
                    </div>
                    <!--End features holder-->

                </div>
                <!--End features-->


                <!--Product details-->
                <div class="productDetails clearfix">


                    <div class="divider"><span><i class="icon-eye"></i></span>
                        <h1><span>take a quick </span> look at some <span>great features</span> !</h1>
                    </div>


                    <!--Details content-->
                    <div class="proddetContent">

                        <!--Shot-->
                        <div class="shot column">
                            <img src="{{asset('vendor/landing')}}/images/sliderImages/gal1.png" alt="">
                        </div>
                        <!--End shot-->

                        <!--Description-->
                        <div class="description column">
                            <h1>Make and sell your app from Crudder Laravel Crud Generator </h1>


                            <ul class="detailsList">
                                <li>Build with simple design.</li>
                                <li>Responsive for mobile.</li>
                                <li>Support .JSON schema for rendering crud</li>
                                <li>Manage your table relationship easily.</li>
                                <li>Provide with sweet alert for your beautiful UI.</li>
                                <li>Instant render MVC and also route.</li>
                                <li>Customizeable page with laravelcollective.</li>
                                <li>Manage your user.</li>
                            </ul>

                        </div>
                        <!--End description-->

                    </div>
                    <!--End details content-->

                </div>
                <!--End product details-->
            </div>
            <!--End Holder 960-->
        </section>
        <!--End about section-->

        <!--FAQS section-->
        <section id="faq" class="clearfix section">

            <!--Holder 960-->
            <div class="holder960 clearfix">

                <!--Section title-->
                <div class="secArrow">
                    <div class="arrowHolder"><img src="{{asset('vendor/landing')}}/images/bigArrow-2.png" alt=""></div>
                    <i class="icon-help"></i>
                </div>
                <!--End section title-->



                <!--Intro-->
                <div class="faqIntro">
                    <h1><span class="black">some frequently asked</span> questions for you</h1>
                </div>
                <!--End intro-->

                <!--Faq content-->
                <div class="faqContent clearfix">

                    <!--Accordion-->
                    <div class="accordion column">

                        <!--Accordion content-->
                        <div id="accordionContent">
                            <div id="accordion" class="forceBackground">
                                <h3><a href="#">What is Crudder ?</a></h3>
                                <div>
                                    <p>
                                        Crudder is laravel based Crud Generator for your instant making app.

                                    </p>
                                </div>
                                <h3><a href="#">Is Crudder customize ?</a></h3>
                                <div>
                                    <p>
                                        All the element of crudder can be customize, and the rendered file also can be
                                        used into other laravel project.

                                    </p>
                                </div>
                                <h3><a href="#">Can we used other backend template ?</a></h3>
                                <div>
                                    <p>
                                        Yes you can. But you must pay for more template.

                                    </p>
                                </div>
                            </div>
                        </div>
                        <!--End accordion content-->
                    </div>
                    <!--End accordion-->

                </div>
                <!--End faq content-->

            </div>
            <!--End Holder 960-->

        </section>
        <!--End faqs-->

        <!--Shareon section-->
        <section id="shareon" class="section">

            <!--Holder 960-->
            <div class="holder960 clearfix">

                <!--Section title-->
                <div class="secArrow">
                    <div class="arrowHolder"><img src="{{asset('vendor/landing')}}/images/bigArrow-3.png" alt=""></div>
                    <i class="icon-share"></i>
                </div>
                <!--End section title-->

                <!--Intro-->
                <div class="shareIntro">
                    <h1><span class="black">keep it calm</span> and share on</h1>

                    <!--Socials share-->
                    <ul class="socialsShare">

                        <li><a class="socialTwitter" href="#"><i class="icon-twitter"></i></a></li>
                        <li><a class="socialFacebook" href="#"><i class="icon-facebook"></i></a></li>

                    </ul>
                    <!--End socials share-->
                </div>
                <!--End intro-->
            </div>
            <!--End Holder 960-->

        </section>
        <!--End shareon section-->

        <!--Footer-->
        <footer id="footer" class="clearfix">
            <p>&copy; 2019. <span>Crudder by GAS</span> All Rights Reserved.</p>
        </footer>
        <!--End footer-->
    </div>
    <!--ENd wrapper-->

    <!--Javascript-->
    <script src="{{asset('vendor/landing')}}/js/jquery-1.9.1.min.js" type="text/javascript"></script>
    <script src="{{asset('vendor/landing')}}/js/jquery-migrate-1.2.1.js"></script>
    <script src="{{asset('vendor/landing')}}/js/jquery.flexslider-min.js" type="text/javascript"></script>
    <script src="{{asset('vendor/landing')}}/js/jquery.easing.1.3.js" type="text/javascript"></script>
    <script src="{{asset('vendor/landing')}}/js/jquery.scrollTo-min.js" type="text/javascript"></script>
    <script src="{{asset('vendor/landing')}}/js/jquery-ui-1.8.23.custom.min.js" type="text/javascript"></script>
    <script src="{{asset('vendor/landing')}}/js/waypoints.js" type="text/javascript"></script>
    <script src="{{asset('vendor/landing')}}/js/jquery.parallax-1.1.3.js"></script>
    <script src="{{asset('vendor/landing')}}/js/Placeholders.min.js" type="text/javascript"></script>
    <script src="{{asset('vendor/landing')}}/js/jquery.ui.totop.min.js" type="text/javascript"></script>
    <script src="{{asset('vendor/landing')}}/js/jquery.validate.js" type="text/javascript"></script>
    <script src="{{asset('vendor/landing')}}/js/script.js" type="text/javascript"></script>

</body>

</html>
