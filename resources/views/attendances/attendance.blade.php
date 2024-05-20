@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/fullcalendar5.9.0.min.css') }}">
<script src="{{ asset('js/fullcalendar5.9.0.main.min.js') }}"></script>
<div class="container">
    <div class="row justify-content-start">
        @include('layouts.left-menu')
        <div class="col-xs-11 col-sm-11 col-md-11 col-lg-10 col-xl-10 col-xxl-10">
            <div class="row pt-2">
                <div class="col ps-4">
                    <h1 class="display-6 mb-3">
                        <i class="bi bi-calendar2-week"></i> View Attendance
                    </h1>

                    <h5><i class="bi bi-person"></i> Student Name: {{$student->first_name}} {{$student->last_name}}</h5>
                    <!-- <div class="row mt-3">
                        <div class="col bg-white p-3 border shadow-sm">
                            <div id="attendanceCalendar"></div>
                        </div>
                    </div> -->
                    <div class="row mt-4">
                        <div class="col bg-white border shadow-sm p-3">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th scope="col">Subject Name</th>
                                        <th scope="col">Number Of Lectures</th>
                                        <th scope="col">Present</th>
                                        <th scope="col">Absent</th>
                                        <th scope="col">Percentage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $subjects = $attendances->groupBy(function($attendance) {
                                            return ($attendance->section == null) ? $attendance->courses->course_name : $attendance->section->section_name;
                                        });
                                    @endphp

                                    @foreach ($subjects as $subjectName => $attendanceGroup)
                                        @php
                                            $totalLectures = $attendanceGroup->count();
                                            $presentCount = $attendanceGroup->where('status', 'on')->count();
                                            $absentCount = $totalLectures - $presentCount;
                                            $percentage = ($totalLectures > 0) ? round(($presentCount / $totalLectures) * 100, 2) : 0;
                                        @endphp
                                        <tr>
                                            <td>{{ $subjectName }}</td>
                                            <td>{{ $totalLectures }}</td>
                                            <td>{{ $presentCount }}</td>
                                            <td>{{ $absentCount }}</td>
                                            <td>{{ $percentage }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </div>
</div>
@php
$events = [];
if (count($attendances) > 0) {
    foreach ($attendances as $attendance) {
        $events[] = [
            'title'=> $attendance->status == "on" ? "Present" : "Absent",
            'start' => $attendance->created_at,
            'color' => $attendance->status == "on" ? 'green' : 'red'
        ];
    }
}
@endphp
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('attendanceCalendar');
    var attEvents = @json($events);
                            
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 350,
        events: attEvents,
    });
    calendar.render();
});
</script>
@endsection
