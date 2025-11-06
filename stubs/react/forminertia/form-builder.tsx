import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import { Form } from "@inertiajs/react";
import React from "react";
import FormField, { FormFieldConfig } from "./form-field";
import FormGrid, { FormGridConfig } from "./form-grid";
import FormSection, { FormSectionConfig } from "./form-section";

export type FormSchemaItem =
    | FormFieldConfig
    | FormGridConfig
    | FormSectionConfig;

export interface FormBuilderProps {
    formSchema: {
        schemas: FormSchemaItem[];
    };
    form: { action: string; method: "get" | "post" };
    submitLabel?: string;
}

const FormBuilder: React.FC<FormBuilderProps> = ({
    formSchema: { schemas },
    form,
    submitLabel = "Save",
}) => {
    return (
        <Card>
            <CardContent>
                <Form {...form} className="flex flex-col gap-6">
                    {({ processing, errors }) => (
                        <>
                            {schemas.map((item, index) => (
                                <React.Fragment key={index}>
                                    {item.type === "section" ? (
                                        <FormSection
                                            section={item as FormSectionConfig}
                                            errors={errors}
                                        />
                                    ) : item.type === "grid" ? (
                                        <FormGrid
                                            grid={item as FormGridConfig}
                                            errors={errors}
                                        />
                                    ) : (
                                        <FormField
                                            field={item as FormFieldConfig}
                                            error={
                                                errors[
                                                    (item as FormFieldConfig)
                                                        .name
                                                ]
                                            }
                                        />
                                    )}
                                </React.Fragment>
                            ))}

                            <div className="flex justify-end gap-4">
                                <Button type="submit" disabled={processing}>
                                    {processing ? "Saving..." : submitLabel}
                                </Button>
                            </div>
                        </>
                    )}
                </Form>
            </CardContent>
        </Card>
    );
};

export default FormBuilder;
