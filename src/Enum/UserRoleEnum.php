<?php

namespace App\Enum;

enum UserRoleEnum: string
{
    case Admin = 'ROLE_ADMIN';
    case Candidate = 'ROLE_CANDIDATE';
    case Recruiter = 'ROLE_RECRUITER';
}
