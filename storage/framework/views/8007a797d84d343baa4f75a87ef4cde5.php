<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Mark Attendance - <?php echo e($folder->name); ?></title>

    <meta name="csrf-token"
        content="<?php echo e(csrf_token()); ?>">

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family:
                Arial,
                Helvetica,
                sans-serif;

            background:
                linear-gradient(
                    135deg,
                    #eef2ff,
                    #f8fafc,
                    #e0f2fe
                );

            min-height: 100vh;

            color: #1e293b;
        }

        .container {
            width: 95%;
            max-width: 1250px;

            margin: 30px auto;
        }

        /* HEADER */

        .topbar {
            background: white;

            padding: 22px;

            border-radius: 18px;

            box-shadow:
                0 10px 30px
                rgba(15, 23, 42, 0.08);

            margin-bottom: 20px;
        }

        .topbar-row {
            display: flex;

            justify-content: space-between;

            align-items: center;

            gap: 15px;

            flex-wrap: wrap;
        }

        .title {
            font-size: 27px;

            font-weight: 800;

            color: #0f172a;
        }

        .subtitle {
            color: #64748b;

            margin-top: 5px;

            font-size: 14px;
        }

        .btn {
            border: none;

            padding: 11px 17px;

            border-radius: 10px;

            cursor: pointer;

            font-weight: 700;

            text-decoration: none;

            display: inline-block;
        }

        .btn-back {
            background: #e2e8f0;

            color: #0f172a;
        }

        .btn-primary {
            background: #4f46e5;

            color: white;
        }

        .btn-danger {
            background: #dc2626;

            color: white;
        }

        .btn-success {
            background: #16a34a;

            color: white;
        }

        .btn:hover {
            opacity: 0.9;
        }

        /* ALERT */

        .alert {
            padding: 14px 18px;

            border-radius: 12px;

            margin-bottom: 18px;

            font-weight: 600;
        }

        .alert-success {
            background: #dcfce7;

            color: #166534;

            border: 1px solid #86efac;
        }

        .alert-error {
            background: #fee2e2;

            color: #991b1b;

            border: 1px solid #fca5a5;
        }

        /* CONTROL CARD */

        .control-card {
            background: white;

            padding: 22px;

            border-radius: 18px;

            box-shadow:
                0 10px 30px
                rgba(15, 23, 42, 0.08);

            margin-bottom: 20px;
        }

        .controls {
            display: grid;

            grid-template-columns:
                1fr 1fr;

            gap: 18px;
        }

        .field label {
            display: block;

            font-size: 13px;

            font-weight: 700;

            color: #475569;

            margin-bottom: 7px;
        }

        input[type="date"],
        input[type="text"] {

            width: 100%;

            padding: 13px;

            border: 1px solid #cbd5e1;

            border-radius: 10px;

            font-size: 15px;

            outline: none;
        }

        input:focus {
            border-color: #6366f1;

            box-shadow:
                0 0 0 3px
                rgba(99, 102, 241, 0.12);
        }

        .date-note {
            margin-top: 7px;

            color: #64748b;

            font-size: 12px;
        }

        /* STATUS */

        .status-box {

            display: flex;

            justify-content: space-between;

            align-items: center;

            flex-wrap: wrap;

            gap: 12px;

            margin-top: 18px;

            padding: 15px;

            border-radius: 12px;

            background: #f8fafc;

            border: 1px solid #e2e8f0;
        }

        .unlock-status {
            font-weight: 700;
        }

        .unlocked {
            color: #15803d;
        }

        .locked {
            color: #b45309;
        }

        /* SEARCH */

        .search-card {
            background: white;

            padding: 18px;

            border-radius: 18px;

            box-shadow:
                0 10px 30px
                rgba(15, 23, 42, 0.08);

            margin-bottom: 20px;
        }

        /* TABLE */

        .attendance-card {

            background: white;

            padding: 20px;

            border-radius: 18px;

            box-shadow:
                0 10px 30px
                rgba(15, 23, 42, 0.08);
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;

            border-collapse: collapse;

            min-width: 850px;
        }

        thead th {

            background: #f1f5f9;

            color: #334155;

            padding: 14px;

            text-align: left;

            font-size: 13px;

            border-bottom:
                2px solid #e2e8f0;
        }

        tbody td {

            padding: 14px;

            border-bottom:
                1px solid #e2e8f0;

            vertical-align: middle;
        }

        tbody tr:hover {
            background: #f8fafc;
        }

        .student-name {
            font-weight: 700;

            cursor: pointer;

            user-select: none;
        }

        .student-name:hover {
            color: #4f46e5;
        }

        .roll {
            font-weight: 800;

            color: #334155;
        }

        .mobile {
            color: #475569;
        }

        /* STATUS BUTTONS */

        .status-buttons {

            display: flex;

            gap: 8px;
        }

        .status-btn {

            border: 1px solid #cbd5e1;

            background: white;

            color: #334155;

            padding: 9px 15px;

            border-radius: 9px;

            cursor: pointer;

            font-weight: 700;
        }

        .status-btn.present.active {

            background: #16a34a;

            color: white;

            border-color: #16a34a;
        }

        .status-btn.absent.active {

            background: #dc2626;

            color: white;

            border-color: #dc2626;
        }

        .na {

            color: #94a3b8;

            font-weight: 700;
        }

        .date-added {

            display: block;

            margin-top: 4px;

            font-size: 11px;

            color: #94a3b8;
        }

        /* FOOTER */

        .submit-area {

            margin-top: 20px;

            display: flex;

            justify-content: flex-end;
        }

        .submit-btn {

            background: #4f46e5;

            color: white;

            border: none;

            padding: 14px 30px;

            border-radius: 11px;

            font-size: 16px;

            font-weight: 800;

            cursor: pointer;
        }

        .submit-btn:hover {
            background: #4338ca;
        }

        /* EMPTY */

        .empty {

            text-align: center;

            padding: 45px;

            color: #64748b;
        }

        /* CLICK COUNTER */

        .click-counter {

            position: fixed;

            right: 20px;

            bottom: 20px;

            background: #0f172a;

            color: white;

            padding: 12px 17px;

            border-radius: 12px;

            font-size: 13px;

            font-weight: 700;

            display: none;

            box-shadow:
                0 8px 25px
                rgba(0,0,0,.2);
        }

        .click-counter.show {
            display: block;
        }

        /* UNLOCK PANEL */

        .unlock-panel {

            margin-top: 15px;

            padding: 15px;

            background: #fff7ed;

            border: 1px solid #fed7aa;

            border-radius: 12px;

            display: none;
        }

        .unlock-panel.show {
            display: block;
        }

        .unlock-title {

            font-weight: 800;

            color: #9a3412;

            margin-bottom: 5px;
        }

        .unlock-text {

            font-size: 13px;

            color: #7c2d12;
        }

        /* RESPONSIVE */

        @media(max-width: 750px) {

            .controls {
                grid-template-columns: 1fr;
            }

            .title {
                font-size: 22px;
            }

            .container {
                width: 94%;
            }

            .topbar-row {
                align-items: flex-start;
            }
        }

    </style>

</head>

<body>

<div class="container">

    

    <div class="topbar">

        <div class="topbar-row">

            <div>

                <div class="title">
                    📋 Mark Attendance
                </div>

                <div class="subtitle">
                    Folder:
                    <strong>
                        <?php echo e($folder->name); ?>

                    </strong>
                </div>

            </div>

            <a
                href="<?php echo e(route('admin.folders.show', $folder->id)); ?>"
                class="btn btn-back"
            >
                ← Back to Folder
            </a>

        </div>

    </div>


    

    <?php if(session('success')): ?>

        <div class="alert alert-success">
            ✅ <?php echo e(session('success')); ?>

        </div>

    <?php endif; ?>


    

    <?php if(session('error')): ?>

        <div class="alert alert-error">
            ⚠️ <?php echo e(session('error')); ?>

        </div>

    <?php endif; ?>


    

    <?php if($errors->any()): ?>

        <div class="alert alert-error">

            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <div>
                    ⚠️ <?php echo e($error); ?>

                </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </div>

    <?php endif; ?>


    

    <div class="control-card">

        <form
            method="GET"
            action="<?php echo e(route('admin.attendance.show', $folder->id)); ?>"
            id="dateForm"
        >

            <div class="controls">

                <div class="field">

                    <label>
                        Select Attendance Date
                    </label>

                    <input
                        type="date"
                        name="date"
                        id="attendanceDate"
                        value="<?php echo e($selectedDate); ?>"
                        max="<?php echo e($today); ?>"
                        <?php if($earliestStudentDate): ?>
                            min="<?php echo e($earliestStudentDate); ?>"
                        <?php endif; ?>
                    >

                    <div class="date-note">

                        Today:
                        <strong>
                            <?php echo e(\Carbon\Carbon::parse($today)->format('d-m-Y')); ?>

                        </strong>

                        <?php if($earliestStudentDate): ?>

                            <br>

                            Earliest student added:
                            <strong>
                                <?php echo e(\Carbon\Carbon::parse($earliestStudentDate)->format('d-m-Y')); ?>

                            </strong>

                        <?php endif; ?>

                    </div>

                </div>


                <div class="field">

                    <label>
                        Search Student
                    </label>

                    <input
                        type="text"
                        id="studentSearch"
                        placeholder="Search name, roll, branch, mobile..."
                    >

                </div>

            </div>


            

            <div class="status-box">

                <div>

                    <?php if($pastUnlocked): ?>

                        <div class="unlock-status unlocked">
                            🔓 Past Attendance: UNLOCKED
                        </div>

                    <?php else: ?>

                        <div class="unlock-status locked">
                            🔒 Past Attendance: LOCKED
                        </div>

                    <?php endif; ?>

                </div>


                <div>

                    <?php if($pastUnlocked): ?>

                        <button
                            type="button"
                            class="btn btn-danger"
                            onclick="lockPastAttendance()"
                        >
                            🔒 Lock Past Attendance
                        </button>

                    <?php else: ?>

                        <button
                            type="button"
                            class="btn btn-primary"
                            onclick="startUnlockInfo()"
                        >
                            🔐 Unlock Past Attendance
                        </button>

                    <?php endif; ?>

                </div>

            </div>


            

            <div
                class="unlock-panel"
                id="unlockPanel"
            >

                <div class="unlock-title">
                    🔐 Hidden Past Attendance Unlock
                </div>

                <div class="unlock-text">
                    Student name वर
                    <strong>10 clicks</strong>
                    complete करा.
                    <br>
                    5 clicks नंतर unlock progress दिसेल.
                </div>

            </div>

        </form>

    </div>


    

    <div class="attendance-card">

        <?php if($students->count() == 0): ?>

            <div class="empty">

                <h3>
                    No Students Found
                </h3>

                <p>
                    या folder मध्ये अजून students add केलेले नाहीत.
                </p>

            </div>

        <?php else: ?>

            <form
                method="POST"
                action="<?php echo e(route('admin.attendance.submit', $folder->id)); ?>"
                id="attendanceForm"
            >

                <?php echo csrf_field(); ?>

                <input
                    type="hidden"
                    name="date"
                    value="<?php echo e($selectedDate); ?>"
                >


                <div class="table-wrapper">

                    <table>

                        <thead>

                            <tr>

                                <th>
                                    S.No
                                </th>

                                <th>
                                    Student Name
                                </th>

                                <th>
                                    Roll Number
                                </th>

                                <th>
                                    Branch
                                </th>

                                <th>
                                    Mobile Number
                                </th>

                                <th>
                                    Attendance
                                </th>

                            </tr>

                        </thead>


                        <tbody id="studentTableBody">

                            <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <?php

                                    $isApplicable =
                                        $student->attendance_applicable;

                                    $currentStatus =
                                        $selected_status[$student->id] ?? null;

                                ?>


                                <tr
                                    class="student-row"
                                    data-applicable="<?php echo e($isApplicable ? '1' : '0'); ?>"
                                    data-search="
                                        <?php echo e(strtolower(
                                            $student->name .
                                            ' ' .
                                            $student->roll_number .
                                            ' ' .
                                            $student->branch .
                                            ' ' .
                                            $student->phone
                                        )); ?>

                                    "
                                >

                                    

                                    <td>
                                        <strong>
                                            <?php echo e($index + 1); ?>

                                        </strong>
                                    </td>


                                    

                                    <td>

                                        <div
                                            class="student-name"
                                            data-student-id="<?php echo e($student->id); ?>"
                                            onclick="studentNameClick(this)"
                                        >

                                            <?php echo e($student->name); ?>


                                        </div>


                                        <?php if($student->student_added_date): ?>

                                            <span class="date-added">

                                                Added:
                                                <?php echo e(\Carbon\Carbon::parse(
                                                        $student->student_added_date
                                                    )->format('d-m-Y')); ?>


                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    

                                    <td>

                                        <span class="roll">
                                            <?php echo e($student->roll_number); ?>

                                        </span>

                                    </td>


                                    

                                    <td>
                                        <?php echo e($student->branch ?: '-'); ?>

                                    </td>


                                    

                                    <td>

                                        <span class="mobile">
                                            <?php echo e($student->phone ?: '-'); ?>

                                        </span>

                                    </td>


                                    

                                    <td>

                                        <?php if(!$isApplicable): ?>

                                            <span class="na">
                                                Not Applicable
                                            </span>

                                        <?php else: ?>

                                            <div class="status-buttons">

                                                

                                                <button
                                                    type="button"
                                                    class="status-btn present
                                                        <?php echo e($currentStatus === 'present' ? 'active' : ''); ?>"
                                                    data-student="<?php echo e($student->id); ?>"
                                                    data-status="present"
                                                    onclick="setAttendance(this)"
                                                >
                                                    ✓ Present
                                                </button>


                                                

                                                <button
                                                    type="button"
                                                    class="status-btn absent
                                                        <?php echo e($currentStatus === 'absent' ? 'active' : ''); ?>"
                                                    data-student="<?php echo e($student->id); ?>"
                                                    data-status="absent"
                                                    onclick="setAttendance(this)"
                                                >
                                                    ✕ Absent
                                                </button>


                                                <input
                                                    type="hidden"
                                                    name="status[<?php echo e($student->id); ?>]"
                                                    id="status_<?php echo e($student->id); ?>"
                                                    value="<?php echo e($currentStatus); ?>"
                                                >

                                            </div>

                                        <?php endif; ?>

                                    </td>

                                </tr>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </tbody>

                    </table>

                </div>


                

                <div class="submit-area">

                    <button
                        type="submit"
                        class="submit-btn"
                        id="submitAttendance"
                    >
                        💾 Save Attendance
                    </button>

                </div>

            </form>

        <?php endif; ?>

    </div>

</div>




<div
    class="click-counter"
    id="clickCounter"
>
    Unlock Clicks:
    <span id="clickNumber">0</span>/10
</div>


<script>

    /*
    |--------------------------------------------------------------------------
    | Date Change
    |--------------------------------------------------------------------------
    */

    document
        .getElementById('attendanceDate')
        .addEventListener('change', function () {

            const selectedDate = this.value;

            if (!selectedDate) {
                return;
            }

            const today = "<?php echo e($today); ?>";

            if (selectedDate > today) {

                alert(
                    'Future date attendance is not allowed.'
                );

                this.value = "<?php echo e($selectedDate); ?>";

                return;
            }

            document
                .getElementById('dateForm')
                .submit();

        });


    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

    document
        .getElementById('studentSearch')
        .addEventListener('input', function () {

            const search =
                this.value
                    .toLowerCase()
                    .trim();

            const rows =
                document.querySelectorAll(
                    '.student-row'
                );

            rows.forEach(function (row) {

                const text =
                    row.dataset.search || '';

                if (
                    text.includes(search)
                ) {

                    row.style.display = '';

                } else {

                    row.style.display = 'none';

                }

            });

        });


    /*
    |--------------------------------------------------------------------------
    | Attendance Present / Absent
    |--------------------------------------------------------------------------
    */

    function setAttendance(button) {

        const studentId =
            button.dataset.student;

        const status =
            button.dataset.status;

        const hidden =
            document.getElementById(
                'status_' + studentId
            );

        if (!hidden) {
            return;
        }

        hidden.value = status;


        const row =
            button.closest('.status-buttons');

        row
            .querySelectorAll('.status-btn')
            .forEach(function (btn) {

                btn.classList.remove(
                    'active'
                );

            });

        button.classList.add(
            'active'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Frontend Validation
    |--------------------------------------------------------------------------
    */

    document
        .getElementById('attendanceForm')
        ?.addEventListener(
            'submit',
            function (event) {

                const rows =
                    document.querySelectorAll(
                        '.student-row[data-applicable="1"]'
                    );

                let missing = [];

                rows.forEach(function (row) {

                    const hidden =
                        row.querySelector(
                            'input[type="hidden"]'
                        );

                    if (
                        hidden &&
                        (
                            hidden.value !== 'present' &&
                            hidden.value !== 'absent'
                        )
                    ) {

                        const name =
                            row.querySelector(
                                '.student-name'
                            )?.innerText
                            || 'Student';

                        missing.push(name);

                    }

                });


                if (missing.length > 0) {

                    event.preventDefault();

                    alert(
                        'Please mark Present or Absent for every applicable student.'
                    );

                    return false;
                }


                /*
                | Past date check
                */

                const selectedDate =
                    "<?php echo e($selectedDate); ?>";

                const today =
                    "<?php echo e($today); ?>";

                const unlocked =
                    <?php echo e($pastUnlocked ? 'true' : 'false'); ?>;

                if (
                    selectedDate < today &&
                    !unlocked
                ) {

                    event.preventDefault();

                    alert(
                        'Past attendance is locked. Unlock it first.'
                    );

                    return false;
                }

            }
        );


    /*
    |--------------------------------------------------------------------------
    | 10 CLICK UNLOCK
    |--------------------------------------------------------------------------
    */

    let unlockClicks = 0;

    function startUnlockInfo() {

        document
            .getElementById('unlockPanel')
            .classList.add('show');

        document
            .getElementById('clickCounter')
            .classList.add('show');

        unlockClicks = 0;

        document
            .getElementById('clickNumber')
            .innerText = '0';

        alert(
            'Unlock started.\n\nStudent Name वर 10 clicks करा.'
        );

    }


    function studentNameClick(element) {

        /*
        | Only start after unlock information
        */
        const panel =
            document.getElementById(
                'unlockPanel'
            );

        if (
            !panel.classList.contains('show')
        ) {
            return;
        }


        /*
        | If already unlocked
        */
        <?php if($pastUnlocked): ?>

            return;

        <?php endif; ?>


        unlockClicks++;

        document
            .getElementById('clickNumber')
            .innerText =
            unlockClicks;


        /*
        |--------------------------------------------------------------------------
        | 5 clicks
        |--------------------------------------------------------------------------
        */

        if (unlockClicks === 5) {

            panel.innerHTML = `
                <div class="unlock-title">
                    🔐 Unlock Progress
                </div>

                <div class="unlock-text">
                    5 clicks completed.
                    <br>
                    अजून 5 clicks करा.
                </div>
            `;

        }


        /*
        |--------------------------------------------------------------------------
        | 10 clicks
        |--------------------------------------------------------------------------
        */

        if (unlockClicks >= 10) {

            unlockClicks = 10;

            document
                .getElementById('clickNumber')
                .innerText = '10';


            unlockPastAttendance();

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Send unlock request
    |--------------------------------------------------------------------------
    */

    function unlockPastAttendance() {

        const form =
            document.createElement('form');

        form.method = 'POST';

        form.action =
            "<?php echo e(route(
                'admin.attendance.unlock',
                $folder->id
            )); ?>";


        const csrf =
            document.createElement('input');

        csrf.type = 'hidden';

        csrf.name = '_token';

        csrf.value =
            document
                .querySelector(
                    'meta[name="csrf-token"]'
                )
                .getAttribute('content');


        form.appendChild(csrf);


        const count =
            document.createElement('input');

        count.type = 'hidden';

        count.name = 'click_count';

        count.value = '10';


        form.appendChild(count);

        document.body.appendChild(form);

        form.submit();

    }


    /*
    |--------------------------------------------------------------------------
    | Lock Past Attendance
    |--------------------------------------------------------------------------
    */

    function lockPastAttendance() {

        if (
            !confirm(
                'Past attendance पुन्हा lock करायची आहे का?'
            )
        ) {
            return;
        }


        const form =
            document.createElement('form');

        form.method = 'POST';

        form.action =
            "<?php echo e(route(
                'admin.attendance.lock',
                $folder->id
            )); ?>";


        const csrf =
            document.createElement('input');

        csrf.type = 'hidden';

        csrf.name = '_token';

        csrf.value =
            document
                .querySelector(
                    'meta[name="csrf-token"]'
                )
                .getAttribute('content');


        form.appendChild(csrf);

        document.body.appendChild(form);

        form.submit();

    }

</script>

</body>

</html><?php /**PATH C:\laragon\www\Attandance_Fremwork_2026\resources\Views/admin/folders/attendance.blade.php ENDPATH**/ ?>