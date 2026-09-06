<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <title>
        Attendance Report - {{ $folder->name }}
    </title>

    <style>

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        h1 {
            text-align: center;
            margin-bottom: 5px;
        }

        .subtitle {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #444;
            padding: 6px;
            text-align: left;
        }

        th {
            background: #eeeeee;
        }

        .center {
            text-align: center;
        }

    </style>

</head>


<body>

<h1>
    Attendance Report
</h1>

<div class="subtitle">

    <strong>
        Folder:
    </strong>

    {{ $folder->name }}

    <br>

    <strong>
        Report Type:
    </strong>

    {{ ucfirst($type) }}

    <br>

    @if($type === 'daily')

        Date:
        {{ \Carbon\Carbon::parse($date)->format('d M Y') }}

    @elseif($type === 'monthly')

        Month:
        {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}

    @else

        From:
        {{ \Carbon\Carbon::parse($start)->format('d M Y') }}

        &nbsp; To: &nbsp;

        {{ \Carbon\Carbon::parse($end)->format('d M Y') }}

    @endif

</div>


<table>

    <thead>

        <tr>

            <th>
                S.No
            </th>

            <th>
                Name
            </th>

            <th>
                Roll
            </th>

            <th>
                Branch
            </th>

            <th>
                Mobile
            </th>


            @if($type === 'daily')

                <th>
                    Status
                </th>

            @else

                <th>
                    Total
                </th>

                <th>
                    Present
                </th>

                <th>
                    Absent
                </th>

                <th>
                    Not Marked
                </th>

                <th>
                    Percentage
                </th>

            @endif

        </tr>

    </thead>


    <tbody>

    @foreach($studentsData as $data)

        <tr>

            <td>
                {{ $data['serno'] }}
            </td>

            <td>
                {{ $data['name'] }}
            </td>

            <td>
                {{ $data['roll'] }}
            </td>

            <td>
                {{ $data['branch'] }}
            </td>

            <td>
                {{ $data['phone'] }}
            </td>


            @if($type === 'daily')

                <td>
                    {{ $data['daily_status'] }}
                </td>

            @else

                <td class="center">
                    {{ $data['total_days'] }}
                </td>

                <td class="center">
                    {{ $data['present'] }}
                </td>

                <td class="center">
                    {{ $data['absent'] }}
                </td>

                <td class="center">
                    {{ $data['not_marked'] }}
                </td>

                <td class="center">
                    {{ $data['percentage'] }}%
                </td>

            @endif

        </tr>

    @endforeach

    </tbody>

</table>


</body>

</html>