<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Store</title>
</head>
<body>
    <center>
        <h2 style="color:red">{{ $storename }}</h2>
        <p style="color:blue">{{ $message }}</p>

        <p style="color:red">
            <b>Store is open: {{ $isopen ? 'yes' : 'no' }}</b>
        </p>

        <h3 style="color:green"><b>OUR PRODUCTS</b></h3>

        @foreach ($products as $product)
            <table>
                <tr>
                    <th>{{$product['name']}}: </th>
                </tr>
                <tr>
                    <td>{{$product['price']}}</td>
                </tr>
            </table>
            
        @endforeach
    </center>
</body>
</html>