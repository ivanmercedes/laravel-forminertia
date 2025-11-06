<template>
    <Card>
        <CardContent>
            <form @submit="handleSubmit" class="flex flex-col gap-6">
                <template
                    v-for="(item, index) in formSchema.schemas"
                    :key="index"
                >
                    <FormSection
                        v-if="item.type === 'section'"
                        :section="item"
                        :errors="form.errors || {}"
                    />
                    <FormGrid
                        v-else-if="item.type === 'grid'"
                        :grid="item"
                        :errors="form.errors || {}"
                    />
                    <FormField
                        v-else
                        :field="item"
                        :error="form.errors?.[item.name]"
                        :model-value="form[item.name]"
                        @update:model-value="updateFormField(item.name, $event)"
                    />
                </template>

                <div class="flex justify-end gap-4">
                    <Button type="submit" :disabled="form.processing">
                        {{ form.processing ? "Saving..." : submitLabel }}
                    </Button>
                </div>
            </form>
        </CardContent>
    </Card>
</template>

<script setup lang="ts">
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import { provide } from "vue";
import type { FormFieldConfig } from "./FormField.vue";
import FormField from "./FormField.vue";
import type { FormGridConfig } from "./FormGrid.vue";
import FormGrid from "./FormGrid.vue";
import type { FormSectionConfig } from "./FormSection.vue";
import FormSection from "./FormSection.vue";

export type FormSchemaItem =
    | FormFieldConfig
    | FormGridConfig
    | FormSectionConfig;

export interface FormBuilderProps {
    formSchema: {
        schemas: FormSchemaItem[];
    };
    form: {
        [key: string]: any;
        processing?: boolean;
        errors?: Record<string, string>;
        submit?: () => void;
    };
    submitLabel?: string;
}

const props = withDefaults(defineProps<FormBuilderProps>(), {
    submitLabel: "Save",
});

// Provide form data and update function to child components
provide("formData", props.form);
provide("updateFormData", updateFormField);

const updateFormField = (fieldName: string, value: any) => {
    props.form[fieldName] = value;
};

const handleSubmit = (event: Event) => {
    event.preventDefault();
    if (props.form.submit) {
        props.form.submit();
    }
};
</script>
