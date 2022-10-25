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
            width: 120px;
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
    <h1>List of Assets for {{ $name }} </h1>
    <h2>As of {{ $date }}</h2>
    <table width="100%;border:0;">
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Serial Number </th>
            <th>Make Number </th>
            <th>Model Number </th>
            <th>Asset Tag Number</th>
        </tr>
    </table>
    <table class="gfg">
        @if(count($assets) > 0)
            @foreach($assets as $audit)
                <tr>
                    <td class="geeks">{{ !empty($audit->AssetTransfers->name) ? $audit->AssetTransfers->name : '' }}</td>
                    <td>{{ !empty($audit->AssetTransfers->description) ? $audit->AssetTransfers->description : '' }}</td>
                    <td>{{ !empty($audit->AssetTransfers->serial_number) ? $audit->AssetTransfers->serial_number : '' }}</td>
                    <td>{{ !empty($audit->AssetTransfers->make_number) ? $audit->AssetTransfers->make_number : '' }}</td>
                    <td>{{ !empty($audit->AssetTransfers->model_number) ? $audit->AssetTransfers->model_number : '' }}</td>
                    <td>{{ !empty($audit->AssetTransfers->asset_tag) ? $audit->AssetTransfers->asset_tag : '' }}</td>
                </tr>
            @endforeach
        @endif
    </table>



    <h1>List of Licences for {{ $name }} </h1>
    <h2>As of {{ $date }}</h2>
    <table width="100%;border:0;">
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Serial Number </th>
            <th>Purchase Date </th>
            <th>Purchase Cost </th>
            <th>Expiration Date</th>
        </tr>
    </table>
    <table class="gfg">
        @if(count($licences) > 0)
            @foreach($licences as $audit)
                <tr>
                    <td class="geeks">{{ !empty($audit->Licenses->name) ? $audit->Licenses->name : '' }}</td>
                    <td>{{ !empty($audit->Licenses->details) ? $audit->Licenses->details : '' }}</td>
                    <td>{{ !empty($audit->Licenses->serial) ? $audit->Licenses->serial : '' }}</td>
                    <td>{{ !empty($audit->Licenses->purchase_date) ? $audit->Licenses->purchase_date : '' }}</td>
                    <td>{{ !empty($audit->Licenses->purchase_cost) ? $audit->Licenses->purchase_cost : '' }}</td>
                    <td>{{ !empty($audit->Licenses->expiration_date) ? $audit->Licenses->expiration_date : '' }}</td>
                </tr>
            @endforeach
        @endif
    </table>
</center>
</body>
</html>
