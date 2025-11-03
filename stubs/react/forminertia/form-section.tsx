import React from "react";
import { FormField, FormFieldConfig } from "./form-field";
import FormGrid, { FormGridConfig } from "./form-grid";

export interface FormSectionConfig {
    type: "section";
    heading: string;
    description?: string;
    schema: (FormFieldConfig | FormGridConfig)[];
}

export interface FormSectionConfig {
    heading: string;
    description?: string;
    schema: (FormFieldConfig | FormGridConfig)[];
}

export interface FormSectionProps {
    section: FormSectionConfig;
    errors?: Record<string, string | undefined>;
}

const FormSection: React.FC<FormSectionProps> = ({ section, errors = {} }) => {
    return (
        <div className="space-y-4">
            <div>
                <h3 className="text-lg font-semibold">{section.heading}</h3>
                {section.description && (
                    <p className="mt-1 text-sm text-gray-500">
                        {section.description}
                    </p>
                )}
            </div>

            <div className="space-y-4">
                {section.schema.map((item, index) => (
                    <React.Fragment key={index}>
                        {item.type === "grid" ? (
                            <FormGrid grid={item} errors={errors} />
                        ) : (
                            <FormField field={item} error={errors[item.name]} />
                        )}
                    </React.Fragment>
                ))}
            </div>
        </div>
    );
};

export default FormSection;
