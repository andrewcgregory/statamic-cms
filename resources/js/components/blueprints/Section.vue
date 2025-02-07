<template>

    <div class="blueprint-section" :class="{ 'w-full': isEditing }">
        <div class="blueprint-section-card card p-0 h-full flex flex-col">

            <div class="bg-grey-20 border-b text-sm flex rounded-t;">
                <div class="blueprint-drag-handle blueprint-section-drag-handle w-4 border-r"></div>
                <div class="p-1.5 py-1 flex-1">
                    <span class="font-medium mr-1">
                        <input ref="displayInput" type="text" v-model="section.display" class="bg-transparent w-full outline-none" />
                    </span>
                </div>
                <div class="flex items-center px-1.5">
                    <button @click.prevent="toggleEditing" class="text-grey-60 hover:text-grey-100 mr-1">
                        <svg-icon :name="isEditing ? 'shrink' : 'expand'" />
                    </button>
                    <button @click.prevent="$emit('deleted')" class="text-grey-60 hover:text-grey-100">
                        <svg-icon name="trash" />
                    </button>
                </div>
            </div>


            <fields
                class="p-2"
                :fields="section.fields"
                :editing-field="editingField"
                :is-section-expanded="isEditing"
                :suggestable-condition-fields="suggestableConditionFields"
                @field-created="fieldCreated"
                @field-updated="fieldUpdated"
                @field-deleted="deleteField"
                @field-linked="fieldLinked"
                @field-editing="editingField = $event"
                @editor-closed="editingField = null"
            >
                <template v-slot:empty-state>
                    <div
                        v-text="__('Add or drag fields here')"
                        class="text-2xs text-grey-60 text-center border border-dashed rounded mb-1 p-1"
                    />
                </template>
            </fields>

        </div>
    </div>

</template>

<script>
import Fields from './Fields.vue';

export default {

    components: {
        Fields,
    },

    props: {
        section: {
            type: Object,
            required: true
        }
    },

    data() {
        return {
            isEditing: false,
            editingField: null,
        }
    },

    computed: {
        suggestableConditionFields() {
            return this.section.fields.map(field => field.handle);
        }
    },

    watch: {

        section: {
            deep: true,
            handler(section) {
                this.$emit('updated', section);
            }
        },

        'section.display': function(display) {
            this.section.handle = this.$slugify(display, '_');
        }

    },

    methods: {

        fieldLinked(field) {
            this.section.fields.push(field);
            this.$toast.success(__('Field added'));

            if (field.type === 'reference') {
                this.$nextTick(() => this.editingField = field._id);
            }
        },

        fieldCreated(field) {
            this.section.fields.push(field);
        },

        fieldUpdated(i, field) {
            this.section.fields.splice(i, 1, field);
        },

        deleteField(i) {
            this.section.fields.splice(i, 1);
        },

        focus() {
            this.$refs.displayInput.select();
        },

        toggleEditing() {
            this.isEditing = ! this.isEditing
        }

    }

}
</script>
