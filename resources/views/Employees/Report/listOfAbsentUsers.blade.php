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
    <h1>Absent Users</h1>
    <h2>As of {{ $date }}</h2>
    <table>
        <tr>
            <th>Employee Number</th>
            <th>Employee Name</th>
            <th>Email</th>
        </tr>
    </table>
    <table class="gfg">
        @if(count($credit) > 0)
            @foreach($credit as $audit)
                <tr>
                    <td class="geeks">{{ !empty($audit['employee_number']) ? $audit['employee_number'] : '' }}</td>
                    <td>{{ !empty($audit['name']) && !empty($audit['surname']) ? $audit['name'].' '.$audit['surname'] : '' }}</td>
                    <td>{{ !empty($audit['email']) ? $audit['email'] : '' }}</td>
                </tr>
            @endforeach
        @endif
    </table>
</center>
</body>
</html>