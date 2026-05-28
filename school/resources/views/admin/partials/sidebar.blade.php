{{-- resources/views/admin/partials/sidebar.blade.php --}}

<aside class="sidebar" id="sidebar">

    <div class="sidebar-top">

        <div class="logo">

            <div class="logo-icon">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>

            <div>
                <h2>THPT Admin</h2>
                <span>Exam Management</span>
            </div>

        </div>

    </div>

    <div class="sidebar-menu">

        <a href="{{ route('dashboard') }}"
           class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-house"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('subjects.index') }}"
           class="{{ request()->routeIs('subjects.*') ? 'active' : '' }}">
            <i class="fa-solid fa-book"></i>
            <span>Môn học</span>
        </a>

        <a href="{{ route('chapters.index') }}"
           class="{{ request()->routeIs('chapters.*') ? 'active' : '' }}">
            <i class="fa-solid fa-layer-group"></i>
            <span>Chuyên đề</span>
        </a>

        <a href="{{ route('questions.index') }}"
           class="{{ request()->routeIs('questions.*') ? 'active' : '' }}">

            <i class="fa-solid fa-circle-question"></i>

            <span>
                Câu hỏi
            </span>

        </a>

        <a href="{{ route('exams.index') }}"
           class="{{ request()->routeIs('exams.*') ? 'active' : '' }}">

            <i class="fa-solid fa-file-lines"></i>

            <span>
                Đề thi
            </span>

        </a>

        <a href="{{ route('students.index') }}"
           class="{{ request()->routeIs('students.*') ? 'active' : '' }}">
            <i class="fa-solid fa-users"></i>
            <span>Học sinh</span>
        </a>

        <a href="{{ route('exam-results.index') }}"
           class="{{ request()->routeIs('exam-results.*') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-column"></i>
            <span>Kết quả thi</span>
        </a>

    </div>

</aside>
