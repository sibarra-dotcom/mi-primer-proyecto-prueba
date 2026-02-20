<?php

if (!function_exists('hasRole')) {
  function hasRole($role)
  {
    $userRole = session()->get('userRole');
    return $userRole === $role;
  }
}

if (!function_exists('hasAnyRole')) {
  function hasAnyRole(array $roles)
  {
    $userRole = session()->get('userRole');
    return in_array($userRole, $roles);
  }
}
