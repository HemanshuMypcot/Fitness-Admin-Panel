<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Booked Course Data</title>
</head>
<body>
    <table>
        <thead>
        <tr>
            <th colspan="9" style="text-align: center;width: 100%;border: 1px solid black; background-color: #bdd7ee"><b>{{ \Carbon\Carbon::parse($startDate)->format('d-m-Y') }} / {{ \Carbon\Carbon::parse($endDate)->format('d-m-Y') }}</b></th>
        </tr>
        <tr>
            <th style="text-align: center;width: 100%;border: 1px solid black; background-color: #bdd7ee"><b>SR.No.</b></th>
            <th style="text-align: center;width: 250%;border: 1px solid black; background-color: #bdd7ee"><b>User Name</b></th>
            <th style="text-align: center;width: 300%;border: 1px solid black; background-color: #bdd7ee"><b>Phone</b></th>
            <th style="text-align: center;width: 250%;border: 1px solid black; background-color: #bdd7ee"><b>Course Name</b></th>
            <th style="text-align: center;width: 250%;border: 1px solid black; background-color: #bdd7ee"><b>Instructor Name</b></th>
            <th style="text-align: center;width: 250%;border: 1px solid black; background-color: #bdd7ee"><b>Start Date</b></th>
            <th style="text-align: center;width: 250%;border: 1px solid black; background-color: #bdd7ee"><b>End Date</b></th>
            <th style="text-align: center;width: 250%;border: 1px solid black; background-color: #bdd7ee"><b>Amount</b></th>
            <th style="text-align: center;width: 250%;border: 1px solid black; background-color: #bdd7ee"><b>Payment Status</b></th>
        </tr>
        </thead>
        <tbody>
        @php
        $srNo = 1;
        @endphp
        @foreach($courseData as $key => $data)
            <tr>
                <td style="text-align: center;border: 1px solid black">{{ $srNo++ }}</td>
                <td style="text-align: center;border: 1px solid black">{{$data->user->name ?? ''}}</td>
                <td style="text-align: center;border: 1px solid black">{{$data->user->phone ?? ''}}</td>
                <td style="text-align: center;border: 1px solid black">{{$data['details']['course_name'] ?? ''}}</td>
                <td style="text-align: center;border: 1px solid black">{{$data['details']['instructor_name'] ?? ''}}</td>
                <td style="text-align: center;border: 1px solid black">{{\Carbon\Carbon::parse($data['details']['start_date'])->format('d-m-Y') ?? ''}}</td>
                <td style="text-align: center;border: 1px solid black">{{\Carbon\Carbon::parse($data['details']['end_date'])->format('d-m-Y') ?? ''}}</td>
                <td style="text-align: center;border: 1px solid black">{{$data['details']['total'] ?? ''}}</td>
                <td style="text-align: center;border: 1px solid black">{{ucfirst($data->payment_status) ?? ''}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </body>
</html>
