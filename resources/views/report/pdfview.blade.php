<html>
<head>
    <title>PDF</title>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <a href="{{ route('pdfview',['download'=>'pdf']) }}">Download PDF</a>
    <table class="table table-bordered">
        <thead>
        <th>Name</th>
        <th>Email</th>
        </thead>
        <tbody>
        {{--@foreach ($report as $key => $value)--}}
            <tr>
                <td>{{ $report->date_start }}</td>
                <td>{{ $report->date_end}}</td>
            </tr>
        {{--@endforeach--}}
        </tbody>
    </table>
</div>
</body>
</html>