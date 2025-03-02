@extends('layouts.admin')

@section('content')
    <div class="lh-main-content">
        <div class="container-fluid">
            <div class="lh-page-title d-flex justify-content-between align-items-center mb-3">
                <div class="lh-breadcrumb">
                    <div class="container">
                        <h2>Chấm công</h2>

                        <form action="{{ route('admin.staff_attendances.check-in') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">Check-in</button>
                        </form>

                        <form action="{{ route('admin.staff_attendances.check-out') }}" method="POST" class="mt-2">
                            @csrf
                            <button type="submit" class="btn btn-danger">Check-out</button>
                        </form>

                        <h3 class="mt-4">Lịch sử chấm công</h3>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Ngày</th>
                                    <th>Check-in</th>
                                    <th>Check-out</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($attendances as $attendance)
                                    <tr>
                                        <td>{{ $attendance->date }}</td>
                                        <td>{{ $attendance->check_in }}</td>
                                        <td>{{ $attendance->check_out ?? 'Chưa check-out' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
