<?php

namespace App\Enum;

enum AdministratorRoleEnum: string
{
    case EDUCATIONAL_DIRECTOR = 'educational_director';
    case CORPORATE_RELATIONSHIP_MANAGER = 'corporate_relationship_manager';
    case PROMOTION_OFFICER = 'promotion_officer';
    case SUPER_ADMINISTRATOR = 'super_administrator';
}
