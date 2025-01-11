<!DOCTYPE html>
<html lang="{{ htmlLang() }}" @langrtl dir="rtl" @endlangrtl>

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>{{ appName() }} | @yield('title')</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{asset("assets/img/favicon.png")}}" rel="icon">
    <link href="{{asset("assets/img/apple-touch-icon.png")}}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{asset("assets/vendor/bootstrap/css/bootstrap.min.css")}}" rel="stylesheet">
    <link href="{{asset("assets/vendor/icofont/icofont.min.css")}}" rel="stylesheet">
    <link href="{{asset("assets/vendor/boxicons/css/boxicons.min.css")}}" rel="stylesheet">
    <link href="{{asset("assets/vendor/owl.carousel/assets/owl.carousel.min.css")}}" rel="stylesheet">
    <link href="{{asset("assets/vendor/venobox/venobox.css")}}" rel="stylesheet">
    <link href="{{asset("assets/vendor/aos/aos.css")}}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{asset("assets/css/style.css")}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">


</head>

<body>

<!-- ======= Header ======= -->
<header id="header" class="fixed-top  header-transparent ">
    <div class="container d-flex align-items-center">

        <div class="logo  @if(app()->getLocale() == 'ar') ml-auto @else mr-auto @endif">
            {{--            <h1 class="text-light"><a href="/">Khadamati APP</a></h1>--}}
            <!-- Uncomment below if you prefer to use an image logo -->
            <a href="/"><img src="{{ asset('img/brand/khadamati-logo.png') }}" alt="" class="img-fluid"></a>
        </div>

        <nav class="nav-menu d-none d-lg-block">
            <ul>
                <li class="active"><a href="/">{{__("Home")}}</a></li>
                <li><a href="#features">{{__("App Features")}}</a></li>
                <li><a href="#gallery">{{__("Gallery")}}</a></li>
                <li><a href="#faq">{{__("F.A.Q")}}</a></li>
                <li><a href="#contact">{{__("Contact Us")}}</a></li>
                @if(config('boilerplate.locale.status') && count(config('boilerplate.locale.languages')) > 1)
                    @if(app()->getLocale() == 'ar')
                        <li><a href="{{route('locale.change', 'en')}}">EN</a></li>
                    @else
                        <li><a href="{{route('locale.change', 'ar')}}">Ar</a></li>
                    @endif
                @endif

                <li class="get-started"><a href="#features">{{__("Get Started")}}</a></li>
            </ul>
        </nav><!-- .nav-menu -->

    </div>
</header><!-- End Header -->

<!-- ======= Hero Section ======= -->



<main id="main" style="
    text-align: center;
    margin-top: 5rem;
    padding: 2rem;
    font-family: Arial, sans-serif;
    font-size: 1rem;
    color: #333;
    line-height: 1.6;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
">
    {!! $privacy->description !!}
</main>


<!-- ======= Footer ======= -->
<footer id="footer">

    <div class="footer-newsletter" data-aos="fade-up">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <h4>{{__("Join Our Newsletter")}}</h4>
                    <p>{{__("Stay updated with the latest news, offers, and updates from our team here in Amman, Jordan.")}}</p>
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
                    <h3>{{__("Kadamati App")}}</h3>
                    <p>
                        {{__("123 Main Street, Tabarbur")}} <br>
                        {{__("Amman, Jordan 11118")}}<br>
                        {{__("Jordan")}} <br><br>
                        <strong>{{__("Phone:")}}</strong> +962 6 123 4567<br>
                        <strong>{{__("Email:")}}</strong> contact@yourcompany.com<br>
                    </p>
                </div>

                <div class="col-lg-4 col-md-6 footer-links" data-aos="fade-up" data-aos-delay="100">
                    <h4>{{__("Useful Links")}}</h4>
                    <ul>
                        <li><i class="bx  @if(app()->getLocale() == 'ar')bx-chevron-left @else bx-chevron-right @endif"></i> <a href="/">{{__("Home")}}</a></li>
                        <li><i class="bx @if(app()->getLocale() == 'ar')bx-chevron-left @else bx-chevron-right @endif"></i> <a href="#features">{{__("App Features")}}</a></li>
                        <li><i class="bx @if(app()->getLocale() == 'ar')bx-chevron-left @else bx-chevron-right @endif"></i> <a href="#gallery">{{__("Gallery")}}</a></li>
                        <li><i class="bx @if(app()->getLocale() == 'ar')bx-chevron-left @else bx-chevron-right @endif"></i> <a href="#faq">{{__("F.A.Q")}}</a></li>
                        <li><i class="bx @if(app()->getLocale() == 'ar')bx-chevron-left @else bx-chevron-right @endif"></i> <a href="#contact">{{__("Contact Us")}}</a></li>
                    </ul>
                </div>



                <div class="col-lg-4 col-md-6 footer-links" data-aos="fade-up" data-aos-delay="300">
                    <h4>{{__("Our Social Networks")}}</h4>
                    <p>{{__("Follow us on social media for the latest updates and news!")}}</p>
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
            &copy; {{__("Copyright")}} <strong><span>{{__("Khadamati App")}}</span></strong>. {{__("All Rights Reserved.")}}
        </div>
    </div>
</footer><!-- End Footer -->


<a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

<!-- Vendor JS Files -->
<script src="{{asset("assets/vendor/jquery/jquery.min.js")}}"></script>
<script src="{{asset("assets/vendor/bootstrap/js/bootstrap.bundle.min.js")}}"></script>
<script src="{{asset("assets/vendor/jquery.easing/jquery.easing.min.js")}}"></script>
<script src="{{asset("assets/vendor/php-email-form/validate.js")}}"></script>
<script src="{{asset("assets/vendor/owl.carousel/owl.carousel.min.js")}}"></script>
<script src="{{asset("assets/vendor/venobox/venobox.min.js")}}"></script>
<script src="{{asset("assets/vendor/aos/aos.js")}}"></script>

<!-- Template Main JS File -->
<script src="{{asset("assets/js/main.js")}}"></script>
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
        toastr.success('{{__("Thank you for subscribing to our newsletter! You will now receive the latest updates directly in your inbox.")}}');
        @endif
        @if(Session::has('success'))
        toastr.success('{{__("Thank you for contacting us! Our team will respond promptly.")}}');
        @endif
    });
</script>
