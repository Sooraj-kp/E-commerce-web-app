            @if($products->count() > 0)

                <div class="product-grid">

                    @foreach($products as $product)
                        
                        <a href="{{ url('/shop/' . $product->id) }}"
                        class="product-card">

                            @if($product->stock <=0)
                            <span class="stock-badge">Out Of Stock</span>
                            @endif
                            @php
                                $mainImage = $product->images->firstWhere('is_primary', true)
                                ?? $product->images->first();
                            @endphp

                            @if($mainImage)
                                <img
                                    src="{{ asset('image_uploads/' . $mainImage->image_path) }}"
                                    alt="{{ $product->name }}"
                                    class="product-image"
                                >
                            @else
                                <div class="product-image no-image"><span>No Image Available</span></div>
                            @endif

                            <div class="product-details">

                                <h3>{{ $product->name }}</h3>

                                <p class="category">
                                    {{ $product->category->name ?? 'Uncategorized' }}
                                </p>

                                <p>{{ Str::limit($product->description, 90) }}</p>

                                <div class="price">
                                    ₹{{ number_format($product->price, 2) }}
                                </div>


                                @if ($product->stock>0)
                                <p class="stock">
                                    In stock: {{ $product->stock }}
                                </p>
                                @else 
                                    <p class="out-stock">Currently Unavailable</p>
                                @endif

                            </div>

                        </a>
                        
                    @endforeach

                </div>
                <div class="pagination-container">{{$products->onEachSide(2)->links()}}</div>

            @else

                    
        <div class="empty-message">
            <div class="empty-icon">
                <i class="fas fa-box-open"></i>
            </div>

                        <button type="button" id="empty-clear-search">
                <i class="fas fa-rotate-left"></i>
                Clear Filters
            </button>

            <h3>No Products Found!</h3>

            <p>
                We couldn't find any products matching your search.
                Try another keyword or clear your filters.
            </p>


        </div>
    

        @endif