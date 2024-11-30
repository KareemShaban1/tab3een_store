<!DOCTYPE html>
<html lang="en">

@include('frontend.layouts.head')


<body>
    <div class="page-wrapper">
    @include('frontend.layouts.header')
        <main class="main">
            @yield('content')

        </main><!-- End .main -->

        @include('frontend.layouts.footer')

    </div><!-- End .page-wrapper -->
    <button id="scroll-top" title="Back to Top"><i class="icon-arrow-up"></i></button>

    @include('frontend.layouts.mobile-menu')


    <!-- Sign in / Register Modal -->
    @include('frontend.layouts.signInRegisterModal')


    @include('frontend.layouts.newsletter')

    @include('frontend.layouts.footer-scripts')


</body>


</html>