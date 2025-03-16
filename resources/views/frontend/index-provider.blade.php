<!DOCTYPE html>
<html lang="{{ htmlLang() }}" @langrtl dir="rtl" @endlangrtl>

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>{{ appName() }} Provider | @yield('title')</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{asset('assets/img/favicon.png')}}" rel="icon">
    <link href="{{asset('assets/img/apple-touch-icon.png')}}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{asset('assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/icofont/icofont.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/boxicons/css/boxicons.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/owl.carousel/assets/owl.carousel.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/venobox/venobox.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/aos/aos.css')}}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">


<style>
    /* Container for the buttons */
    .download-buttons {
        display: flex;
        flex-wrap: wrap; /* Allows buttons to wrap on smaller screens */
        gap: 15px; /* Space between buttons */
        justify-content: center; /* Center buttons horizontally */
        margin-top: 20px; /* Space above the buttons */
    }

    /* Style for individual buttons */
    .download-btn {
        display: inline-flex;
        align-items: center;
        padding: 10px 20px;
        background-color: #007bff; /* Example background color */
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }

    .download-btn:hover {
        background-color: #0056b3; /* Darker shade on hover */
    }

    /* Icon spacing */
    .download-btn i {
        margin-right: 8px; /* Space between icon and text in LTR */
    }

    /* RTL adjustments */
    [dir="rtl"] .download-btn i {
        margin-right: 0;
        margin-left: 8px; /* Space between icon and text in RTL */
    }

    /* Ensure text alignment respects direction */
    [dir="rtl"] .download-btn {
        direction: rtl;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .download-buttons {
            flex-direction: column;
            align-items: center;
        }
        .download-btn {
            width: 100%; /* Full width on small screens */
            max-width: 250px; /* Limit width for aesthetics */
            justify-content: center;
        }
    }
</style>
</head>


<body>

<!-- ======= Header ======= -->
<header id="header" class="fixed-top header-transparent">
    <div class="container d-flex align-items-center">
        <div  class="logo @if(app()->getLocale() == 'ar') ml-auto @else mr-auto @endif">
            <a href="/"><img  src="{{ asset('img/brand/khadamati-logo.png') }}" alt="" class="img-fluid"></a>
        </div>

        <nav class="nav-menu d-none d-lg-block">
            <ul>
                <li ><a href="/">{{__('Home')}}</a></li>
                <li class="active" ><a href="{{route('frontend.provider.index')}}">{{__("Providers")}}</a></li>
                <li><a href="#gallery">{{__('Gallery')}}</a></li>
                <li><a href="#faq">{{__('F.A.Q')}}</a></li>
                <li><a href="#contact">{{__('Contact Us')}}</a></li>
                @if(config('boilerplate.locale.status') && count(config('boilerplate.locale.languages')) > 1)
                    @if(app()->getLocale() == 'ar')
                        <li><a href="{{route('locale.change', 'en')}}">EN</a></li>
                    @else
                        <li><a href="{{route('locale.change', 'ar')}}">Ar</a></li>
                    @endif
                @endif
                <li class="get-started"><a href="#features">{{__('Get Started')}}</a></li>
            </ul>
        </nav><!-- .nav-menu -->
    </div>
</header><!-- End Header -->

<!-- ======= Hero Section ======= -->
<section id="hero" class="d-flex align-items-center">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 d-lg-flex flex-lg-column justify-content-center" data-aos="fade-up">
                <div>
                    <h1>{{__('Grow Your Business with Khadamati')}}</h1>
                    <h2>{{__('Join thousands of service providers on the Khadamati Provider App. Showcase your skills, manage bookings, and connect with customers effortlessly. Download now to start growing!')}}</h2>
                    <div class="download-buttons">
                        <a href="https://play.google.com/store/apps/details?id=com.khadamati_plus.service_provider" target="_blank" class="download-btn"><i class="bx bxl-play-store"></i> {{__('Google Play')}}</a>
                        <a href="https://apps.apple.com/us/app/%D8%AE%D8%AF%D9%85%D8%A7%D8%AA%D9%8A-%D8%A8%D9%84%D8%B3-%D8%A7%D9%84%D8%B4%D8%B1%D9%83%D8%A7%D8%A1/id6736589491" target="_blank" class="download-btn"><i class="bx bxl-apple"></i> {{__('App Store')}}</a>
                        <a href="https://appgallery.huawei.com/app/C112154125" target="_blank" class="download-btn"><i class="bx bx-mobile"></i> {{__('Huawei AppGallery')}}</a>
                        <!-- Added Learn How to Register Button -->
                        <button type="button" class="download-btn" data-toggle="modal" data-target="#registerVideoModal">
                            <i class="bx bx-video"></i> {{__('Learn How to Register')}}
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 d-lg-flex flex-lg-column align-items-stretch order-1 order-lg-2 hero-img" data-aos="fade-up">
                <img src="{{asset('assets/img/hero-img.png')}}" class="img-fluid" alt="">
            </div>
        </div>
    </div>

    <!-- Modal for Video -->
    <div class="modal fade" id="registerVideoModal" tabindex="-1" aria-labelledby="registerVideoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerVideoModalLabel">{{__('How to Register on Khadamati')}}</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">x</button>
                </div>
                <div class="modal-body">
                    <video width="100%" height="auto" controls>
                        <source src="{{asset('assets/videos/register-tutorial.mp4')}}" type="video/mp4">
                        {{__('Your browser does not support the video tag.')}}
                    </video>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                </div>
            </div>
        </div>
    </div>
</section><!-- End Hero -->

<main id="main">

    <!-- ======= Provider Tools Section ======= -->
    <section id="features" class="features">
        <div class="container">
            <div class="section-title">
                <h2>{{__('Provider Tools')}}</h2>
                <p>{{__('Unlock powerful tools designed to help service providers succeed. From managing your services to reaching new customers, the Khadamati Provider App has you covered.')}}</p>
            </div>

            <div class="row no-gutters">
                <div class="col-xl-7 d-flex align-items-stretch order-2 order-lg-1">
                    <div class="content d-flex flex-column justify-content-center">
                        <div class="row">
                            <div class="col-md-6 icon-box" data-aos="fade-up">
                                <i class="bx bx-upload"></i>
                                <h4>{{__('Service Management')}}</h4>
                                <p>{{__('Easily create, edit, and manage your service listings with photos, pricing, and availability.')}}</p>
                            </div>
                            <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="100">
                                <i class="bx bx-calendar"></i>
                                <h4>{{__('Booking System')}}</h4>
                                <p>{{__('Streamline appointments with an integrated booking system tailored to your schedule.')}}</p>
                            </div>
                            <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="200">
                                <i class="bx bx-user-plus"></i>
                                <h4>{{__('Customer Outreach')}}</h4>
                                <p>{{__('Reach a wider audience and attract new clients with targeted visibility.')}}</p>
                            </div>
                            <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="300">
                                <i class="bx bx-star"></i>
                                <h4>{{__('Reputation Building')}}</h4>
                                <p>{{__('Collect reviews and ratings to boost your credibility and attract more business.')}}</p>
                            </div>
                            <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="400">
                                <i class="bx bx-stats"></i>
                                <h4>{{__('Performance Insights')}}</h4>
                                <p>{{__('Track your earnings, bookings, and customer interactions with detailed analytics.')}}</p>
                            </div>
                            <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="500">
                                <i class="bx bx-wallet"></i>
                                <h4>{{__('Secure Payments')}}</h4>
                                <p>{{__('Receive payments quickly and securely directly through the app.')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="image col-xl-5 d-flex align-items-stretch justify-content-center order-1 order-lg-2" data-aos="fade-left" data-aos-delay="100">
                    <img src="{{asset('assets/img/features.svg')}}" class="img-fluid" alt="">
                </div>
            </div>
        </div>
    </section><!-- End Provider Tools Section -->

    <!-- ======= Details Section ======= -->
    <section id="details" class="details">
        <div class="container">
            <div class="row content">
                <div class="col-md-4" data-aos="fade-right">
                    <img src="{{asset('assets/img/details-1.png')}}" class="img-fluid" alt="{{__('Service Management')}}">
                </div>
                <div class="col-md-8 pt-4" data-aos="fade-up">
                    <h3>{{__('Manage Your Services Effortlessly')}}</h3>
                    <p class="font-italic">
                        {{__('The Khadamati Provider App simplifies how you list and manage your services, helping you stay organized and efficient.')}}
                    </p>
                    <ul>
                        <li><i class="icofont-check"></i> {{__('Add photos, descriptions, and pricing in minutes.')}}</li>
                        <li><i class="icofont-check"></i> {{__('Update availability and details on the go.')}}</li>
                        <li><i class="icofont-check"></i> {{__('Manage multiple services from one dashboard.')}}</li>
                        <li><i class="icofont-check"></i> {{__('Showcase your work to thousands of potential clients.')}}</li>
                    </ul>
                    <p>
                        {{__('Whether you’re a freelancer or a small business, our tools help you stay in control and grow your reach.')}}
                    </p>
                </div>
            </div>

            <div class="row content">
                <div class="col-md-4 order-1 order-md-2" data-aos="fade-left">
                    <img src="{{asset('assets/img/details-2.png')}}" class="img-fluid" alt="{{__('Reach Customers')}}">
                </div>
                <div class="col-md-8 pt-5 order-2 order-md-1" data-aos="fade-up">
                    <h3>{{__('Reach More Customers')}}</h3>
                    <p class="font-italic">
                        {{__('Expand your client base with tools designed to increase your visibility and attract business.')}}
                    </p>
                    <p>
                        {{__('Get discovered by customers searching for your services. Promote your offerings and connect with clients in your area.')}}
                    </p>
                    <p>
                        {{__('With Khadamati, your services are just a tap away from thousands of potential customers.')}}
                    </p>
                </div>
            </div>

            <div class="row content">
                <div class="col-md-4" data-aos="fade-right">
                    <img src="{{asset('assets/img/details-3.png')}}" class="img-fluid" alt="{{__('Seamless Communication')}}">
                </div>
                <div class="col-md-8 pt-5" data-aos="fade-up">
                    <h3>{{__('Communicate with Ease')}}</h3>
                    <p>{{__('Stay connected with your clients through our built-in communication tools designed for providers.')}}</p>
                    <ul>
                        <li><i class="icofont-check"></i> {{__('Chat directly with customers within the app.')}}</li>
                        <li><i class="icofont-check"></i> {{__('Get notified about new inquiries and bookings.')}}</li>
                        <li><i class="icofont-check"></i> {{__('Respond quickly with a user-friendly interface.')}}</li>
                    </ul>
                    <p>
                        {{__('Efficient communication means happier clients and more repeat business.')}}
                    </p>
                </div>
            </div>

            <div class="row content">
                <div class="col-md-4 order-1 order-md-2" data-aos="fade-left">
                    <img src="{{asset('assets/img/details-4.png')}}" class="img-fluid" alt="{{__('Reliable Platform')}}">
                </div>
                <div class="col-md-8 pt-5 order-2 order-md-1" data-aos="fade-up">
                    <h3>{{__('A Platform You Can Trust')}}</h3>
                    <p class="font-italic">
                        {{__('Khadamati is built to support providers with a reliable and secure experience.')}}
                    </p>
                    <p>
                        {{__('From payments to performance tracking, our app ensures you have everything you need to succeed.')}}
                    </p>
                    <ul>
                        <li><i class="icofont-check"></i> {{__('Secure payment processing for your earnings.')}}</li>
                        <li><i class="icofont-check"></i> {{__('Regular app updates for a smooth experience.')}}</li>
                        <li><i class="icofont-check"></i> {{__('Dedicated support to help you succeed.')}}</li>
                    </ul>
                </div>
            </div>
        </div>
    </section><!-- End Details Section -->

    <!-- ======= Gallery Section ======= -->
    <section id="gallery" class="gallery" style="direction: ltr">
        <div class="container">
            <div class="section-title">
                <h2>{{__('Gallery')}}</h2>
                <p>{{__('See how providers like you are thriving on Khadamati. From service showcases to success stories, our gallery highlights the opportunities waiting for you.')}}</p>
            </div>
            <div class="owl-carousel gallery-carousel" data-aos="fade-up">
                <a href="{{asset('assets/img/provider/provider-1.jpg')}}" class="venobox" data-gall="gallery-carousel"><img src="{{asset("assets/img/provider/provider-1.jpg")}}" alt=""></a>
                <a href="{{asset('assets/img/provider/provider-2.jpg')}}" class="venobox" data-gall="gallery-carousel"><img src="{{asset("assets/img/provider/provider-2.jpg")}}" alt=""></a>
                <a href="{{asset('assets/img/provider/provider-3.jpg')}}" class="venobox" data-gall="gallery-carousel"><img src="{{asset("assets/img/provider/provider-3.jpg")}}" alt=""></a>
                <a href="{{asset('assets/img/provider/provider-4.jpg')}}" class="venobox" data-gall="gallery-carousel"><img src="{{asset("assets/img/provider/provider-4.jpg")}}" alt=""></a>
                <a href="{{asset('assets/img/provider/provider-5.jpg')}}" class="venobox" data-gall="gallery-carousel"><img src="{{asset("assets/img/provider/provider-5.jpg")}}" alt=""></a>
                <a href="{{asset('assets/img/provider/provider-6.jpg')}}" class="venobox" data-gall="gallery-carousel"><img src="{{asset("assets/img/provider/provider-6.jpg")}}" alt=""></a>
            </div>
        </div>
    </section><!-- End Gallery Section -->

    <!-- ======= Testimonials Section ======= -->
    <section id="testimonials" class="testimonials section-bg" style="direction: ltr">
        <div class="container">
            <div class="section-title">
                <h2>{{__('Provider Testimonials')}}</h2>
                <p>{{__('Hear from service providers who have transformed their businesses with Khadamati. Their success stories are a testament to our platform’s impact.')}}</p>
            </div>
            <div class="owl-carousel testimonials-carousel" data-aos="fade-up">
                <div class="testimonial-wrap">
                    <div class="testimonial-item">
                        <h3>{{__('Ahmed Al-Hussein')}}</h3>
                        <h4>{{__('Plumber')}}</h4>
                        <p>
                            <i class="bx bxs-quote-alt-left quote-icon-left"></i>
                            {{__('Khadamati helped me double my bookings in just a few months. The app is easy to use, and the support is amazing!')}}
                            <i class="bx bxs-quote-alt-right quote-icon-right"></i>
                        </p>
                    </div>
                </div>
                <div class="testimonial-wrap">
                    <div class="testimonial-item">
                        <h3>{{__('Fatima Al-Sayed')}}</h3>
                        <h4>{{__('Graphic Designer')}}</h4>
                        <p>
                            <i class="bx bxs-quote-alt-left quote-icon-left"></i>
                            {{__('This app made it so simple to showcase my work and connect with clients. My business has grown faster than ever.')}}
                            <i class="bx bxs-quote-alt-right quote-icon-right"></i>
                        </p>
                    </div>
                </div>
                <div class="testimonial-wrap">
                    <div class="testimonial-item">
                        <h3>{{__('Omar Al-Farsi')}}</h3>
                        <h4>{{__('Electrician')}}</h4>
                        <p>
                            <i class="bx bxs-quote-alt-left quote-icon-left"></i>
                            {{__('The booking system and payment tools are a game-changer. I can focus on my work while Khadamati handles the rest.')}}
                            <i class="bx bxs-quote-alt-right quote-icon-right"></i>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section><!-- End Testimonials Section -->

    <!-- ======= Frequently Asked Questions Section ======= -->
    <section id="faq" class="faq section-bg">
        <div class="container">
            <div class="section-title">
                <h2>{{__('Frequently Asked Questions')}}</h2>
                <p>{{__('Got questions about becoming a provider? Here are answers to some common queries. Contact us if you need more help!')}}</p>
            </div>
            <div class="accordion-list">
                <ul>
                    <li data-aos="fade-up">
                        <i class="bx bx-help-circle icon-help"></i>
                        <a data-toggle="collapse" class="collapse" href="#accordion-list-1">
                            {{__('How do I get started as a provider?')}}
                            <i class="bx bx-chevron-down icon-show"></i>
                            <i class="bx bx-chevron-up icon-close"></i>
                        </a>
                        <div id="accordion-list-1" class="collapse show" data-parent=".accordion-list">
                            <p>
                                {{__('Download the app, create a provider profile, and start adding your services. It’s quick and easy to set up!')}}
                            </p>
                        </div>
                    </li>
                    <li data-aos="fade-up" data-aos-delay="100">
                        <i class="bx bx-help-circle icon-help"></i>
                        <a data-toggle="collapse" href="#accordion-list-2" class="collapsed">
                            {{__('Is there a fee to join as a provider?')}}
                            <i class="bx bx-chevron-down icon-show"></i>
                            <i class="bx bx-chevron-up icon-close"></i>
                        </a>
                        <div id="accordion-list-2" class="collapse" data-parent=".accordion-list">
                            <p>
                                {{__('Joining is free! We may charge a small commission on bookings, but there are no upfront costs to get started.')}}
                            </p>
                        </div>
                    </li>
                    <li data-aos="fade-up" data-aos-delay="200">
                        <i class="bx bx-help-circle icon-help"></i>
                        <a data-toggle="collapse" href="#accordion-list-3" class="collapsed">
                            {{__('How do I receive payments?')}}
                            <i class="bx bx-chevron-down icon-show"></i>
                            <i class="bx bx-chevron-up icon-close"></i>
                        </a>
                        <div id="accordion-list-3" class="collapse" data-parent=".accordion-list">
                            <p>
                                {{__('Payments are processed securely through the app and transferred to your preferred account after each job.')}}
                            </p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </section><!-- End Frequently Asked Questions Section -->

    <!-- ======= Contact Section ======= -->
    <section id="contact" class="contact">
        <div class="container">
            <div class="section-title">
                <h2>{{__('Contact Us')}}</h2>
                <p>{{__('We’re here to support providers in Amman, Jordan, and beyond. Reach out with any questions or assistance you need!')}}</p>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="contact-info">
                        <h3>{{__('Our Office')}}</h3>
                        <p><strong>{{__('Location:')}}</strong> {{__('Amman, Jordan')}}</p>
                        <p><strong>{{__('Phone:')}}</strong> +962796667820</p>
                        <p><strong>{{__('Email:')}}</strong>info@futurespioneers.com</p>
                        <p><strong>{{__('Working Hours:')}}</strong> {{__('Sunday - Thursday, 9:00 AM - 5:00 PM')}}</p>
                        <p>{{__('Drop by or contact us—we’re excited to help you succeed as a provider!')}}</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <form action="{{route('frontend.contactUsSubmission')}}" method="post" role="form" class="php-email-form">
                        @csrf
                        <div class="form-group">
                            <input required placeholder="{{__('Your Email')}}" type="email" class="form-control" name="email" id="email" data-rule="email" data-msg="{{__('Please enter a valid email')}}" />
                            <div class="validate"></div>
                        </div>
                        <div class="form-group">
                            <input required placeholder="{{__('Subject')}}" type="text" class="form-control" name="subject" id="subject" data-rule="minlen:4" data-msg="{{__('Please enter at least 4 chars of subject')}}" />
                            <div class="validate"></div>
                        </div>
                        <div class="form-group">
                            <textarea required placeholder="{{__('Message')}}" class="form-control" name="message" rows="5" data-rule="required" data-msg="{{__('Please write something for us')}}"></textarea>
                            <div class="validate"></div>
                        </div>
                        <div class="text-center"><button type="submit">{{__('Send Message')}}</button></div>
                    </form>
                </div>
            </div>
        </div>
    </section><!-- End Contact Section -->

</main><!-- End #main -->

<!-- ======= Footer ======= -->
<footer id="footer">
    <div class="footer-newsletter" data-aos="fade-up">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <h4>{{__('Join Our Newsletter')}}</h4>
                    <p>{{__('Get the latest tips, updates, and opportunities for providers straight to your inbox.')}}</p>
                    <form action="{{ route('frontend.storeSubscriber') }}" method="post">
                        @csrf
                        <input type="email" name="email" placeholder="{{__('Enter your email')}}">
                        <input type="submit" value="{{__('Subscribe')}}">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 footer-contact" data-aos="fade-up">
                    <h3>{{__('Khadamati Provider')}}</h3>
                    <p>
                        {{__("Amman, Jordan")}}<br>
                        {{__("Jordan")}} <br><br>
                        <strong>{{__("Phone:")}}</strong> +962796667820<br>
                        <strong>{{__("Email:")}}</strong> info@futurespioneers.com<br>
                    </p>
                </div>
                <div class="col-lg-4 col-md-6 footer-links" data-aos="fade-up" data-aos-delay="100">
                    <h4>{{__('Useful Links')}}</h4>
                    <ul>
                        <li><i class="bx @if(app()->getLocale() == 'ar')bx-chevron-left @else bx-chevron-right @endif"></i> <a href="/">{{__('Home')}}</a></li>
                        <li><i class="bx @if(app()->getLocale() == 'ar')bx-chevron-left @else bx-chevron-right @endif"></i> <a href="#features">{{__('Provider Tools')}}</a></li>
                        <li><i class="bx @if(app()->getLocale() == 'ar')bx-chevron-left @else bx-chevron-right @endif"></i> <a href="#gallery">{{__('Gallery')}}</a></li>
                        <li><i class="bx @if(app()->getLocale() == 'ar')bx-chevron-left @else bx-chevron-right @endif"></i> <a href="#faq">{{__('F.A.Q')}}</a></li>
                        <li><i class="bx @if(app()->getLocale() == 'ar')bx-chevron-left @else bx-chevron-right @endif"></i> <a href="#contact">{{__('Contact Us')}}</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-6 footer-links" data-aos="fade-up" data-aos-delay="300">
                    <h4>{{__('Our Social Networks')}}</h4>
                    <p>{{__('Stay connected with us for provider tips and updates!')}}</p>
                    <div class="social-links mt-3">
                        <a href="#" class="facebook"><i class="bx bxl-facebook"></i></a>
                        <a href="#" class="instagram"><i class="bx bxl-instagram"></i></a>
                        <a href="#" class="linkedin"><i class="bx bxl-linkedin"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-4">
        <div class="copyright">
            © {{__('Copyright')}} <strong><span>{{__('Khadamati')}}</span></strong>. {{__('All Rights Reserved.')}}
        </div>
    </div>
</footer><!-- End Footer -->

<a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

<!-- Vendor JS Files -->
<script src="{{asset('assets/vendor/jquery/jquery.min.js')}}"></script>
<script src="{{asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/vendor/jquery.easing/jquery.easing.min.js')}}"></script>
<script src="{{asset('assets/vendor/php-email-form/validate.js')}}"></script>
<script src="{{asset('assets/vendor/owl.carousel/owl.carousel.min.js')}}"></script>
<script src="{{asset('assets/vendor/venobox/venobox.min.js')}}"></script>
<script src="{{asset('assets/vendor/aos/aos.js')}}"></script>

<!-- Template Main JS File -->
<script src="{{asset('assets/js/main.js')}}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

</body>

</html>
<script>
    $(document).ready(function() {
        toastr.options = {
            'closeButton': true,
            'debug': false,
            'newestOnTop': false,
            'progressBar': false,
            'positionClass': 'toast-top-right',
            'preventDuplicates': false,
            'showDuration': '1000',
            'hideDuration': '1000',
            'timeOut': '10000',
            'extendedTimeOut': '11000',
            'showEasing': 'swing',
            'hideEasing': 'linear',
            'showMethod': 'fadeIn',
            'hideMethod': 'fadeOut',
            'toastClass': 'custom-toast'
        }
        @if(Session::has('successSubscriber'))
        toastr.success('{{__('Thank you for subscribing to our newsletter! You will now receive the latest updates directly in your inbox.')}}');
        @endif
        @if(Session::has('success'))
        toastr.success('{{__('Thank you for contacting us! Our team will respond promptly.')}}');
        @endif
    });
</script>
