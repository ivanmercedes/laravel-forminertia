import { cn } from '@/lib/utils'; // o tu ruta a cn()
import React from 'react';
import { FormField, FormFieldConfig } from './form-field';

export interface FormGridConfig {
    type: 'grid';
    columns: number;
    schema: FormFieldConfig[];
}

export interface FormGridProps {
    grid: FormGridConfig;
    errors?: Record<string, string | undefined>;
    className?: string;
}

const FormGrid: React.FC<FormGridProps> = ({
    grid,
    errors = {},
    className,
}) => {
    const columnClass =
        {
            1: 'md:grid-cols-1',
            2: 'md:grid-cols-2',
            3: 'md:grid-cols-3',
            4: 'md:grid-cols-4',
            5: 'md:grid-cols-5',
            6: 'md:grid-cols-6',
        }[grid.columns] || 'md:grid-cols-1';

    return (
        <div className={cn('grid grid-cols-1 gap-4', columnClass, className)}>
            {grid.schema.map((field, index) => (
                <FormField
                    key={field.name || index}
                    field={field}
                    error={errors[field.name]}
                />
            ))}
        </div>
    );
};

export default FormGrid;
