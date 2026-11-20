
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'My Store')</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f6fa;
            color: #222;
        }

        /* Horizontal Customer Navbar */
        .customer-navbar {
            background: #2874f0;
            color: white;
            padding: 15px 5%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 25px;
            flex-wrap: wrap;
        }

        .customer-logo {
            color: white;
            font-size: 25px;
            font-weight: bold;
            text-decoration: none;
        }

        .customer-nav {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .customer-nav a {
            color: white;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
        }

        .customer-nav a:hover {
            color: #e0e7ff;
        }

        .customer-user {
            color: white;
            font-size: 14px;
        }

        .logout-button {
            background: white;
            color: #2874f0;
            border: none;
            padding: 9px 16px;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
        }

        .customer-content {
            min-height: 80vh;
        }

        .customer-footer {
            background: #172337;
            color: white;
            text-align: center;
            padding: 20px;
        }
        

        
    @media (max-width: 900px) {
        .customer-navbar {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 15px;
        }

        .customer-nav {
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 12px;
        }

        .customer-nav a {
            font-size: 13px;
        }

        .customer-user {
            font-size: 12px;
        }

        .logout-button {
            padding: 7px 12px;
            font-size: 12px;
        }
    }

    </style>


    <div id="customer-page-styles" hidden>
        @stack('styles')
    </div>

</head>
    
<body>

    <!-- Horizontal Navigation -->
    <header class="customer-navbar">

        <a href="{{ url('/shop') }}" class="customer-logo">
            My Store
        </a>

        <nav class="customer-nav">

        

            @auth
                

                <a href="{{ route('customer.account') }}">My Account</a>
                <a href="{{ route('shop') }}">Shop</a>

                <a href="{{route('cart.index')}}">My Cart</a>

                <a href="{{ route('customer.orders.index') }}">My Orders</a>

                <span class="customer-user">
                    Hello, {{ auth()->user()->name }}
                </span>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-button">
                        Logout
                    </button>
                </form>

            @else

                <a href="{{ route('login') }}">Login</a>

                <a href="{{ route('register') }}">Register</a>

            @endauth

        </nav>

    </header>

    <!-- Page Content -->
    <main class="customer-content" id="customer-content">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="customer-footer">
        <p>&copy; {{ date('Y') }} My Store. All rights reserved.</p>
    </footer>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <div id="customer-page-scripts">
        @stack('scripts')
        <script>
$(document).ready(function () {

    let isLoading = false;

    function loadCustomerPage(url, addHistory = true) {

        if (isLoading) return;

        isLoading = true;

        $('#customer-content').css('opacity', '0.5');

        fetch(url, {
            method: 'GET',
            headers: {
                
                'Accept': 'text/html'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Page could not be loaded');
            }

            return response.text();
        })
        .then(html => {

            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            const newContent = doc.querySelector('#customer-content');
            const newStyles = doc.querySelector('#customer-page-styles');
            const newScripts = doc.querySelector('#customer-page-scripts');

            // If the response is not a customer page, use normal navigation.
            if (!newContent) {
                window.location.href = url;
                return;
            }

            // Replace page content.
            document.querySelector('#customer-content').innerHTML =
                newContent.innerHTML;

            // Replace page-specific CSS.
            if (newStyles) {
                document.querySelector('#customer-page-styles').innerHTML =
                    newStyles.innerHTML;
            }

            // Update page title.
            document.title = doc.title;

            // Update browser URL.
            if (addHistory) {
                history.pushState({}, '', url);
            }

            // Re-execute page-specific scripts.
            if (newScripts) {

                const scriptContainer =
                    document.querySelector('#customer-page-scripts');

                scriptContainer.innerHTML = '';

                newScripts.querySelectorAll('script').forEach(oldScript => {

                    const newScript = document.createElement('script');

                    Array.from(oldScript.attributes).forEach(attribute => {
                        newScript.setAttribute(attribute.name, attribute.value);
                    });

                    newScript.textContent = oldScript.textContent;

                    scriptContainer.appendChild(newScript);
                });
            }

            // Return to the top of the page.
            window.scrollTo(0, 0);

        })
        .catch(error => {
            console.error('AJAX Navigation Error:', error);
            window.location.href = url;
        })
        .finally(() => {
            isLoading = false;
            $('#customer-content').css('opacity', '1');
        });
    }


    // Handle internal customer navigation links.
    $(document).on('click', 'a[href]', function (e) {

        const link = this;
        const url = new URL(link.href, window.location.origin);

        // Allow normal browser behaviour for special clicks.
        if (
            e.ctrlKey ||
            e.shiftKey ||
            e.altKey ||
            e.metaKey ||
            e.button !== 0 ||
            link.target ||
            link.hasAttribute('download') ||
            link.getAttribute('href').startsWith('#') ||
            link.dataset.ajax === 'false' ||
            url.origin !== window.location.origin ||
            e.isDefaultPrevented()
        ) {
            return;
        }

        e.preventDefault();

        loadCustomerPage(url.href);
    });


    // Support browser Back and Forward buttons.
    window.addEventListener('popstate', function () {
        loadCustomerPage(window.location.href, false);
    });

});
</script>
    </div>
    

</body>
</html>
