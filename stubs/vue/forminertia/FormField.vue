<template>
    <div
        :class="
            cn('space-y-2', field.columnSpan && `col-span-${field.columnSpan}`)
        "
    >
        <Label v-if="field.type !== 'checkbox' && field.label" :for="fieldId">
            {{ field.label }}
            <span v-if="field.required" class="ml-1 text-destructive">*</span>
        </Label>

        <!-- Text Input -->
        <Input
            v-if="field.type === 'text' || field.type === 'date'"
            :id="fieldId"
            :name="field.name"
            :type="field.type === 'text' ? field.inputType || 'text' : 'date'"
            :model-value="modelValue"
            :placeholder="field.placeholder"
            :disabled="field.disabled"
            :maxlength="field.maxLength"
            :required="field.required"
            @update:model-value="$emit('update:modelValue', $event)"
        />

        <!-- Textarea -->
        <Textarea
            v-else-if="field.type === 'textarea'"
            :id="fieldId"
            :name="field.name"
            :model-value="modelValue"
            :placeholder="field.placeholder"
            :disabled="field.disabled"
            :rows="field.rows"
            :required="field.required"
            @update:model-value="$emit('update:modelValue', $event)"
        />

        <!-- Select -->
        <Select
            v-else-if="field.type === 'select'"
            :model-value="modelValue"
            :disabled="field.disabled"
            @update:model-value="$emit('update:modelValue', $event)"
        >
            <SelectTrigger class="w-full">
                <SelectValue
                    :placeholder="field.placeholder || 'Select an Option'"
                />
            </SelectTrigger>
            <SelectContent>
                <SelectGroup>
                    <SelectLabel v-if="field.label">{{
                        field.label
                    }}</SelectLabel>
                    <SelectItem
                        v-for="[key, label] in Object.entries(
                            field.options || {}
                        )"
                        :key="key"
                        :value="key"
                    >
                        {{ label }}
                    </SelectItem>
                </SelectGroup>
            </SelectContent>
        </Select>

        <!-- Checkbox -->
        <div
            v-else-if="field.type === 'checkbox'"
            class="flex items-center space-x-2"
        >
            <Checkbox
                :id="fieldId"
                :name="field.name"
                :checked="!!modelValue"
                @update:checked="$emit('update:modelValue', $event)"
            />
            <Label v-if="field.label" :for="fieldId" class="cursor-pointer">
                {{ field.label }}
            </Label>
        </div>

        <!-- Unsupported field type -->
        <div v-else>Unsupported field: {{ field.type }}</div>

        <p v-if="field.helperText" class="text-sm text-gray-500">
            {{ field.helperText }}
        </p>
        <p v-if="error" class="text-sm text-destructive">
            {{ error }}
        </p>
    </div>
</template>

<script setup lang="ts">
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
import { computed } from "vue";

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
    modelValue?: string | boolean | number;
}

const props = defineProps<FormFieldProps>();

const emit = defineEmits<{
    "update:modelValue": [value: string | boolean | number];
}>();

const fieldId = computed(() => `field-${props.field.name}`);
</script>
