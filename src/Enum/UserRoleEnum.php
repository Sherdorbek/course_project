<?php

namespace App\Enum;

enum UserRoleEnum : String
{
    case Admin = 'admin';
    case Candidate = 'candidate';
    case Recruiter = 'recruiter';
}