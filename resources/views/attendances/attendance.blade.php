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
                                @foreach ($attendances as $attendance)
                                        @php
                                            $total_attended = \App\Models\Attendance::where('student_id', $attendance->student_id)->where('session_id', $attendance->session_id)->where('course_id' , $attendance->course_id)->count();
                                            $present_count = \App\Models\Attendance::where('student_id', $attendance->student_id)->where('session_id', $attendance->session_id)->where('course_id' , $attendance->course_id)->where('status', 'on')->count();
                                            $absent_count = $total_attended - $present_count;
                                            $percentage = ($total_attended > 0) ? round(($present_count / $total_attended) * 100, 2) : 0;
                                        @endphp
                                        <tr>
                                            <td>{{$attendance->course->course_name}} </td>
                                            <td>{{$total_attended}}</td>
                                            <td>
                                                <!-- @if ($attendance->status == "on")
                                                    <span class="badge bg-success">present_count</span>
                                                @else
                                                    <span class="badge bg-danger">ABSENT</span>
                                                @endif -->
                                                <span class="badge bg-success">{{$present_count}}</span>
                                                
                                            </td>
                                            <td>
                                                <span class="badge bg-danger">{{$absent_count}}</span>
                                            </td>
                                            <td>
                                                {{$percentage}}%
                                            </td>
                                            
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
