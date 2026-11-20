
        @extends('layouts.customer')

        @section('title', 'Shop - My Store')

        @section('content')

        <div class="shop-container">


            <div class="page-heading">
                <h1>Our Products</h1>
                <p>Discover products available in our store.</p>
            </div>  
            <div class="shop-search">
                
                <form action="{{url('/shop')}}" method="GET" id="shop-filter-form">
                    <input type="text" name="search" placeholder="Search Products..." value="{{request('search')}}" id="search-input">
                    <select name="category">
                        <option value="">All Categories</option>

                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <select name="sort">
                        <option value="">Default Sorting</option>
                        <option value="low_to_high" {{request('sort')=='low_to_high' ? 'selected' : '' }}>
                            Price : Low to High 
                        </option>
                        <option value="high_to_low" {{request('sort') == 'high_to_low' ? 'selected' : '' }}>
                            Price : High to Low
                        </option>

                    </select>
                    
                    <button type="submit" >Search</button>
                    <button type="button" id="clear-filters" class="btn-primary">
                        Cancel
                    </button>


                
                </form>
                


            </div>

            <div id="product-container">
                @include('customer.shop.partials.products')
            </div>

        </div>

        @endsection

        @push('styles')
        <style>
            .shop-search select {
                padding: 12px 15px;
                border: 1px solid #ddd;
                border-radius: 6px;
                background: white;
                font-size: 15px;
                cursor: pointer;
            }
            .shop-container {
                width: 88%;
                max-width: 1200px;
                margin: 35px auto;

            }

            .page-heading {
                margin-bottom: 25px;
            }

            .product-grid {
                display: grid;
                grid-template-columns: repeat(4, minmax(0, 1fr));
                gap: 35px;
                align-items:stretch;

            }
            .no-image {
                display: flex;
                align-items: center;
                justify-content: center;
                color: #64748b;
                font-size: 14px;
            }
            .product-card {
                background: white;
                border-radius: 10px;
                overflow: hidden;
                box-shadow: 0 3px 12px rgba(0,0,0,0.08);
                text-decoration: none;
                color: inherit;
                display: block;
                transition:transform 0.3s ease,box-shadow 0.3s ease;
                position:relative;
            }
            .product-card:hover 
            {
                transform:translateY(-6px);
                box-shadow:0 10px 25px rgba(0,0,0,0.12);
            }
            .stock-badge {
                position: absolute;
                top: 10px;
                right: 10px;
                background: red;
                color: white;
                padding: 6px 10px;
                font-size: 12px;
                border-radius: 4px;
            }
            
            .out-stock {
                color: #dc2626;
                font-size: 13px;
                font-weight: bold;
            }
            .product-image {
                width: 100%;
                height: 220px;
                object-fit: cover;
                background: #e5e7eb;
                transition:transform 0.3s ease;
            }
            .product-image:hover{
                transform:scale(1.05);
            }

            .product-details {
                padding: 24px;
                
            }

            .category {
                display:inline-block;
                padding:5px 10px;
                background: #e5e7eb;
                color: #09090a;
                font-size: 14px;
            }
            .btn-primary {
                display: inline-block;
                padding: 12px 24px;
                background-color: #2563eb;
                color: white;
                text-decoration: none;
                border-radius: 6px;
                font-size: 15px;
                font-weight: 600;
                transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #1d4ed8;
            color: white;
        }

            .price {
                font-size: 20px;
                font-weight: bold;
                color: #2563eb;
                margin: 12px 0;
            }

            .stock {
                color: #15803d;
                font-size: 13px;
            }
            .shop-search {
            margin-bottom: 25px;
        }

        .shop-search form {
            display: flex;
            gap: 10px;
            max-width: 550px;
        }

        .shop-search input {
            flex: 1;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 15px;
        }

                /* Loading container */
        .loading-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 50px;
            gap: 15px;
        }

        /* Spinner animation */
        .loader {
            width: 40px;
            height: 40px;
            border: 4px solid #ddd;
            border-top: 4px solid #2563eb;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

                /* Empty products section */
        .empty-message {
            width: 100%;
            min-height: 350px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 40px 20px;
            background: #ffffff;
            border-radius: 12px;
            margin: 20px 0;
        }

        .pagination-container {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        .pagination-container svg {
            width: 16px;
            height: 16px;
        }


        /* Heading */
        .empty-message h3 {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 10px;
        }

        /* Description */
        .empty-message p {
            max-width: 420px;
            font-size: 15px;
            line-height: 1.6;
            color: #64748b;
            margin-bottom: 25px;
        }

        /* Clear filters button */
        #empty-clear-search {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 12px 25px;
            background: #2563eb;
            color: #ffffff;
            border: none;
            border-radius: 7px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        #empty-clear-search:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
        }       .pagination-container svg {
            width: 16px !important;
            height: 16px !important;
        }

        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 25px;
        }

        .pagination-container a,
        .pagination-container span {
            font-size: 14px;
        }

        /* Rotation animation */
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .shop-search button {
            padding: 12px 22px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
            .empty-message {
                background: white;
                padding: 25px;
                border-radius: 8px;
                text-align: center;
            }

            @media (max-width: 900px) {
                .product-grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }
            }

            @media (max-width: 550px) {
                .product-grid {
                    grid-template-columns: 1fr;
                }
            }
            @media (max-width:900px){
                .shop-container{
                    width:94%;
                    margin:25px auto;
                }
            }
            @media (max-width:550px) {
                .shop-container{
                    width:96%;
                    margin:18px auto;
                }
                
            }
            @media (max-width:900px) {
                .product-grid{
                    gap:18px;
                }
            }
            @media (max-width:550px) {
                .product-grid{
                    gap:14px;
                }
                
            }
            @media (max-width:550px){
                .product-image{
                    height:200px;
                }
            }

        </style>
        @endpush

        @push('scripts')
        <script>
            $(document).ready(function(){

                let searchTimer;
                let searchRequest=null;

                function loadproducts(){
                    let formData=$('#shop-filter-form').serialize();
                        if(searchRequest !== null){
                            searchRequest.abort();
                        }
                        searchRequest=$.ajax({
                            url:$('#shop-filter-form').attr('action'),
                            type:'GET',
                            data:formData,

                            beforeSend: function () {
                                $('#product-container').html(`
                                    <div class="loading-container">
                                        <div class="loader"></div>
                                        <p>Loading products...</p>
                                    </div>
                                `);
                            },

                            success:function(response){
                                $('#product-container').html(response);
                            },
                            error: function(xhr) {
                                if(xhr.statusText !== 'abort'){
                                    console.log(xhr.responseText);
                                    alert('Unable to load products')
                                }   
                               
                            },
                            complete: function(){
                                console.log('Ajax request completed');
                            }
                            

                        });
                }

                $('#search-input').on('input',function(){
                    clearTimeout(searchTimer);
                    searchTimer=setTimeout(function(){
                        loadproducts();
                    },500);

                });
                $('#shop-filter-form select').on('change',function(){
                    clearTimeout(searchTimer);
                    loadproducts();
                });
                $('#clear-filters').on('click',function(){
                    clearTimeout(searchTimer);
                    $('#shop-filter-form')[0].reset();
                    loadproducts();

                });
                $('#shop-filter-form').on('submit',function(e){
                    e.preventDefault();
                    clearTimeout(searchTimer);
                    loadproducts();

                });
                $(document).on('click', '#empty-clear-search',function(){
                    $('#shop-filter-form')[0].reset();
                    clearTimeout(searchTimer);
                    loadproducts();
                });
                $(document).on('click','#product-container nav[role="navigation"] a',function(e){
                    e.preventDefault();
                    let pageUrl=$(this).attr('href');

                    if(searchRequest !== null){
                        searchRequest.abort();
                    }
                    searchRequest = $.ajax({
                        url: pageUrl,
                        type:'GET',

                        beforeSend: function(){
                            $('#product-container').html('<p>Page Loading</p>');

                        },
                        success: function(response){
                            $('#product-container').html(response);
                        },
                        error: function(xhr){
                            if(xhr.statusText !== 'abort'){
                                console.log(xhr.responseText);
                                alert('Unable to Load the next page');
                            }
                        }

                    });

                });

            });
        </script>
       @endpush