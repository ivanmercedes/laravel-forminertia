<template>
    <div class="space-y-4">
        <div>
            <h3 class="text-lg font-semibold">{{ section.heading }}</h3>
            <p v-if="section.description" class="mt-1 text-sm text-gray-500">
                {{ section.description }}
            </p>
        </div>

        <div class="space-y-4">
            <template v-for="(item, index) in section.schema" :key="index">
                <FormGrid
                    v-if="item.type === 'grid'"
                    :grid="item"
                    :errors="errors"
                />
                <FormField
                    v-else
                    :field="item"
                    :error="errors[item.name]"
                    :model-value="getFieldValue(item.name)"
                    @update:model-value="updateFieldValue(item.name, $event)"
                />
            </template>
        </div>
    </div>
</template>

<script setup lang="ts">
import { inject } from "vue";
import FormField, { type FormFieldConfig } from "./FormField.vue";
import FormGrid, { type FormGridConfig } from "./FormGrid.vue";

export interface FormSectionConfig {
    type: "section";
    heading: string;
    description?: string;
    schema: (FormFieldConfig | FormGridConfig)[];
}

export interface FormSectionProps {
    section: FormSectionConfig;
    errors?: Record<string, string | undefined>;
}

const props = withDefaults(defineProps<FormSectionProps>(), {
    errors: () => ({}),
});

// Inject form data and update function from parent
const formData = inject<Record<string, any>>("formData", {});
const updateFormData = inject<(field: string, value: any) => void>(
    "updateFormData",
    () => {}
);

const getFieldValue = (fieldName: string) => {
    return formData[fieldName];
};

const updateFieldValue = (fieldName: string, value: any) => {
    updateFormData(fieldName, value);
};
</script>
