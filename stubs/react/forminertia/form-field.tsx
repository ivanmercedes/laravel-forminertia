import { Checkbox } from "@/components/ui/checkbox";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";
import { Textarea } from "@/components/ui/textarea";
import { cn } from "@/lib/utils";
import React, { useState } from "react";

export type FieldType = "text" | "textarea" | "select" | "checkbox" | "date";

export interface FormFieldConfig {
    type: FieldType;
    name: string;
    label?: string;
    placeholder?: string;
    inputType?: string;
    disabled?: boolean;
    required?: boolean;
    maxLength?: number;
    rows?: number;
    options?: Record<string, string>;
    helperText?: string;
    columnSpan?: number;
    value?: string | boolean | number | undefined;
}

export interface FormFieldProps {
    field: FormFieldConfig;
    error?: string;
}

export const FormField: React.FC<FormFieldProps> = ({ field, error }) => {
    const fieldId = `field-${field.name}`;

    
    const getInitialValue = () => {
        if (field.value !== undefined && field.value !== null) {
            return field.value;
        }
        return field.type === "checkbox" ? false : "";
    };

    const [value, setValue] = useState(getInitialValue);

    const handleChange = (newValue: string | boolean) => {
        setValue(newValue);
    };

    const renderInput = () => {
        switch (field.type) {
            case "text":
            case "date":
                return (
                    <Input
                        name={field.name}
                        id={fieldId}
                        type={
                            field.type === "text"
                                ? field.inputType || "text"
                                : "date"
                        }
                        value={value as string}
                        placeholder={field.placeholder}
                        disabled={field.disabled}
                        maxLength={field.maxLength}
                        required={field.required}
                        onChange={(e) => handleChange(e.target.value)}
                    />
                );

            case "textarea":
                return (
                    <Textarea
                        name={field.name}
                        id={fieldId}
                        value={value as string}
                        placeholder={field.placeholder}
                        disabled={field.disabled}
                        rows={field.rows}
                        required={field.required}
                        onChange={(e) => handleChange(e.target.value)}
                    />
                );

            case "select":
                return (
                    <Select
                        name={field.name}
                        value={value as string}
                        disabled={field.disabled}
                        onValueChange={(val) => handleChange(val)}
                    >
                        <SelectTrigger className="w-full">
                            <SelectValue
                                placeholder={
                                    field.placeholder || "Select a Option"
                                }
                            />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                {field.label && (
                                    <SelectLabel>{field.label}</SelectLabel>
                                )}
                                {(field.options
                                    ? Object.entries(field.options)
                                    : []
                                ).map(([key, label]) => (
                                    <SelectItem key={key} value={key}>
                                        {label}
                                    </SelectItem>
                                ))}
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                );

            case "checkbox":
                return (
                    <div className="flex items-center space-x-2">
                        <Checkbox
                            name={field.name}
                            id={fieldId}
                            checked={value as boolean}
                            onCheckedChange={(checked) =>
                                handleChange(Boolean(checked))
                            }
                        />
                        {field.label && (
                            <Label htmlFor={fieldId} className="cursor-pointer">
                                {field.label}
                            </Label>
                        )}
                    </div>
                );

            default:
                return <div>Unsupported field: {field.type}</div>;
        }
    };

    return (
        <div
            className={cn(
                "space-y-2",
                field.columnSpan && `col-span-${field.columnSpan}`
            )}
        >
            {field.type !== "checkbox" && field.label && (
                <Label htmlFor={fieldId}>
                    {field.label}
                    {field.required && (
                        <span className="ml-1 text-destructive">*</span>
                    )}
                </Label>
            )}

            {renderInput()}

            {field.helperText && (
                <p className="text-sm text-gray-500">{field.helperText}</p>
            )}
            {error && <p className="text-sm text-destructive">{error}</p>}
        </div>
    );
};

export default FormField;
