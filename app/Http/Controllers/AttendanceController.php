<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Interfaces\UserInterface;
use App\Interfaces\SchoolClassInterface;
use App\Interfaces\SchoolSessionInterface;
use App\Interfaces\AcademicSettingInterface;
use App\Http\Requests\AttendanceStoreRequest;
use App\Interfaces\SectionInterface;
use App\Repositories\AttendanceRepository;
use App\Repositories\CourseRepository;
use App\Traits\SchoolSession;
use Carbon\Carbon;
class AttendanceController extends Controller
{
    use SchoolSession;
    protected $academicSettingRepository;
    protected $schoolSessionRepository;
    protected $schoolClassRepository;
    protected $sectionRepository;
    protected $userRepository;

    public function __construct(
        UserInterface $userRepository,
        AcademicSettingInterface $academicSettingRepository,
        SchoolSessionInterface $schoolSessionRepository,
        SchoolClassInterface $schoolClassRepository,
        SectionInterface $sectionRepository
    ) {
        $this->middleware(['can:view attendances']);

        $this->userRepository = $userRepository;
        $this->academicSettingRepository = $academicSettingRepository;
        $this->schoolSessionRepository = $schoolSessionRepository;
        $this->schoolClassRepository = $schoolClassRepository;
        $this->sectionRepository = $sectionRepository;
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
        if($request->query('class_id') == null){
            return abort(404);
        }
        // date_default_timezone_set('Asia/Kolkata');
        
        // $timestamp = time();
        // $date_time = date("d-m-Y (D) H:i:s", $timestamp);
        // echo "Current date and local time on this server is $date_time";
        try{
            $academic_setting = $this->academicSettingRepository->getAcademicSetting();
            $current_school_session_id = $this->getSchoolCurrentSession();

            $class_id = $request->query('class_id');
            $section_id = $request->query('section_id', 0);
            $course_id = $request->query('course_id');

            $student_list = $this->userRepository->getAllStudents($current_school_session_id, $class_id, $section_id);

            $school_class = $this->schoolClassRepository->findById($class_id);
            $school_section = $this->sectionRepository->findById($section_id);

            $attendanceRepository = new AttendanceRepository();

            if($academic_setting->attendance_type == 'section') {
                $attendance_count = $attendanceRepository->getSectionAttendance($class_id, $section_id, $current_school_session_id)->count();
            } else {
                $attendance_count = $attendanceRepository->getCourseAttendance($class_id, $course_id, $current_school_session_id)->count();
            }

            $data = [
                'current_school_session_id' => $current_school_session_id,
                'academic_setting'  => $academic_setting,
                'student_list'      => $student_list,
                'school_class'      => $school_class,
                'school_section'    => $school_section,
                'attendance_count'  => $attendance_count,
            ];

            return view('attendances.take', $data);
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
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
            $attendanceDateTime = $request->input('attendance_datetime');
    
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
                $attendance->status = $status;
                // $attendance->attendance = $request->input('attendance_datetime');
                
                // Store the datetime value from the form
                $attendance->attendance_Date_Time = $attendanceDateTime;
                // dd($request->all());
                // Save the attendance data
                $attendance->save();
            }
    
            return back()->with('status', 'Attendance saved successfully!');
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
        if($request->query('class_id') == null){
            return abort(404);
        }

        $current_school_session_id = $this->getSchoolCurrentSession();

        $class_id = $request->query('class_id');
        $section_id = $request->query('section_id');
        $course_id = $request->query('course_id');

        $attendanceRepository = new AttendanceRepository();

        try {
            $academic_setting = $this->academicSettingRepository->getAcademicSetting();
            if($academic_setting->attendance_type == 'section') {
                $attendances = $attendanceRepository->getSectionAttendance($class_id, $section_id, $current_school_session_id);
            } else {
                $attendances = $attendanceRepository->getCourseAttendance($class_id, $course_id, $current_school_session_id);
            }
            $data = ['attendances' => $attendances];
            
            return view('attendances.view', $data);
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function showStudentAttendance($id) {
        if(auth()->user()->role == "student" && auth()->user()->id != $id) {
            return abort(404);
        }
        $current_school_session_id = $this->getSchoolCurrentSession();

        $attendanceRepository = new AttendanceRepository();
        $attendances = $attendanceRepository->getStudentAttendance($current_school_session_id, $id);
        $student = $this->userRepository->findStudent($id);

        $data = [
            'attendances'   => $attendances,
            'student'       => $student,
        ];

        return view('attendances.attendance', $data);
    }
}
