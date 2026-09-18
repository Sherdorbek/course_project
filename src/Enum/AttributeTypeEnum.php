<?php

namespace App\Enum;

enum AttributeTypeEnum : String
{
    case StringType = 'string';
    case TextType = 'text';
    case ImageType = 'image';
    case NumericType = 'numeric';
    case DateType = 'date';
    case PeriodType = 'period';
    case BoolType = 'boolean';
    case OneOfMany = 'one_of_many';
}