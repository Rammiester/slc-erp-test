<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttendanceStoreRequest;
use App\Interfaces\AcademicSettingInterface;
use App\Interfaces\CourseInterface;
use App\Interfaces\SchoolClassInterface;
use App\Interfaces\SchoolSessionInterface;
use App\Interfaces\SectionInterface;
use App\Interfaces\UserInterface;
use App\Models\Attendance;
use App\Models\Routine;
use App\Repositories\AttendanceRepository;
use App\Repositories\CourseRepository;
use App\Traits\SchoolSession;
use Illuminate\Http\Request;
// Make sure this is the correct namespace
class AttendanceController extends Controller
{
    use SchoolSession;
    protected $academicSettingRepository;
    protected $schoolSessionRepository;
    protected $schoolClassRepository;
    protected $sectionRepository;
    protected $userRepository;
    protected $courseRepository;

    public function __construct(
        UserInterface $userRepository,
        AcademicSettingInterface $academicSettingRepository,
        SchoolSessionInterface $schoolSessionRepository,
        SchoolClassInterface $schoolClassRepository,
        SectionInterface $sectionRepository,
        CourseInterface $courseRepository
    ) {
        $this->middleware(['can:view attendances']);

        $this->userRepository = $userRepository;
        $this->academicSettingRepository = $academicSettingRepository;
        $this->schoolSessionRepository = $schoolSessionRepository;
        $this->schoolClassRepository = $schoolClassRepository;
        $this->sectionRepository = $sectionRepository;
        $this->courseRepository = $courseRepository;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return back();
        // $academic_setting = $this->academicSettingRepository->getAcademicSetting();

        // $current_school_session_id = $this->getSchoolCurrentSession();

        // $classes_and_sections = $this->schoolClassRepository->getClassesAndSections($current_school_session_id);
        // $courseRepository = new CourseRepository();
        // $courses = $courseRepository->getAll($current_school_session_id);

        // $data = [
        //     'academic_setting'      => $academic_setting,
        //     'classes_and_sections'  => $classes_and_sections,
        //     'courses'               => $courses,
        // ];

        // return view('attendances.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if ($request->query('class_id') == null) {
            return abort(404);
        }

        try {
            $academic_setting = $this->academicSettingRepository->getAcademicSetting();
            $current_school_session_id = $this->getSchoolCurrentSession();

            $class_id = $request->query('class_id');
            $section_id = $request->query('section_id', 0);
            $course_id = $request->query('course_id');

            $student_list = $this->userRepository->getAllStudents($current_school_session_id, $class_id, $section_id);

            $school_class = $this->schoolClassRepository->findById($class_id);
            $school_section = $this->sectionRepository->findById($section_id);

            $attendanceRepository = new AttendanceRepository();

            if ($academic_setting->attendance_type == 'section') {
                $attendance_count = $attendanceRepository->getSectionAttendance($class_id, $section_id, $current_school_session_id)->count();
            } else {
                $attendance_count = $attendanceRepository->getCourseAttendance($class_id, $course_id, $current_school_session_id)->count();
            }

            // Fetch routines for the selected date
            $routines = Routine::whereDate('start', now()->toDateString())->get();

            // Initialize an empty array to store available classes
            $availableClasses = [];

            // Iterate over the fetched routines to extract class information
            foreach ($routines as $routine) {
                // Assuming each routine has a class_id attribute
                $classId = $routine->class_id;
                $courseId = $routine->course_id;

                // Check if the class ID is not already in the available classes array
                if (!in_array($classId, $availableClasses)) {
                    // Add the class ID to the available classes array
                    $availableClasses[] = $classId;
                }
                // Check if the course ID is not already in the available courses array
                if (!in_array($courseId, $availableCourses)) {
                    // Add the course ID to the available courses array
                    $availableCourses[] = $courseId;
                }
            }

            $data = [
                'current_school_session_id' => $current_school_session_id,
                'academic_setting' => $academic_setting,
                'student_list' => $student_list,
                'school_class' => $school_class,
                'school_section' => $school_section,
                'attendance_count' => $attendance_count,
                'availableClasses' => $availableClasses, // Pass the available classes to the view
            ];

            return view('attendances.take', $data);
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function returnRoutine(Request $request)
    {
        try {
            // Retrieve the selected date from the request

            $selectedDate = $request->input('attendance_datetime');
            // dd($selectedDate);
            $selectedSession = $request->input('session_id');
            $selectedClass = $request->query('class_id');
            $selectedClassName = $request->query('class_name');
            $selectedSection = $request->query('section_name');
            // dd($selectedSection);
            // dd($selectedClass);
            $selectedSection = $request->query('section_id', 0);
            $selectedCourse = $request->query('course_id');
            // $school_class = $this->schoolClassRepository->findById($selectedClass);
            // $school_section = $this->sectionRepository->findById($selectedSection);

            // Convert the selected date to the day of the week (1 for Sunday, 2 for Monday, and so on)
            $dayOfWeek = date('N', strtotime($selectedDate));

            // Fetch routine data based on the day of the week and start time
            $routines = Routine::where('weekday', $dayOfWeek)
                ->where('section_id', $selectedSection)
                ->where('course_id', $selectedCourse)
                ->get();
            // \Log::info('Fetched Routines: ' . json_encode($routines));
            if ($routines === null) {
                // \Log::info("Not class Available for selected Day of Week");
                return response()->json(["No Classes Available"]);
                return back()->withInput()->withErrors(['No Classes Available' => 'No Classes Available']);

            } else {
                // Initialize an empty array to store available classes
                $availableClasses = [];

                // Iterate over the fetched routines to extract class information
                foreach ($routines as $routine) {

                    $startTime = $routine->start;
                    $endTime = $routine->end;
                    $timeRange = $startTime . ' - ' . $endTime;
                    $availableClasses[] = $timeRange;

                }

                // Fetch academic setting data
                $academic_setting = $this->academicSettingRepository->getAcademicSetting();

                // Fetch current school session id
                $current_school_session_id = $this->getSchoolCurrentSession();

                // Fetch student list
                $student_list = $this->userRepository->getAllStudents($current_school_session_id, $selectedClass, $selectedSection);

                \Log::info('Fetched available Classes: ' . json_encode($availableClasses));

                // Return the list of available classes in JSON format
                $data = [
                    'current_school_session_id' => $current_school_session_id,
                    'academic_setting' => $academic_setting,
                    'student_list' => $student_list,
                    'class_name' => $selectedClassName,
                    'section_name' => $selectedSection,
                    // 'attendance_count' => $attendance_count,
                    'availableClasses' => $availableClasses,
                ];

                return view('attendances.take', $data);
                // return $data;
                // return view('attendances.take')->with([
                //     'availableClasses' => $availableClasses,
                //     'academic_setting' => $academic_setting,
                //     'current_school_session_id' => $current_school_session_id,
                //     'student_list' => $student_list,
                //     'class_name' => $school_class,
                //     'section_name' => $school_section,

                // ]);
            }

        } catch (\Exception $e) {
            // Handle any exceptions and return an error response
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\AttendanceStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AttendanceStoreRequest $request)
    {
        try {
            // Retrieve datetime value from the request
            $attendanceDate = $request->input('attendance_date');
            $class = $request->input('class');
            
            \Log::info('Fetched Time ' . json_encode($class));
            \Log::info('Fetched Date ' . json_encode($attendanceDate));
            // dd($request->input('course_id'));
            // Ensure the attendance_Date_Time value is not null
            if ($attendanceDate && $class) {
                // Iterate over each student's attendance status
                foreach ($request->input('status') as $studentId => $status) {
                    // Create a new Attendance instance
                    $attendance = new Attendance();

                    // Populate attendance data
                    $attendance->session_id = $request->input('session_id');
                    $attendance->class_id = $request->input('class_id');
                    $attendance->course_id = $request->input('course_id');
                    $attendance->section_id = $request->input('section_id');
                    $attendance->student_id = $studentId;

                    // Check if the checkbox is checked
                    if ($status === 'on') {
                        $attendance->status = 'on'; // Set status to "on"
                    } else {
                        $attendance->status = 'off'; // Set status to "off" if checkbox is unchecked
                    }

                    // Concatenate attendance_date and class
                    $attendance->attendance_Date_Time = $attendanceDate . ' ' . $class;

                    // Save the attendance data
                    $attendance->save();
                }

                return back()->with('status', 'Attendance saved successfully!');
            } else {
                // Handle the case where attendance_Date_Time or class is null
                return back()->withInput()->withErrors(['attendance_datetime' => 'Attendance Date Time and Class are required']);
            }
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }



    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        if ($request->query('class_id') == null) {
            return abort(404);
        }

        $current_school_session_id = $this->getSchoolCurrentSession();

        $class_id = $request->query('class_id');
        $section_id = $request->query('section_id');
        $course_id = $request->query('course_id');

        $attendanceRepository = new AttendanceRepository();

        try {
            $academic_setting = $this->academicSettingRepository->getAcademicSetting();
            if ($academic_setting->attendance_type == 'section') {
                $attendances = $attendanceRepository->getSectionAttendance($class_id, $section_id, $current_school_session_id);
                // dd($attendances);
            } else {
                $attendances = $attendanceRepository->getCourseAttendance($class_id, $course_id, $current_school_session_id);
                // dd($attendances);
            }
            // dd($attendances);


            $data = ['attendances' => $attendances];

            return view('attendances.view', $data);
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function showStudentAttendance($id)
    {
        if (auth()->user()->role == "student" && auth()->user()->id != $id) {
            return abort(404);
        }
        // dd($id);
        $current_school_session_id = $this->getSchoolCurrentSession();
        $attendanceRepository = new AttendanceRepository();
        $attendances = $attendanceRepository->getStudentAttendance($current_school_session_id, $id);
        $student = $this->userRepository->findStudent($id);

        $data = [
            'attendances' => $attendances,
            'student' => $student,
        ];

        return view('attendances.attendance', $data);
    }

}
