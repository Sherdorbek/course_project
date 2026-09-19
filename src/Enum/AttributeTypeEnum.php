<?php

namespace App\Enum;

enum AttributeTypeEnum: string
{
    case StringType = 'string';
    case TextType = 'text';
    case ImageType = 'image';
    case NumericType = 'numeric';
    case DateType = 'date';
    case PeriodType = 'period';
    case BoolType = 'boolean';
    case OneOfMany = 'one of many';

    public function getColor(): string
    {
        return match ($this) {
            self::StringType => 'primary',
            self::TextType => 'secondary',
            self::ImageType => 'info',
            self::NumericType => 'warning',
            self::DateType => 'success',
            self::PeriodType => 'dark',
            self::BoolType => 'danger',
            self::OneOfMany => 'secondary',
        };
    }
}
