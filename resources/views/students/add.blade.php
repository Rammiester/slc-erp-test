@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-start">
        @include('layouts.left-menu')
        <div class="col-xs-11 col-sm-11 col-md-11 col-lg-10 col-xl-10 col-xxl-10">
            <div class="row pt-2">
                <div class="col ps-4">
                    <h1 class="display-6 mb-3">
                        <i class="bi bi-person-lines-fill"></i> Add Student
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Add Student</li>
                        </ol>
                    </nav>

                    @include('session-messages') 

                    <p class="text-primary">
                        <small><i class="bi bi-exclamation-diamond-fill me-2"></i> Remember to create related "Class" and "Section" before adding student</small>
                    </p>
                    <div class="mb-4">
                        <form class="row g-3" action="{{route('school.student.create')}}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label for="inputFirstName" class="form-label">First Name<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                    <input type="text" class="form-control" id="inputFirstName" name="first_name" placeholder="First Name" required value="{{old('first_name')}}">
                                </div>
                                <div class="col-md-3">
                                    <label for="inputLastName" class="form-label">Last Name<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                    <input type="text" class="form-control" id="inputLastName" name="last_name" placeholder="Last Name" required value="{{old('last_name')}}">
                                </div>
                                <div class="col-md-6">
                                    <label for="inputEmail4" class="form-label">Email<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                    <input type="email" class="form-control" id="inputEmail4" name="email" required value="{{old('email')}}">
                                </div>
                                <div class="col-md-6">
                                    <label for="inputPassword4" class="form-label">Password<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                    <input type="password" class="form-control" id="inputPassword4" name="password" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="formFile" class="form-label">Photo</label>
                                    <input class="form-control" type="file" id="formFile" onchange="previewFile()">
                                    <div id="previewPhoto"></div>
                                    <input type="hidden" id="photoHiddenInput" name="photo" value="">
                                </div>
                                <div class="col-md-3">
                                    <label for="inputBirthday" class="form-label">Birthday<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                    <input type="date" class="form-control" id="inputBirthday" name="birthday" placeholder="Birthday" required value="{{old('birthday')}}">
                                </div>
                                <div class="col-3-md">
                                    <label for="inputAddress" class="form-label">Address<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                    <input type="text" class="form-control" id="inputAddress" name="address" placeholder="634 Main St" required value="{{old('address')}}">
                                </div>
                                <div class="col-3-md">
                                    <label for="inputAddress2" class="form-label">Address 2</label>
                                    <input type="text" class="form-control" id="inputAddress2" name="address2" placeholder="Apartment, studio, or floor" value="{{old('address2')}}">
                                </div>
                                <div class="col-md-3">
                                    <label for="inputCity" class="form-label">City<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                    <input type="text" class="form-control" id="inputCity" name="city" placeholder="Dhaka..." required value="{{old('city')}}">
                                </div>
                                <div class="col-md-3">
                                    <label for="inputZip" class="form-label">Zip<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                    <input type="text" class="form-control" id="inputZip" name="zip" required value="{{old('zip')}}">
                                </div>
                                <div class="col-md-3">
                                    <label for="inputState" class="form-label">Gender<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                    <select id="inputState" class="form-select" name="gender" required>
                                        <option value="Male" {{old('gender') == 'male' ? 'selected' : ''}}>Male</option>
                                        <option value="Female" {{old('gender') == 'female' ? 'selected' : ''}}>Female</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="inputNationality" class="form-label">Nationality<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                    <input type="text" class="form-control" id="inputNationality" name="nationality" placeholder="e.g. Bangladeshi, German, ..." required value="{{old('nationality')}}">
                                </div>
                                <div class="col-md-4">
                                    <label for="inputAdmissionCriteria" class="form-label">Admission Criteria<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                    <input type="text" class="form-control" id="inputAdmissionCriteria" name="admission_criteria" placeholder="e.g. UR" required value="{{old('admission_criteria')}}">
                                </div>
                                <div class="col-md-4">
                                    <label for="inputRollNo" class="form-label">Roll No<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                    <input type="text" class="form-control" id="inputRollNo" name="board_reg_no" placeholder="12254" required value="{{old('board_reg_no')}}">>    
                                </div>
                                <div class="col-md-4">
                                    <label for="inputPhone" class="form-label">Phone<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                    <input type="text" class="form-control" id="inputPhone" name="phone" placeholder="+880 01......" required value="{{old('phone')}}">
                                </div>
                                <div class="col-5-md">
                                    <label for="inputExamRollNo" class="form-label">Exam Roll No.<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                    <input type="text" class="form-control" id="inputExamRollNo" name="exam_roll_no" required value="{{old('exam_roll_no')}}">
                                </div>
                            </div>
                            <div class="row mt-4 g-3">
                                <h6>Parents' Information</h6>
                                <div class="col-md-3">
                                    <label for="inputFatherName" class="form-label">Father Name<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                    <input type="text" class="form-control" id="inputFatherName" name="father_name" placeholder="Father Name" required value="{{old('father_name')}}">
                                </div>
                                <div class="col-md-3">
                                    <label for="inputFatherPhone" class="form-label">Father's Phone<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                    <input type="text" class="form-control" id="inputFatherPhone" name="father_phone" placeholder="+880 01......" required value="{{old('father_phone')}}">
                                </div>
                                <div class="col-md-3">
                                    <label for="inputMotherName" class="form-label">Mother Name<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                    <input type="text" class="form-control" id="inputMotherName" name="mother_name" placeholder="Mother Name" required value="{{old('mother_name')}}">
                                </div>
                                <div class="col-md-3">
                                    <label for="inputMotherPhone" class="form-label">Mother's Phone<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                    <input type="text" class="form-control" id="inputMotherPhone" name="mother_phone" placeholder="+880 01......" required value="{{old('mother_name')}}">
                                </div>
                                <div class="col-4-md">
                                    <label for="inputParentAddress" class="form-label">Address<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                    <input type="text" class="form-control" id="inputParentAddress" name="parent_address" placeholder="634 Main St" required value="{{old('parent_address')}}">
                                </div>
                            </div>
                            <div class="row mt-4 g-3">
                                <h6>Academic Information</h6>
                                <div class="col-md-6">
                                    <label for="inputAssignToClass" class="form-label">Assign to class:<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                    <select onchange="getSections(this);" class="form-select" id="inputAssignToClass" name="class_id" required>
                                        @isset($school_classes)
                                            <option selected disabled>Please select a class</option>
                                            @foreach ($school_classes as $school_class)
                                                <option value="{{$school_class->id}}" >{{$school_class->class_name}}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="inputAssignToSection" class="form-label">Assign to section:<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                    <select class="form-select" id="inputAssignToSection" name="section_id" required>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="inputAssignToSemester" class="form-label">Assign to Semester:<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                    <select class="form-select" id="inputAssignToSemester" name="semester" required>
                                        @isset($semesters)
                                            <option selected disabled>Please select a Semester</option>
                                            @foreach ($semesters as $semester)
                                                <option value="{{$semester->id}}" >{{$semester->semester_name}}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label for="inputFormNo" class="form-label">Form No</label>
                                    <input type="text" class="form-control" id="inputFormNo" name="id_card_number" placeholder="Form No." value="{{old('id_card_number')}}">
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-3">
                                        <label for="inputStateOfDomicile" class="form-label">State of Domicile<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="text" class="form-control" id="inputStateOfDomicile" name="state_of_domicile" placeholder="State of Domicile" required value="{{ old('state_of_domicile') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="inputApplyToMinorityCollegesas" class="form-label">Apply to Minority Colleges as<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="text" class="form-control" id="inputApplyToMinorityCollegesas" name="apply_to_minority_colleges"  required value="{{ old('apply_to_minority_colleges') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="inputKashmiriMigrant" class="form-label">Kashmiri Migrant Category<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="text" class="form-control" id="inputKashmiriMigrant" name="kashmiri_migrant"  required value="{{ old('kashmiri_migrant') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="inputBoardOfEducation" class="form-label">Board of Education<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="text" class="form-control" id="inputBoardOfEducation" name="board_of_education" placeholder="CBSE" required value="{{ old('board_of_education') }}">
                                    </div>
                                    
                                    
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-3">
                                        <label for="inputPersonsWithDisabilities" class="form-label">Persons with Disabilities (PwD) Category<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="text" class="form-control" id="inputPersonsWithDisabilities" name="persons_with_disabilities"  required value="{{ old('persons_with_disabilities') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="inputAnnualFamilyIncome" class="form-label">Annual Family Income only for OBC (2018-19)<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="text" class="form-control" id="inputAnnualFamilyIncome" name="annual_family_income"  required value="{{ old('annual_family_income') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="inputSikkimeseStudents" class="form-label">Sikkimese Students nominated by the Govt. of Sikkim<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="text" class="form-control" id="inputSikkimeseStudents" name="sikkimese_students"  required value="{{ old('sikkimese_students') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="inputAadharCardNumber" class="form-label">Applicant's Aadhar Card Number<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="text" class="form-control" id="inputAadharCardNumber" name="aadhar_card_number" placeholder="1111 1111 1111" required value="{{ old('aadhar_card_number') }}">
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-4-md">
                                        <label for="inputJ&KStudents" class="form-label">Prime Minister's Special Scholarship Scheme for J&K Students<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="text" class="form-control" id="inputJ&KStudents" name="j&k_students"  required value="{{ old('j&k_students') }}">
                                    </div>
                                </div>
                                

                                <input type="hidden" name="session_id" value="{{$current_school_session_id}}">
                                <input type="hidden" name="id_card_number" value = "100">
                            </div>
                            <div class="row mt-4">
                                <div class="col-12-md">
                                    <button type="submit" class="btn btn-sm btn-outline-primary"><i class="bi bi-person-plus"></i> Add</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </div>
</div>
<script>
    function getSections(obj) {
        var class_id = obj.options[obj.selectedIndex].value;

        var url = "{{route('get.sections.courses.by.classId')}}?class_id=" + class_id 

        fetch(url)
        .then((resp) => resp.json())
        .then(function(data) {
            var sectionSelect = document.getElementById('inputAssignToSection');
            sectionSelect.options.length = 0;
            data.sections.unshift({'id': 0,'section_name': 'Please select a section'})
            data.sections.forEach(function(section, key) {
                sectionSelect[key] = new Option(section.section_name, section.id);
            });
        })
        .catch(function(error) {
            console.log(error);
        });
    }
</script>
@include('components.photos.photo-input')
@endsection
