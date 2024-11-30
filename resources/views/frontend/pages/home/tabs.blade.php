<div class="container featured">

<div class="owl-carousel owl-full carousel-equal-height carousel-with-shadow" data-toggle="owl" data-owl-options='{
                                "nav": true, 
                                "dots": true,
                                "margin": 20,
                                "loop": false,
                                "responsive": {
                                    "0": {
                                        "items":2
                                    },
                                    "600": {
                                        "items":2
                                    },
                                    "992": {
                                        "items":3
                                    },
                                    "1200": {
                                        "items":4
                                    }
                                }
                            }'>
          @foreach ($products as $product)

                                <div class="product product-2">
                                                            <figure class="product-media">
                                                                                  <span class="product-label label-circle label-new">New</span>
                                                                                  <a href="product.html">
                                                                                                              <img src="{{$product->image_url}}"
                                                                                                                                    alt="Product image" class="product-image">
                                                                                                              <img src="{{url('frontend2/assets/images/demos/demo-3/products/product-2-2.jpg')}}"
                                                                                                                                    alt="Product image" class="product-image-hover">
                                                                                  </a>

                                                                                  <div class="product-action-vertical">
                                                                                                              <a href="#" class="btn-product-icon btn-wishlist btn-expandable"><span>add to
                                                                                                                                                                wishlist</span></a>
                                                                                  </div><!-- End .product-action -->

                                                                                  <div class="product-action product-action-dark">
                                                                                                              <a href="#" class="btn-product btn-cart" title="Add to cart"><span>add to
                                                                                                                                                                cart</span></a>
                                                                                                              <a href="popup/quickView.html" class="btn-product btn-quickview"
                                                                                                                                    title="Quick view"><span>quick view</span></a>
                                                                                  </div><!-- End .product-action -->
                                                            </figure><!-- End .product-media -->

                                                            <div class="product-body">
                                                                                  <div class="product-cat">
                                                                                                              <a href="#">Smartwatches</a>
                                                                                  </div><!-- End .product-cat -->
                                                                                  <h3 class="product-title"><a href="product.html">
                                                                                                                                    <!-- Apple - Apple Watch Series 3 with White Sport Band -->
                                                                                                                                    {{ $product->name }}
                                                                                                              </a></h3><!-- End .product-title -->
                                                                                  <div class="product-price">
                                                                                                              $214.99
                                                                                  </div><!-- End .product-price -->
                                                                                  <div class="ratings-container">
                                                                                                              <div class="ratings">
                                                                                                                                    <div class="ratings-val" style="width: 0%;"></div>
                                                                                                                                    <!-- End .ratings-val -->
                                                                                                              </div><!-- End .ratings -->
                                                                                                              <span class="ratings-text">( 0 Reviews )</span>
                                                                                  </div><!-- End .rating-container -->

                                                                                  <div class="product-nav product-nav-dots">
                                                                                                              <a href="#" class="active" style="background: #e2e2e2;"><span
                                                                                                                                                                class="sr-only">Color name</span></a>
                                                                                                              <a href="#" style="background: #333333;"><span class="sr-only">Color
                                                                                                                                                                name</span></a>
                                                                                                              <a href="#" style="background: #f2bc9e;"><span class="sr-only">Color
                                                                                                                                                                name</span></a>
                                                                                  </div><!-- End .product-nav -->
                                                            </div><!-- End .product-body -->
                                </div>
                      @endforeach
</div>
</div>