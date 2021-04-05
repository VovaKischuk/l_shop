<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>GN-Techics</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Montserrat%7CRoboto:300,400,700" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">

        <!-- FAVICON  -->
        <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}">

        <!-- ADD BOOTSTRAP  -->
        <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
        <script type="text/javascript" src="{{ asset('js/bootstrap.js') }}"></script>

        <script type="text/javascript" src="{{ asset('js/script.js') }}"></script>

    </head>

    <body>
        <div id="app">

            @include('partials.nav')

            <div class="hero">
                <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="img/slider_1.jpg" class="d-block w-100" alt="...">
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>

            <div class="featured-section">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">                            
                            <div class="block_category">                                
                                @foreach ($categories as $category)
                                    <div class="up_block">
                                        <div class="category_block">
                                            <a href="{{ route('shop.index', ['category' => $category->slug]) }}">
                                                <img src="{{ productImage($category['image']) }}">
                                            </a>                                        
                                        </div>
                                        <div class="category_name">
                                            <span>{{ $category->name }}</span>
                                        </div>
                                    </div>
                                @endforeach                                
                            </div>
                        </div>

                        <div class="col-md-12">
                            <p class="section-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolore vitae nisi, consequuntur illum dolores cumque pariatur quis provident deleniti nesciunt officia est reprehenderit sunt aliquid possimus temporibus enim eum hic lorem.</p>

                            <div class="products text-center">
                                @foreach ($products as $product)
                                    <div class="product">
                                        <a href="{{ route('shop.show', $product->slug) }}"><img src="{{ productImage($product->image) }}" alt="product"></a>
                                        <a href="{{ route('shop.show', $product->slug) }}"><div class="product-name">{{ $product->name }}</div></a>
                                        <div class="product-price">{{ $product->presentPrice() }}</div>
                                    </div>
                                @endforeach

                            </div> <!-- end products -->

                            <div class="text-center button-container">
                                <a href="{{ route('shop.index') }}" class="button">View more products</a>
                            </div>
                        </div>
                    </div>
                </div> <!-- end container -->

            </div> <!-- end featured-section -->

            <div class="main_action">
                <div class="container">
                    <img src="/img/main_action.jpg">
                </div>
            </div>

            <div class="lorem_info_text">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <span>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?</span>
                        </div>
                        <div class="col-md-6">
                            <span>But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences that are extremely painful. Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure. To take a trivial example, which of us ever undertakes laborious physical exercise, except to obtain some advantage from it? But who has any right to find fault with a man who chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that produces no resultant pleasure?</span>
                        </div>
                    </div>
                </div>
            </div>

            @include('partials.footer')            

        </div> <!-- end #app -->
        
    </body>
</html>
