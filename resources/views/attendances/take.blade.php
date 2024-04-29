@extends('layouts.app')

@section('content')
<script>
    function dateSubmit () {
        console.log("I'm running ");
    }
</script>
<div class="container">
    <div class="row justify-content-start">
        @include('layouts.left-menu')
        <div class="col-xs-11 col-sm-11 col-md-11 col-lg-10 col-xl-10 col-xxl-10">
            <div class="row pt-2">
                <div class="col ps-4">
                    <h1 class="display-6 mb-3">
                        <i class="bi bi-calendar2-week"></i> Take Attendance
                    </h1>

                    @include('session-messages')

                    <h3><i class="bi bi-compass"></i>
                        Class #{{ request()->query('class_name') }}, 
                        @if ($academic_setting->attendance_type == 'course')
                            Course: {{ request()->query('course_name') }}
                        @else
                            Section #{{ request()->query('section_name') }}
                        @endif
                    </h3>

                    <div class="row mt-4">
                        <div class="col-10 bg-white border p-3 shadow-sm">
                            <form action="{{ route('attendances.returnRoutine') }}" method="GET" id='formId'>
                                @csrf
                                <div class="mt-4">
                                    <label for="attendance_datetime">Enter Date:</label>
                                    <input type="date" id="attendance_datetime" name="attendance_datetime" value="{{ request()->query('attendance_datetime') }}" >
                                    <button type="submit">Submit</button>
                                </div>
                            </form>

                            <!-- Dropdown menu for selecting classes -->
                            <div class="mt-4">
                                <label for="class_id">Select Class:</label>
                                <select class="form-control" id="class_id" name="class_id">
                                    <option value="">Select Class</option>
                                    @foreach ($availableClasses as $classId)
                                        <option value="{{ $classId }}">{{ $classId }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Form for taking attendance -->
                            <form action="{{ route('attendances.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="session_id" value="{{ $current_school_session_id }}">
                                <input type="hidden" name="class_id" value="{{ request()->query('class_id') }}">
                                @if ($academic_setting->attendance_type == 'course')
                                    <input type="hidden" name="course_id" value="{{ request()->query('course_id') }}">
                                    <input type="hidden" name="section_id" value="0">
                                @else
                                    <input type="hidden" name="course_id" value="0">
                                    <input type="hidden" name="section_id" value="{{ request()->query('section_id') }}">
                                @endif
                                @foreach ($student_list as $student)
                                    <input type="hidden" name="student_ids[]" value="{{ $student->student_id }}">
                                @endforeach
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col"># ID Card Number</th>
                                            <th scope="col">Student Name</th>
                                            <th scope="col">Present</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($student_list as $student)
                                            <tr>
                                                <th scope="row">{{ $student->id_card_number }}</th>
                                                <td>{{ $student->student->first_name }} {{ $student->student->last_name }}</td>
                                                <td>
                                                    <input class="form-check-input" type="checkbox" name="status[{{ $student->student_id }}]" checked>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @if (count($student_list) > 0)
                                    <div class="mb-4">
                                        <button type="submit" class="btn btn-outline-primary"><i class="bi bi-check2"></i> Submit</button>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </div>
</div>

@endsection
