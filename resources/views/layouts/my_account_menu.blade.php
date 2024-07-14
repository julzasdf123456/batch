<li class="nav-item">
    <a href="{{ route('users.my-account-index') }}"
       class="nav-link {{ Request::is('users.my-account-index*') ? 'active' : '' }}">
       <i class="fas fa-home nav-icon"></i>
        <p>Dashboard</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('users.my-classes') }}"
       class="nav-link {{ Request::is('users.my-classes*') ? 'active' : '' }}">
       <i class="fas fa-book nav-icon"></i>
        <p>My Classes</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('users.my-advisory') }}"
       class="nav-link {{ Request::is('users.my-advisory*') ? 'active' : '' }}">
       <i class="fas fa-users nav-icon"></i>
        <p>My Advisory</p>
    </a>
</li>