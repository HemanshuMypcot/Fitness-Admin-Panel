<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Customer Data</title>
</head>
<body>
    <table>
        <thead>
        <tr>
            <th colspan="8" style="text-align: center;width: 100%;border: 1px solid black; background-color: #bdd7ee"><b>{{ \Carbon\Carbon::parse($startDate)->format('d-m-Y') }} / {{ \Carbon\Carbon::parse($endDate)->format('d-m-Y') }}</b></th>
        </tr>
        <tr>
            <th style="text-align: center;width: 100%;border: 1px solid black; background-color: #bdd7ee"><b>SR.No.</b></th>
            <th style="text-align: center;width: 250%;border: 1px solid black; background-color: #bdd7ee"><b>User Name</b></th>
            <th style="text-align: center;width: 250%;border: 1px solid black; background-color: #bdd7ee"><b>Phone No.</b></th>
            <th style="text-align: center;width: 250%;border: 1px solid black; background-color: #bdd7ee"><b>Registered On</b></th>
            <th style="text-align: center;width: 250%;border: 1px solid black; background-color: #bdd7ee"><b>Courses Booked</b></th>
            <th style="text-align: center;width: 300%;border: 1px solid black; background-color: #bdd7ee"><b>Last Course Booking Date</b></th>
            <th style="text-align: center;width: 250%;border: 1px solid black; background-color: #bdd7ee"><b>Total Payments</b></th>
            <th style="text-align: center;width: 250%;border: 1px solid black; background-color: #bdd7ee"><b>Status</b></th>
        </tr>
        </thead>
        <tbody>
        @php
        $srNo = 1;
        @endphp
        @foreach($userData as $key => $data)

            <tr>
                <td style="text-align: center;border: 1px solid black">{{$srNo++ }}</td>
                <td style="text-align: center;border: 1px solid black">{{$data->name ?? ''}}</td>
                <td style="text-align: center;border: 1px solid black">{{$data->phone ?? ''}}</td>
                <td style="text-align: center;border: 1px solid black">{{$data->created_at->format('d-m-Y') ?? ''}}</td>
                <td style="text-align: center;border: 1px solid black">{{$data->bookedCourse->count() ?? ''}}</td>
                <td style="text-align: center;border: 1px solid black">
                    <?php
                    if(!empty($data->newBookedCourse)) {
                        echo $data->newBookedCourse->created_at->format('d-m-Y') ?? '';
                    }else {
                        echo "";
                    }
                    ?>
                </td>
                <td style="text-align: center;border: 1px solid black">{{$data->courses->sum('total') ?? ''}}</td>
                <td style="text-align: center;border: 1px solid black">{{displayStatus($data->status)}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </body>
</html>
