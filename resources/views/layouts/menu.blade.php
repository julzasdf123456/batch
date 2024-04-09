{{-- ENROLLMENT SIDE --}}
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-sign-in-alt"></i>
        <p>
            Enrollment
            <i class="right fas fa-caret-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">

        <li class="nav-item">
            <a href="{{ route('students.new-student') }}"
               class="nav-link {{ Request::is('students.new-student*') ? 'active' : '' }}">
               <i class="fas fa-folder-plus nav-icon"></i>
                <p>New Student</p>
            </a>
        </li>
        
        <li class="nav-item">
            <a href="{{ route('classes.existing-student') }}"
               class="nav-link {{ Request::is('classes.existing-student*') ? 'active' : '' }}">
               <i class="fas fa-folder-open nav-icon"></i>
                <p>Existing Student</p>
            </a>
        </li>
    </ul>
</li>

{{-- CASHIERING SIDE --}}
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-coins"></i>
        <p>
            Cashering
            <i class="right fas fa-caret-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">

        <li class="nav-item">
            <a href="{{ route('transactions.enrollment') }}"
               class="nav-link {{ Request::is('transactions.enrollment*') ? 'active' : '' }}">
               <i class="fas fa-sign-in-alt nav-icon"></i>
                <p>Enrollment</p>
            </a>
        </li>
        
        <li class="nav-item">
            <a href="{{ route('classes.existing-student') }}"
               class="nav-link {{ Request::is('classes.existing-student*') ? 'active' : '' }}">
               <i class="fas fa-file-invoice-dollar nav-icon"></i>
                <p>School Tuitions</p>
            </a>
        </li>
    </ul>
</li>

{{-- @canany('god permission') --}}
{{-- SETTINGS SIDE --}}
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-cogs"></i>
        <p>
            Management
            <i class="right fas fa-caret-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('teachers.index') }}" class="nav-link {{ Request::is('teachers*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-user-circle"></i>
                <p>Teachers</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('classesRepos.index') }}" class="nav-link {{ Request::is('classesRepos*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-puzzle-piece"></i>
                <p>Classes & Sections</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('schoolYears.index') }}" class="nav-link {{ Request::is('schoolYears*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-calendar-check"></i>
                <p>School Years</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('subjects.index') }}" class="nav-link {{ Request::is('subjects*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-book"></i>
                <p>Subjects</p>
            </a>
        </li>

        <div class="divider"></div>

        <li class="nav-item">
            <a href="{{ route('towns.index') }}"
               class="nav-link {{ Request::is('towns*') ? 'active' : '' }}">
               <i class="fas fa-map-marker-alt nav-icon"></i>
                <p>Towns</p>
            </a>
        </li>
        
        <li class="nav-item">
            <a href="{{ route('barangays.index') }}"
               class="nav-link {{ Request::is('barangays*') ? 'active' : '' }}">
               <i class="fas fa-map-marker-alt nav-icon"></i>
                <p>Barangays</p>
            </a>
        </li>
    </ul>
</li>
{{-- @endcanany --}}

{{-- @canany('god permission') --}}
{{-- ADMIN SIDE --}}
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-shield-alt"></i>
        <p>
            Administrative
            <i class="right fas fa-caret-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('users.index') }}" class="nav-link {{ Request::is('users*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-users"></i>
                <p>Users</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('roles.index') }}" class="nav-link {{ Request::is('roles*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-lock"></i>
                <p>Roles</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('permissions.index') }}" class="nav-link {{ Request::is('permissions*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-key"></i>
                <p>Permissions</p>
            </a>
        </li>
    </ul>
</li>
{{-- @endcanany --}}
