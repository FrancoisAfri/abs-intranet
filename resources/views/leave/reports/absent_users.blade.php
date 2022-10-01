<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            border-collapse: collapse;
        }

        th {
            background-color: blue;
            Color: white;
        }

        th, td {
            width: 180px;
            text-align: center;
            border: 1px solid black;
            padding: 1px

        }

        .geeks {
            border-right: hidden;
        }

        .gfg {
            border-collapse: separate;
            border-spacing: 0 15px;
        }

        h1 {
            color: black;
        }
    </style>
</head>
<body>
<center>
    <h1>Leave Balances</h1>
{{--    <h2>As of {{ $date }}</h2>--}}
    <table>
        <tr>
            <th>Employee Number</th>
            <th>Employee Name</th>
            <th>Leave Type</th>
            <th>Balance</th>
        </tr>
    </table>
    <table class="gfg">
        @if(count($AbsentUsersColl) > 0)
            @foreach($AbsentUsersColl as $audit)
                <tr>
{{--                    <td class="geeks">{{ !empty($audit->employee_number) ? $audit->employee_number : '' }}</td>--}}
{{--                    <td>{{ !empty($audit->first_name) && !empty($audit->surname) ? $audit->first_name.' '.$audit->surname : '' }}</td>--}}
{{--                    <td>{{ !empty($audit->leaveType) ? $audit->leaveType : '' }}</td>--}}
{{--                    <td>{{ !empty($audit->Balance) ? number_format($audit->Balance/8, 2) : 0 }}--}}
{{--                        days(s)--}}
{{--                    </td>--}}
                </tr>
            @endforeach
        @endif
    </table>
</center>
</body>
</html>