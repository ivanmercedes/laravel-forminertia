<template>
    <div :class="cn('grid grid-cols-1 gap-4', columnClass, className)">
        <FormField
            v-for="(field, index) in grid.schema"
            :key="field.name || index"
            :field="field"
            :error="errors[field.name]"
            :model-value="getFieldValue(field.name)"
            @update:model-value="updateFieldValue(field.name, $event)"
        />
    </div>
</template>

<script setup lang="ts">
import { cn } from "@/lib/utils";
import { computed, inject } from "vue";
import FormField, { type FormFieldConfig } from "./FormField.vue";

export interface FormGridConfig {
    type: "grid";
    columns: number;
    schema: FormFieldConfig[];
}

export interface FormGridProps {
    grid: FormGridConfig;
    errors?: Record<string, string | undefined>;
    className?: string;
}

const props = withDefaults(defineProps<FormGridProps>(), {
    errors: () => ({}),
});

// Inject form data and update function from parent
const formData = inject<Record<string, any>>("formData", {});
const updateFormData = inject<(field: string, value: any) => void>(
    "updateFormData",
    () => {}
);

const columnClass = computed(() => {
    const columnClasses: Record<number, string> = {
        1: "md:grid-cols-1",
        2: "md:grid-cols-2",
        3: "md:grid-cols-3",
        4: "md:grid-cols-4",
        5: "md:grid-cols-5",
        6: "md:grid-cols-6",
    };
    return columnClasses[props.grid.columns] || "md:grid-cols-1";
});

const getFieldValue = (fieldName: string) => {
    return formData[fieldName];
};

const updateFieldValue = (fieldName: string, value: any) => {
    updateFormData(fieldName, value);
};
</script>
